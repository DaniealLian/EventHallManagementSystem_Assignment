<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'code',
        'name',
        'address',
        'capacity',
        'postal_code',
    ];

    public function getRouteKeyName()
    {
        return 'code';
    }
}
