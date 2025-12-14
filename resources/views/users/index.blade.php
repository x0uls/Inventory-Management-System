@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<x-page-header title="User Management" description="Manage users and their roles" />

<!-- Search Section -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('users.index') }}" class="search-container">
            <div style="flex: 1;">
                <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search by name or email..." class="form-input">
            </div>
            <div style="width: 200px;">
                <select name="role" class="form-select">
                    <option value="all" {{ $currentRole === 'all' ? 'selected' : '' }}>All User Groups</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ $currentRole === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            @if($currentSearch || $currentRole !== 'all')
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Add User Button -->
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('users.create') }}" class="btn btn-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add User
    </a>
</div>

<!-- Users Table -->
<div class="table-container" id="users-table-container">
    @include('users.partials.table')
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/ajax-search.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initAjaxSearch('input[name="search"]', '#users-table-container', '{{ route("users.index") }}');
});
</script>
@endpush
