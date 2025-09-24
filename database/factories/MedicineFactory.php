<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'          => 'Obat ' . $this->faker->word(),
            'category_id'   => Category::inRandomOrder()->first()->id, // Ambil ID kategori acak
            'stock'         => $this->faker->numberBetween(10, 100),
            'price'         => $this->faker->randomFloat(2, 5000, 100000),
            'unit'          => $this->faker->randomElement(['strip', 'botol', 'tablet']),
            'expired_date'  => $this->faker->dateTimeBetween('+1 month', '+2 years'),
        ];
    }
}