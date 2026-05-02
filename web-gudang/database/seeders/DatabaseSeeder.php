<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@khwarizmi.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Regular User
        User::create([
            'name' => 'Pak Budi',
            'email' => 'budi@khwarizmi.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Categories
        $atk = Category::create(['name' => 'ATK', 'icon' => '🖋️']);
        $elektronik = Category::create(['name' => 'Elektronik', 'icon' => '🔌']);
        $kebersihan = Category::create(['name' => 'Kebersihan', 'icon' => '🧹']);

        // Items
        Item::create([
            'code' => 'BRG-001',
            'name' => 'Spidol Snowman Hitam',
            'category_id' => $atk->id,
            'stock' => 50,
            'unit' => 'Pcs',
            'icon' => '🖋️',
        ]);

        Item::create([
            'code' => 'BRG-002',
            'name' => 'Kertas HVS A4 80gr',
            'category_id' => $atk->id,
            'stock' => 20,
            'unit' => 'Rim',
            'icon' => '📄',
        ]);

        Item::create([
            'code' => 'BRG-003',
            'name' => 'Router Mikrotik',
            'category_id' => $elektronik->id,
            'stock' => 2,
            'unit' => 'Pcs',
            'icon' => '🔌',
        ]);
    }
}
