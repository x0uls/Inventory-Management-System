@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<x-page-header title="Stock Management" description="View and manage product stock levels" />

<!-- Search Section -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('stock.index') }}" class="search-container">
            <div style="flex: 1;">
                <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search by product name or description..." class="form-input">
            </div>
            <div style="width: 200px;">
                <select name="filter" class="form-select">
                    <option value="all" {{ $currentFilter === 'all' ? 'selected' : '' }}>All Products</option>
                    <option value="low_stock" {{ $currentFilter === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                </select>
            </div>

            @if($currentSearch || $currentFilter !== 'all')
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Add Stock Button -->
<div style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
    <a href="{{ route('stock.create') }}" class="btn btn-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Stock
    </a>
    <a href="{{ route('stock.generate-qrcode') }}" class="btn btn-primary">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Generate QR Code
    </a>
</div>

<!-- Stock Table -->
<div class="table-container" id="stock-table-container">
    @include('stock.partials.table')
</div>

<script src="{{ asset('js/ajax-search.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    initAjaxSearch('input[name="search"]', '#stock-table-container', '{{ route("stock.index") }}');
});
</script>
@endsection
