<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create user groups
        $adminGroup = Group::firstOrCreate(
            ['group_name' => 'Admin'],
            ['description' => 'Administrator group with full system access']
        );

        $staffGroup = Group::firstOrCreate(
            ['group_name' => 'Staff'],
            ['description' => 'Staff group with limited access']
        );

        // Create default admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'roles' => 'admin',
                'group_id' => $adminGroup->group_id,
            ]
        );

        // Create default staff
        User::firstOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('staff123'),
                'roles' => 'staff',
                'group_id' => $staffGroup->group_id,
            ]
        );

        // Call the Convenience Store Seeder
        $this->call(ConvenienceStoreSeeder::class);
    }
}
