<?php

namespace App\Factories;

use App\Contracts\UserFactoryInterface;
use App\Contracts\UserTypeInterface;
use App\Models\User;
use App\UserTypes\CustomerUser;
use App\UserTypes\ManagerUser;
use App\UserTypes\AdminUser;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UserFactory implements UserFactoryInterface
{
    protected array $userTypes = [
        'customer' => CustomerUser::class,
        'manager' => ManagerUser::class,
        'admin' => AdminUser::class,
    ];

    public function createUser(string $userType, array $data): User
    {
        $userTypeInstance = $this->getUserType($userType);
        return $userTypeInstance->createDatabaseRecord($data);
    }

    public function updateUser(User $user, array $data): bool
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

        if (isset($data['role'])) {
            // When role changes, update related fields
            $newUserType = $this->getUserType($data['role']);
            $defaultAttributes = $newUserType->getDefaultAttributes();
            $updateData = array_merge($updateData, $defaultAttributes);
        }

        return $user->update($updateData);
    }

    public function getUserType(string $role): UserTypeInterface
    {
        if (!isset($this->userTypes[$role])) {
            throw new InvalidArgumentException("Unknown user type: {$role}");
        }

        return new $this->userTypes[$role]();
    }

    // Additional factory method for creating users with permissions check
    public function createUserWithPermissions(string $userType, array $data, ?User $creator = null): User
    {
        // Business logic: Only admins can create managers/admins
        if (in_array($userType, ['manager', 'admin']) && $creator && !$creator->isAdmin()) {
            throw new InvalidArgumentException("Insufficient permissions to create {$userType}");
        }

        return $this->createUser($userType, $data);
    }
}