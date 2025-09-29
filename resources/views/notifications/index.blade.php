@extends('layouts.app')

@section('title', 'Notificações')
@section('page-title', 'Central de Notificações')
@section('title-icon', 'mdi-bell')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Notificações</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card fade-in">
                <div class="card-header d-flex justify-content-between align-items-center bg-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-bell-ring text-primary me-2"></i>
                        Todas as Notificações
                    </h5>
                    <div>
                        @if($notifications->where('is_read', false)->count() > 0)
                            <form method="POST" action="{{ route('notifications.markAllAsRead') }}" style="display: inline;" id="mark-all-form">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-primary" id="mark-all-read-page">
                                    <i class="mdi mdi-check-all me-1"></i>
                                    Marcar Todas como Lidas
                                    <span class="badge bg-primary ms-1">{{ $notifications->where('is_read', false)->count() }}</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body p-3">
                    @if($notifications->count() > 0)
                        <div id="notifications-container">
                            @foreach($notifications as $notification)
                                <div class="notification-item card mb-3 {{ $notification->is_read ? 'border-light' : 'border-primary shadow-sm' }}"
                                     data-notification-id="{{ $notification->id }}">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <!-- Icon -->
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle p-3 {{ $notification->is_read ? 'bg-secondary' : 'bg-primary' }}">
                                                    <i class="mdi {{ $notification->is_read ? 'mdi-bell' : 'mdi-bell-ring' }} text-white fs-4"></i>
                                                </div>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <h6 class="card-title mb-0 {{ $notification->is_read ? 'text-muted' : 'fw-bold' }}">
                                                        {{ $notification->title }}
                                                    </h6>
                                                    @if(!$notification->is_read)
                                                        <span class="badge bg-primary">Nova</span>
                                                    @endif
                                                </div>
                                                
                                                <p class="card-text text-muted mb-2">
                                                    {{ $notification->message }}
                                                </p>
                                                
                                                <div class="d-flex align-items-center gap-3">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-clock-outline me-1"></i>
                                                        {{ $notification->created_at->format('d/m/Y H:i') }}
                                                    </small>
                                                    
                                                    <span class="badge bg-{{ $notification->priority === 'high' ? 'danger' : ($notification->priority === 'medium' ? 'warning' : 'info') }}">
                                                        {{ ucfirst($notification->priority) }}
                                                    </span>

                                                    @if($notification->type)
                                                        <span class="badge bg-secondary">
                                                            {{ ucfirst($notification->type) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Action Button -->
                                            <div class="flex-shrink-0 ms-3">
                                                @if(!$notification->is_read)
                                                    <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success mark-as-read" title="Marcar como lida">
                                                            <i class="mdi mdi-check-circle me-1"></i>
                                                            Marcar como lida
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted small">
                                                        <i class="mdi mdi-check-circle text-success"></i>
                                                        Lida
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <div class="d-flex justify-content-center mt-4">
                                {{ $notifications->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="mdi mdi-bell-off-outline display-1 text-muted"></i>
                            </div>
                            <h5 class="text-muted mb-3">Nenhuma notificação</h5>
                            <p class="text-muted mb-4">
                                Quando tiver notificações, elas aparecerão aqui.
                            </p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="mdi mdi-home me-1"></i>
                                Voltar ao Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.notification-item {
    transition: all 0.3s ease;
}

.notification-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.notification-item.border-primary {
    border-left-width: 4px !important;
}

.notification-item .rounded-circle {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.mark-as-read {
    transition: all 0.2s ease;
}

.mark-as-read:hover {
    transform: scale(1.05);
}

.fade-in {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marcar todas como lidas - com confirmação
    const markAllBtn = document.getElementById('mark-all-read-page');
    const markAllForm = document.getElementById('mark-all-form');
    
    if (markAllBtn && markAllForm) {
        markAllForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Deseja marcar todas as notificações como lidas?')) {
                this.submit();
            }
        });
    }

    // Confirmação opcional para marcar individualmente
    document.querySelectorAll('.mark-as-read').forEach(btn => {
        btn.closest('form').addEventListener('submit', function(e) {
            // Remover comentário abaixo se quiser confirmação individual
            // e.preventDefault();
            // if (confirm('Marcar esta notificação como lida?')) {
            //     this.submit();
            // }
        });
    });

    // Toast para mensagens de sessão
    @if(session('success'))
        showToast('{{ session("success") }}', 'success');
    @endif

    @if(session('error'))
        showToast('{{ session("error") }}', 'error');
    @endif
});
</script>
@endpush