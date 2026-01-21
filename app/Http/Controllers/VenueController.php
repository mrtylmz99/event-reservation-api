<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VenueController extends Controller
{
    /**
     * Tüm mekanları listele
     */
    public function index()
    {
        return response()->json(Venue::all());
    }

    /**
     * Yeni mekan oluştur (Admin Only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'capacity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $venue = Venue::create($request->all());

        return response()->json($venue, 201);
    }

    /**
     * Belirli bir mekanı göster
     */
    public function show($id)
    {
        $venue = Venue::find($id);
        if (!$venue) {
            return response()->json(['message' => 'Venue not found'], 404);
        }
        return response()->json($venue);
    }

    /**
     * Mekan güncelle (Admin Only)
     */
    public function update(Request $request, $id)
    {
        $venue = Venue::find($id);
        if (!$venue) {
            return response()->json(['message' => 'Venue not found'], 404);
        }

        $venue->update($request->all());

        return response()->json($venue);
    }

    /**
     * Mekan sil (Admin Only)
     */
    public function destroy($id)
    {
        $venue = Venue::find($id);
        if (!$venue) {
            return response()->json(['message' => 'Venue not found'], 404);
        }

        $venue->delete();

        return response()->json(['message' => 'Venue deleted successfully']);
    }

    /**
     * Mekana ait koltukları getir
     */
    public function getSeats($id)
    {
        $venue = Venue::find($id);
        if (!$venue) {
            return response()->json(['message' => 'Venue not found'], 404);
        }
        return response()->json($venue->seats);
    }
}
