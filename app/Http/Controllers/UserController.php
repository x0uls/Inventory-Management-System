<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        // Don't cache if there's a search/filter (user wants fresh results)
        $cacheKey = 'users.index.' . md5($request->getQueryString());

        if ($request->filled('search') || ($request->filled('role') && $request->role !== 'all')) {
            // No cache for filtered results
            $query = User::with('group');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('role') && $request->role !== 'all') {
                $query->where('roles', $request->role);
            }

            $users = $query->latest('user_id')->paginate(10)->withQueryString();
        } else {
            $users = User::with('group')->latest('user_id')->paginate(10);
        }

        // Roles list
        $roles = User::distinct()->pluck('roles')->filter()->sort()->values();

        // Groups list
        $groups = Group::orderBy('group_name')->get();

        if ($request->ajax()) {
            return view('users.partials.table', compact('users'));
        }

        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
            'groups' => $groups,
            'currentSearch' => $request->search,
            'currentRole' => $request->role ?? 'all',
        ]);
    }

    public function create(): View
    {
        $groups = Group::orderBy('group_name')->get();

        return view('users.create', [
            'groups' => $groups,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles' => Group::find($request->group_id)->group_name,
            'group_id' => $request->group_id,
        ]);



        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user): View
    {
        $groups = Group::orderBy('group_name')->get();

        return view('users.edit', [
            'user' => $user,
            'groups' => $groups,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'roles' => Group::find($request->group_id)->group_name,
            'group_id' => $request->group_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);



        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();



        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}
