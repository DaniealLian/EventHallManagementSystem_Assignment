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

    public function holdInventory(int $pricingTierId, int $quantity): void
    {
        $key = "inventory_hold:{$pricingTierId}";
        Redis::incrby($key, $quantity);
        Redis::expire($key, self::TIMEOUT_DURATION * 60);
    }

    public function getRealAvailableQty($tier): int
    {
        $heldTickets = Redis::get("inventory_hold:{$tier->id}") ?? 0;
        return max(0, $tier->available_qty - $heldTickets);
    }

    public function releaseInventory(int $pricingTierId, int $quantity): void
    {
        $key = "inventory_hold:{$pricingTierId}";
        Redis::decrby($key, $quantity);
    }

    // Check real availability
    public function getAvailableQuantity(PricingTier $tier): int
    {
        $held = Redis::get("inventory_hold:{$tier->id}") ?? 0;
        return max(0, $tier->available_qty - $held);
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
