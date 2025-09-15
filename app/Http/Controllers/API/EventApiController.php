<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    public function index()
    {
        return response()->json(Event::with('venue', 'pricingTiers')->get());
    }

    public function show(Event $event)
    {
        return response()->json($event->load('venue', 'pricingTiers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'venue_id'    => 'required|exists:venues,id',
            'user_id'     => 'required|exists:users,id',
        ]);

        $event = Event::create($validated);
        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_time'  => 'sometimes|date',
            'end_time'    => 'sometimes|date|after:start_time',
            'venue_id'    => 'sometimes|exists:venues,id',
        ]);

        $event->update($validated);
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message' => 'Event deleted']);
    }
}
