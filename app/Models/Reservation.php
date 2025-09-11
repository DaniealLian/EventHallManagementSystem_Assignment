<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'seat_type_id',
        'quantity',
        'total_price',
        'status',
    ];

    /**
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function seatType()
    {
        return $this->belongsTo(SeatType::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}