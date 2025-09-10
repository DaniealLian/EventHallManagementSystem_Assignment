<?php

namespace App\AdminTypes;

use App\Contracts\AdminTypeInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class SuperAdmin implements AdminTypeInterface
{
    public function getType(): string
    {
        return 'super';
    }

    public function getPermissions(): array
    {
        return ['*'];
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
