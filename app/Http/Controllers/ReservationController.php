<?php

namespace App\Http\Controllers;

use App\Services\ReservationSessionService;
use App\BuilderPattern\ReservationBuilder;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\PricingTier;
use App\Models\ReservationItem;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    protected $sessionService;

    public function __construct(ReservationSessionService $sessionService)
    {
        $this->sessionService = $sessionService;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $events = Event::all();
        return view('reservations.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        $event->load('pricingTiers');

        foreach ($event->pricingTiers as $tier) {
        $tier->real_available_qty = $this->sessionService->getRealAvailableQty($tier);
        }
        return view('reservations.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {

        $request->validate([
        'reserved_date_time' => 'required|date|after:now',
        'tiers' => 'required|array',
        'tiers.*' => 'integer|min:0',
        ]);
        
        try{
            $builder = new ReservationBuilder($event, $this->sessionService);
            $reservationDate = new \DateTime($request->reserved_date_time);
            $builder->addReservation($reservationDate);

            foreach ($request->tiers as $tierId => $quantity) {
                if ($quantity > 0) {
                    $builder->addItem($tierId, $quantity);
                }
            }

            $token =  $builder->getSessionToken();

            return redirect()
                ->route('reservations.finalize', ['event' => $event, 'token' => $token])
                ->with('success', 'Please finalize and confirm your reservation. :D');

        }catch(\Exception $e){
            return back()
                ->withInput()
                ->with('error', $e->getMessage());

        }

    }
  


    public function finalize(Event $event, string $token)
    {
        $builder = ReservationBuilder::fromSession($token, $event, $this->sessionService);

         if (!$builder) {
            return redirect()
                ->route('reservations.index')
                ->with('error', 'Reservation session expired. Please start over.');
        }
        $this->sessionService->extendSession($token);

        $reservation_session = $builder->getReservationData();
        $sessionData = $this->sessionService->getSession($token);

        return view('reservations.finalize', compact('event', 'reservation_session', 'sessionData', 'token'));

    }

    public function confirmReservation(Request $request, Event $event, string $token)
    {
        try {
            
            $builder = ReservationBuilder::fromSession($token, $event, $this->sessionService);
            
            if (!$builder) {
                throw new \Exception('Reservation session expired. Please start over.');
            }

            $reservation = $builder->save(); 
            
            return redirect()
                ->route('payments.checkout', ['reservation' => $reservation->id])
                ->with('success', 'Reservation confirmed successfully!');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('reservations.index', $event)
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($reservationID)
    {
        //
        $reservation = Reservation::findOrFail($reservationID);
        $reservation->delete();

        return redirect()->route('reservation.index')->with('removed reservation record');
    }


}