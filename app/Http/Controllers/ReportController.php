<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('reports.index');
    }

    public function inventory(): View
    {
        $products = Product::with(['category', 'batches.sales'])
            ->withSum('batches', 'quantity')
            ->orderBy('product_name')
            ->get();

        // Calculate sales data for each product
        foreach ($products as $product) {
            $totalSold = 0;
            $totalRevenue = 0;

            foreach ($product->batches as $batch) {
                $totalSold += $batch->sales->sum('quantity');
                $totalRevenue += $batch->sales->sum('total_amount');
            }

            $product->total_sold = $totalSold;
            $product->total_revenue = $totalRevenue;
        }

        return view('reports.inventory', compact('products'));
    }
}
