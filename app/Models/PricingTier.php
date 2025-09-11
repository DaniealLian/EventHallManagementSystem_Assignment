<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingTier extends Model
{
    //
    protected $fillable =['event_id','tier', 'price'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }
}
