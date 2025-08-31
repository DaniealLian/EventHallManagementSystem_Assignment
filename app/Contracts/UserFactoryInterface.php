<?php

namespace App\Contracts;

interface UserFactoryInterface
{
    public function createUser(array $data);
    public function updateUser($user, array $data);
}