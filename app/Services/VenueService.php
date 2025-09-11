<?php

namespace App\Services;

use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VenueService
{
    public function getAllVenues()
    {
        return Venue::orderBy('code')->get();
    }

    public function getVenueByCode(string $code): ?Venue
    {
        return Venue::where('code', $code)->first();
    }

    public function createVenue(array $data): Venue
    {
        return DB::transaction(function () use ($data) {
            $last = Venue::selectRaw("CAST(SUBSTRING(code, 2) AS UNSIGNED) as n")
                         ->orderByDesc('n')
                         ->first();

            $n = $last ? ($last->n + 1) : 1;
            $data['code'] = 'V' . str_pad($n, 3, '0', STR_PAD_LEFT);

            $venue = Venue::create($data);

            Log::info('Venue created', [
                'code' => $venue->code,
                'user' => optional(auth()->user())->id,
            ]);
            
            return $venue;
        });
    }

    public function updateVenue(string $code, array $data): ?Venue
    {
        $venue = $this->getVenueByCode($code);
        if (! $venue) {
            return null;
        }

        $venue->update($data);

        Log::info('Venue updated', ['code' => $venue->code, 'user' => optional(auth()->user())->id]);

        return $venue;
    }

    public function deleteVenue(string $code): bool
    {
        $venue = $this->getVenueByCode($code);
        if (! $venue) {
            return false;
        }

        $ok = $venue->delete();

        if ($ok) {
            Log::info('Venue deleted', ['code' => $code, 'user' => optional(auth()->user())->id]);
        }

        return $ok;
    }
}
    