@extends('layouts.app')

@section('title', 'New Sale')

@section('content')
<x-page-header title="New Sale" description="Process a new sales transaction" :center="true" />

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-body">
        <form id="newSaleForm">
            @csrf

            <!-- Customer Information -->
            <x-input name="customer_name" label="Customer Name" placeholder="Enter customer name (optional)" />

            <!-- Product Selection -->
            <div class="form-group">
                <label class="form-label">Select Products</label>
                <div style="border: 2px solid var(--color-slate-200); border-radius: var(--radius-md); padding: 1rem; margin-bottom: 1rem;">
                    <div class="product-selection-row">
                        <select id="product-select" class="form-select" style="flex: 1;">
                            <option value="">Select a product</option>
                            @foreach($products as $product)
                                @php
                                    $totalStock = $product->batches->sum('quantity');
                                @endphp
                                <option value="{{ $product->product_id }}" 
                                        data-price="{{ $product->unit_price }}" 
                                        data-stock="{{ $totalStock }}">
                                    {{ $product->product_name }} (Stock: {{ $totalStock }})
                                </option>
                            @endforeach
                        </select>
                        <input type="number" id="product-quantity" class="form-input" placeholder="Qty" value="1" min="1" style="width: 100px;">
                        <button type="button" onclick="addProductToSale()" class="btn btn-primary">Add</button>
                    </div>
                    
                    <!-- Selected Products Table -->
                    <div id="selected-products" class="table-container" style="display: none;">
                        <table class="table" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="products-list">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Total -->
            <div style="background: var(--color-slate-50); padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                <div class="total-section">
                    <span style="font-size: 1.25rem; font-weight: 600;">Total:</span>
                    <span id="sale-total" style="font-size: 1.5rem; font-weight: 700; color: var(--color-primary);">RM 0.00</span>
                </div>
            </div>

            <!-- Payment Method -->
            <x-select name="payment_method" label="Payment Method" required>
                <option value="cash">Cash</option>
                <option value="card">Card</option>
                <option value="online">Online Transfer</option>
            </x-select>

            <div class="form-actions">
                <button type="submit" class="btn btn-success">Complete Sale</button>
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
let saleItems = [];
let saleTotal = 0;

function addProductToSale() {
    const productSelect = document.getElementById('product-select');
    const quantity = parseInt(document.getElementById('product-quantity').value) || 1;
    
    if (!productSelect.value) {
        alert('Please select a product');
        return;
    }

    const productId = productSelect.value;
    const productName = productSelect.options[productSelect.selectedIndex].text.split(' (Stock:')[0];
    const price = parseFloat(productSelect.options[productSelect.selectedIndex].dataset.price);
    
    // Check if product already in list
    const existingItem = saleItems.find(item => item.id === productId);
    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        saleItems.push({
            id: productId,
            name: productName,
            price: price,
            quantity: quantity
        });
    }
    
    updateSaleDisplay();
    document.getElementById('product-quantity').value = 1;
}

function removeProductFromSale(productId) {
    saleItems = saleItems.filter(item => item.id !== productId);
    updateSaleDisplay();
}

function updateSaleDisplay() {
    const productsList = document.getElementById('products-list');
    const selectedProducts = document.getElementById('selected-products');
    const totalElement = document.getElementById('sale-total');
    
    if (saleItems.length === 0) {
        selectedProducts.style.display = 'none';
        productsList.innerHTML = '';
        saleTotal = 0;
    } else {
        selectedProducts.style.display = 'block';
        productsList.innerHTML = saleItems.map(item => {
            const subtotal = item.price * item.quantity;
            return `
                <tr>
                    <td>${item.name}</td>
                    <td>RM ${item.price.toFixed(2)}</td>
                    <td>${item.quantity}</td>
                    <td>RM ${subtotal.toFixed(2)}</td>
                    <td style="text-align: right;">
                        <button type="button" onclick="removeProductFromSale('${item.id}')" class="btn btn-danger btn-sm">Remove</button>
                    </td>
                </tr>
            `;
        }).join('');
        
        saleTotal = saleItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    }
    
    totalElement.textContent = `RM ${saleTotal.toFixed(2)}`;
}

// Form submission
document.getElementById('newSaleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (saleItems.length === 0) {
        alert('Please add at least one product to the sale');
        return;
    }

    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Processing...';

    try {
        const response = await fetch('{{ route("sales.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                items: saleItems,
                payment_method: document.querySelector('select[name="payment_method"]').value
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Something went wrong');
        }

        alert('Sale completed successfully!');
        window.location.href = "{{ route('sales.index') }}";

    } catch (error) {
        alert(error.message);
        submitBtn.disabled = false;
        submitBtn.textContent = 'Complete Sale';
    }
});
</script>
@endsection
