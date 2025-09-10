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
        // Use a different guard for admin authentication
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

    // Get admin permissions through factory
    public function getAdminPermissions(Admin $admin): array
    {
        if ($admin->isSuperAdmin()) {
            return ['*'];
        }
        return $admin->permissions ?? [];
    }

    // Check if admin can perform action
    public function canAdminPerform(Admin $admin, string $permission): bool
    {
        return $admin->hasPermission($permission);
    }

    // Deactivate admin (soft delete alternative)
    // public function deactivateAdmin(Admin $admin, Admin $deactivatedBy): bool
    // {
    //     if (!$deactivatedBy->canManageAdmins()) {
    //         throw new \InvalidArgumentException("Insufficient permissions to deactivate admin");
    //     }

    //     if ($admin->isSuperAdmin() && !$deactivatedBy->isSuperAdmin()) {
    //         throw new \InvalidArgumentException("Cannot deactivate super admin");
    //     }

    //     return $admin->update(['is_active' => false]);
    // }

    // public function activateAdmin(Admin $admin, Admin $activatedBy): bool
    // {
    //     if (!$activatedBy->canManageAdmins()) {
    //         throw new \InvalidArgumentException("Insufficient permissions to activate admin");
    //     }

    //     return $admin->update(['is_active' => true]);
    // }
}
