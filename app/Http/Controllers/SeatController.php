<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    /**
     * Koltuğu blokla (Geçici rezervasyon vb.)
     */
    public function block(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id',
        ]);

        $seat = Seat::find($request->seat_id);

        if ($seat->status !== 'available') {
            return response()->json(['message' => 'Seat is not available'], 400);
        }

        $seat->status = 'blocked';
        $seat->save();

        return response()->json(['message' => 'Seat blocked successfully', 'seat' => $seat]);
    }

    /**
     * Koltuğu serbest bırak
     */
    public function release(Request $request)
    {
        $request->validate([
            'seat_id' => 'required|exists:seats,id',
        ]);

        $seat = Seat::find($request->seat_id);
        $seat->status = 'available';
        $seat->save();

        return response()->json(['message' => 'Seat released successfully', 'seat' => $seat]);
    }
}
