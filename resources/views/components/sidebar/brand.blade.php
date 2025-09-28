
@props([
    'logo' => null,
    'title' => 'CaféLufamina',
    'subtitle' => 'Sistema de Gestão',
    'version' => '1.0.0'
])

<div class="sidebar-brand">
    <div class="logo-wrapper">
        @if($logo)
            <img src="{{ asset($logo) }}" alt="Logo" style="width: 60px; height: 60px;">
        @else
            <i class="mdi mdi-silverware-fork-knife text-white" style="font-size: 2.5rem;"></i>
        @endif
        
        <!-- Pulse effect -->
        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-circle border border-white opacity-25"
             style="animation: pulse-ring 3s ease-out infinite;"></div>
    </div>
    
    <h4 class="brand-title">
        @php
            $titleParts = explode(' ', $title);
            $mainTitle = $titleParts[0] ?? $title;
            $accentTitle = $titleParts[1] ?? null;
        @endphp
        
        {{ $mainTitle }}
        @if($accentTitle)
            <span class="text-warning">{{ $accentTitle }}</span>
        @endif
    </h4>
    
    <small class="brand-subtitle">{{ $subtitle }}</small>
    
    @if($version)
        <div class="brand-version mt-2">
            <span class="badge bg-dark text-light opacity-75">v{{ $version }}</span>
        </div>
    @endif
    
    <!-- System Status -->
    <div class="system-status mt-2">
        <div class="d-flex align-items-center justify-content-center text-success">
            <div class="status-dot bg-success rounded-circle me-2" 
                 style="width: 6px; height: 6px; animation: pulse-dot 2s ease-in-out infinite;"></div>
            <small>Sistema Online</small>
        </div>
    </div>
</div>

<style>
@keyframes pulse-ring {
    0% {
        transform: scale(0.8);
        opacity: 1;
    }
    100% {
        transform: scale(1.4);
        opacity: 0;
    }
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.brand-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 1rem 0 0.5rem 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.brand-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 400;
    font-size: 0.85rem;
}

.brand-version .badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

.system-status {
    font-size: 0.75rem;
}
</style>
