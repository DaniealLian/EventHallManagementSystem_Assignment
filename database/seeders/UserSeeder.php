<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\UserService;
use App\Factories\UserFactory;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userFactory = new UserFactory();
        $userService = new UserService($userFactory);

        $userService->register([
            'name'=>'Admin User',
            'email'=>'admin@gmail.com',
            'password'=>'password123',
            'role'=>'admin',
        ]);

        $userService->register([
            'name'=>'Event Manager',
            'email'=>'eventManager@gmail.com',
            'password'=>'password123',
            'role'=>'manager',
        ]);

        $userService->register([
            'name'=>'Danieal User',
            'email'=>'danieal@gmail.com',
            'password'=>'password123',
            'role'=>'customer',
        ]);
    }
}
