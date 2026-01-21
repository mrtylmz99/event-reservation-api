<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
// use Barryvdh\DomPDF\Facade\Pdf; 
// Laravel auto-discovery should register 'Pdf' alias usually, or we use class directly.
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    /**
     * Kullanıcının biletlerini listele
     */
    public function index()
    {
        // Reservation üzerinden biletleri çekiyoruz
        $tickets = Ticket::whereHas('reservation', function($q) {
            $q->where('user_id', auth()->id());
        })->with(['reservation.event', 'seat.venue'])->get();

        return response()->json($tickets);
    }

    /**
     * Bilet detayını göster
     */
    public function show($id)
    {
        $ticket = Ticket::where('id', $id)->whereHas('reservation', function($q) {
            $q->where('user_id', auth()->id());
        })->with(['reservation.event', 'seat.venue'])->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        return response()->json($ticket);
    }

    /**
     * Bileti PDF olarak indir
     */
    public function download($id)
    {
        $ticket = Ticket::where('id', $id)->whereHas('reservation', function($q) {
            $q->where('user_id', auth()->id());
        })->with(['reservation.event', 'reservation.user', 'seat.venue'])->first();

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        $pdf = Pdf::loadView('tickets.pdf', ['ticket' => $ticket]);
        return $pdf->download('ticket-'.$ticket->ticket_code.'.pdf');
    }
}
