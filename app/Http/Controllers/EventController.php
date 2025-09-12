<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
        $events = Event::with('organizer')->get();

        return view('events.index', compact('events'));
    }

    
    public function create()
    {
        return view('events.create');
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        try {
            $validated = $request->validate([
                'title'        => 'required|string|max:255',
                'description'  => 'nullable|string|max:1000',
                'start_time'   => 'required|date',
                'end_time'     => 'required|date|after:start_time',
                'secret_notes' => 'nullable|string|max:500',
            ]);

            $validated['organizer_id'] = Auth::id();

            if (!empty($validated['secret_notes'])) {
                $validated['secret_notes'] = Crypt::encryptString($validated['secret_notes']);
            }

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
        $this->authorize('update', $event);

        return view('events.edit', compact('event'));
    }

    
    public function update(Request $request, Event $event)
    {
        $this->authorize('update', $event);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'date',
            'end_time' => 'date|after:start_time',
        ]);
        try {
            $validated = $request->validate([
                'title'        => 'string|max:255',
                'description'  => 'nullable|string|max:1000',
                'start_time'   => 'date',
                'end_time'     => 'date|after:start_time',
                'secret_notes' => 'nullable|string|max:500',
            ]);

            if (!empty($validated['secret_notes'])) {
                $validated['secret_notes'] = Crypt::encryptString($validated['secret_notes']);
            }

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
        $this->authorize('delete', $event);

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
        $events = Event::with('organizer')->get();
        return view('events.public_index', compact('events'));
    }
}