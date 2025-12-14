@extends('layouts.app')

@section('title', 'View QR Code')

@section('content')
<x-page-header title="QR Code: {{ $batch->batch_number }}" description="View and print QR Code" />

<div class="mb-6">
    <a href="{{ route('stock.batches.view', $batch->product_id) }}" class="btn btn-secondary">
        <svg style="width: 20px; height: 20px; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Batches
    </a>
</div>

<div class="card" style="max-width: 500px; margin: 0 auto;">
    <div class="card-body" style="text-align: center; padding: 3rem;">
        @if($batch->qr_code_path)
            <img src="{{ asset($batch->qr_code_path) }}" alt="QR Code {{ $batch->batch_number }}" style="max-width: 100%; height: auto; margin-bottom: 2rem; border: 1px solid var(--color-slate-200); padding: 1rem; border-radius: var(--radius-md);">
            <div style="font-size: 1.25rem; font-weight: 700; color: var(--color-slate-800); margin-bottom: 2rem;">
                {{ $batch->batch_number }}
            </div>
            <button onclick="window.print()" class="btn btn-primary btn-lg">
                <svg style="width: 20px; height: 20px; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print QR Code
            </button>
        @else
            <div style="color: var(--color-error); margin-bottom: 1rem;">No QR Code image found.</div>
        @endif
    </div>
</div>
@endsection
