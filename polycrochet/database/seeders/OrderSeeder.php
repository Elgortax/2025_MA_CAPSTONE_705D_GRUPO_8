<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::query()->get();

        if ($products->isEmpty()) {
            $this->command?->warn('No hay productos para generar pedidos.');
            return;
        }

        $customers = User::query()
            ->where('role', 'customer')
            ->get();

        if ($customers->isEmpty()) {
            $this->command?->warn('No hay clientes para generar pedidos.');
            return;
        }

        $statusOptions = [
            'pendiente',
            'pagado',
            'en_produccion',
            'enviado',
            'entregado',
            'cancelado',
        ];

        foreach ($customers as $customer) {
            $ordersToCreate = fake()->numberBetween(1, 3);

            for ($i = 0; $i < $ordersToCreate; $i++) {
                $selectedStatus = Arr::random($statusOptions);
                $shippingCost = fake()->randomElement([0, 4500]);
                $order = Order::create([
                    'uuid' => (string) Str::uuid(),
                    'order_number' => strtoupper('PC-' . Str::random(8)),
                    'user_id' => $customer->id,
                    'status' => $selectedStatus,
                    'items_total' => 0,
                    'shipping_total' => $shippingCost,
                    'total' => 0,
                    'currency' => 'CLP',
                    'paid_at' => in_array($selectedStatus, ['pagado', 'en_produccion', 'enviado', 'entregado'], true)
                        ? now()->subDays(fake()->numberBetween(1, 30))
                        : null,
                    'shipped_at' => in_array($selectedStatus, ['enviado', 'entregado'], true)
                        ? now()->subDays(fake()->numberBetween(1, 15))
                        : null,
                    'billing_data' => null,
                    'shipping_data' => null,
                ]);

                $items = $products->random(fake()->numberBetween(1, min(3, $products->count())));
                $itemsTotal = 0;

                foreach ($items as $product) {
                    $quantity = fake()->numberBetween(1, 3);
                    $unitPrice = (float) $product->price;
                    $lineTotal = $quantity * $unitPrice;
                    $itemsTotal += $lineTotal;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku ?? null,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                        'options' => null,
                    ]);
                }

                $order->update([
                    'items_total' => $itemsTotal,
                    'total' => $itemsTotal + $shippingCost,
                ]);
            }
        }
    }
}
