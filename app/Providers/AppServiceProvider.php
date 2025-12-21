<?php

namespace App\Providers;

use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        // Share low stock count with all views (cached for 2 minutes)
        View::composer('*', function ($view) {
            try {
                // Check if batch table exists
                if (!Schema::hasTable('batch')) {
                    $lowStockCount = 0;
                } else {
                    // More efficient: use raw query to count products where total quantity <= lowstock_alert
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

            $view->with('lowStockCount', $lowStockCount);
        });
    }
}
