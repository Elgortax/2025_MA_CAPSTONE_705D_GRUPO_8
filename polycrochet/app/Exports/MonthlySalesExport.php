<?php

namespace App\Exports;

use App\Models\Order;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MonthlySalesExport implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting
{
    /**
     * @var array<int, \App\Models\Order>
     */
    protected Collection $orders;

    public function __construct(
        CarbonInterface $start,
        CarbonInterface $end,
        array $statuses
    ) {
        $this->orders = Order::query()
            ->whereIn('status', $statuses)
            ->whereBetween('created_at', [$start, $end])
            ->with(['user', 'items'])
            ->orderByDesc('created_at')
            ->get();
    }

    public function collection(): Collection
    {
        return $this->orders;
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Numero de pedido',
            'Cliente',
            'Estado',
            'Unidades',
            'Total (CLP)',
        ];
    }

    /**
     * @param  \App\Models\Order  $order
     */
    public function map($order): array
    {
        $units = $order->items->sum('quantity');

        $status = match ($order->status) {
            'en_produccion' => 'En produccion',
            'enviado' => 'Enviado',
            'pagado' => 'Pagado',
            'pendiente' => 'Pendiente',
            'cancelado' => 'Cancelado',
            'entregado' => 'Entregado',
            default => Str::headline($order->status),
        };

        return [
            $order->created_at->translatedFormat('d/m/Y H:i'),
            $order->order_number,
            optional($order->user)->name ?? 'Invitado',
            $status,
            $units,
            (int) round((float) $order->total),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#,##0',
        ];
    }
}
