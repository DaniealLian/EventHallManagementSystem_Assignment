<?php

namespace App\Services;

use App\Contracts\userFactoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userFactory;

    public function _construct(UserFactoryInterface $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function register(array $data)
    {
        $user = $this->userFactory->createUser($data);
        return $user;
    }

    public function login(array $credentials)
    {
        if(Auth::attempt($credentials)){
            return  Auth::user();
        }
        return false;
    }

    public function updateProfile($user, array $data)
    {
        return $this->userFactory->updateUser($user,$data);
    }

    public function logout()
    {
        Auth::logout();
    }

}