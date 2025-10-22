<?php

namespace App\Exports;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategorySalesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    /**
     * @var \Illuminate\Support\Collection<int, array<string, mixed>>
     */
    protected Collection $rows;

    public function __construct(
        CarbonInterface $start,
        CarbonInterface $end,
        array $statuses
    ) {
        $this->rows = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('products as products_by_slug', function ($join) {
                $join->on(DB::raw("JSON_UNQUOTE(order_items.options->'$.slug')"), '=', 'products_by_slug.slug');
            })
            ->leftJoin('products as products_by_name', function ($join) {
                $join->on('order_items.product_name', '=', 'products_by_name.name');
            })
            ->whereIn('orders.status', $statuses)
            ->whereBetween('orders.created_at', [$start, $end])
            ->selectRaw("COALESCE(NULLIF(products.category, ''), NULLIF(products_by_slug.category, ''), NULLIF(products_by_name.category, ''), 'sin_categoria') as category")
            ->selectRaw('SUM(order_items.quantity) as units')
            ->selectRaw('SUM(order_items.line_total) as total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(static fn ($row) => [
                'category' => $row->category === 'sin_categoria'
                    ? 'Sin categoria'
                    : Str::title($row->category),
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
        return ['Categoria', 'Unidades', 'Total (CLP)'];
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public function map($row): array
    {
        return [
            $row['category'],
            $row['units'],
            (int) round($row['total']),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => '#,##0',
        ];
    }
}
