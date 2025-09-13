<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingTier extends Model
{
    use HasFactory;
    //
    protected $fillable =['event_id','tier', 'price', 'available_qty', 'description'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function reservationItems(): HasMany
    {
        return $this->hasMany(ReservationItem::class);
    }
}
