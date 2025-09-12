<?php

namespace App\Contracts;

use App\Models\Event;

interface EventServiceInterface
{
    public function createEvent(array $data): Event;
    public function updateEvent(Event $event, array $data): Event;
    public function deleteEvent(Event $event): bool;
}
?>