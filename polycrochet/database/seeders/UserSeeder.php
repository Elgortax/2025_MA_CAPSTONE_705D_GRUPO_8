<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()
            ->admin()
            ->create([
                'first_name' => 'PolyCrochet',
                'last_name' => 'Admin',
                'email' => 'admin@polycrochet.test',
                'phone' => '911111111',
                'password' => 'password',
                'email_verified_at' => now(),
            ]);

        User::factory()
            ->count(10)
            ->create();
    }
}
