class ReservationService
{
    public function createReservation(int $eventId, array $items, ?\DateTime $date = null): Reservation
    {
        $builder = new ReservationBuilder();
        
        $builder->forEvent($eventId);
        
        if ($date) {
            $builder->onDate($date);
        }
        
        $builder->addMultipleItems($items);
        
        return $builder->build();
    }

    public function getAvailableTiers(int $eventId): Collection
    {
        return PricingTier::where('event_id', $eventId)
            ->orderBy('price')
            ->get();
    }
}