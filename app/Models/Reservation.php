<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Reservation extends Model
{
    //which stuff can be mass filled in the database for security
    protected $fillable =['event_id', 'user_id', 'reserved_date_time', 'total_price'];

    //convert reservation Date value into php type when accessing it
    protected $casts =[
        'reserved_date_time' => 'datetime',
        'total_price' => 'decimal:2'
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

    public function customer():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

}
