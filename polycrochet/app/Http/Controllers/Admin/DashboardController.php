<?php

namespace App\Http\Controllers\Admin;

use App\Exports\WeeklySalesExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    /**
     * Display analytics for the admin dashboard.
     */
    public function index()
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfPreviousMonth = $startOfMonth->copy()->subMonth();
        $endOfPreviousMonth = $startOfMonth->copy()->subSecond();

        $paidStatuses = ['pagado', 'en_produccion', 'enviado'];

        $currentMonthOrders = Order::query()
            ->whereIn('status', $paidStatuses)
            ->whereBetween('created_at', [$startOfMonth, $now])
            ->with('items')
            ->get();

        $previousMonthOrders = Order::query()
            ->whereIn('status', $paidStatuses)
            ->whereBetween('created_at', [$startOfPreviousMonth, $endOfPreviousMonth])
            ->get();

        $monthSales = $currentMonthOrders->sum('total');
        $monthUnits = $currentMonthOrders->flatMap->items->sum('quantity');

        $previousMonthSales = $previousMonthOrders->sum('total');
        $salesVariance = $previousMonthSales > 0
            ? (($monthSales - $previousMonthSales) / $previousMonthSales) * 100
            : null;

        $chartPoints = Order::query()
            ->selectRaw('DATE(created_at) as day, SUM(total) as total')
            ->whereIn('status', $paidStatuses)
            ->whereBetween('created_at', [$startOfMonth, $now])
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn ($row) => [
                'day' => Carbon::parse($row->day)->translatedFormat('d M'),
                'total' => (float) $row->total,
            ]);

        $metrics = [
            'month_sales' => $monthSales,
            'month_units' => $monthUnits,
            'sales_variance' => $salesVariance,
            'orders_count' => $currentMonthOrders->count(),
        ];

        return view('admin.dashboard', [
            'metrics' => $metrics,
            'chartPoints' => $chartPoints,
            'recentOrders' => Order::query()
                ->latest()
                ->take(5)
                ->with('user')
                ->get(),
        ]);
    }

    /**
     * Download the orders of the last 7 days as Excel.
     */
    public function downloadWeeklySales(Request $request)
    {
        $fileName = 'ventas-semanales-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new WeeklySalesExport, $fileName);
    }
}
