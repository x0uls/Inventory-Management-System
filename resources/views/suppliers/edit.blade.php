@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<x-page-header title="Edit Supplier" description="Update supplier details" />

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="supplier_name" class="form-label">Supplier Name</label>
                <input type="text" name="supplier_name" id="supplier_name" class="form-input" value="{{ old('supplier_name', $supplier->supplier_name) }}" required>
                @error('supplier_name')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="category_id" class="form-label">Category (Optional)</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->category_id }}" {{ old('category_id', $supplier->category_id) == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="contact_person" class="form-label">Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" class="form-input" value="{{ old('contact_person', $supplier->contact_person) }}">
                @error('contact_person')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $supplier->email) }}">
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" name="phone" id="phone" class="form-input" value="{{ old('phone', $supplier->phone) }}">
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="address" class="form-label">Address</label>
                <textarea name="address" id="address" class="form-input" rows="3">{{ old('address', $supplier->address) }}</textarea>
                @error('address')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem;">
                <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Supplier</button>
            </div>
        </form>
    </div>
</div>
@endsection
