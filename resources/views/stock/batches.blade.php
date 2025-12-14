@extends('layouts.app')

@section('title', 'Batches - ' . $product->product_name)

@section('content')
<x-page-header title="Batches: {{ $product->product_name }}" description="Manage batches for this product" />

<div class="mb-6">
    <a href="{{ route('stock.index') }}" class="btn btn-secondary">
        <svg style="width: 20px; height: 20px; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Stock
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Batch Number</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>QR Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                        <tr>
                            <td>{{ $batch->batch_number }}</td>
                            <td>{{ $batch->quantity }}</td>
                            <td>{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d') : '-' }}</td>
                            <td>
                                @if($batch->qr_code_path)
                                    <a href="{{ route('stock.batch.barcode', $batch->batch_id) }}" class="btn btn-sm btn-primary">View QR Code</a>
                                @else
                                    <span style="color: var(--color-slate-400);">No QR Code</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex gap-2" style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('stock.batch.edit', $batch->batch_id) }}" class="btn btn-sm btn-secondary" title="Edit">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    @if(auth()->user()->roles !== 'staff')
                                    <button onclick="deleteBatch({{ $batch->batch_id }})" class="btn btn-sm btn-danger" title="Delete">
                                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--color-slate-500);">No batches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function deleteBatch(batchId) {
    if (!confirm('Are you sure you want to delete this batch? This action cannot be undone.')) return;

    fetch(`/stock/batch/${batchId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Failed to delete batch');
        return data;
    })
    .then(data => {
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message);
    });
}
</script>
@endsection
