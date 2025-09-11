<?php

namespace App\Builders;

use App\Models\Event;
use App\Models\SeatType;
use App\Models\Reservation;

class ConcreteReservationBuilder implements ReservationBuilder
{
    protected ?Event $event = null;
    protected ?SeatType $seatType = null;
    protected int $quantity = 1;
    protected ?int $userId = null;
    protected float $total = 0;

    public function setEvent(Event $event): self
    {
        $this->event = $event;
        return $this;
    }

    public function setSeatType(SeatType $seatType): self
    {
        $this->seatType = $seatType;
        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function setUser(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function calculateTotal(): self
    {
        if (!$this->seatType) {
            throw new \Exception("Seat type must be selected before calculating total.");
        }

        if ($this->quantity > $this->seatType->capacity) {
            throw new \Exception("Not enough seats available for {$this->seatType->name}");
        }

        $this->total = $this->seatType->price * $this->quantity;
        return $this;
    }

    public function build(): Reservation
    {
        if (!$this->event || !$this->seatType || !$this->userId) {
            throw new \Exception("Reservation is missing required fields.");
        }

        $reservation = Reservation::create([
            'event_id'     => $this->event->id,
            'user_id'      => $this->userId,
            'seat_type_id' => $this->seatType->id,
            'quantity'     => $this->quantity,
            'total_price'  => $this->total,
            'status'       => 'pending',
        ]);

        // reduce seat availability
        $this->seatType->decrement('capacity', $this->quantity);

        return $reservation;
    }
}