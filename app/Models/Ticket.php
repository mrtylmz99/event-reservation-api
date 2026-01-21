<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = ['reservation_id', 'seat_id', 'ticket_code', 'status'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
}
