<?php

namespace App\Http\Controllers;

use App\BuilderPattern\ReservationBuilder;
use App\Models\Event;
use App\Models\Reservation;
use App\Models\PricingTier;
use App\Models\ReservationItem;
use Illuminate\Http\Request;

class ReservationController extends Controller
{

    public function index(Event $event)
    {
        $event->load('pricingTiers');
        return view('reservations.index', compact('event'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {

        $request->validate([
        'reserved_date_time' => 'required|date',
        ]);
        
        $builder = new ReservationBuilder($event);

        $reservationDate = new \DateTime($request->reserved_date_time);

        $reservation = $builder->addReservation($reservationDate);

        foreach ($request->tiers as $tierId => $quantity) {
            if ($quantity > 0) {
                $builder->addItem($tierId, $quantity);
            }
        }

        $reservation = $builder->save();

        return redirect()->route('events.index')->with('success', 'Reservation created!');
    }
    /**
     * Display the specified resource.
     */
    public function show(reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reservation $reservation)
    {
        //
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
