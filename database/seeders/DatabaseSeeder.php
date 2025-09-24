<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // Membuat data dummy menggunakan factory
        // Pastikan Anda sudah mendefinisikan factory untuk Category, Supplier, dan Medicine
        \App\Models\Category::factory(3)->create();
        \App\Models\Supplier::factory(2)->create();
        \App\Models\Medicine::factory(10)->create();
        $this->call([
            TransactionSeeder::class,
        ]);}
}
