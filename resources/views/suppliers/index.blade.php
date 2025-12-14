@extends('layouts.app')

@section('title', 'Supplier Management')

@section('content')
<x-page-header title="Supplier Management" description="Manage your suppliers" />

<!-- Search Section -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('suppliers.index') }}" class="search-container">
            <div style="flex: 1;">
                <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search by name, contact person, or email..." class="form-input">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            @if($currentSearch)
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Add Supplier Button -->
<!-- Add Supplier Button -->
@if(auth()->user()->roles !== 'staff')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('suppliers.create') }}" class="btn btn-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Supplier
    </a>
</div>
@endif

<!-- Suppliers Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier Name</th>
                <th>Category</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->supplier_id }}</td>
                    <td style="font-weight: 600;">{{ $supplier->supplier_name }}</td>
                    <td>
                        @if($supplier->category)
                            <x-badge type="info">{{ $supplier->category->category_name }}</x-badge>
                        @else
                            <span style="color: var(--color-slate-400);">-</span>
                        @endif
                    </td>
                    <td>{{ $supplier->contact_person ?? '-' }}</td>
                    <td>{{ $supplier->phone ?? '-' }}</td>
                    <td>{{ $supplier->email ?? '-' }}</td>
                    <td>
                        <div class="action-buttons">
                            @if(auth()->user()->roles !== 'staff')
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary btn-sm">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit
                            </a>
                            <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
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
                    <td colspan="7" style="text-align: center; padding: 2rem; color: var(--color-slate-500);">No suppliers found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($suppliers->hasPages())
<div style="margin-top: 1.5rem;">{{ $suppliers->links() }}</div>
@endif
@endsection
