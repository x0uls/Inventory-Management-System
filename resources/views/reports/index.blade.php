@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<x-page-header title="Reports" description="Generate and view system reports" />

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Total Inventory Report Card -->
    <div class="card h-full">
        <div class="card-body flex flex-col items-center justify-center text-center p-8">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-2">Comprehensive Business Report</h3>
            <p class="text-slate-500 mb-6">View a complete overview of inventory levels, stock value, and sales performance.</p>
            <a href="{{ route('reports.inventory') }}" target="_blank" class="btn btn-primary w-full justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Generate Report
            </a>
        </div>
    </div>
</div>
@endsection
