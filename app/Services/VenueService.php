<?php

namespace App\Services;

use App\Models\Venue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VenueService
{
    public function getAllVenues()
    {
        return Venue::orderBy('id')->get();
    }

    public function getVenueByCode(string $id): ?Venue
    {
        return Venue::where('id', $id)->first();
    }

    public function createVenue(array $data): Venue
    {
        return DB::transaction(function () use ($data) {
            $last = Venue::selectRaw("CAST(SUBSTRING(id, 2) AS UNSIGNED) as n")
                         ->orderByDesc('n')
                         ->first();

            $venue = Venue::create($data);

            Log::info('Venue created', [
                'id' => $venue->id,
                'user' => optional(auth()->user())->id,
            ]);
            
            return $venue;
        });
    }

    public function updateVenue(string $id, array $data): ?Venue
    {
        $venue = $this->getVenueByCode($id);
        if (! $venue) {
            return null;
        }

        $venue->update($data);

        Log::info('Venue updated', ['id' => $venue->id, 'user' => optional(auth()->user())->id]);

        return $venue;
    }

    public function deleteVenue(string $id): bool
    {
        $venue = $this->getVenueByCode($id);
        if (! $venue) {
            return false;
        }

        $ok = $venue->delete();

        if ($ok) {
            Log::info('Venue deleted', ['id' => $id, 'user' => optional(auth()->user())->id]);
        }

        return $ok;
    }
}
    