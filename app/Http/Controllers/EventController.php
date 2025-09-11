<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EventServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EventController extends Controller
{
    private EventServiceInterface $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
        $this->middleware('auth');
    }

    /**
     * Show all events
     */
    public function index()
    {
        $events = Event::with('organizer')->get();
        return view('events.index', compact('events')); // show view
    }

    /**
     * Show form to create new event
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a new event
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
        ]);

        $validated['organizer_id'] = Auth::id();

        $this->eventService->createEvent($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    /**
     * Update an event
     */
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title'       => 'string|max:255',
            'description' => 'nullable|string',
            'start_time'  => 'date',
            'end_time'    => 'date|after:start_time',
        ]);

        $this->eventService->updateEvent($event, $validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully!');
    }

    /**
     * Delete an event
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        $this->eventService->deleteEvent($event);

        return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
    }
}
