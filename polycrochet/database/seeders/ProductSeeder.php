<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catalog = collect([
            [
                'name' => 'Ramo Animalitos Gatito Pastel',
                'category' => 'ramos',
                'summary' => 'Bouquet con gatito tejido y flores intercambiables.',
                'description' => 'Bouquet con gatito tejido y flores intercambiables. Personaliza colores y mensaje para sorprender a quien más quieres.',
                'price' => 39990,
            ],
            [
                'name' => 'Ramo Animalitos Pollito Sol',
                'category' => 'ramos',
                'summary' => 'Ramo con pollito y girasoles tejidos a mano.',
                'description' => 'Ramo con pollito y girasoles tejidos a mano. Ideal para cumpleaños, baby showers o para alegrar cualquier día especial.',
                'price' => 36500,
            ],
            [
                'name' => 'Set de Girasoles con Abejas',
                'category' => 'flores',
                'summary' => 'Cinco girasoles con pequeñas abejitas crochet.',
                'description' => 'Cinco girasoles crochet con pequeñas abejitas para iluminar cualquier rincón. Incluye tallos moldeables y tarjeta personalizada.',
                'price' => 22900,
            ],
            [
                'name' => 'Dúo Floral Tonos Rosados',
                'category' => 'flores',
                'summary' => 'Pareja de flores en tonos pastel personalizables.',
                'description' => 'Pareja de flores en tonos pastel. Combina distintos colores para crear tu arreglo perfecto o complementa tus ramos existentes.',
                'price' => 18900,
            ],
            [
                'name' => 'Muñeca Crochet Jardín',
                'category' => 'muñecas',
                'summary' => 'Muñeca personalizada con paleta floral primaveral.',
                'description' => 'Muñeca crochet inspirada en un jardín primaveral. Puedes escoger tonos de vestido, cabello y accesorios para personalizarla por completo.',
                'price' => 28990,
            ],
            [
                'name' => 'Amigurumi Perrito Llavero',
                'category' => 'amigurumis',
                'summary' => 'Pequeño llavero tejido a mano, personalizable.',
                'description' => 'Pequeño llavero tejido a crochet, con opción de personalizar raza y color. Ideal para regalos corporativos o detalles especiales.',
                'price' => 8900,
            ],
        ]);

        $catalog->each(function (array $item, int $index): void {
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                array_merge($item, [
                    'sku' => strtoupper('PC-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT)),
                    'stock' => 10,
                    'is_active' => true,
                    'metadata' => [
                        'tags' => ['hecho a mano', 'personalizable'],
                    ],
                ])
            );

            $product->images()->updateOrCreate(
                ['product_id' => $product->id, 'position' => 0],
                [
                    'path' => 'products/sample-' . ($index + 1) . '.jpg',
                    'disk' => 'public',
                    'alt_text' => $product->name,
                    'is_primary' => true,
                ]
            );

            $product->variants()->updateOrCreate(
                ['product_id' => $product->id, 'is_default' => true],
                [
                    'name' => $product->name . ' estándar',
                    'price' => $product->price,
                    'stock' => 10,
                ]
            );
        });
    }
}
