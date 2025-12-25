@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<x-page-header title="Edit Product" description="Update product details" :center="true" />

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Image Upload Section -->
            <div class="form-group">
                <label class="form-label">Product Image</label>
                <!-- Hidden input for remove_image flag -->
                <input type="checkbox" name="remove_image" id="remove_image" value="1" style="display: none;">
                
                <div 
                    id="image-drop-zone" 
                    style="border: 2px dashed var(--color-slate-300); border-radius: var(--radius-md); padding: 2rem; text-align: center; cursor: pointer; transition: all 0.2s; background: var(--color-slate-50); position: relative;"
                    onclick="document.getElementById('image').click()"
                    ondrop="handleDrop(event)"
                    ondragover="handleDragOver(event)"
                    ondragleave="handleDragLeave(event)"
                >
                    <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)" style="display: none;">
                    
                    <div id="upload-placeholder" style="{{ $product->image_path ? 'display: none;' : '' }}">
                        <svg style="width: 48px; height: 48px; color: var(--color-slate-400); margin: 0 auto 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p style="color: var(--color-slate-600); font-weight: 500;">Click to upload or drag and drop</p>
                        <p style="color: var(--color-slate-400); font-size: 0.875rem;">SVG, PNG, JPG or GIF (MAX. 2MB)</p>
                    </div>

                    <div id="image-preview" style="{{ $product->image_path ? '' : 'display: none;' }} position: relative;">
                        <img id="preview-img" src="{{ $product->image_path ? asset($product->image_path) : '' }}" alt="Preview" style="max-height: 300px; max-width: 100%; object-fit: contain; margin: 0 auto; border-radius: var(--radius-sm); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                        <button type="button" onclick="clearImage(event)" class="btn btn-danger btn-sm" style="position: absolute; top: -10px; right: 50%; transform: translateX(50%) translateY(-100%); margin-top: -10px;">
                            Remove Image
                        </button>
                    </div>
                </div>
                @error('image')
                    <span class="text-red-500 text-sm" style="display: block; margin-top: 0.5rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="product_name" class="form-label">Product Name</label>
                <input type="text" name="product_name" id="product_name" class="form-input" value="{{ old('product_name', $product->product_name) }}" required>
                @error('product_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id', $product->category_id) == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-input" rows="4">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="unit_price" class="form-label">Unit Price</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--color-slate-500);">RM</span>
                    <input type="number" name="unit_price" id="unit_price" class="form-input" value="{{ old('unit_price', $product->unit_price) }}" step="0.01" min="0" style="padding-left: 2.5rem;" required>
                </div>
                @error('unit_price')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="lowstock_alert" class="form-label">Low Stock Alert Level</label>
                <input type="number" name="lowstock_alert" id="lowstock_alert" class="form-input" value="{{ old('lowstock_alert', $product->lowstock_alert) }}" min="0" required>
                <p style="font-size: 0.875rem; color: var(--color-slate-500); margin-top: 0.5rem;">
                    You will be notified when stock falls below this quantity.
                </p>
                @error('lowstock_alert')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('image-drop-zone').style.borderColor = 'var(--color-primary)';
        document.getElementById('image-drop-zone').style.background = 'var(--color-primary-light)';
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('image-drop-zone').style.borderColor = 'var(--color-slate-300)';
        document.getElementById('image-drop-zone').style.background = 'var(--color-slate-50)';
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('image-drop-zone').style.borderColor = 'var(--color-slate-300)';
        document.getElementById('image-drop-zone').style.background = 'var(--color-slate-50)';
        
        const dt = e.dataTransfer;
        const files = dt.files;
        
        if (files && files[0]) {
            const input = document.getElementById('image');
            input.files = files;
            previewImage(input);
        }
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('upload-placeholder').style.display = 'none';
                document.getElementById('image-preview').style.display = 'block';
                // Uncheck remove_image if user uploads a new one
                document.getElementById('remove_image').checked = false;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearImage(e) {
        e.stopPropagation(); 
        document.getElementById('image').value = '';
        document.getElementById('image-preview').style.display = 'none';
        document.getElementById('upload-placeholder').style.display = 'block';
        document.getElementById('preview-img').src = '';
        
        // Mark image for removal
        document.getElementById('remove_image').checked = true;
    }
</script>
@endpush
