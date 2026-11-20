<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@inka.co.id'],
            [
                'name' => 'Administrator INKA',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Seed Akun Pembeli
        User::firstOrCreate(
            ['email' => 'pembeli@example.com'],
            [
                'name' => 'Pembeli Demo',
                'password' => bcrypt('password'),
                'role' => 'pembeli',
            ]
        );

        // Update/create test user
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
                'role' => 'pembeli',
            ]
        );

        $this->call([
            // KategoriSeeder::class,
            // TahunSeeder::class,
            // VolumeMesinSeeder::class,
            // ModelSeeder::class,
            // PartSeeder::class,
            // StokSeeder::class,
        ]);
    }
}
