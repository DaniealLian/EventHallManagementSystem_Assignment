<?php

namespace App\UserTypes;

use App\Contracts\UserTypeInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ManagerUser implements UserTypeInterface
{
    public function getRole(): string
    {
        return 'manager';
    }

    public function getPermissions(): array
    {
        return [
            'view_halls',
            'make_bookings',
            'view_own_bookings',
            'manage_venue',
            'view_all_bookings',
            'view_events',
            'create_events',
            'edit_events',
            'manage_events'
        ];
    }

    public function canManageHalls(): bool
    {
        return true;
    }

    public function canViewAllBookings(): bool
    {
        return true;
    }

    public function canApproveApplications(): bool
    {
        return false;
    }

    public function getDefaultAttributes(): array
    {
        return [
            'role' => 'manager',
            'manager_status' => 'approved'
        ];
    }

    public function createDatabaseRecord(array $data): User
    {
        $userData = array_merge($data, $this->getDefaultAttributes());

        return User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'phone_number' => $userData['phone_number'] ?? null,
            'role' => $this->getRole(),
            'manager_status' => 'approved'
        ]);
    }
}
