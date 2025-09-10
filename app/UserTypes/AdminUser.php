<?php

namespace App\AdminTypes;

use App\Contracts\AdminTypeInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

// Super Admin Type
class SuperAdmin implements AdminTypeInterface
{
    public function getType(): string
    {
        return 'super';
    }

    public function getPermissions(): array
    {
        return ['*']; // All permissions
    }

    public function isSuperAdmin(): bool
    {
        return true;
    }

    public function getDefaultAttributes(): array
    {
        return [
            'is_super_admin' => true,
            'is_active' => true,
            'permissions' => ['*']
        ];
    }

    public function createDatabaseRecord(array $data): Admin
    {
        $adminData = array_merge($data, $this->getDefaultAttributes());

        return Admin::create([
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => Hash::make($adminData['password']),
            'phone_number' => $adminData['phone_number'] ?? null,
            'is_super_admin' => true,
            'is_active' => true,
            'permissions' => ['*']
        ]);
    }
}

// Regular Admin Type
class RegularAdmin implements AdminTypeInterface
{
    public function getType(): string
    {
        return 'regular';
    }

    public function getPermissions(): array
    {
        return [
            'manage_users',
            'view_reports',
            'manage_halls',
            'approve_applications'
        ];
    }

    public function isSuperAdmin(): bool
    {
        return false;
    }

    public function getDefaultAttributes(): array
    {
        return [
            'is_super_admin' => false,
            'is_active' => true,
            'permissions' => $this->getPermissions()
        ];
    }

    public function createDatabaseRecord(array $data): Admin
    {
        $adminData = array_merge($data, $this->getDefaultAttributes());

        return Admin::create([
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => Hash::make($adminData['password']),
            'phone_number' => $adminData['phone_number'] ?? null,
            'is_super_admin' => false,
            'is_active' => true,
            'permissions' => $this->getPermissions()
        ]);
    }
}

// Moderator Admin Type (Limited permissions)
class ModeratorAdmin implements AdminTypeInterface
{
    public function getType(): string
    {
        return 'moderator';
    }

    public function getPermissions(): array
    {
        return [
            'view_reports',
            'approve_applications',
            'moderate_content'
        ];
    }

    public function isSuperAdmin(): bool
    {
        return false;
    }

    public function getDefaultAttributes(): array
    {
        return [
            'is_super_admin' => false,
            'is_active' => true,
            'permissions' => $this->getPermissions()
        ];
    }

    public function createDatabaseRecord(array $data): Admin
    {
        $adminData = array_merge($data, $this->getDefaultAttributes());

        return Admin::create([
            'name' => $adminData['name'],
            'email' => $adminData['email'],
            'password' => Hash::make($adminData['password']),
            'phone_number' => $adminData['phone_number'] ?? null,
            'is_super_admin' => false,
            'is_active' => true,
            'permissions' => $this->getPermissions()
        ]);
    }
}
