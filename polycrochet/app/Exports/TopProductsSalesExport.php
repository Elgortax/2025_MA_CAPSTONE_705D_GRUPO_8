<?php

namespace App\Exports;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TopProductsSalesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @var \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    protected Collection $rows;

    public function __construct(
        CarbonInterface $start,
        CarbonInterface $end,
        array $statuses,
        int $limit = 25
    ) {
        $this->rows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.status', $statuses)
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw("COALESCE(products.name, order_items.product_name) as name")
            ->selectRaw("COALESCE(products.sku, order_items.product_sku) as sku")
            ->selectRaw('SUM(order_items.quantity) as units')
            ->selectRaw('SUM(order_items.line_total) as total')
            ->groupByRaw('COALESCE(products.name, order_items.product_name), COALESCE(products.sku, order_items.product_sku)')
            ->orderByDesc('total')
            ->limit($limit)
            ->get()
            ->map(static fn ($row) => [
                'name' => $row->name,
                'sku' => $row->sku,
                'units' => (int) $row->units,
                'total' => (float) $row->total,
            ]);
    }

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return ['Producto', 'SKU', 'Unidades', 'Total (CLP)'];
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public function map($row): array
    {
        return [
            $row['name'],
            $row['sku'],
            $row['units'],
            number_format($row['total'], 0, ',', '.'),
        ];
    }
}
