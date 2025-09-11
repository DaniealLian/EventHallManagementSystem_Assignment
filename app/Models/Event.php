<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SeatType;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'organizer_id',
    ];

    // An event belongs to an organizer (User)
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    // 🔗 An event has many seat types (VIP, Gold, etc.)
    public function seatTypes()
    {
        return $this->hasMany(SeatType::class);
    }


    // 🔗 An event has many reservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
