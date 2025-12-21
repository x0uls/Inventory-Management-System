@extends('layouts.app')

@section('title', 'Generate QR Code')

@section('content')
<x-page-header title="Generate QR Code" description="Create new batches and QR codes" :center="true" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form id="generateBarcodeForm" method="POST" action="{{ route('stock.store') }}">
            @csrf
            
            <!-- Category Selection -->
            <div class="form-group">
                <label for="gen_category_id" class="form-label required">Category</label>
                <select name="category_id" id="gen_category_id" class="form-select" required onchange="filterProductsByCategory(this.value)">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Product Selection -->
            <div class="form-group">
                <label for="gen_product_id" class="form-label required">Product</label>
                <select name="product_id" id="gen_product_id" class="form-select" required>
                    <option value="">Select a category first</option>
                </select>
            </div>

            <!-- Expiry Date -->
            <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input type="text" class="form-input" id="display_expiry_date" placeholder="DD-MM-YYYY" oninput="formatDateInput(this)">
                <input type="hidden" name="expiry_date" id="expiry_date">
                <p style="font-size: 0.8em; color: var(--color-slate-500); margin-top: 0.25rem;">Format: DD-MM-YYYY</p>
            </div>

            <!-- Quantity -->
            <x-input name="quantity" type="number" label="Quantity" placeholder="Enter quantity" required value="1" />

            <div class="flex gap-4" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Generate & Add</button>
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function formatDateInput(input) {
        let v = input.value.replace(/\D/g, '').slice(0, 8);
        if (v.length >= 5) {
            v = v.slice(0, 2) + '-' + v.slice(2, 4) + '-' + v.slice(4);
        } else if (v.length >= 3) {
            v = v.slice(0, 2) + '-' + v.slice(2);
        }
        input.value = v;

        // Update hidden input for backend (Y-m-d)
        if (v.length === 10) {
            const parts = v.split('-');
            // Input: DD-MM-YYYY -> Output: YYYY-MM-DD
            document.getElementById('expiry_date').value = `${parts[2]}-${parts[1]}-${parts[0]}`;
        } else {
            document.getElementById('expiry_date').value = '';
        }
    }

// Store products data for filtering
@php
    $productsForJs = \App\Models\Product::all()->map(function($p) {
        return [
            'id' => $p->product_id,
            'name' => $p->product_name,
            'description' => $p->description,
            'category_id' => $p->category_id
        ];
    });
@endphp
const productsData = @json($productsForJs);

function filterProductsByCategory(categoryId) {
    const productSelect = document.getElementById('gen_product_id');
    productSelect.innerHTML = '<option value="">Select a product</option>';
    
    if (!categoryId) return;

    const filteredProducts = productsData.filter(p => p.category_id == categoryId);
    
    filteredProducts.forEach(product => {
        const option = document.createElement('option');
        option.value = product.id;
        option.textContent = `${product.name} - ${product.description}`;
        productSelect.appendChild(option);
    });
}
</script>
@endsection
