<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CategorySalesExport;
use App\Exports\MonthlySalesExport;
use App\Exports\TopProductsSalesExport;
use App\Exports\WeeklySalesExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    private const REPORT_STATUSES = ['pagado', 'en_produccion', 'enviado'];

    /**
     * Display analytics for the admin dashboard.
     */
    public function index()
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfPreviousMonth = $startOfMonth->copy()->subMonth();
        $endOfPreviousMonth = $startOfMonth->copy()->subSecond();

        $paidStatuses = self::REPORT_STATUSES;

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

        $categoryBreakdown = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('products as products_by_slug', function ($join) {
                $join->on(DB::raw("JSON_UNQUOTE(order_items.options->'$.slug')"), '=', 'products_by_slug.slug');
            })
            ->leftJoin('products as products_by_name', function ($join) {
                $join->on('order_items.product_name', '=', 'products_by_name.name');
            })
            ->whereIn('orders.status', $paidStatuses)
            ->whereBetween('orders.created_at', [$startOfMonth, $now])
            ->selectRaw("COALESCE(NULLIF(products.category, ''), NULLIF(products_by_slug.category, ''), NULLIF(products_by_name.category, ''), 'sin_categoria') as category")
            ->selectRaw('SUM(order_items.line_total) as total')
            ->selectRaw('SUM(order_items.quantity) as units')
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'category' => $row->category === 'sin_categoria'
                    ? 'Sin categoria'
                    : Str::title($row->category),
                'total' => (float) $row->total,
                'units' => (int) $row->units,
            ]);

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->leftJoin('products', 'order_items.product_id', '=', 'products.id')
            ->whereIn('orders.status', $paidStatuses)
            ->whereBetween('orders.created_at', [$startOfMonth, $now])
            ->selectRaw("COALESCE(products.name, order_items.product_name) as name")
            ->selectRaw("COALESCE(products.sku, order_items.product_sku) as sku")
            ->selectRaw('SUM(order_items.line_total) as total')
            ->selectRaw('SUM(order_items.quantity) as units')
            ->groupByRaw('COALESCE(products.name, order_items.product_name), COALESCE(products.sku, order_items.product_sku)')
            ->orderByDesc('total')
            ->limit(8)
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'sku' => $row->sku,
                'total' => (float) $row->total,
                'units' => (int) $row->units,
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
            'categoryBreakdown' => $categoryBreakdown,
            'topProducts' => $topProducts,
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

    /**
     * Download the orders of the current month as Excel.
     */
    public function downloadMonthlySales(Request $request)
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $fileName = 'ventas-mensuales-' . $now->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new MonthlySalesExport($startOfMonth, $now, self::REPORT_STATUSES),
            $fileName
        );
    }

    /**
     * Download the monthly sales grouped by category.
     */
    public function downloadCategorySales(Request $request)
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $fileName = 'ventas-categorias-' . $now->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new CategorySalesExport($startOfMonth, $now, self::REPORT_STATUSES),
            $fileName
        );
    }

    /**
     * Download the monthly sales grouped by product.
     */
    public function downloadProductSales(Request $request)
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $fileName = 'ventas-productos-' . $now->format('Ymd_His') . '.xlsx';

        return Excel::download(
            new TopProductsSalesExport($startOfMonth, $now, self::REPORT_STATUSES),
            $fileName
        );
    }
}
