@props([
    'route', 
    'icon' => null, 
    'title', 
    'badge' => null, 
    'badgeClass' => 'badge-primary',
    'badgePrefix' => '',
    'showBadge' => true,
    'external' => false
])
@php
$isActive = $route ? request()->routeIs($route) : false;
$href = $external ? $route : ($route && Route::has($route) ? route($route) : '#');
@endphp
{{-- @php
    $isActive = request()->routeIs($route);
    $href = $external ? $route : ($route !== '#' ? route($route) : '#');
@endphp
 --}}
<li class="nav-item {{ $isActive ? 'active' : '' }}">
    <a href="{{ $href }}" class="nav-link {{ $isActive ? 'active' : '' }}"
       @if($external) target="_blank" @endif>
        @if($icon)
            <i class="menu-icon mdi {{ $icon }}"></i>
        @else
            <span class="menu-dot"></span>
        @endif
        <span class="menu-title">{{ $title }}</span>
        
        @if(isset($badge) && $showBadge && $badge)
            <span class="badge {{ $badgeClass }} ml-auto">
                {{ $badgePrefix }}{{ $badge }}
            </span>
        @endif
        
        @if(isset($slot) && !empty(trim($slot->toHtml())))
            <span class="status-indicator ml-auto">
                {{ $slot }}
            </span>
        @endif
    </a>
</li>