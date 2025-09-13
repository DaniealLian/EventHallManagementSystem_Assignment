<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;

class SecureProxyEventService implements EventServiceInterface
{
    private EventService $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Securely create a new event
     */
    public function createEvent(array $data): Event
    {
        try {
            $userId = Auth::id();

            // Enforce ownership → organizer_id is always the current user
            $data['organizer_id'] = $userId;

            // Encrypt sensitive notes
            if (!empty($data['secret_notes'])) {
                $data['secret_notes'] = Crypt::encryptString($data['secret_notes']);
            }

            $event = $this->eventService->createEvent($data);

            Log::info("Event created securely", [
                'event_id' => $event->id,
                'user_id' => $userId
            ]);

            return $event;
        } catch (ValidationException $e) {
            Log::warning("Event creation validation failed", [
                'user_id' => Auth::id(),
                'errors' => $e->errors()
            ]);
            throw $e; // bubble up validation errors safely
        } catch (\Exception $e) {
            Log::error("Unexpected error creating event", [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            throw $e; // rethrow for controller to handle
        }
    }

    /**
     * Securely update an event
     */
    public function updateEvent(Event $event, array $data): Event
    {
        try {
            $userId = Auth::id();

            // Authorization check → only organizer or admin can update
            if ($event->organizer_id !== $userId && Auth::user()->role !== 'admin') {
                Log::warning("Unauthorized update attempt", [
                    'user_id' => $userId,
                    'event_id' => $event->id
                ]);
                throw new AuthorizationException("You are not authorized to update this event.");
            }

            // Encrypt secret notes again if updating
            if (!empty($data['secret_notes'])) {
                $data['secret_notes'] = Crypt::encryptString($data['secret_notes']);
            }

            $updatedEvent = $this->eventService->updateEvent($event, $data);

            Log::info("Event updated securely", [
                'event_id' => $event->id,
                'user_id' => $userId
            ]);

            return $updatedEvent;
        } catch (\Exception $e) {
            Log::error("Unexpected error updating event", [
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Securely delete an event
     */
    public function deleteEvent(Event $event): bool
    {
        try {
            $userId = Auth::id();

            // Authorization check
            if ($event->organizer_id !== $userId && Auth::user()->role !== 'admin') {
                Log::warning("Unauthorized delete attempt", [
                    'user_id' => $userId,
                    'event_id' => $event->id
                ]);
                throw new AuthorizationException("You are not authorized to delete this event.");
            }

            $deleted = $this->eventService->deleteEvent($event);

            if ($deleted) {
                Log::warning("Event deleted securely", [
                    'event_id' => $event->id,
                    'user_id' => $userId
                ]);
            }

            return $deleted;
        } catch (\Exception $e) {
            Log::error("Unexpected error deleting event", [
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function addPricingTiers(Event $event, array $tiers): void
    {
        $this->eventService->addPricingTiers($event, $tiers);
    }
}