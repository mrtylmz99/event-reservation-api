<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['venue_id', 'name', 'description', 'start_date', 'end_date', 'status'];

    // Etkinliğin mekanı
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // Etkinliğe ait rezervasyonlar
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
