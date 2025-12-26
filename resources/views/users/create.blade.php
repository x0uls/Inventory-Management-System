@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<x-page-header title="Add New User" description="Create a new user account" :center="true" />

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <x-input name="name" label="Full Name" placeholder="Enter full name" required :value="old('name')" />
            
            <x-input name="email" type="email" label="Email Address" placeholder="Enter email address" required :value="old('email')" />
            <div class="form-group">
                <label for="password" class="form-label required">Password</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="Enter password" required>
                <p style="font-size: 0.8em; color: var(--color-slate-500); margin-top: 0.25rem;">Password must be between 8 and 20 characters.</p>
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
            <x-input name="password_confirmation" type="password" label="Confirm Password" placeholder="Confirm password" required />
            
            <x-select name="group_id" label="User Group" required placeholder="Select user group">
                @foreach(\App\Models\Group::all() as $group)
                    <option value="{{ $group->group_id }}" {{ old('group_id') == $group->group_id ? 'selected' : '' }}>
                        {{ $group->group_name }}
                    </option>
                @endforeach
            </x-select>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
