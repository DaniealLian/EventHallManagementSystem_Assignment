<?php

namespace App\Factories;

use App\Contracts\AdminFactoryInterface;
use App\Contracts\AdminTypeInterface;
use App\Models\Admin;
use App\AdminTypes\SuperAdmin;
use App\AdminTypes\RegularAdmin;
use App\AdminTypes\ModeratorAdmin;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class AdminFactory implements AdminFactoryInterface
{
    protected array $adminTypes = [
        'super' => SuperAdmin::class,
        'regular' => RegularAdmin::class,
        'moderator' => ModeratorAdmin::class,
    ];

    public function createAdmin(string $adminType, array $data): Admin
    {
        $adminTypeInstance = $this->getAdminType($adminType);
        return $adminTypeInstance->createDatabaseRecord($data);
    }

    public function updateAdmin(Admin $admin, array $data): bool
    {
        $updateData = [];

        if (isset($data['name'])) {
            $updateData['name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $updateData['email'] = $data['email'];
        }

        if (isset($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        if (isset($data['phone_number'])) {
            $updateData['phone_number'] = $data['phone_number'];
        }

        if (isset($data['is_active'])) {
            $updateData['is_active'] = $data['is_active'];
        }

        if (isset($data['permissions'])) {
            $updateData['permissions'] = $data['permissions'];
        }

        if (isset($data['admin_type'])) {
            // When admin type changes, update related fields
            $newAdminType = $this->getAdminType($data['admin_type']);
            $defaultAttributes = $newAdminType->getDefaultAttributes();
            $updateData = array_merge($updateData, $defaultAttributes);
        }

        return $admin->update($updateData);
    }

    public function getAdminType(string $type): AdminTypeInterface
    {
        if (!isset($this->adminTypes[$type])) {
            throw new InvalidArgumentException("Unknown admin type: {$type}");
        }

        return new $this->adminTypes[$type]();
    }

    // Security method - only super admins can create other admins
    public function createAdminWithPermissions(string $adminType, array $data, ?Admin $creator = null): Admin
    {
        if ($creator && !$creator->isSuperAdmin()) {
            throw new InvalidArgumentException("Only super admins can create new admin accounts");
        }

        // Super admins can only be created by existing super admins
        if ($adminType === 'super' && $creator && !$creator->isSuperAdmin()) {
            throw new InvalidArgumentException("Only super admins can create other super admins");
        }

        return $this->createAdmin($adminType, $data);
    }
}
