@props([
    'route' => null, 
    'url' => null,
    'icon' => null, 
    'title', 
    'badge' => null, 
    'badgeClass' => 'badge-primary',
    'badgePrefix' => '',
    'showBadge' => true,
    'external' => false
])
@php
$isActive = $route ? request()->routeIs($route) : ($url ? request()->fullUrlIs($url) : false);
$href = $url ?? ($external ? $route : ($route && Route::has($route) ? route($route) : '#'));
@endphp

<li>
    <a href="{{ $href }}" class="flex items-center pl-11 pr-3 py-2 text-sm rounded-lg transition-colors {{ $isActive ? 'text-primary font-medium bg-orange-50/50 dark:bg-gray-800/50' : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-50 dark:hover:bg-gray-800/50' }}"
       @if($external) target="_blank" @endif>
        @if($icon)
            <i class="mdi {{ $icon }} mr-2 text-lg"></i>
        @endif
        <span class="flex-1">{{ $title }}</span>
        
        @if(isset($badge) && $showBadge && $badge)
            <span class="{{ $badgeClass }} px-2 py-0.5 rounded text-xs font-semibold ml-2">
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