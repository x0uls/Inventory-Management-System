<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Current Stock</th>
            <th>Low Stock Alert</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($products as $product)
            @php
                $totalQuantity = $product->batches_sum_quantity ?? 0;
                $isLowStock = $totalQuantity <= $product->lowstock_alert;
            @endphp
            <tr>
                <td>{{ $product->product_id }}</td>
                <td style="font-weight: 600;">{{ $product->product_name }}</td>
                <td>{{ $product->description }}</td>
                <td style="font-weight: 700;">{{ $totalQuantity }}</td>
                <td>{{ $product->lowstock_alert }}</td>
                <td>
                    @if($isLowStock)
                        <x-badge type="error">Low Stock</x-badge>
                    @else
                        <x-badge type="success">In Stock</x-badge>
                    @endif
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="{{ route('stock.batches.view', $product->product_id) }}" class="btn btn-sm btn-info" title="View Batches">
                            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </a>
                        @if(Auth::user()->roles === 'admin')

                            <form action="{{ route('products.destroy', $product->product_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 2rem; color: var(--color-slate-500);">No products found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($products->hasPages())
<div style="margin-top: 1.5rem;">{{ $products->links() }}</div>
@endif
