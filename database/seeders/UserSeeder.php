<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@farmasi.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');

        $apoteker = User::create([
            'name' => 'Apoteker User',
            'email' => 'apoteker@farmasi.com',
            'password' => Hash::make('password'),
        ]);
        $apoteker->assignRole('Apoteker');

        $kasir = User::create([
            'name' => 'Kasir User',
            'email' => 'kasir@farmasi.com',
            'password' => Hash::make('password'),
        ]);
        $kasir->assignRole('Kasir');
    }
}