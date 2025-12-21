@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<x-page-header title="Edit User" description="Update user information" :center="true" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('users.update', $user) }}">
            @csrf
            @method('PUT')

            <x-input name="name" label="Full Name" placeholder="Enter full name" required :value="old('name', $user->name)" />
            <x-input name="email" type="email" label="Email Address" placeholder="Enter email address" required :value="old('email', $user->email)" />
            
            <div class="form-group">
                <label class="form-label">Password (leave blank to keep current)</label>
                <input type="password" name="password" class="form-input" placeholder="Enter new password">
            </div>

            <x-select name="group_id" label="User Group" required placeholder="Select user group">
                @foreach(\App\Models\Group::all() as $group)
                    <option value="{{ $group->group_id }}" {{ old('group_id', $user->group_id) == $group->group_id ? 'selected' : '' }}>
                        {{ $group->group_name }}
                    </option>
                @endforeach
            </x-select>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
