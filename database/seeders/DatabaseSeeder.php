<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Inventory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicate conflicts on reseeding
        User::truncate();
        Inventory::truncate();

        // Seed Users
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'SuperAdmin',
        ]);

        User::create([
            'name' => 'Sales Staff',
            'email' => 'sales@example.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
        ]);

        User::create([
            'name' => 'Purchase Staff',
            'email' => 'purchase@example.com',
            'password' => Hash::make('password'),
            'role' => 'Purchase',
        ]);

        User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'role' => 'Manager',
        ]);

        // Seed Sample Inventories
        Inventory::create([
            'code' => 'INV-001',
            'name' => 'Laptop Asus ROG',
            'price' => 15000000.00,
            'stock' => 10,
        ]);

        Inventory::create([
            'code' => 'INV-002',
            'name' => 'Mouse Logitech G Pro',
            'price' => 1500000.00,
            'stock' => 50,
        ]);

        Inventory::create([
            'code' => 'INV-003',
            'name' => 'Keyboard Keychron K2',
            'price' => 1200000.00,
            'stock' => 30,
        ]);

        Inventory::create([
            'code' => 'INV-004',
            'name' => 'Monitor Dell UltraSharp',
            'price' => 5000000.00,
            'stock' => 15,
        ]);
    }
}
