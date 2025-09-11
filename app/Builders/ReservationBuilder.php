<?php

namespace App\Builders;

use App\Models\Event;
use App\Models\SeatType;
use App\Models\Reservation;

interface ReservationBuilder
{
    public function setEvent(Event $event): self;
    public function setSeatType(SeatType $seatType): self;
    public function setQuantity(int $quantity): self;
    public function setUser(int $userId): self;
    public function calculateTotal(): self;
    public function build(): Reservation;
}
