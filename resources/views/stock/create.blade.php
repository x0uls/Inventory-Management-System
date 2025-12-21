@extends('layouts.app')

@section('title', 'Add Stock')

@section('content')
<x-page-header title="Add Stock" description="Add new stock to inventory" :center="true" />



<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form id="addStockForm" method="POST" action="{{ route('stock.store') }}">
            @csrf

            <!-- Step 1: Scan QR Code -->
            <div id="add-stock-step-1">
                <div style="margin-bottom: 1.5rem; text-align: center;">
                    <p style="margin-bottom: 1rem; color: var(--color-slate-600);">Scan the product QR code to add stock.</p>
                    <div style="border: 2px dashed var(--color-slate-300); border-radius: var(--radius-md); padding: 1rem; background: var(--color-slate-50); max-width: 400px; margin: 0 auto;">
                        <div id="barcode-scanner"></div>
                        <div id="scanner-status" style="font-size: 0.875rem; color: var(--color-slate-600); margin-top: 0.5rem;"></div>
                    </div>
                    <div style="margin-top: 1rem; display: flex; gap: 1rem; justify-content: center;">
                        <button type="button" id="start-scanner-btn" onclick="startScanner()" class="btn btn-primary">
                            Start Scanner
                        </button>
                        <button type="button" id="stop-scanner-btn" onclick="stopScanner()" class="btn btn-danger" style="display: none;">
                            Stop Scanner
                        </button>
                        <a href="{{ route('stock.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
                
                <div style="margin-top: 2rem; border-top: 1px solid var(--color-slate-200); padding-top: 1rem;">
                    <p style="text-align: center; font-size: 0.875rem; color: var(--color-slate-500); margin-bottom: 0.5rem;">Scanner not working? Enter Batch Number:</p>
                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                        <input type="text" id="manual-batch-input" class="form-input" placeholder="Enter Batch Number" style="max-width: 200px;">
                        <button type="button" onclick="checkManualBatch()" class="btn btn-primary">Check</button>
                    </div>
                    <p id="manual-entry-error" style="color: var(--color-error); font-size: 0.875rem; margin-top: 0.5rem; display: none; text-align: center;"></p>
                </div>
            </div>

            <!-- Step 2: Product Details & Quantity -->
            <div id="add-stock-step-2" style="display: none;">
                <div style="margin-bottom: 1.5rem; padding: 1rem; background: var(--color-slate-50); border-radius: var(--radius-md); border: 1px solid var(--color-slate-200);">
                    <h4 style="margin: 0 0 0.5rem 0; font-size: 1.1rem; color: var(--color-slate-800);" id="step2-product-name">Product Name</h4>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--color-slate-600);" id="step2-product-desc">Description</p>
                </div>

                <input type="hidden" name="batch_number" id="step2-batch-number">
                
                <!-- Product Selection (Hidden if found, visible if manual) -->
                <div id="manual-product-select" style="display: none;">
                    <x-select name="product_id" label="Product" required placeholder="Select a product">
                        @foreach(\App\Models\Product::orderBy('product_name')->get() as $product)
                            <option value="{{ $product->product_id }}">{{ $product->product_name }} - {{ $product->description }}</option>
                        @endforeach
                    </x-select>
                </div>
                
                <!-- Hidden input for product_id when found via scan -->
                <input type="hidden" name="product_id" id="step2-product-id-hidden">

                <div class="form-group">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" id="step2-expiry-date" class="form-input">
                </div>

                <x-input name="quantity" type="number" label="Quantity to Add" placeholder="Enter quantity" required value="1" min="1" />

                <div class="flex gap-4" style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="button" onclick="showStep1()" class="btn btn-secondary">Back to Scan</button>
                    <button type="submit" class="btn btn-success" style="flex: 1;">Confirm & Add Stock</button>
                    <a href="{{ route('stock.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let html5QrcodeScanner = null;
let isScanning = false;

// Store products data for filtering
@php
    $productsForJs = \App\Models\Product::all()->map(function($p) {
        return [
            'id' => $p->product_id,
            'name' => $p->product_name,
            'description' => $p->description,
            'category_id' => $p->category_id
        ];
    });
@endphp
const productsData = @json($productsForJs);

function showStep1() {
    document.getElementById('add-stock-step-1').style.display = 'block';
    document.getElementById('add-stock-step-2').style.display = 'none';
    startScanner();
}

function showStep2(batchNumber = null, product = null, expiryDate = null) {
    stopScanner();
    document.getElementById('add-stock-step-1').style.display = 'none';
    document.getElementById('add-stock-step-2').style.display = 'block';

    const productNameEl = document.getElementById('step2-product-name');
    const productDescEl = document.getElementById('step2-product-desc');
    const manualSelect = document.getElementById('manual-product-select');
    const hiddenProductId = document.getElementById('step2-product-id-hidden');
    const batchInput = document.getElementById('step2-batch-number');
    const expiryInput = document.getElementById('step2-expiry-date');

    // Reset fields
    productNameEl.textContent = 'Manual Entry';
    productDescEl.textContent = 'Select a product below';
    manualSelect.style.display = 'block';
    hiddenProductId.value = '';
    batchInput.value = '';
    expiryInput.value = '';

    if (batchNumber) {
        batchInput.value = batchNumber;
    }

    if (product) {
        productNameEl.textContent = product.product_name;
        productDescEl.textContent = product.description;
        manualSelect.style.display = 'none';
        hiddenProductId.value = product.product_id;
        
        // If we have a product, we don't need the manual select required
        document.querySelector('#manual-product-select select').removeAttribute('required');
    } else {
        document.querySelector('#manual-product-select select').setAttribute('required', 'required');
    }

    if (expiryDate) {
        expiryInput.value = expiryDate;
    }
}

function checkManualBatch() {
    const batchInput = document.getElementById('manual-batch-input');
    const batchNumber = batchInput.value.trim();
    const errorMsg = document.getElementById('manual-entry-error');
    
    if (!batchNumber) {
        errorMsg.textContent = 'Please enter a batch number.';
        errorMsg.style.display = 'block';
        return;
    }
    
    errorMsg.style.display = 'none';
    findProductByBatchNumber(batchNumber, true);
}

function startScanner() {
    if (isScanning) return;

    const scannerElement = document.getElementById('barcode-scanner');
    const startBtn = document.getElementById('start-scanner-btn');
    const stopBtn = document.getElementById('stop-scanner-btn');
    const statusDiv = document.getElementById('scanner-status');

    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }

    html5QrcodeScanner = new Html5Qrcode("barcode-scanner");

    html5QrcodeScanner.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        (decodedText, decodedResult) => {
            statusDiv.textContent = 'Batch number scanned: ' + decodedText;
            statusDiv.style.color = 'var(--color-success)';
            findProductByBatchNumber(decodedText);
        },
        (errorMessage) => {
            // Ignore scanning errors
        }
    ).catch((err) => {
        console.error("Unable to start scanning", err);
        statusDiv.textContent = 'Error: Could not access camera. Please check permissions.';
        statusDiv.style.color = 'var(--color-error)';
    });

    isScanning = true;
    startBtn.style.display = 'none';
    stopBtn.style.display = 'block';
    statusDiv.textContent = 'Scanning... Point camera at QR Code';
    statusDiv.style.color = 'var(--color-primary)';
}

function stopScanner() {
    if (!isScanning || !html5QrcodeScanner) return;

    html5QrcodeScanner.stop().then(() => {
        html5QrcodeScanner.clear();
        isScanning = false;
        document.getElementById('start-scanner-btn').style.display = 'block';
        document.getElementById('stop-scanner-btn').style.display = 'none';
        document.getElementById('scanner-status').textContent = '';
    }).catch((err) => {
        console.error("Error stopping scanner", err);
    });
}

function findProductByBatchNumber(batchNumber, isManual = false) {
    fetch('{{ route("stock.find-barcode") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ batch_number: batchNumber })
    })
    .then(response => response.json())
    .then(data => {
        if (data.found) {
            showStep2(batchNumber, data.batch.product, data.batch.expiry_date);
        } else {
            if (isManual) {
                const errorMsg = document.getElementById('manual-entry-error');
                errorMsg.textContent = 'Invalid Batch Number. Batch not found.';
                errorMsg.style.display = 'block';
            } else {
                document.getElementById('scanner-status').textContent = 'Batch not found: ' + batchNumber;
                document.getElementById('scanner-status').style.color = 'var(--color-error)';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (isManual) {
            const errorMsg = document.getElementById('manual-entry-error');
            errorMsg.textContent = 'Error checking batch number.';
            errorMsg.style.display = 'block';
        }
    });
}

// Start scanner automatically on load
document.addEventListener('DOMContentLoaded', function() {
    startScanner();
});
</script>
@endsection
