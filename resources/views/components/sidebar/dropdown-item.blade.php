@props([
    'route' => null, 
    'url' => null,
    'icon' => null, 
    'title', 
    'badge' => null, 
    'badgeClass' => 'bg-gray-100 text-gray-700',
    'badgePrefix' => '',
    'showBadge' => true,
    'external' => false
])

@php
    $isActive = false;
    if ($url) {
        $isActive = request()->fullUrlIs($url);
    } elseif ($route && Route::has($route)) {
        $isActive = request()->routeIs($route) || request()->routeIs($route . '.*');
    }
    $href = $url ?? ($external ? $route : ($route && Route::has($route) ? route($route) : '#'));
@endphp

<li>
    <a href="{{ $href }}"
       @if($external) target="_blank" @endif
       class="group flex items-center px-2.5 py-1.5 text-xs font-medium rounded-md transition-all duration-150 {{ $isActive 
            ? 'text-primary font-semibold bg-primary/10' 
            : 'text-muted-foreground hover:text-foreground hover:bg-accent/60' }}">
        
        @if($icon)
            <i class="mdi {{ $icon }} mr-2 text-base {{ $isActive ? 'text-primary' : 'text-muted-foreground group-hover:text-foreground' }}"></i>
        @else
            <span class="size-1.5 rounded-full mr-2 transition-colors {{ $isActive ? 'bg-primary' : 'bg-muted-foreground/40 group-hover:bg-muted-foreground' }}"></span>
        @endif

        <span class="flex-1 truncate tracking-tight">{{ $title }}</span>

        @if(isset($badge) && $showBadge && $badge !== null && $badge !== '')
            <span class="ml-2 text-[10px] font-bold px-1.5 py-0.5 rounded-full {{ $badgeClass }}">
                {{ $badgePrefix }}{{ $badge }}
            </span>
        @endif

        @if(isset($slot) && !empty(trim($slot->toHtml())))
            <span class="ml-2">
                {{ $slot }}
            </span>
        @endif
    </a>
</li>