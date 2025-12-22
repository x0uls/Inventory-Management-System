<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        // Don't cache if there's a search (user wants fresh results)
        if ($request->filled('search')) {
            $query = Category::query();
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
            $categories = $query->latest('category_id')->paginate(10)->withQueryString();
        } else {
            $categories = Category::latest('category_id')->paginate(10);
        }

        if ($request->ajax()) {
            return view('categories.partials.table', compact('categories'));
        }

        return view('categories.index', [
            'categories' => $categories,
            'currentSearch' => $request->search,
        ]);
    }

    public function create(): View
    {
        if (strtolower(auth()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        return view('categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        if (strtolower($request->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        // Find gaps in IDs
        $ids = Category::orderBy('category_id', 'asc')->pluck('category_id')->toArray();
        $newId = 1;
        foreach ($ids as $id) {
            if ($id != $newId) {
                break;
            }
            $newId++;
        }

        Category::create([
            'category_id' => $newId,
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);

        // Clear cache
        Cache::forget('categories.index');
        Cache::flush();

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        if (strtolower(auth()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        return view('categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        if (strtolower($request->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        $category->update([
            'category_name' => $request->category_name,
            'description' => $request->description,
        ]);



        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if (strtolower(request()->user()->roles) === 'staff') {
            abort(403, 'Unauthorized action.');
        }

        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete category associated with existing products.');
        }

        $category->delete();



        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
