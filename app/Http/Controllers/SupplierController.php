<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(Request $request): View
    {
        $query = Supplier::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('supplier_name', 'like', "%{$search}%")
                    ->orWhere('contact_person', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $suppliers = $query->latest('supplier_id')->paginate(10)->withQueryString();

        return view('suppliers.index', [
            'suppliers' => $suppliers,
            'currentSearch' => $request->search,
        ]);
    }

    public function create(): View
    {
        if (strtolower(auth()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::orderBy('category_name')->get();
        return view('suppliers.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (strtolower($request->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'supplier_name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,category_id'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $data = $request->all();

        // Find gaps in IDs
        $ids = Supplier::orderBy('supplier_id', 'asc')->pluck('supplier_id')->toArray();
        $newId = 1;
        foreach ($ids as $id) {
            if ($id != $newId) {
                break;
            }
            $newId++;
        }
        $data['supplier_id'] = $newId;

        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function edit(Supplier $supplier): View
    {
        if (strtolower(auth()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $categories = Category::orderBy('category_name')->get();
        return view('suppliers.edit', compact('supplier', 'categories'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        if (strtolower($request->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'supplier_name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,category_id'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if (strtolower(request()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
