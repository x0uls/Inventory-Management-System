@extends('layouts.app')

@section('title', 'Product Management')

@section('content')
<x-page-header title="Product Management" description="Manage your product catalog" />

<!-- Search Section -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('products.index') }}" class="search-container">
            <div style="flex: 1;">
                <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search by name or description..." class="form-input">
            </div>
            <div style="width: 200px;">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ $currentCategory == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            @if($currentSearch || $currentCategory)
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Add Product Button -->
@if(strtolower(auth()->user()->roles) !== 'staff')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('products.create') }}" class="btn btn-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Product
    </a>
</div>
@endif

<!-- Products Table -->
<div class="table-container" id="products-table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Unit Price</th>
                <th>Description</th>
                <th>Low Stock Alert</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->product_id }}</td>
                    <td>
                        <div style="width: 50px; height: 50px; border-radius: var(--radius-sm); overflow: hidden; background-color: var(--color-slate-100); display: flex; align-items: center; justify-content: center; border: 1px solid var(--color-slate-200);">
                            @if($product->image_path)
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->product_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <svg style="width: 24px; height: 24px; color: var(--color-slate-400);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                    </td>
                    <td style="font-weight: 600;">{{ $product->product_name }}</td>
                    <td>
                        @if($product->category)
                            <x-badge type="info">{{ $product->category->category_name }}</x-badge>
                        @else
                            <span style="color: var(--color-slate-400);">No Category</span>
                        @endif
                    </td>
                    <td>RM {{ number_format($product->unit_price, 2) }}</td>
                    <td>{{ Str::limit($product->description, 50) }}</td>
                    <td>{{ $product->lowstock_alert }}</td>
                    <td>
                        <div class="action-buttons">
                            @if(strtolower(auth()->user()->roles) !== 'staff')
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-secondary" title="Edit">
                                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                            @else
                                <span style="color: var(--color-slate-400); font-size: 0.875rem;">View Only</span>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: var(--color-slate-500);">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
<div style="margin-top: 1.5rem;">{{ $products->links() }}</div>
@endif
@endsection
