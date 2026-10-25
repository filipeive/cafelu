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
       class="group flex items-center px-2.5 py-1.5 text-xs font-medium rounded-lg transition-all duration-150 {{ $isActive 
            ? 'text-orange-600 dark:text-orange-400 font-semibold bg-orange-500/10' 
            : 'text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100/70 dark:hover:bg-gray-800/50' }}">
        
        @if($icon)
            <i class="mdi {{ $icon }} mr-2 text-base {{ $isActive ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300' }}"></i>
        @else
            <span class="w-1.5 h-1.5 rounded-full mr-2 transition-colors {{ $isActive ? 'bg-orange-500' : 'bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-500' }}"></span>
        @endif

        <span class="flex-1 truncate">{{ $title }}</span>

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