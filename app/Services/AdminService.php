<?php

namespace App\Services;

use App\Contracts\AdminFactoryInterface;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AdminService
{
    protected $adminFactory;

    public function __construct(AdminFactoryInterface $adminFactory)
    {
        $this->adminFactory = $adminFactory;
    }

    public function createAdmin(string $adminType, array $data, ?Admin $creator = null): Admin
    {
        return $this->adminFactory->createAdminWithPermissions($adminType, $data, $creator);
    }

    public function login(array $credentials): Admin|false
    {
        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            $admin->updateLastLogin();
            return $admin;
        }
        return false;
    }

    public function updateProfile(Admin $admin, array $data): bool
    {
        return $this->adminFactory->updateAdmin($admin, $data);
    }

    public function logout(): void
    {
        Auth::guard('admin')->logout();
    }

    public function getAdminPermissions(Admin $admin): array
    {
        if ($admin->isSuperAdmin()) {
            return ['*'];
        }
        return $admin->permissions ?? [];
    }

    public function canAdminPerform(Admin $admin, string $permission): bool
    {
        return $admin->hasPermission($permission);
    }

}
