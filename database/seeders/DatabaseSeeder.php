<?php

namespace Database\Seeders;

use App\Models\Supplier;
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

                // Data Master
            RealisticCategorySeeder::class,
            RealisticLocationSeeder::class,
            RealisticMedicineSeeder::class,
            SupplierSeeder::class,
            SettingsSeeder::class,
        ]);

    }
}
