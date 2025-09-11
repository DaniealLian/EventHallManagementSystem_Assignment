<?php

namespace App\Services;

use App\Models\Event;

class EventService implements EventServiceInterface
{
    public function createEvent(array $data): Event
    {
        return Event::create($data);
    }

    public function updateEvent(Event $event, array $data): Event
    {
        $event->update($data);
        return $event;
    }

    public function deleteEvent(Event $event): bool
    {
        return $event->delete();
    }
}

?>