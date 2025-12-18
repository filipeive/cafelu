@props([
    'route' => '#',
    'icon' => '',
    'title' => '',
    'badge' => null,
    'badgeClass' => 'badge-light',
    'badgePrefix' => '',
    'showBadge' => true,
    'external' => false
])

@php
    $isActive = request()->routeIs($route);
    $href = $external ? $route : ($route !== '#' ? route($route) : '#');
@endphp

<li class="mb-1">
    <a class="flex items-center px-3 py-2 rounded-lg transition-colors group {{ $isActive ? 'bg-orange-50 text-primary dark:bg-gray-800 dark:text-primary' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}" 
       :class="{ 'justify-center': sidebarCollapsed }"
       href="{{ $href }}"
       @if($external) target="_blank" @endif>
        <i class="mdi {{ $icon }} text-xl {{ $isActive ? 'text-primary' : 'text-gray-400 group-hover:text-gray-600 dark:text-gray-500 dark:group-hover:text-gray-300' }}"
           :class="{ 'mr-3': !sidebarCollapsed }"></i>
        
        <span class="font-medium flex-1" x-show="!sidebarCollapsed" x-transition>{{ $title }}</span>
        
        @if(isset($badge) && $showBadge && $badge)
            <span class="{{ $badgeClass }} px-2 py-0.5 rounded text-xs font-semibold ml-2" x-show="!sidebarCollapsed" x-transition>
                {{ $badgePrefix }}{{ $badge }}
            </span>
        @endif
        
        @if(isset($slot) && $slot->isNotEmpty())
            <span class="ml-2" x-show="!sidebarCollapsed" x-transition>
                {{ $slot }}
            </span>
        @endif
    </a>
</li>