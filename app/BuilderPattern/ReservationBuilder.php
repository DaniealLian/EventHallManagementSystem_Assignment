<?php

namespace App\BuilderPattern;

use Illuminate\Support\Facades\DB;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Services\ReservationSessionService;


class ReservationBuilder
{
    protected $event;
    protected $user; // later integrate
    protected $reservation_session;
    protected $sessionToken;
    protected $sessionService;
    

    public function __construct(Event $event, ReservationSessionService $sessionService, $user = null)
    {
        $this->event = $event;
        $this->user = $user;
        $this->sessionService = $sessionService;
        $this->sessionToken = $this->sessionService->generateToken();
        $this->reservation_session = [
            'event_id' => $this->event->id,
            'reservationItems' => [],
            'total_price' => 0,
            'created_at' => now()->timestamp
        ];
    }

    //Build reservation time
    public function addReservation(\DateTime $date): self
    {
        $this->reservation_session['reserved_date_time'] = $date->format('Y-m-d H:i:s');
        $this->updateSession();
        return $this;
    }


    public function addItem(int $pricingTierId, int $qty): self
    {
        $pricing_tier = $this->event->pricingTiers()->findOrFail($pricingTierId);

        $this->reservation_session['reservationItems'][$pricingTierId]= [
            'pricing_tier_id' => $pricing_tier->id,
            'quantity' => $qty,
            'unit_price' => $pricing_tier->price, 
            'subtotal' => $qty * $pricing_tier->price
            
        ];

        $this->totalPrice();
        $this->updateSession();

        return $this;
    }


    public function removeItem(int $pricingTierId): self
    {
        unset($this->reservation_session['reservationItems'][$pricingTierId]);
        $this->totalPrice();
        $this->updateSession();
        
        return $this;
    }

    public function getSessionToken(): string
    {
        return $this->sessionToken;
    }


    public function getReservationData(): array
    {
        return $this->reservation_session;
    }


    //----------------------------

    public function save(): Reservation
    {
 
        if (!$this->sessionService->isSessionValid($this->sessionToken)) {
            abort(403, 'Reservation session expired. Please try again.');
        }

        if (empty($this->reservation_session['reservationItems'])) {
            throw new \Exception('Please choose your reservation.');
        }

        if (empty($this->reservation_session['reserved_date_time'])) {
        throw new \Exception('Please select a reservation schedule.');
        }   

        return DB::transaction(function () {
            $reservation = Reservation::create([
                'event_id' => $this->reservation_session['event_id'],
                'reserved_date_time' => $this->reservation_session['reserved_date_time'],
                'total_price' => $this->reservation_session['total_price'],
            
            ]);

            
            foreach ($this->reservation_session['reservationItems'] as $item) {
                ReservationItem::create([
                    'reservation_id' => $reservation->id,
                    'pricing_tier_id' => $item['pricing_tier_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }

            
            $this->sessionService->destroySession($this->sessionToken);

            return $reservation->fresh()->load('reservationItems.pricingTier');
        });
    }

    public static function fromSession(string $token, Event $event, ReservationSessionService $sessionService): ?self
    {
        $sessionData = $sessionService->getSession($token);
        
        if (!$sessionData || $sessionData['event_id'] !== $event->id) {
            return null;
        }

        $builder = new self($event, $sessionService);
        $builder->sessionToken = $token;
        $builder->reservation_session = $sessionData;
        
        return $builder;
    }


    public function totalPrice(): void{
        $this->reservation_session['total_price'] =  collect($this->reservation_session['reservationItems'])
            ->sum('subtotal');
    }
   

    private function updateSession(): void
    {
        $this->sessionService->storeSession($this->sessionToken, $this->reservation_session);
    }
}

