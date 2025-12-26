<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category')->withSum('batches', 'quantity');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by low stock
        if ($request->filled('filter') && $request->filter === 'low_stock') {
            $products = $query->get()->filter(function ($product) {
                $totalQuantity = $product->batches_sum_quantity ?? 0;

                return $totalQuantity <= $product->lowstock_alert;
            });
        } else {
            $products = $query->get();
        }

        // Paginate manually since we're filtering in memory
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $items = $products->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $products->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax()) {
            return view('stock.partials.table', ['products' => $paginator]);
        }

        return view('stock.index', [
            'products' => $paginator,
            'categories' => Category::orderBy('category_name')->get(),
            'currentSearch' => $request->search,
            'currentFilter' => $request->filter ?? 'all',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'batch_number' => ['nullable', 'string'],
            'product_id' => ['required', 'exists:products,product_id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'expiry_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '') {
                        $parts = explode('-', $value);
                        if (count($parts) === 3) {
                            $year = (int) $parts[0];
                            $currentYear = (int) date('Y');
                            $maxYear = $currentYear + 100;
                            
                            if ($year < $currentYear || $year > $maxYear) {
                                $fail("The expiry date year must be between {$currentYear} and {$maxYear}.");
                            }
                        }
                    }
                },
            ],
        ]);

        $batchNumber = $request->batch_number;

        // Auto-generate batch number if not provided
        if (empty($batchNumber)) {
            $batchNumber = 'BATCH-'.time().'-'.rand(1000, 9999);
        } else {
            // Check if provided batch_number already exists
            $existingBatch = Batch::where('batch_number', $batchNumber)->first();
            if ($existingBatch) {
                $existingBatch->quantity += $request->quantity;
                $existingBatch->save();

                return redirect()->route('stock.index')
                    ->with('success', 'Stock quantity updated for existing batch: '.$batchNumber);
            }
        }

        // Generate QR Code Image (SVG)
        $options = new \chillerlan\QRCode\QROptions([
            'version' => 5,
            'outputType' => \chillerlan\QRCode\QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel' => \chillerlan\QRCode\QRCode::ECC_L,
            'imageBase64' => false,
        ]);

        $qrcode = new \chillerlan\QRCode\QRCode($options);
        $barcodeData = $qrcode->render($batchNumber);
        $barcodePath = 'barcodes/'.$batchNumber.'.svg';

        // Ensure directory exists
        if (! file_exists(public_path('barcodes'))) {
            mkdir(public_path('barcodes'), 0755, true);
        }

        file_put_contents(public_path($barcodePath), $barcodeData);

        Batch::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'batch_number' => $batchNumber,
            'expiry_date' => $request->expiry_date,
            'qr_code_path' => $barcodePath,
        ]);

        return redirect()->route('stock.index')
            ->with('success', 'Stock added successfully.');
    }

    public function findByBarcode(Request $request)
    {
        $request->validate([
            'batch_number' => ['required', 'string'],
        ]);

        $batch = Batch::with('product')->where('batch_number', $request->batch_number)->first();

        if ($batch && $batch->product) {
            return response()->json([
                'found' => true,
                'batch' => [
                    'batch_id' => $batch->batch_id,
                    'batch_number' => $batch->batch_number,
                    'quantity' => $batch->quantity,
                    'expiry_date' => $batch->expiry_date ? $batch->expiry_date->format('Y-m-d') : null,
                    'product' => [
                        'product_id' => $batch->product->product_id,
                        'product_name' => $batch->product->product_name,
                        'description' => $batch->product->description,
                    ],
                ],
            ]);
        }

        // Try to find product by barcode pattern or other method
        // For now, return not found
        return response()->json([
            'found' => false,
            'message' => 'Barcode not found. Please select a product manually.',
        ]);
    }

    public function getBatches($productId)
    {
        $batches = Batch::where('product_id', $productId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json(['batches' => $batches]);
    }

    public function updateBatch(Request $request, $id)
    {
        // Debugging ping
        // \Illuminate\Support\Facades\Log::info("UpdateBatch hit for ID: $id");

        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
            'expiry_date' => [
                'nullable',
                'date',
                'date_format:Y-m-d',
                function ($attribute, $value, $fail) {
                    if ($value !== null && $value !== '') {
                        $parts = explode('-', $value);
                        if (count($parts) === 3) {
                            $year = (int) $parts[0];
                            $currentYear = (int) date('Y');
                            $maxYear = $currentYear + 100;
                            
                            if ($year < $currentYear || $year > $maxYear) {
                                $fail("The expiry date year must be between {$currentYear} and {$maxYear}.");
                            }
                        }
                    }
                },
            ],
        ]);

        $batch = Batch::findOrFail($id);
        $batch->quantity = $request->quantity;
        $batch->expiry_date = $request->expiry_date;

        // If quantity becomes 0, handle QR code cleanup
        if ($batch->quantity == 0) {
            if ($batch->qr_code_path && file_exists(public_path($batch->qr_code_path))) {
                unlink(public_path($batch->qr_code_path));
            }
            $batch->qr_code_path = null;
        }

        $batch->save();

        return response()->json(['message' => 'Batch updated successfully']);
    }

    public function create()
    {
        return view('stock.create');
    }

    public function generateBarcode()
    {
        return view('stock.generate-qrcode', [
            'categories' => Category::orderBy('category_name')->get(),
        ]);
    }

    public function viewBatches(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $query = Batch::where('product_id', $id);

        // Search
        if ($request->filled('search')) {
            $query->where('batch_number', 'like', '%'.$request->search.'%');
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Allowable sort columns
        if (in_array($sort, ['batch_number', 'quantity', 'expiry_date', 'created_at'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $batches = $query->get();

        return view('stock.batches', compact('product', 'batches'));
    }

    public function editBatch($id)
    {
        $batch = Batch::with('product')->findOrFail($id);

        return view('stock.edit-batch', compact('batch'));
    }

    public function viewBarcode($id)
    {
        $batch = Batch::findOrFail($id);

        return view('stock.barcode-view', compact('batch'));
    }

    public function destroyBatch($id)
    {
        if (strtolower(request()->user()->roles) === 'staff') {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
        $batch = Batch::withCount('sales')->findOrFail($id);

        if ($batch->sales_count > 0) {
            return response()->json(['message' => 'Cannot delete batch with associated sales history.'], 422);
        }

        if ($batch->qr_code_path && file_exists(public_path($batch->qr_code_path))) {
            unlink(public_path($batch->qr_code_path));
        }

        $batch->delete();

        return response()->json(['message' => 'Batch deleted successfully']);
    }
}
