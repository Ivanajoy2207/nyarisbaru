<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'   => 1,
            'name'      => $this->faker->words(3, true),
            'category'  => $this->faker->randomElement(['Fashion Wanita',
                                                        'Fashion Pria',
                                                        'Beauty & Skincare',
                                                        'Sepatu & Sneakers',
                                                        'Tas & Aksesoris',
                                                        'Elektronik & Gadget',
                                                        'Buku & Alat Kuliah',
                                                        'Hobi (kamera, musik, game)',
                                                        'Peralatan Rumah / Kost',
                                                        'Bayi & Anak',
                                                        'Olahraga',
                                                        'Kesehatan']),
            'city'      => $this->faker->randomElement(['Jakarta','Bandung','Surabaya','Depok']),
            'price'     => $this->faker->numberBetween(20000, 2000000),
            'condition' => $this->faker->numberBetween(70, 100),
            'buy_year'  => $this->faker->numberBetween(2018, 2024),
            'description' => $this->faker->sentence(12),
        ];
    }

}
