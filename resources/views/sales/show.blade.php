@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
<x-page-header title="Sale Details" description="View transaction information" />

<div class="mb-6">
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        <svg style="width: 20px; height: 20px; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Sales
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Transaction #{{ $sale->sales_id }}</h3>
        <span class="text-sm text-slate-500">{{ $sale->date->format('M d, Y h:i A') }}</span>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Product Details -->
            <div>
                <h4 class="text-lg font-semibold mb-3">Product Information</h4>
                <div class="space-y-3">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-600">Product Name:</span>
                        <span class="font-medium">{{ $sale->batch->product->product_name ?? 'Unknown Product' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-600">Batch Number:</span>
                        <span class="font-medium">{{ $sale->batch->batch_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-600">Category:</span>
                        <span class="font-medium">{{ $sale->batch->product->category->category_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Transaction Details -->
            <div>
                <h4 class="text-lg font-semibold mb-3">Transaction Details</h4>
                <div class="space-y-3">
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-600">Customer Name:</span>
                        <span class="font-medium">{{ $sale->customer_name ?? 'Walk-in Customer' }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-600">Payment Method:</span>
                        <span class="font-medium capitalize">{{ str_replace('_', ' ', $sale->payment_method ?? 'Cash') }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-600">Quantity Sold:</span>
                        <span class="font-medium">{{ $sale->quantity }}</span>
                    </div>
                    <div class="flex justify-between border-b pb-2">
                        <span class="text-slate-600">Unit Price:</span>
                        <span class="font-medium">RM {{ number_format($sale->unit_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between pt-2">
                        <span class="text-lg font-bold text-slate-800">Total Amount:</span>
                        <span class="text-lg font-bold text-primary">RM {{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
