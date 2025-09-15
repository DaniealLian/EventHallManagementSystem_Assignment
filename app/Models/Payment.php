<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 



class Payment extends Model
{
    protected $fillable = [
        'reservation_id',
        'method',
        'amount',
        'status',
        'transaction_id'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    
}