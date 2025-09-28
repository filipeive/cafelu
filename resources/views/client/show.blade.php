@extends('layouts.app')

@section('title', $client->name)
@section('title-icon', 'mdi-account')
@section('page-title', 'Detalhes do Cliente')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Dashboard
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('client.index') }}" class="text-decoration-none">
            <i class="mdi mdi-account-multiple"></i> Clientes
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">{{ $client->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Alertas -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle-outline me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Informações Principais -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white d-flex align-items-center">
                    <i class="mdi mdi-account text-primary me-2 fs-5"></i>
                    <h5 class="mb-0">Informações do Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Nome Completo</label>
                            <p class="fs-6 mb-0">{{ $client->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Email</label>
                            <p class="fs-6 mb-0">
                                @if($client->email)
                                    <a href="mailto:{{ $client->email }}" class="text-decoration-none">
                                        {{ $client->email }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Telefone</label>
                            <p class="fs-6 mb-0">
                                @if($client->phone)
                                    <a href="tel:{{ $client->phone }}" class="text-decoration-none">
                                        {{ $client->phone }}
                                    </a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Data de Cadastro</label>
                            <p class="fs-6 mb-0">{{ $client->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold text-muted">Endereço</label>
                            <p class="fs-6 mb-0">{{ $client->address ?? 'Nenhum endereço cadastrado' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar com Ações -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="mdi mdi-cog me-2"></i>Ações</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('edit_clients')
                        <a href="{{ route('client.edit', $client->id) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil me-2"></i>Editar Cliente
                        </a>
                        @endcan
                        
                        @if($client->email)
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="mdi mdi-email me-2"></i>Enviar Email
                        </button>
                        @endif
                        
                        @if($client->phone)
                        <a href="tel:{{ $client->phone }}" class="btn btn-outline-success">
                            <i class="mdi mdi-phone me-2"></i>Ligar
                        </a>
                        @endif
                        
                        <a href="{{ route('client.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-2"></i>Voltar à Lista
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informações de Contato -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h6 class="mb-0"><i class="mdi mdi-information-outline me-2"></i>Contato Rápido</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if($client->email)
                        <a href="mailto:{{ $client->email }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="mdi mdi-email-outline text-primary me-3"></i>
                            <div>
                                <small class="text-muted">Email</small>
                                <div class="fw-medium">{{ $client->email }}</div>
                            </div>
                        </a>
                        @endif
                        
                        @if($client->phone)
                        <a href="tel:{{ $client->phone }}" class="list-group-item list-group-item-action d-flex align-items-center">
                            <i class="mdi mdi-phone-outline text-success me-3"></i>
                            <div>
                                <small class="text-muted">Telefone</small>
                                <div class="fw-medium">{{ $client->phone }}</div>
                            </div>
                        </a>
                        @endif
                        
                        <div class="list-group-item d-flex align-items-center">
                            <i class="mdi mdi-calendar text-info me-3"></i>
                            <div>
                                <small class="text-muted">Membro desde</small>
                                <div class="fw-medium">{{ $client->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Contato -->
@if($client->email)
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-email me-2"></i>Enviar Mensagem
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Enviar mensagem para: <strong>{{ $client->name }}</strong></p>
                <div class="mb-3">
                    <label class="form-label">Assunto</label>
                    <input type="text" class="form-control" placeholder="Assunto da mensagem">
                </div>
                <div class="mb-3">
                    <label class="form-label">Mensagem</label>
                    <textarea class="form-control" rows="4" placeholder="Digite sua mensagem..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary">Enviar Mensagem</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection