@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<x-page-header title="Reports & Analytics" description="View sales reports and inventory analytics" />

<!-- Report Type Selection -->
<div class="grid grid-cols-2 mb-6" style="margin-bottom: 1.5rem;">
    <div class="card" style="cursor: pointer; transition: all var(--transition-base);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
        <div class="card-body" style="text-align: center;">
            <div style="width: 60px; height: 60px; background: rgba(102, 126, 234, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg style="width: 30px; height: 30px; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Sales Report</h3>
            <p style="font-size: 0.875rem; color: var(--color-slate-600);">View sales performance and trends</p>
        </div>
    </div>

    <div class="card" style="cursor: pointer; transition: all var(--transition-base);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
        <div class="card-body" style="text-align: center;">
            <div style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <svg style="width: 30px; height: 30px; color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </div>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Inventory Report</h3>
            <p style="font-size: 0.875rem; color: var(--color-slate-600);">Stock levels and movements</p>
        </div>
    </div>
</div>

<!-- Sales Overview Chart -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Sales Overview</h3>
    </div>
    <div class="card-body">
        <div style="height: 300px; display: flex; align-items: center; justify-content: center; background: var(--color-slate-50); border-radius: var(--radius-md);">
            <div style="text-align: center; color: var(--color-slate-500);">
                <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                <div style="font-weight: 600; margin-bottom: 0.5rem;">Sales Chart Placeholder</div>
                <div style="font-size: 0.875rem;">Monthly sales trend will be displayed here</div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Overview Chart -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Inventory Overview</h3>
    </div>
    <div class="card-body">
        <div style="height: 300px; display: flex; align-items: center; justify-content: center; background: var(--color-slate-50); border-radius: var(--radius-md);">
            <div style="text-align: center; color: var(--color-slate-500);">
                <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
                <div style="font-weight: 600; margin-bottom: 0.5rem;">Inventory Chart Placeholder</div>
                <div style="font-size: 0.875rem;">Stock distribution by category will be displayed here</div>
            </div>
        </div>
    </div>
</div>

<!-- Summary Tables -->
<div class="grid grid-cols-2 gap-6">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Top Selling Products</h3>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--color-slate-500);">
                                No sales data available
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Low Stock Alert</h3>
        </div>
        <div class="card-body">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--color-slate-500);">
                                No low stock items
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Summary -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Inventory Summary</h3>
    </div>
    <div class="card-body">
        <div class="grid grid-cols-4">
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.5rem;">0</div>
                <div style="font-size: 0.875rem; color: var(--color-slate-600);">Total Products</div>
            </div>
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-success); margin-bottom: 0.5rem;">0</div>
                <div style="font-size: 0.875rem; color: var(--color-slate-600);">In Stock</div>
            </div>
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-warning); margin-bottom: 0.5rem;">0</div>
                <div style="font-size: 0.875rem; color: var(--color-slate-600);">Low Stock</div>
            </div>
            <div style="text-align: center; padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: var(--color-error); margin-bottom: 0.5rem;">0</div>
                <div style="font-size: 0.875rem; color: var(--color-slate-600);">Out of Stock</div>
            </div>
        </div>
    </div>
</div>
@endsection
