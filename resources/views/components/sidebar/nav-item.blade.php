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

<li class="nav-item {{ $isActive ? 'active' : '' }}">
    <a class="nav-link {{ $isActive ? 'active' : '' }}" 
       href="{{ $href }}"
       @if($external) target="_blank" @endif>
        <div class="menu-icon-wrapper">
            <i class="mdi {{ $icon }} menu-icon"></i>
        </div>
        <span class="menu-title">{{ $title }}</span>
        
        @if(isset($badge) && $showBadge && $badge)
            <span class="badge {{ $badgeClass }} ml-auto">
                {{ $badgePrefix }}{{ $badge }}
            </span>
        @endif
        
        @if(isset($slot) && $slot->isNotEmpty())
            <span class="ml-auto status-indicator">
                {{ $slot }}
            </span>
        @endif
    </a>
</li>