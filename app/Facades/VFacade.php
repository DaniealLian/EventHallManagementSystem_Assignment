<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class VFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'venueService';
    }
}
