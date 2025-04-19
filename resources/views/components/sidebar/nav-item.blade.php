@props([
    'route' => '#',
    'icon' => '',
    'title' => '',
    'badge' => null,
    'badgeClass' => 'badge-light',
    'badgePrefix' => '',
    'showBadge' => true
])

<li class="nav-item">
    <a class="nav-link {{ request()->routeIs($route) ? 'active' : '' }}" 
       href="{{ $route !== '#' ? route($route) : '#' }}">
        <i class="mdi {{ $icon }} menu-icon"></i>
        <span class="menu-title">{{ $title }}</span>
        
        @if(isset($badge) && $showBadge && $badge)
            <span class="badge {{ $badgeClass }} ml-auto">
                {{ $badgePrefix }}{{ $badge }}
            </span>
        @endif
        
        @if(isset($slot) && $slot->isNotEmpty())
            <span class="ml-auto">
                {{ $slot }}
            </span>
        @endif
    </a>
</li>