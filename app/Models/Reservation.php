<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Event;
use App\Models\ReservationItem;
use App\Models\Ticket;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id', 'expires_at', 'status', 'total_amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function items()
    {
        return $this->hasMany(ReservationItem::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
