@props(['title', 'description' => null, 'back' => null, 'center' => false])

<div class="page-header" @if($center) style="text-align: center;" @endif>
    @if($center)
        <h1 class="page-title">{{ $title }}</h1>
        @if(isset($description))
            <p class="page-description">{{ $description }}</p>
        @endif
        
        @if($back)
            <div style="margin-top: 1rem;">
                <a href="{{ $back }}" class="btn btn-secondary" style="display: inline-flex; align-items: center;">
                    <svg style="width: 20px; height: 20px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        @endif
    @else
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="page-title">{{ $title }}</h1>
                @if(isset($description))
                    <p class="page-description">{{ $description }}</p>
                @endif
            </div>
            @if($back)
                <a href="{{ $back }}" class="btn btn-secondary" style="display: inline-flex; align-items: center;">
                    <svg style="width: 20px; height: 20px; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            @endif
        </div>
    @endif
</div>
