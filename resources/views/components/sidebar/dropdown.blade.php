@props(['icon', 'title', 'id', 'badge' => null, 'badgeClass' => 'badge-primary'])

@php
    // Verificar se alguma rota filha está ativa
    $isActive = false;

    // Vamos usar o conteúdo do slot para verificar se há itens ativos
    $content = $slot->toHtml();
    if (strpos($content, 'active') !== false) {
        $isActive = true;
    }
@endphp

<li class="nav-item dropdown-container {{ $isActive ? 'active-dropdown' : '' }}">
    <a class="nav-link dropdown-toggle {{ $isActive ? 'active' : '' }}" data-bs-toggle="collapse"
        href="#{{ $id }}" aria-expanded="{{ $isActive ? 'true' : 'false' }}" aria-controls="{{ $id }}">
        @if (!empty($icon))
            <div class="menu-icon-wrapper">
                <i class="menu-icon mdi {{ $icon }}"></i>
            </div>
        @endif
        <span class="menu-title">{{ $title }}</span>

        @if (isset($badge) && $badge)
            <span class="badge {{ $badgeClass }} ml-auto me-2">
                {{ $badge }}
            </span>
        @endif

        <i class="menu-arrow mdi mdi-chevron-down"></i>
    </a>
    <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $id }}">
        <ul class="nav flex-column">
            {{ $slot }}
        </ul>
    </div>
</li>
