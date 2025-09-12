<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'user_id',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //reference foreign key with these two table
     public function reservations(): HasMany{
        return $this->hasMany(Reservation::class);
    }

    public function pricingTiers(): HasMany{
        return $this->hasMany(PricingTier::class);
    }
}

?>