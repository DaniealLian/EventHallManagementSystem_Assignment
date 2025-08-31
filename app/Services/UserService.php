<?php

namespace App\Services;

use App\Contracts\UserFactoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userFactory;

    public function __construct(UserFactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function register(array $data): User
    {
        // Default to customer for registration
        $userType = $data['role'] ?? 'customer';
        return $this->userFactory->createUser($userType, $data);
    }

    public function createUserWithRole(string $role, array $data): User
    {
        return $this->userFactory->createUser($role, $data);
    }

    public function login(array $credentials): User|false
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }
        return false;
    }

    public function updateProfile(User $user, array $data): bool
    {
        return $this->userFactory->updateUser($user, $data);
    }

    public function logout(): void
    {
        Auth::logout();
    }

    // Get user permissions through factory
    public function getUserPermissions(User $user): array
    {
        $userType = $this->userFactory->getUserType($user->role);
        return $userType->getPermissions();
    }

    // Check if user can perform action
    public function canUserPerform(User $user, string $permission): bool
    {
        $permissions = $this->getUserPermissions($user);
        return in_array('*', $permissions) || in_array($permission, $permissions);
    }
}