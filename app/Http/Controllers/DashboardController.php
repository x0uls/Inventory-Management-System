<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Statistics
        $totalProducts = Product::count();
        $totalSales = Sale::count();
        $activeSuppliers = Supplier::count();

        // Calculate low stock items
        try {
            // Check if batch table exists
            if (!\Illuminate\Support\Facades\Schema::hasTable('batch')) {
                $lowStockCount = 0;
            } else {
                $lowStockCount = Product::withSum('batches', 'quantity')
                    ->get()
                    ->filter(function ($product) {
                        $totalQuantity = $product->batches_sum_quantity ?? 0;
                        return $totalQuantity <= $product->lowstock_alert;
                    })
                    ->count();
            }
        } catch (\Exception $e) {
            // If there's any error (table doesn't exist, etc.), return 0
            $lowStockCount = 0;
        }



        // Charts Data

        // 1. Revenue by Category
        $revenueByCategory = Category::select('categories.category_name')
            ->join('products', 'categories.category_id', '=', 'products.category_id')
            ->join('batch', 'products.product_id', '=', 'batch.product_id')
            ->join('sales', 'batch.batch_id', '=', 'sales.batch_id')
            ->selectRaw('SUM(sales.total_amount) as total_revenue')
            ->groupBy('categories.category_name')
            ->get();

        // 2. Top Selling Products
        $topSellingProducts = Product::select('products.product_name')
            ->join('batch', 'products.product_id', '=', 'batch.product_id')
            ->join('sales', 'batch.batch_id', '=', 'sales.batch_id')
            ->selectRaw('SUM(sales.quantity) as total_sold')
            ->groupBy('products.product_name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // 3. Sales Trend (Last 30 Days)
        $salesTrend = Sale::selectRaw('DATE(date) as sale_date, SUM(total_amount) as total')
            ->where('date', '>=', now()->subDays(30))
            ->groupBy('sale_date')
            ->orderBy('sale_date')
            ->get();

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'totalSales' => $totalSales,
            'activeSuppliers' => $activeSuppliers,
            'revenueByCategory' => $revenueByCategory,
            'topSellingProducts' => $topSellingProducts,
            'salesTrend' => $salesTrend,
        ]);
    }
}
