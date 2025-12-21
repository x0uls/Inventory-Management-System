<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest('product_id')->paginate(10)->withQueryString();
        $categories = Category::orderBy('category_name')->get();

        return view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'currentSearch' => $request->search,
            'currentCategory' => $request->category,
        ]);
    }

    public function create(): View
    {
        if (strtolower(auth()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::orderBy('category_name')->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (strtolower($request->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'product_name' => ['required', 'string', 'max:255', 'unique:products,product_name'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,category_id'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'lowstock_alert' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'], // Max 2MB
        ], [
            'category_id.required' => 'The category field is required.',
        ]);

        $data = $request->all();

        // Find gaps in IDs
        $ids = Product::orderBy('product_id', 'asc')->pluck('product_id')->toArray();
        $newId = 1;
        foreach ($ids as $id) {
            if ($id != $newId) {
                break;
            }
            $newId++;
        }
        $data['product_id'] = $newId;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        if (strtolower(auth()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::orderBy('category_name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        if (strtolower($request->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'product_name' => ['required', 'string', 'max:255', 'unique:products,product_name,' . $product->product_id . ',product_id'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,category_id'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'lowstock_alert' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'remove_image' => ['nullable', 'boolean'],
        ], [
            'category_id.required' => 'The category field is required.',
        ]);

        $data = $request->all();

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($product->image_path && file_exists(public_path($product->image_path))) {
                unlink(public_path($product->image_path));
            }
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path && file_exists(public_path($product->image_path))) {
                unlink(public_path($product->image_path));
            }
            
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if (strtolower(request()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        // Check if product has batches
        if ($product->batches()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Cannot delete product with existing stock batches.');
        }

        if ($product->image_path && file_exists(public_path($product->image_path))) {
            unlink(public_path($product->image_path));
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
