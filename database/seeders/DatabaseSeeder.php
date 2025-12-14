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

        // Seed Categories
        $electronics = \App\Models\Category::create(['category_name' => 'Electronics', 'description' => 'Electronic devices and accessories']);
        $clothing = \App\Models\Category::create(['category_name' => 'Clothing', 'description' => 'Apparel and fashion items']);

        // Seed Suppliers
        $supplierA = \App\Models\Supplier::create(['supplier_name' => 'Tech Supplies Inc', 'contact_person' => 'John Doe', 'email' => 'john@techsupplies.com', 'phone' => '1234567890', 'address' => '123 Tech St']);
        $supplierB = \App\Models\Supplier::create(['supplier_name' => 'Fashion Hub', 'contact_person' => 'Jane Smith', 'email' => 'jane@fashionhub.com', 'phone' => '0987654321', 'address' => '456 Fashion Ave']);

        // Seed Products
        $laptop = \App\Models\Product::create(['product_name' => 'Gaming Laptop', 'description' => 'High performance laptop', 'category_id' => $electronics->category_id, 'supplier_id' => $supplierA->supplier_id, 'lowstock_alert' => 5]);
        $tshirt = \App\Models\Product::create(['product_name' => 'Cotton T-Shirt', 'description' => '100% Cotton', 'category_id' => $clothing->category_id, 'supplier_id' => $supplierB->supplier_id, 'lowstock_alert' => 20]);

        // Seed Batches
        $batch1 = \App\Models\Batch::create(['product_id' => $laptop->product_id, 'batch_number' => 'BATCH-001', 'quantity' => 10, 'expiry_date' => null]);
        $batch2 = \App\Models\Batch::create(['product_id' => $tshirt->product_id, 'batch_number' => 'BATCH-002', 'quantity' => 100, 'expiry_date' => null]);

        // Seed Sales
        \App\Models\Sale::create([
            'batch_id' => $batch1->batch_id,
            'quantity' => 1,
            'unit_price' => 1500.00,
            'total_amount' => 1500.00,
            'date' => now(),
        ]);

        \App\Models\Sale::create([
            'batch_id' => $batch2->batch_id,
            'quantity' => 5,
            'unit_price' => 20.00,
            'total_amount' => 100.00,
            'date' => now()->subDays(1),
        ]);
    }
}
