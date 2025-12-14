@extends('layouts.app')

@section('title', 'Sales')

@section('content')
<x-page-header title="Sales Management" description="Record and manage sales transactions" />

<!-- Quick Stats -->
<div class="grid grid-cols-2 mb-6" style="margin-bottom: 1.5rem;">
    <div class="stat-card stat-primary">
        <div class="stat-card-header">
            <div class="stat-card-title">Total Transactions</div>
            <div class="stat-card-icon icon-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
        <div class="stat-card-value">{{ number_format($totalTransactions) }}</div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-card-header">
            <div class="stat-card-title">Total Revenue</div>
            <div class="stat-card-icon icon-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="stat-card-value">RM {{ number_format($totalRevenue, 2) }}</div>
    </div>
</div>

<!-- New Sale Button -->
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('sales.create') }}" class="btn btn-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Sale
    </a>
</div>

<!-- Sales Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Sales</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sale ID</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Amount</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>#{{ $sale->sales_id }}</td>
                            <td>{{ $sale->date->format('M d, Y') }}</td>
                            <td>
                                <div style="font-weight: 600;">{{ $sale->batch->product->product_name ?? 'Unknown Product' }}</div>
                                <div style="font-size: 0.75rem; color: var(--color-slate-500);">Batch: {{ $sale->batch->batch_number ?? 'N/A' }}</div>
                            </td>
                            <td>{{ $sale->quantity }}</td>
                            <td>RM {{ number_format($sale->unit_price, 2) }}</td>
                            <td style="font-weight: 600; color: var(--color-slate-900);">RM {{ number_format($sale->total_amount, 2) }}</td>
                            <td style="text-align: right;">
                                <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 3rem; color: var(--color-slate-500);">
                                <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div style="font-weight: 600; margin-bottom: 0.5rem;">No sales recorded yet</div>
                                <div style="font-size: 0.875rem;">Click "New Sale" to record your first transaction</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sales->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
