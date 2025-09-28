{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Notificações')
@section('title-icon', 'mdi-bell')

@section('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">Notificações</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card fade-in">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Notificações</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-primary" id="mark-all-read-page">
                            <i class="mdi mdi-check-all me-1"></i>Marcar Todas como Lidas
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="notifications-container">
                        @foreach($notifications as $notification)
                            <div class="notification-item card mb-3 {{ $notification->is_read ? '' : 'border-primary' }}"
                                 data-notification-id="{{ $notification->id }}">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded-circle p-2 {{ $notification->is_read ? 'bg-secondary' : 'bg-primary' }}">
                                                <i class="mdi {{ $notification->is_read ? 'mdi-bell' : 'mdi-bell-ring' }} text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title mb-1">{{ $notification->title }}</h6>
                                            <p class="card-text text-muted mb-1">{{ $notification->message }}</p>
                                            <small class="text-muted">
                                                {{ $notification->created_at->format('d/m/Y H:i') }}
                                                • Prioridade: <span class="badge bg-{{ $notification->priority === 'high' ? 'danger' : ($notification->priority === 'medium' ? 'warning' : 'info') }}">
                                                    {{ $notification->priority }}
                                                </span>
                                            </small>
                                        </div>
                                        <div class="flex-shrink-0">
                                            @if(!$notification->is_read)
                                                <button class="btn btn-sm btn-outline-success mark-as-read">
                                                    <i class="mdi mdi-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($notifications->isEmpty())
                        <div class="text-center py-5">
                            <i class="mdi mdi-bell-off-outline fs-1 text-muted"></i>
                            <h5 class="text-muted mt-3">Nenhuma notificação</h5>
                            <p class="text-muted">Quando tiver notificações, elas aparecerão aqui.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Marcar todas como lidas
    document.getElementById('mark-all-read-page')?.addEventListener('click', function() {
        fetch('{{ route("notifications.markAllAsRead") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    });

    // Marcar individualmente como lida
    document.querySelectorAll('.mark-as-read').forEach(btn => {
        btn.addEventListener('click', function() {
            const notificationId = this.closest('.notification-item').dataset.notificationId;
            
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        });
    });
});
</script>
@endpush