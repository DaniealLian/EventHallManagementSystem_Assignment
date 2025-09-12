<?php

namespace App\Services;

use App\Models\Event;
use App\Models\PricingTier;
use App\Models\Reservation;
use App\Models\ReservationItem;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReservationSessionService
{
    const TIMEOUT_DURATION = 1;
    const REDIS_PREFIX = 'temp_reservation:';

    public function generateToken(): string
    {
        return Str::uuid();
    }

    public function storeSession(string $token, array $data): void
    {
        $key = self::REDIS_PREFIX . $token;
        Redis::setex($key, self::TIMEOUT_DURATION * 60, json_encode($data));
    }

    public function getSession(string $token): ?array
    {
        $key = self::REDIS_PREFIX . $token;
        $data = Redis::get($key);
        
        if (!$data) {
            return null;
        }
        
        $reservation = json_decode($data, true);
        $reservation['time_remaining'] = Redis::ttl($key);
        
        return $reservation;
    }

     public function extendSession(string $token): bool
    {
        $key = self::REDIS_PREFIX . $token;
        return Redis::expire($key, self::TIMEOUT_DURATION * 60);
    }

    public function destroySession(string $token): bool
    {
        $key = self::REDIS_PREFIX . $token;
        return Redis::del($key) > 0;
    }

    public function isSessionValid(string $token): bool
    {
        $key = self::REDIS_PREFIX . $token;
        return Redis::exists($key);
    }
}
