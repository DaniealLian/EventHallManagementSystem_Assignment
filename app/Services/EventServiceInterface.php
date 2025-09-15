<?php

namespace App\Services;

use App\Models\Event;
use App\Models\PricingTier;
use App\Models\Venue;

interface EventServiceInterface
{
    public function createEvent(array $data): Event;
    public function updateEvent(Event $event, array $data): Event;
    public function deleteEvent(Event $event): bool;

    //for adding new tiers for the events
    public function addPricingTiers(Event $event, array $tiers): void;
    public function validateVenueCapacity(Venue $venue, array $pricingTiers): void;
}
?>