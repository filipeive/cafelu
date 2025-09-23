
@props([
    'user' => null
])

@php
    $currentUser = $user ?? auth()->user();
@endphp

<div class="user-area">
    <div class="d-flex align-items-center text-white mb-3">
        <div class="position-relative me-3">
            @if($currentUser->profile_photo_path)
                <img src="{{ asset('storage/' . $currentUser->profile_photo_path) }}" 
                     alt="Avatar" 
                     class="rounded-circle" 
                     style="width: 40px; height: 40px; object-fit: cover;">
            @else
                <div class="avatar bg-white text-primary rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 40px; height: 40px;">
                    <i class="mdi mdi-chef-hat"></i>
                </div>
            @endif
            <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"
                  title="Online">
                <span class="visually-hidden">Online</span>
            </span>
        </div>
        <div class="flex-grow-1">
            <div class="fw-semibold">{{ $currentUser->name }}</div>
            <small class="opacity-75">
                {{ $currentUser->role ?? 'Usuário' }}
                @if($currentUser->restaurant_id)
                    <span class="text-muted">• Filial {{ $currentUser->restaurant_id }}</span>
                @endif
            </small>
        </div>
    </div>

    <!-- User Quick Stats -->
    <div class="user-stats mb-3">
        <div class="row g-2 text-center">
            <div class="col-4">
                <div class="user-stat-item">
                    <div class="fw-bold text-warning">{{ $todayOrders ?? 0 }}</div>
                    <small class="text-muted">Pedidos</small>
                </div>
            </div>
            <div class="col-4">
                <div class="user-stat-item">
                    <div class="fw-bold text-success">R$ {{ number_format($todaySales ?? 0, 2, ',', '.') }}</div>
                    <small class="text-muted">Vendas</small>
                </div>
            </div>
            <div class="col-4">
                <div class="user-stat-item">
                    <div class="fw-bold text-info">{{ $activeTime ?? '8h' }}</div>
                    <small class="text-muted">Online</small>
                </div>
            </div>
        </div>
    </div>

    <!-- User Actions -->
    <div class="user-actions mb-3">
        <div class="btn-group w-100" role="group">
            <a href="{{ route('profile') }}" 
               class="btn btn-outline-light btn-sm"
               title="Perfil">
                <i class="mdi mdi-account-edit"></i>
            </a>
            <button type="button" 
                    class="btn btn-outline-light btn-sm"
                    onclick="toggleTheme()"
                    title="Alternar Tema">
                <i class="mdi mdi-theme-light-dark"></i>
            </button>
            <button type="button" 
                    class="btn btn-outline-light btn-sm"
                    onclick="showQuickActions()"
                    title="Ações Rápidas">
                <i class="mdi mdi-lightning-bolt"></i>
            </button>
        </div>
    </div>

    <!-- Logout Button -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" 
                class="btn btn-outline-light btn-sm w-100 d-flex align-items-center justify-content-center"
                onclick="return confirm('Deseja realmente sair do sistema?')">
            <i class="mdi mdi-logout me-2"></i> 
            Sair do Sistema
        </button>
    </form>
</div>

<style>
.user-stat-item {
    padding: 0.5rem 0.25rem;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
}

.user-actions .btn {
    border-color: rgba(255, 255, 255, 0.3);
}

.user-actions .btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.5);
}
</style>
