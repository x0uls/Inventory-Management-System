@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<x-page-header title="Edit Category" description="Update category information" :center="true" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <x-input
                name="category_name"
                label="Category Name"
                placeholder="Enter category name"
                required
                :value="old('category_name', $category->category_name)" />

            <x-textarea
                name="description"
                label="Description"
                placeholder="Enter category description"
                required
                :value="old('description', $category->description)" />

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
