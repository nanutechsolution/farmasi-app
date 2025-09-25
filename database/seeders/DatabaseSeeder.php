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
            PermissionSeeder::class,
            UserSeeder::class,
        ]);

        // Membuat data dummy menggunakan factory
        // Pastikan Anda sudah mendefinisikan factory untuk Category, Supplier, dan Medicine
        \App\Models\Supplier::factory(2)->create();
        $this->call([
            RealisticMedicineSeeder::class,
        ]);
        $this->call([
            // TransactionSeeder::class,
               SettingsSeeder::class,
        ]);
    }
}
