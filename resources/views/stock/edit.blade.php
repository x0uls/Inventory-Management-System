@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<x-page-header title="Edit Product" description="Update product details" />

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('stock.update', $product->product_id) }}">
            @csrf
            @method('PUT')

            <x-input name="product_name" label="Product Name" :value="$product->product_name" required />
            
            <x-input name="description" label="Description" :value="$product->description" />

            <x-select name="category_id" label="Category" required>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}" {{ $product->category_id == $category->category_id ? 'selected' : '' }}>
                        {{ $category->category_name }}
                    </option>
                @endforeach
            </x-select>

            <x-input name="lowstock_alert" type="number" label="Low Stock Alert Level" :value="$product->lowstock_alert" required min="0" />

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
