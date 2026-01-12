<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@anhkiet.store'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_admin' => true,
                'phone' => '0123456789',
            ]
        );

        echo "Admin user created!\n";
        echo "Email: admin@anhkiet.store\n";
        echo "Password: admin123\n";
    }
}
