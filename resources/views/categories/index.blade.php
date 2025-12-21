@extends('layouts.app')

@section('title', 'Category Management')

@section('content')
<x-page-header title="Category Management" description="Manage product categories" />

<!-- Search Section -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('categories.index') }}" class="search-container">
            <div style="flex: 1;">
                <input
                    type="text"
                    name="search"
                    value="{{ $currentSearch }}"
                    placeholder="Search by name or description..."
                    class="form-input">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            @if($currentSearch)
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Add Category Button -->
@if(strtolower(auth()->user()->roles) !== 'staff')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('categories.create') }}" class="btn btn-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Category
    </a>
</div>
@endif

<!-- Categories Table -->
<div class="table-container" id="categories-table-container">
    @include('categories.partials.table')
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/ajax-search.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initAjaxSearch('input[name="search"]', '#categories-table-container', '{{ route("categories.index") }}');
});
</script>
@endpush
