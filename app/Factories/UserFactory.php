<?php
namespace App\Factories;

use App\Contracts\UserFactoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory implements UserFactoryInterface
{
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'role' => $data['role'] ?? 'customer',
            ]);
    }

    public function updateUser($user, array $data)
    {
        $updateData = [];

        if(isset($data['name'])){
            $updateData['name'] = $data['name'];
        }

        if(isset($data['email'])){
            $updateData['email'] = $data['email'];
        }

        if(isset($data['password'])){
            $updateData['password'] = $data['password'];
        }

        if(isset($data['phone_number'])){
            $updateData['phone_number'] = $data['phone_number'];
        }

        if(isset($data['role'])){
            $updateData['role'] = $data['role'];
        }

        return $user->update($updateData);
    }
}