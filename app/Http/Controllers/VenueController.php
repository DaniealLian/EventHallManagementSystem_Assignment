<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facades\VFacade;

class VenueController extends Controller
{
    public function index()
    {
        $venues = VFacade::getAllVenues();
        return view('venues.index', ['venues' => $venues]);
    }

    public function create()
    {
        return view('venues.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'address'     => 'required|string',
            'capacity'    => 'required|integer|min:1|max:1000',
            'postal_code' => 'required|string|max:10',
        ]);

        $venue = VFacade::createVenue($data);

        return redirect()->route('venues.index')->with('success', 'Venue created: ' . ($venue->code ?? ''));
    }

    public function edit($code)
    {
        $venue = VFacade::getVenueByCode($code);
        if (!$venue) abort(404);
        return view('venues.edit', compact('venue'));
    }

    public function update(Request $request, $code)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'address'     => 'required|string',
            'capacity'    => 'required|integer|min:1|max:1000',
            'postal_code' => 'required|string|max:10',
        ]);

        $updated = VFacade::updateVenue($code, $data);

        if (! $updated) {
            return back()->withErrors(['err' => 'Venue not found.']);
        }

        return redirect()->route('venues.index')->with('success', 'Venue updated.');
    }

    public function destroy($code)
    {
        $ok = VFacade::deleteVenue($code);
        $msg = $ok ? 'Venue deleted.' : 'Venue not found.';
        return redirect()->route('venues.index')->with('success', $msg);
    }
}
