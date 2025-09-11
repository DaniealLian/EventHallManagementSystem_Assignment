<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 

class ReservationItem extends Model
{
    //
    protected $fillable =['reservation_id', 'pricing_tier_id', 'quantity', 'unit_price'];

    public function reservation() :BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    
    public function pricingTier():BelongsTo
    {
        return $this->belongsTo(PricingTier::class);
    }
}   
