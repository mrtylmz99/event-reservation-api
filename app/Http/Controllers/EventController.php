<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\ReservationItem;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    /**
     * Tüm etkinlikleri listele
     */
    public function index()
    {
        return response()->json(Event::with('venue')->get());
    }

    /**
     * Yeni etkinlik oluştur (Admin Only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'venue_id' => 'required|exists:venues,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $event = Event::create($request->all());

        return response()->json($event, 201);
    }

    /**
     * Etkinlik detaylarını getir
     */
    public function show($id)
    {
        $event = Event::with('venue')->find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    /**
     * Etkinlik güncelle (Admin Only)
     */
    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->update($request->all());

        return response()->json($event);
    }

    /**
     * Etkinlik sil (Admin Only)
     */
    public function destroy($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }

    /**
     * Etkinlik bazlı koltuk durumu
     */
    public function getSeats($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        // Mekanın tüm koltukları
        $seats = Seat::where('venue_id', $event->venue_id)->get();

        // Bu etkinlik için rezerve edilmiş koltuk ID'leri (pending veya confirmed)
        // ReservationItem üzerinden kontrol ediyoruz
        $reservedSeatIds = ReservationItem::whereHas('reservation', function($q) use ($id) {
            $q->where('event_id', $id)
              ->whereIn('status', ['confirmed', 'pending']);
        })->pluck('seat_id')->toArray();

        // Koltukları dolaş ve durumu işaretle
        $seats->transform(function($seat) use ($reservedSeatIds) {
            if (in_array($seat->id, $reservedSeatIds)) {
                $seat->status = 'booked'; // Etkinlik için dolu
            }
            // Seat modelindeki base status (maintenance vs) korunur, booked değilse
            // Ama API yanıtı için 'booked' önceliklidir.
            return $seat;
        });

        return response()->json($seats);
    }
}
