@props(['icon', 'title', 'id'])

@php
    // Verificar se alguma rota filha está ativa
    $isActive = false;
    
    // Vamos usar o conteúdo do slot para verificar se há itens ativos
    $content = $slot->toHtml();
    if (strpos($content, 'active') !== false) {
        $isActive = true;
    }
@endphp

<li class="nav-item">
    <a class="nav-link {{ $isActive ? 'active' : '' }}" 
       data-bs-toggle="collapse" 
       href="#{{ $id }}" 
       aria-expanded="{{ $isActive ? 'true' : 'false' }}" 
       aria-controls="{{ $id }}">
        <i class="menu-icon mdi {{ $icon }}"></i>
        <span class="menu-title">{{ $title }}</span>
        <i class="menu-arrow"></i>  <!-- Certifique-se de que esta linha existe -->
    </a>
    <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $id }}">
        <ul class="nav flex-column sub-menu">
            {{ $slot }}
        </ul>
    </div>
</li>