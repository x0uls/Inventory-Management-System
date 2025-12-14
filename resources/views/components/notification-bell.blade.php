<div style="position: relative;">
    <button id="notification-btn" class="notification-bell">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if($count > 0)
            <span class="notification-badge">{{ $count > 99 ? '99+' : $count }}</span>
        @endif
    </button>

    <!-- Notification Dropdown -->
    <div 
        id="notification-dropdown"
        style="position: absolute; right: 0; top: calc(100% + 0.5rem); width: 320px; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-xl); z-index: 1000; display: none;"
    >
        <!-- Notification Header -->
        <div style="padding: 1rem 1.25rem; border-bottom: 1px solid var(--color-slate-200); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-weight: 600; font-size: 1rem; margin: 0; color: var(--color-slate-900);">Notifications</h3>
            @if($count > 0)
                <span class="badge badge-error">{{ $count }}</span>
            @endif
        </div>

        <!-- Notification List -->
        <div style="max-height: 400px; overflow-y: auto;">
            @if($count > 0)
                <!-- Low Stock Notifications -->
                <a href="{{ route('stock.index') }}?filter=low_stock" style="display: block; padding: 1rem 1.25rem; border-bottom: 1px solid var(--color-slate-100); text-decoration: none; transition: background var(--transition-base);" onmouseover="this.style.background='var(--color-slate-50)'" onmouseout="this.style.background=''">
                    <div style="display: flex; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; background: rgba(239, 68, 68, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            <svg style="width: 20px; height: 20px; color: var(--color-error);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.875rem; color: var(--color-slate-900); margin-bottom: 0.25rem;">Low Stock Alert</div>
                            <div style="font-size: 0.8125rem; color: var(--color-slate-600);">{{ $count }} {{ $count === 1 ? 'product' : 'products' }} running low on stock</div>
                            <div style="font-size: 0.75rem; color: var(--color-slate-400); margin-top: 0.25rem;">Click to view</div>
                        </div>
                    </div>
                </a>
            @else
                <!-- No Notifications -->
                <div style="padding: 3rem 1.25rem; text-align: center;">
                    <svg style="width: 48px; height: 48px; margin: 0 auto 1rem; opacity: 0.3; color: var(--color-slate-400);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <div style="font-weight: 600; color: var(--color-slate-900); margin-bottom: 0.25rem;">No notifications</div>
                    <div style="font-size: 0.875rem; color: var(--color-slate-500);">You're all caught up!</div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const $btn = $('#notification-btn');
        const $dropdown = $('#notification-dropdown');

        $btn.on('click', function(e) {
            e.stopPropagation();
            $dropdown.fadeToggle(200);
        });

        $(document).on('click', function(e) {
            if (!$btn.is(e.target) && $btn.has(e.target).length === 0 && 
                !$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
                $dropdown.fadeOut(200);
            }
        });
    });
</script>
@endpush
