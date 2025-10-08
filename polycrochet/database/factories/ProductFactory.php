<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = Str::title($this->faker->unique()->words(3, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => strtoupper($this->faker->bothify('PC-#####')),
            'category' => $this->faker->randomElement(['ramos', 'flores', 'amigurumis', 'muñecas']),
            'summary' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->randomFloat(0, 15990, 69990),
            'stock' => $this->faker->numberBetween(0, 50),
            'is_active' => $this->faker->boolean(80),
            'metadata' => [
                'materials' => $this->faker->randomElements(['algodón', 'lino', 'acrílico'], 2),
                'care' => $this->faker->randomElement(['Lavar a mano', 'Secar a la sombra', 'No planchar']),
            ],
        ];
    }
}
