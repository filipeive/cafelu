
@props([
    'icon' => 'mdi-folder',
    'title' => '',
    'id' => '',
    'expanded' => false
])

@php
    $uniqueId = $id ?: 'dropdown-' . uniqid();
    $hasActiveChild = false;
    
    // Check if any child is active - this would require custom logic
    // For now, we'll use a simple check
    $currentRoute = request()->route()->getName();
    $isExpanded = $expanded || $hasActiveChild;
@endphp

<div class="nav-dropdown mb-2" data-dropdown="{{ $uniqueId }}">
    <div class="nav-dropdown-toggle" 
         data-bs-toggle="collapse" 
         data-bs-target="#{{ $uniqueId }}" 
         role="button"
         aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
         aria-controls="{{ $uniqueId }}">
        <i class="{{ $icon }} me-2"></i>
        <span class="flex-grow-1">{{ $title }}</span>
        <i class="mdi mdi-chevron-down transition-transform ms-auto dropdown-arrow"></i>
    </div>
    
    <div class="collapse {{ $isExpanded ? 'show' : '' }}" id="{{ $uniqueId }}">
        <div class="nav-dropdown-content">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
.dropdown-arrow {
    transition: transform 0.3s ease;
}

.nav-dropdown-toggle[aria-expanded="true"] .dropdown-arrow {
    transform: rotate(180deg);
}

.nav-dropdown-content {
    padding-left: 0.5rem;
    border-left: 2px solid rgba(255, 255, 255, 0.1);
    margin-left: 1rem;
    margin-top: 0.5rem;
}
</style>
