<?php

namespace App\UserTypes;

use App\Contracts\UserTypeInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerUser implements UserTypeInterface
{
    public function getRole(): string
    {
        return 'customer';
    }

    public function getPermissions(): array
    {
        return [
            'view_halls',
            'make_bookings',
            'view_own_bookings',
            'apply_for_manager'
        ];
    }

    public function canManageHalls(): bool
    {
        return false;
    }

    public function canViewAllBookings(): bool
    {
        return false;
    }

    public function canApproveApplications(): bool
    {
        return false;
    }

    public function getDefaultAttributes(): array
    {
        return [
            'role' => 'customer',
            'manager_status' => 'none'
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
            'manager_status' => 'none'
        ]);
    }
}