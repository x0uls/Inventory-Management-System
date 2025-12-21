@extends('layouts.app')

@section('title', 'User Groups Management')

@section('content')
<x-page-header title="User Groups Management" description="Manage user groups" />

<!-- Search Section -->
<div class="card mb-6" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('groups.index') }}" class="search-container">
            <div style="flex: 1;">
                <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search by name or description..." class="form-input">
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
            @if($currentSearch)
                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<!-- Add Group Button -->
@if(strtolower(auth()->user()->roles) !== 'staff')
<div style="margin-bottom: 1.5rem;">
    <a href="{{ route('groups.create') }}" class="btn btn-success">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Group
    </a>
</div>
@endif

<!-- Groups Table -->
<div class="table-container" id="groups-table-container">
    @include('groups.partials.table')
</div>

@endsection

@push('scripts')
<script src="{{ asset('js/ajax-search.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    initAjaxSearch('input[name="search"]', '#groups-table-container', '{{ route("groups.index") }}');
});
</script>
@endpush
