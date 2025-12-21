@extends('layouts.app')

@section('title', 'Edit Batch')

@section('content')
<x-page-header title="Edit Batch" description="Update batch details" :center="true" />

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
            <div class="form-group">
                <label class="form-label">Expiry Date</label>
                <input type="text" class="form-input" value="{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d-m-Y') : '-' }}" disabled readonly style="background: var(--color-slate-100); cursor: not-allowed; color: var(--color-slate-500);">
                <input type="hidden" id="expiry_date" value="{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d') : '' }}">
            </div>
            
            <div class="flex gap-4" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Save Changes</button>
                <a href="{{ route('stock.batches.view', $batch->product_id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
const form = document.getElementById('editBatchForm');
form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Immediate feedback
    // alert('Submitting form...'); 
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    submitBtn.innerText = 'Saving...';
    submitBtn.disabled = true;

    const batchId = document.getElementById('edit_batch_id').value;
    
    // Handle empty expiry date
    const expiryDateRef = document.getElementById('expiry_date');
    const expiryDateValue = expiryDateRef.value === '' ? null : expiryDateRef.value;

    const formData = {
        quantity: document.getElementById('quantity').value,
        expiry_date: expiryDateValue
    };

    fetch(`/stock/batch/${batchId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json', // Force JSON response from Laravel
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(async response => {
        // Check content type to ensure it is JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
             throw new Error("Received non-JSON response from server (possible 500 error).");
        }

        const data = await response.json();
        
        if (!response.ok) {
            // Handle validation errors (422)
            if (response.status === 422) {
                let errorMessage = data.message;
                if (data.errors) {
                    errorMessage += '\n' + Object.values(data.errors).flat().join('\n');
                }
                throw new Error(errorMessage);
            }
            throw new Error(data.message || 'Failed to update batch');
        }

        // Success
        window.location.href = "{{ route('stock.batches.view', $batch->product_id) }}";
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
        // Reset button
        submitBtn.innerText = originalText;
        submitBtn.disabled = false;
    });
});
</script>
@endsection
