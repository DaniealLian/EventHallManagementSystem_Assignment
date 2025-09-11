<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SecureProxyEventService implements EventServiceInterface
{
    private EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function createEvent(array $data): Event
    {
        Log::info("User " . Auth::id() . " creating event");
        $data['user_id'] = Auth::id(); // enforce ownership
        return $this->eventService->createEvent($data);
    }

    public function updateEvent(Event $event, array $data): Event
    {
        Log::info("User " . Auth::id() . " updating event {$event->id}");
        return $this->eventService->updateEvent($event, $data);
    }

    public function deleteEvent(Event $event): bool
    {
        Log::warning("User " . Auth::id() . " deleting event {$event->id}");
        return $this->eventService->deleteEvent($event);
    }
}

?>