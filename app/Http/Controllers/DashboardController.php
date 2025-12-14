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

        return view('dashboard', [
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'totalSales' => $totalSales,
            'activeSuppliers' => $activeSuppliers,
        ]);
    }
}
