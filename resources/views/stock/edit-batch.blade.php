@extends('layouts.app')

@section('title', 'Edit Batch')

@section('content')
<x-page-header title="Edit Batch" description="Update batch details" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form id="editBatchForm">
            <input type="hidden" id="edit_batch_id" value="{{ $batch->batch_id }}">
            
            <div class="form-group">
                <label class="form-label">Batch Number</label>
                <input type="text" class="form-input" value="{{ $batch->batch_number }}" disabled style="background: var(--color-slate-100);">
            </div>

            <div class="form-group">
                <label class="form-label">Product</label>
                <input type="text" class="form-input" value="{{ $batch->product->product_name }}" disabled style="background: var(--color-slate-100);">
            </div>

            <x-input name="quantity" id="edit_quantity" type="number" label="Quantity" required min="0" value="{{ $batch->quantity }}" />
            <x-input name="expiry_date" id="edit_expiry_date" type="date" label="Expiry Date" value="{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d') : '' }}" />
            
            <div class="flex gap-4" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Changes</button>
                <a href="{{ route('stock.batches.view', $batch->product_id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('editBatchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const batchId = document.getElementById('edit_batch_id').value;
    const formData = {
        quantity: document.getElementById('edit_quantity').value,
        expiry_date: document.getElementById('edit_expiry_date').value
    };

    fetch(`/stock/batch/${batchId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        window.location.href = "{{ route('stock.batches.view', $batch->product_id) }}";
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to update batch');
    });
});
</script>
@endsection
