<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'address',
        'capacity',
        'postal_code',
    ];

    public function getRouteKeyName()
    {
        return 'id';
    }
}
