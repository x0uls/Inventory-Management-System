@extends('layouts.app')

@section('title', 'Add Category')

@section('content')
<x-page-header title="Add New Category" description="Create a new product category" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <x-input
                name="category_name"
                label="Category Name"
                placeholder="Enter category name"
                required
                :value="old('category_name')" />

            <x-textarea
                name="description"
                label="Description"
                placeholder="Enter category description"
                required
                :value="old('description')" />

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
