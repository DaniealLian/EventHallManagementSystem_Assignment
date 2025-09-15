<?php


namespace App\Services;

use App\Models\Event;
use App\Models\PricingTier;
use App\Models\Venue;

class EventService implements EventServiceInterface
{
    public function createEvent(array $data): Event
    {
        $pricingTiers = $data['pricing_tiers'] ?? [];
        unset($data['pricing_tiers']);

        if (isset($data['venue_id']) && !empty($pricingTiers)) {
            $venue = Venue::findOrFail($data['venue_id']);
            $this->validateVenueCapacity($venue, $pricingTiers);
        }

        unset($data['pricing_tiers']);

        // Create the event
        $event = Event::create($data);
        
        // Add pricing tiers
        if (!empty($pricingTiers)) {
            $this->addPricingTiers($event, $pricingTiers);
        }
        
        return $event->load('pricingTiers');
    }

    public function updateEvent(Event $event, array $data): Event
    {
        $pricingTiers = $data['pricing_tiers'] ?? [];

        if (isset($data['venue_id']) && !empty($pricingTiers)) {
            $venue = Venue::findOrFail($data['venue_id']);
            $this->validateVenueCapacity($venue, $pricingTiers);
        }

        unset($data['pricing_tiers']);
        
        
        $event->update($data);
        
        //Handle pricing tiers
        if (!empty($pricingTiers)) {
            // Delete existing tiers and recreate
            $event->pricingTiers()->delete();
            $this->addPricingTiers($event, $pricingTiers);
        }
        
        return $event->fresh(['pricingTiers']);
    }

    public function deleteEvent(Event $event): bool
    {
        return $event->delete();
    }

    public function addPricingTiers(Event $event, array $tiers): void
    {
        foreach($tiers as $tier) {
            $event->pricingTiers()->create([
                'tier' => $tier['tier'],
                'price' => $tier['price'],
                'available_qty' => $tier['available_qty'],
                'description' => $tier['description'] ?? null,
            ]);
        }
    }

    public function validateVenueCapacity(Venue $venue, array $pricingTiers): void
    {
        $totalTickets = collect($pricingTiers)->sum('available_qty');
        
        if ($totalTickets > $venue->capacity) {
            throw new \Exception("Total tickets ({$totalTickets}) exceed venue capacity ({$venue->capacity})");
        }
    }
}