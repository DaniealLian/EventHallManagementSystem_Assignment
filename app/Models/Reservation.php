<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Reservation extends Model
{
    //which stuff can be mass filled in the database for security
    protected $fillable =['event_id', 'reserved_date_time', 'total_price', 'session_duration'];

    //convert reservation Date value into php type when accessing it
    protected $casts =[
        'reserved_date_time' => 'datetime',
        'session_duration' => 'datetime',
    ];
    //this reservation has many reservationItem
    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }

     public function event():BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

}
