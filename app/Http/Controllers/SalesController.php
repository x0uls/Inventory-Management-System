<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['batch.product'])
            ->latest('date')
            ->paginate(10);

        $totalTransactions = Sale::count();
        $totalRevenue = Sale::sum('total_amount');

        // Fetch products with available stock
        $products = \App\Models\Product::with(['batches' => function($query) {
            $query->where('quantity', '>', 0);
        }])->whereHas('batches', function($query) {
            $query->where('quantity', '>', 0);
        })->get();

        return view('sales', compact('sales', 'totalTransactions', 'totalRevenue', 'products'));
    }

    public function create()
    {
        // Fetch products that have at least one batch with quantity > 0
        $products = Product::whereHas('batches', function ($query) {
            $query->where('quantity', '>', 0);
        })->with(['batches' => function ($query) {
            $query->where('quantity', '>', 0);
        }])->get();

        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,product_id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'customer_name' => 'nullable|string|max:255',
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            foreach ($request->items as $item) {
                $product = \App\Models\Product::with(['batches' => function($query) {
                    $query->where('quantity', '>', 0)
                          ->orderBy('expiry_date', 'asc');
                }])->find($item['id']);

                $requestedQty = $item['quantity'];
                $totalAvailable = $product->batches->sum('quantity');

                if ($totalAvailable < $requestedQty) {
                    throw new \Exception("Not enough stock for {$product->product_name}. Available: {$totalAvailable}, Requested: {$requestedQty}");
                }

                foreach ($product->batches as $batch) {
                    if ($requestedQty <= 0) break;

                    $deductQty = min($batch->quantity, $requestedQty);
                    
                    // Create sale record
                    Sale::create([
                        'batch_id' => $batch->batch_id,
                        'quantity' => $deductQty,
                        'unit_price' => $product->unit_price,
                        'total_amount' => $deductQty * $product->unit_price,
                        'date' => now(),
                        'customer_name' => $request->customer_name,
                        'payment_method' => $request->payment_method,
                    ]);

                    // Update batch quantity
                    $batch->decrement('quantity', $deductQty);

                    // Check if batch is empty and cleanup QR code
                    if ($batch->refresh()->quantity == 0) {
                        if ($batch->qr_code_path && file_exists(public_path($batch->qr_code_path))) {
                            unlink(public_path($batch->qr_code_path));
                        }
                        $batch->qr_code_path = null;
                        $batch->save();
                    }
                    
                    $requestedQty -= $deductQty;
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return response()->json(['message' => 'Sale completed successfully']);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function show(Sale $sale): View
    {
        return view('sales.show', compact('sale'));
    }
}
