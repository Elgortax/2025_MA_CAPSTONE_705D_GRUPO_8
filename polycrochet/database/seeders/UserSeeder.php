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
        $regions = \App\Models\Region::with('communes')->get()->filter(
            fn ($region) => $region->communes->isNotEmpty()
        );

        $admin = User::factory()
            ->admin()
            ->create([
                'first_name' => 'PolyCrochet',
                'last_name' => 'Admin',
                'email' => 'admin@polycrochet.test',
                'phone' => '911111111',
                'password' => 'password',
                'email_verified_at' => now(),
            ]);

        $this->attachAddress($admin, $regions->firstWhere('code', 'RM') ?? $regions->first());

        User::factory()
            ->count(10)
            ->create()
            ->each(function (User $user) use ($regions): void {
                $this->attachAddress($user, $regions->random());
            });
    }

    /**
     * Attach a default address to the given user.
     */
    protected function attachAddress(User $user, ?\App\Models\Region $region): void
    {
        if (! $region?->communes?->count()) {
            return;
        }

        $faker = fake('es_CL');
        $commune = $region->communes->random();

        $user->addresses()->create([
            'region_id' => $region->id,
            'commune_id' => $commune->id,
            'street' => $faker->streetName(),
            'number' => (string) $faker->buildingNumber(),
            'apartment' => $faker->optional(0.3)->bothify('Depto ##?'),
            'reference' => $faker->optional()->sentence(6),
            'postal_code' => null,
            'is_default' => true,
        ]);
    }
}
