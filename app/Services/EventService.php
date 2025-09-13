<?php

namespace App\Services;

use App\Models\Event;
use App\Models\PricingTier;

class EventService implements EventServiceInterface
{
    public function createEvent(array $data): Event
    {
        $pricingTiers = $data['pricing_tiers'] ?? [];
        unset($data['pricing_tiers']);

        // Create the event
        $event = Event::create($data);

        //add pricing tier
         if (!empty($pricingTiers)) {
            $this->addPricingTiers($event, $pricingTiers);
        }
       
        
        return $event;
    }

    
    public function updateEvent(Event $event, array $data): Event
    {
        $pricingTiers = $data['pricing_tiers'] ?? [];
        unset($data['pricing_tiers']);

        $event->update($data);
        return $event;
    }

    public function deleteEvent(Event $event): bool
    {
        return $event->delete();
    }

    public function addPricingTiers(Event $event, array $tiers): void
    {
        foreach($tiers as $tier){
            $event->pricingTiers()->create([
                'tier' => $tier['tier'],
                'price' => $tier['price'],
                'available_qty' => $tier['available_qty'],
                'description'   => $tier['description'] ?? null,
            ]);
        }
    }

}

?>