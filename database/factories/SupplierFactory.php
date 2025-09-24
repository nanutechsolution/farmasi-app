<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'           => $this->faker->company(),
            'contact_person' => $this->faker->name(),
            'phone'          => $this->faker->phoneNumber(),
            'address'        => $this->faker->address(),
        ];
    }
}