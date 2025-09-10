<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create single predefined admin
        Admin::updateOrCreate(
            ['email' => 'admin@eventhall.com'],
            [
                'name' => 'System Admin',
                'email' => 'admin@eventhall.com',
                'password' => Hash::make('admin123'),
                'phone_number' => '+60123456789',
                'is_super_admin' => true,
                'is_active' => true,
                'permissions' => ['*'], // All permissions
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@eventhall.com');
        $this->command->info('Password: admin123');
    }
}
