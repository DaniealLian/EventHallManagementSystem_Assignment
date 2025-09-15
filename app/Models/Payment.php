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
        'transaction_id',
        'gateway',                    // Added
        'gateway_transaction_id',     // Added
        'ip_address',                 // Added
        'user_agent',                 // Added
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }


}
