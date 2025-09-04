<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'amount',
        'method',
        'status',
    ];

    /**
     * Relationships (if needed later)
     */

    // Each transaction belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Each transaction belongs to an event (if you have Event model)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
