<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Ticket;
use App\Models\Seat;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Kullanıcının rezervasyonlarını listele
     */
    public function index()
    {
        $reservations = auth()->user()->reservations()->with(['event', 'items.seat'])->get();
        return response()->json($reservations);
    }

    /**
     * Rezervasyon detayını getir
     */
    public function show($id)
    {
        $reservation = auth()->user()->reservations()->with(['event', 'items.seat', 'tickets'])->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }

    /**
     * Yeni rezervasyon oluştur
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id',
        ]);

        $event = Event::find($request->event_id);
        
        // Transaction başlat
        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $seatItems = [];

            foreach ($request->seat_ids as $seatId) {
                // Koltuk uygunluğu kontrolü
                $isReserved = ReservationItem::where('seat_id', $seatId)
                    ->whereHas('reservation', function($q) use ($event) {
                        $q->where('event_id', $event->id)
                          ->whereIn('status', ['confirmed', 'pending'])
                          // Süresi geçmiş pending rezervasyonları hariç tutabiliriz, ancak cron job temizleyecek.
                          // Güvenlik için expire kontrolü de ekleyelim:
                          ->where(function($qq) {
                              $qq->where('status', 'confirmed')
                                 ->orWhere('expires_at', '>', now());
                          });
                    })->exists();

                if ($isReserved) {
                    throw new \Exception("Seat ID $seatId is already currently reserved.");
                }

                $seat = Seat::find($seatId);
                // Venue kontrolü
                if ($seat->venue_id != $event->venue_id) {
                     throw new \Exception("Seat ID $seatId does not belong to the event venue.");
                }
                
                // Fiyat hesapla (Etkinlik bazlı fiyatlandırma olmadığı içi koltuk fiyatı baz alınıyor)
                // İdealde PriceTier vb. olurdu.
                $price = $seat->price;
                $totalAmount += $price;

                $seatItems[] = ['seat' => $seat, 'price' => $price];
            }

            // Rezervasyonu oluştur
            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'event_id' => $event->id,
                'status' => 'pending',
                'expires_at' => Carbon::now()->addMinutes(15),
                'total_amount' => $totalAmount
            ]);

            // Itemları ekle
            foreach ($seatItems as $item) {
                ReservationItem::create([
                    'reservation_id' => $reservation->id,
                    'seat_id' => $item['seat']->id,
                    'price' => $item['price']
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Reservation created successfully', 
                'reservation' => $reservation->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Reservation failed', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * Rezervasyonu onayla (Ödeme simülasyonu)
     */
    public function confirm($id)
    {
        $reservation = auth()->user()->reservations()->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        if ($reservation->status !== 'pending') {
            return response()->json(['message' => 'Reservation is not pending'], 400);
        }

        if (Carbon::now()->gt($reservation->expires_at)) {
            $reservation->status = 'cancelled'; // Expired
            $reservation->save();
            return response()->json(['message' => 'Reservation expired'], 400);
        }

        // Ödeme işlemi burada yapılır... Başarılı varsayıyoruz.

        DB::beginTransaction();
        try {
            $reservation->status = 'confirmed';
            $reservation->save();

            // Biletleri oluştur
            foreach ($reservation->items as $item) {
                Ticket::create([
                    'reservation_id' => $reservation->id,
                    'seat_id' => $item->seat_id,
                    'ticket_code' => strtoupper(Str::random(10)), // Benzersiz kod
                    'status' => 'valid'
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Reservation confirmed and tickets generated',
                'tickets' => $reservation->tickets
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Confirmation failed', 'error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Rezervasyonu iptal et
     */
    public function cancel($id)
    {
         $reservation = auth()->user()->reservations()->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }
        
        if ($reservation->status == 'completed') { // Confirmed yerine completed mı dedik? create migrationda default pending demiştik.
            // Onaylı rezervasyon iadesi kuralı: etkinlik start date'den 24 saat önce.
             $eventStartDate = Carbon::parse($reservation->event->start_date);
             if (Carbon::now()->addHours(24)->gte($eventStartDate)) {
                 return response()->json(['message' => 'Cancellation not allowed within 24 hours of event'], 400);
             }
        }

        $reservation->status = 'cancelled';
        $reservation->save();
        
        // Ticketları iptal et
        foreach($reservation->tickets as $ticket) {
            $ticket->status = 'cancelled';
            $ticket->save();
        }

        return response()->json(['message' => 'Reservation cancelled']);
    }
}
