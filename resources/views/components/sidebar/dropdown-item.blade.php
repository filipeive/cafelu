
@props([
    'route' => '#',
    'icon' => 'mdi-circle-outline',
    'title' => '',
    'badge' => null,
    'badgeClass' => 'badge-primary',
    'badgePrefix' => '',
    'showBadge' => true,
    'active' => false
])

@php
    $isActive = $active || (is_string($route) && $route !== '#' && request()->routeIs($route . '*'));
    $href = $route !== '#' && \Illuminate\Support\Facades\Route::has($route) ? route($route) : $route;
@endphp

<a class="nav-dropdown-item {{ $isActive ? 'active' : '' }}" 
   href="{{ $href }}"
   @if($route === '#') onclick="return false;" style="opacity: 0.6; cursor: not-allowed;" @endif>
    <i class="{{ $icon }} me-2"></i>
    <span class="flex-grow-1">{{ $title }}</span>
    
    @if($badge && $showBadge)
        <span class="badge {{ $badgeClass }} ms-auto">
            {{ $badgePrefix }}{{ $badge }}
        </span>
    @endif
    
    {{ $slot }}
</a>
