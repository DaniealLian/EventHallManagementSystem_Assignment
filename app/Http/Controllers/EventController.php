<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\PricingTier;
use App\Models\Venue;
use App\Services\EventServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    private EventServiceInterface $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
        $this->middleware('auth');
    }

    /**
     * Show all events (list)
     */
    public function index()
    {
        $events = Event::with('organizer', 'pricingTiers', 'venue')->get();

        return view('events.index', compact('events'));
    }


    public function create(Request $request)
    {
        $venues = Venue::orderBy('name')->get(); 
        return view('events.create', compact('venues')); 
    }

    /**
     * Display the specified event
     */
    public function show(Event $event)
    {
        $event->load(['organizer', 'venue', 'pricingTiers']);
        return view('events.show', compact('event'));
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'        => 'required|string|max:255',
                'description'  => 'nullable|string|max:1000',
                'start_time'   => 'required|date',
                'end_time'     => 'required|date|after:start_time',
                'secret_notes' => 'nullable|string|max:500',

                'venue_id'     => 'required|exists:venues,id',

                'pricing_tiers' => 'required|array|min:1',
                'pricing_tiers.*.tier' => 'required|string|max:100',
                'pricing_tiers.*.price' => 'required|numeric|min:1',
                'pricing_tiers.*.available_qty' => 'required|integer|min:1',
                'pricing_tiers.*.description' => 'nullable|string'
            ]);

            if (Auth::guard('admin')->check()) {
                $validated['user_id'] = 1; // Or handle admin-created events differently
            } else {
                $validated['user_id'] = auth()->id();
            }





            // The SecureProxyEventService will handle setting user_id and encryption
            $this->eventService->createEvent($validated);

            return redirect()->route('events.index')->with('success', 'Event created successfully!');
        } catch (ValidationException $e) {
            Log::warning('Event validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Event creation failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while creating the event. Please try again.');
        }
    }


    public function edit(Event $event)
    {
        $event->load(['pricingTiers', 'venue']);
        $venueName = Venue::orderBy('name')->get();

        // Optional: Check authorization before showing edit form
        //$this->authorize('update', $event);
        
        return view('events.edit', compact('event'));
    }


    public function update(Request $request, Event $event)
    {

        try {
            $validated = $request->validate([
                'title'        => 'required|string|max:255',
                'description'  => 'nullable|string|max:1000',
                'start_time'   => 'required|date',
                'end_time'     => 'required|date|after:start_time',
                'secret_notes' => 'nullable|string|max:500',

                'venue_id'     => 'required|exists:venue,id',

                'pricing_tiers' => 'required|array|min:1',
                'pricing_tiers.*.tier' => 'required|string|max:100',
                'pricing_tiers.*.price' => 'required|numeric|min:1',
                'pricing_tiers.*.available_qty' => 'required|integer|min:1',
                'pricing_tiers.*.description' => 'nullable|string'
            ]);

            // The SecureProxyEventService will handle authorization and encryption
            $this->eventService->updateEvent($event, $validated);

            return redirect()->route('events.index')->with('success', 'Event updated successfully!');
        } catch (ValidationException $e) {
            Log::warning('Event update validation failed', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Event update failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while updating the event.');
        }
    }


    public function destroy(Event $event)
    {
        if (!Auth::guard('admin')->check()) {
            $this->authorize('delete', $event);
        }

        try {
            $this->eventService->deleteEvent($event);

            return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Event deletion failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while deleting the event.');
        }
    }

    public function publicIndex()
    {
        $events = Event::with('organizer', 'venue')->get();
        return view('events.public_index', compact('events'));
    }
}
