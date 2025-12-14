@extends('layouts.app')

@section('title', 'Add Group')

@section('content')
<x-page-header title="Add New Group" description="Create a new user group" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('groups.store') }}">
            @csrf

            <x-input name="group_name" label="User Group Name" placeholder="Enter user group name" required :value="old('group_name')" />
            <x-textarea name="description" label="Description" placeholder="Enter user group description" :value="old('description')" />

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Group</button>
                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
