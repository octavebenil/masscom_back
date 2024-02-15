<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            [
                'email' => 'admin@masscom.com',
            ],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => bcrypt('12345678')
            ]);
    }
}
