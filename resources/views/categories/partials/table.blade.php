<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Category Name</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categories as $category)
        <tr>
            <td>{{ $category->category_id }}</td>
            <td style="font-weight: 600;">{{ $category->category_name }}</td>
            <td>{{ $category->description }}</td>
            <td>
                <div class="action-buttons">
                    @if(strtolower(auth()->user()->roles) !== 'staff')
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-secondary" title="Edit">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                    @else
                    <span style="color: var(--color-slate-400); font-size: 0.875rem;">View Only</span>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--color-slate-500);">
                No categories found.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

@if($categories->hasPages())
<div style="margin-top: 1.5rem;">
    {{ $categories->links() }}
</div>
@endif
