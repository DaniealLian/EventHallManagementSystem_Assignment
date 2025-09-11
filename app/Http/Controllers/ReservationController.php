<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\SeatType;
use App\Builders\ConcreteReservationBuilder;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Store a new reservation
     */
    public function store(Request $request, $eventId)
    {
        // ✅ Validate input
        $request->validate([
            'seat_type_id' => 'required|exists:seat_types,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $event = Event::findOrFail($eventId);
        $seatType = SeatType::findOrFail($request->seat_type_id);

        try {
            $builder = new ConcreteReservationBuilder();

            $reservation = $builder
                ->setEvent($event)
                ->setSeatType($seatType)
                ->setQuantity($request->quantity)
                ->setUser(auth()->id())
                ->calculateTotal()
                ->build();

            // ✅ Redirect to Payment Checkout page with reservation id
            return redirect()->route('payments.checkout', ['reservation' => $reservation->id])
                ->with('success', 'Reservation created. Proceed to payment.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show checkout form for a specific event
     */
    public function checkout($eventId)
    {
        $event = Event::findOrFail($eventId);

        // ✅ Ensure Event model has relation: public function seatTypes()
        $seatTypes = $event->seatTypes;

        return view('reservations.checkout', compact('event', 'seatTypes'));
    }
}