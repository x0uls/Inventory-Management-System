<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class GroupController extends Controller
{
    public function index(Request $request): View
    {
        // Don't cache if there's a search (user wants fresh results)
        if ($request->filled('search')) {
            $query = Group::query();
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('group_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
            $groups = $query->latest('group_id')->paginate(10)->withQueryString();
        } else {
            $groups = Group::latest('group_id')->paginate(10);
        }

        if ($request->ajax()) {
            return view('groups.partials.table', compact('groups'));
        }

        return view('groups.index', [
            'groups' => $groups,
            'currentSearch' => $request->search,
        ]);
    }

    public function create(): View
    {
        return view('groups.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'group_name' => ['required', 'string', 'max:255', 'unique:user_groups,group_name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        // Find gaps in IDs
        $ids = Group::orderBy('group_id', 'asc')->pluck('group_id')->toArray();
        $newId = 1;
        foreach ($ids as $id) {
            if ($id != $newId) {
                break;
            }
            $newId++;
        }

        Group::create([
            'group_id' => $newId,
            'group_name' => $request->group_name,
            'description' => $request->description,
        ]);

        // Clear cache
        Cache::forget('groups.index');
        Cache::flush();

        return redirect()->route('groups.index')
            ->with('success', 'Group created successfully.');
    }

    public function edit(Group $group): View
    {
        return view('groups.edit', [
            'group' => $group,
        ]);
    }

    public function update(Request $request, Group $group): RedirectResponse
    {
        $request->validate([
            'group_name' => ['required', 'string', 'max:255', 'unique:user_groups,group_name,' . $group->group_id . ',group_id'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $group->update([
            'group_name' => $request->group_name,
            'description' => $request->description,
        ]);



        return redirect()->route('groups.index')
            ->with('success', 'Group updated successfully.');
    }

    public function destroy(Group $group): RedirectResponse
    {
        // Check if group has users
        if ($group->users()->count() > 0) {
            return redirect()->route('groups.index')
                ->with('error', 'Cannot delete group with assigned users.');
        }

        $group->delete();



        return redirect()->route('groups.index')
            ->with('success', 'Group deleted successfully.');
    }
}
