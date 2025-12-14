@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<x-page-header 
    title="Welcome back, {{ Auth::user()->name ?? 'User' }}!"
    description="Here's an overview of your inventory management system"
/>

<!-- Statistics Cards -->
<div class="grid grid-cols-4 mb-6">
    <x-stat-card
        title="Total Products"
        :value="$totalProducts"
        type="primary"
    >
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
            </svg>
        </x-slot>
    </x-stat-card>

    <x-stat-card
        title="Low Stock Items"
        :value="$lowStockCount"
        type="warning"
    >
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </x-slot>
    </x-stat-card>

    <x-stat-card
        title="Total Sales"
        :value="$totalSales"
        type="success"
    >
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </x-slot>
    </x-stat-card>

    <x-stat-card
        title="Active Suppliers"
        :value="$activeSuppliers"
        type="primary"
    >
        <x-slot name="icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </x-slot>
    </x-stat-card>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="grid grid-cols-2 gap-6">
    <!-- Quick Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-2 gap-4">
                <a href="#" class="quick-action-btn">
                    <div class="quick-action-icon" style="background: rgba(102, 126, 234, 0.1);">
                        <svg fill="none" stroke="var(--color-primary)" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">Add Product</div>
                        <div class="quick-action-desc">Register new item</div>
                    </div>
                </a>

                <a href="#" class="quick-action-btn">
                    <div class="quick-action-icon" style="background: rgba(16, 185, 129, 0.1);">
                        <svg fill="none" stroke="var(--color-success)" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">New Sale</div>
                        <div class="quick-action-desc">Record transaction</div>
                    </div>
                </a>

                <a href="#" class="quick-action-btn">
                    <div class="quick-action-icon" style="background: rgba(245, 158, 11, 0.1);">
                        <svg fill="none" stroke="var(--color-warning)" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">Stock Update</div>
                        <div class="quick-action-desc">Adjust inventory</div>
                    </div>
                </a>

                <a href="#" class="quick-action-btn">
                    <div class="quick-action-icon" style="background: rgba(102, 126, 234, 0.1);">
                        <svg fill="none" stroke="var(--color-primary)" viewBox="0 0 24 24" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="quick-action-content">
                        <div class="quick-action-title">Add Supplier</div>
                        <div class="quick-action-desc">New vendor</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Activity</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 1rem; border-bottom: 1px solid var(--color-slate-200);">
                    <div>
                        <div style="font-weight: 600; color: var(--color-slate-900); font-size: 0.9rem;">System Initialized</div>
                        <div style="font-size: 0.8125rem; color: var(--color-slate-500); margin-top: 0.25rem;">Welcome to your inventory management system</div>
                    </div>
                    <x-badge type="success">New</x-badge>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="font-weight: 600; color: var(--color-slate-900); font-size: 0.9rem;">Ready to get started</div>
                        <div style="font-size: 0.8125rem; color: var(--color-slate-500); margin-top: 0.25rem;">Add your first product to begin</div>
                    </div>
                    <x-badge type="warning">Pending</x-badge>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
