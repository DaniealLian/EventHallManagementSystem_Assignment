<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Services\AdminService;
use App\Factories\AdminFactory;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminFactory = new AdminFactory();
        $adminService = new AdminService($adminFactory);

        // Create Super Admin
        $superAdmin = $adminService->createAdmin('super', [
            'name' => 'Super Admin',
            'email' => 'superadmin@yourapp.com',
            'password' => 'password123',
            'phone_number' => '+1234567890',
        ]);

        // Create Regular Admin (created by super admin)
        $adminService->createAdmin('regular', [
            'name' => 'Regular Admin',
            'email' => 'admin@yourapp.com',
            'password' => 'password123',
            'phone_number' => '+1234567891',
        ], $superAdmin);

        // Create Moderator Admin (created by super admin)
        $adminService->createAdmin('moderator', [
            'name' => 'Moderator Admin',
            'email' => 'moderator@yourapp.com',
            'password' => 'password123',
            'phone_number' => '+1234567892',
        ], $superAdmin);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Super Admin: superadmin@yourapp.com');
        $this->command->info('Regular Admin: admin@yourapp.com');
        $this->command->info('Moderator Admin: moderator@yourapp.com');
        $this->command->info('Password for all: password123');
    }
}
