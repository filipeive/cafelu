@props([
    'route', 
    'icon', 
    'title', 
    'badge' => null, 
    'badgeClass' => 'badge-primary',
    'badgePrefix' => '',
    'showBadge' => true
])

@php
    $isActive = request()->routeIs($route);
    $href = $route !== '#' ? route($route) : '#';
@endphp

<li class="nav-item">
    <a href="{{ $href }}" class="nav-link {{ $isActive ? 'active' : '' }}">
        <i class="menu-icon mdi {{ $icon }}"></i>
        <span class="menu-title">{{ $title }}</span>
        
        @if(isset($badge) && $showBadge && $badge)
            <span class="badge {{ $badgeClass }} ml-auto">
                {{ $badgePrefix }}{{ $badge }}
            </span>
        @endif
        
        @if(isset($slot) && !empty(trim($slot->toHtml())))
            {{ $slot }}
        @endif
    </a>
</li>