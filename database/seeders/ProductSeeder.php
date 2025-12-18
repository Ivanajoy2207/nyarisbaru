<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Demo Seller',
            'email' => 'seller@example.com',
        ]);

        Product::factory()->count(20)->create([
            'user_id' => $user->id,
        ]);
    }
}
