<div class="stat-card stat-{{ $type ?? 'primary' }}">
    <div class="stat-card-header">
        <div class="stat-card-title">{{ $title }}</div>
        <div class="stat-card-icon icon-{{ $type ?? 'primary' }}">
            {!! $icon !!}
        </div>
    </div>
    <div class="stat-card-value">{{ $value }}</div>
    @if(isset($change))
        <div class="stat-card-change {{ $changeType ?? 'positive' }}">
            @if(($changeType ?? 'positive') === 'positive')
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            @else
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            @endif
            <span>{{ $change }}</span>
        </div>
    @endif
</div>
