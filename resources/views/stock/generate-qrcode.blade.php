@extends('layouts.app')

@section('title', 'Generate QR Code')

@section('content')
<x-page-header title="Generate QR Code" description="Create new batches and QR codes" :center="true" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form id="generateBarcodeForm" method="POST" action="{{ route('stock.store') }}" onsubmit="return validateForm()">
            @csrf
            
            <!-- Category Selection -->
            <div class="form-group">
                <label for="gen_category_id" class="form-label required">Category</label>
                <select name="category_id" id="gen_category_id" class="form-select" required onchange="filterProductsByCategory(this.value)">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Product Selection -->
            <div class="form-group">
                <label for="gen_product_id" class="form-label required">Product</label>
                <select name="product_id" id="gen_product_id" class="form-select" required>
                    <option value="">Select a category first</option>
                </select>
            </div>

            <!-- Expiry Date -->
            <div class="form-group">
                <label class="form-label">Expiry Date <span style="color: var(--color-error);">*</span></label>
                <input type="text" class="form-input" id="display_expiry_date" placeholder="DD-MM-YYYY" oninput="formatDateInput(this)" onblur="validateDateInput(this)" required>
                <input type="hidden" name="expiry_date" id="expiry_date" required>
                <p style="font-size: 0.8em; color: var(--color-slate-500); margin-top: 0.25rem;">Format: DD-MM-YYYY</p>
                <p id="date-error" style="font-size: 0.8em; color: var(--color-error); margin-top: 0.25rem; display: none;"></p>
            </div>

            <!-- Quantity -->
            <x-input name="quantity" type="number" label="Quantity" placeholder="Enter quantity" required value="1" />

            <div class="flex gap-4" style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">Generate & Add</button>
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    function formatDateInput(input) {
        let v = input.value.replace(/\D/g, '').slice(0, 8);
        if (v.length >= 5) {
            v = v.slice(0, 2) + '-' + v.slice(2, 4) + '-' + v.slice(4);
        } else if (v.length >= 3) {
            v = v.slice(0, 2) + '-' + v.slice(2);
        }
        input.value = v;

        // Clear error message while typing
        const errorElement = document.getElementById('date-error');
        if (errorElement) {
            errorElement.style.display = 'none';
            errorElement.textContent = '';
        }

        // Update hidden input for backend (Y-m-d) only if valid
        if (v.length === 10) {
            const parts = v.split('-');
            const day = parseInt(parts[0], 10);
            const month = parseInt(parts[1], 10);
            const year = parts[2];
            
            // Validate year is 4 digits
            if (year.length === 4 && !isNaN(parseInt(year, 10))) {
                const currentYear = new Date().getFullYear();
                const maxYear = currentYear + 50;
                const yearInt = parseInt(year, 10);
                
                // Validate date is valid
                const date = new Date(`${year}-${month}-${day}`);
                if (date.getFullYear() == year && 
                    date.getMonth() + 1 == month && 
                    date.getDate() == day &&
                    yearInt >= currentYear && 
                    yearInt <= maxYear) {
                    // Input: DD-MM-YYYY -> Output: YYYY-MM-DD
                    document.getElementById('expiry_date').value = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                } else {
                    document.getElementById('expiry_date').value = '';
                }
            } else {
                document.getElementById('expiry_date').value = '';
            }
        } else {
            document.getElementById('expiry_date').value = '';
        }
    }

    function validateDateInput(input) {
        const errorElement = document.getElementById('date-error');
        const hiddenInput = document.getElementById('expiry_date');
        const value = input.value.trim();
        
        // Clear previous error
        if (errorElement) {
            errorElement.style.display = 'none';
            errorElement.textContent = '';
        }
        
        // If empty, required field
        if (value === '') {
            hiddenInput.value = '';
            if (errorElement) {
                errorElement.textContent = 'Expiry date is required in DD-MM-YYYY format';
                errorElement.style.display = 'block';
            }
            return false;
        }
        
        // Check format DD-MM-YYYY
        const datePattern = /^(\d{2})-(\d{2})-(\d{4})$/;
        const match = value.match(datePattern);
        
        if (!match) {
            if (errorElement) {
                errorElement.textContent = 'Please enter a valid date in DD-MM-YYYY format (e.g., 22-12-1990)';
                errorElement.style.display = 'block';
            }
            hiddenInput.value = '';
            return false;
        }
        
        const day = parseInt(match[1], 10);
        const month = parseInt(match[2], 10);
        const year = parseInt(match[3], 10);
        
        // Validate year range (current year to current year + 100)
        const currentYear = new Date().getFullYear();
        const maxYear = currentYear + 50;
        if (year < currentYear || year > maxYear) {
            if (errorElement) {
                errorElement.textContent = `Year must be between ${currentYear} and ${maxYear}`;
                errorElement.style.display = 'block';
            }
            hiddenInput.value = '';
            return false;
        }
        
        // Validate month
        if (month < 1 || month > 12) {
            if (errorElement) {
                errorElement.textContent = 'Month must be between 01 and 12';
                errorElement.style.display = 'block';
            }
            hiddenInput.value = '';
            return false;
        }
        
        // Validate day
        const daysInMonth = new Date(year, month, 0).getDate();
        if (day < 1 || day > daysInMonth) {
            if (errorElement) {
                errorElement.textContent = `Day must be between 01 and ${daysInMonth} for the selected month`;
                errorElement.style.display = 'block';
            }
            hiddenInput.value = '';
            return false;
        }
        
        // Validate the actual date
        const date = new Date(year, month - 1, day);
        if (date.getFullYear() !== year || 
            date.getMonth() + 1 !== month || 
            date.getDate() !== day) {
            if (errorElement) {
                errorElement.textContent = 'Invalid date. Please check the day, month, and year.';
                errorElement.style.display = 'block';
            }
            hiddenInput.value = '';
            return false;
        }
        
        // Set the hidden input value
        hiddenInput.value = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        return true;
    }

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

function filterProductsByCategory(categoryId) {
    const productSelect = document.getElementById('gen_product_id');
    productSelect.innerHTML = '<option value="">Select a product</option>';
    
    if (!categoryId) return;

    const filteredProducts = productsData.filter(p => p.category_id == categoryId);
    
    filteredProducts.forEach(product => {
        const option = document.createElement('option');
        option.value = product.id;
        option.textContent = `${product.name} - ${product.description}`;
        productSelect.appendChild(option);
    });
}

function validateForm() {
    const displayDateInput = document.getElementById('display_expiry_date');
    const hiddenDateInput = document.getElementById('expiry_date');
    const dateValue = displayDateInput.value.trim();
    
    // Date is required
    if (dateValue === '') {
        const errorElement = document.getElementById('date-error');
        if (errorElement) {
            errorElement.textContent = 'Expiry date is required in DD-MM-YYYY format';
            errorElement.style.display = 'block';
        }
        displayDateInput.focus();
        return false;
    }

    // Validate entered date
    if (!validateDateInput(displayDateInput)) {
        displayDateInput.focus();
        return false;
    }
    
    // Double-check hidden input has a value
    if (!hiddenDateInput.value || hiddenDateInput.value === '') {
        const errorElement = document.getElementById('date-error');
        if (errorElement) {
            errorElement.textContent = 'Please enter a valid date in DD-MM-YYYY format';
            errorElement.style.display = 'block';
        }
        displayDateInput.focus();
        return false;
    }
    
    return true;
}
</script>
@endsection
