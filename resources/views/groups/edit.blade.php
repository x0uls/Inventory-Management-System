@extends('layouts.app')

@section('title', 'Edit User Group')

@section('content')
<x-page-header title="Edit User Group" description="Update user group information" :center="true" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('groups.update', $group) }}">
            @csrf
            @method('PUT')

            <x-input name="group_name" label="User Group Name" placeholder="Enter usergroup name" required :value="old('group_name', $group->group_name)" />
            <x-textarea name="description" label="Description" placeholder="Enter group description" :value="old('description', $group->description)" />

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Group</button>
                <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
