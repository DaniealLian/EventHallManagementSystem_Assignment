<?php

namespace App\BuilderPattern;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\ReservationItem;


class ReservationBuilder
{
    protected $event;
    protected $user; // later integrate
    protected $reservation;
    protected $reservation_items = [];

    public function __construct(Event $event, $user = null)
    {
        $this->event = $event;
        $this->user = $user;
    }

    //Build reservation time
    public function addReservation(\DateTime $date): self
    {
        $this->reservation = Reservation::create([
            'event_id' => $this->event->id,
            'reserved_date_time' => $date,
            'total_price' => 0,
            //session security part
            'session_duration'=> now()->addMinutes(1), //for testing, might change duration
        ]);

        return $this;
    }

    public function addItem(int $pricingTierId, int $qty): self
    {
        $pricing_tier = $this->event->pricingTiers()->findOrFail($pricingTierId);

        $this->reservation_items[] = [
            'reservation_id' => $this->reservation->id,
            'pricing_tier_id' => $pricing_tier->id,
            'quantity' => $qty,
            'unit_price' => $pricing_tier->price, 
            
        ];

        return $this;
    }


    //----------------------------

    public function save(): Reservation
    {
 
        if ($this->reservation->expires_at && $this->reservation->expires_at->isPast()) {
        abort(403, 'Reservation session expired. Please try again.');
        }
        $total = 0;

        foreach ($this->reservation_items as $item) {
            $total += $item['quantity'] * $item['unit_price'];
            ReservationItem::create($item);
        }
        $this->reservation->update(['total_price' => $total]);

        return $this->reservation->fresh()->load('reservationItems.pricingTier');
    }
   
}

