@extends('layouts.app')

@section('title', 'Clientes')
@section('title-icon', 'mdi-account-multiple')
@section('page-title', 'Gestão de Clientes')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Dashboard
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Clientes</li>
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

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle-outline me-2"></i>
            <strong>Erros encontrados:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header com Ações -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <i class="mdi mdi-account-multiple text-primary me-2"></i>
                                Lista de Clientes
                            </h5>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end gap-2">
                            <form method="GET" action="{{ route('client.search') }}" class="d-flex flex-grow-1 me-2">
                                <div class="input-group">
                                    <input type="text" name="query" class="form-control" 
                                           placeholder="Pesquisar clientes..." value="{{ request('query') }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="mdi mdi-magnify"></i>
                                    </button>
                                </div>
                            </form>
                            @can('create_clients')
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <i class="mdi mdi-plus-circle-outline me-1"></i>Novo Cliente
                            </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Clientes -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @if($clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Telefone</th>
                                        <th>Endereço</th>
                                        <th class="text-center" style="width: 150px;">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td class="ps-4 fw-semibold">{{ $client->id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                                                        <i class="mdi mdi-account text-primary"></i>
                                                    </div>
                                                    <span class="fw-medium">{{ $client->name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if($client->email)
                                                    <a href="mailto:{{ $client->email }}" class="text-decoration-none">
                                                        {{ $client->email }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($client->phone)
                                                    <a href="tel:{{ $client->phone }}" class="text-decoration-none">
                                                        {{ $client->phone }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                      title="{{ $client->address ?? 'N/A' }}">
                                                    {{ $client->address ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('client.show', $client->id) }}" 
                                                       class="btn btn-sm btn-outline-primary" 
                                                       title="Ver detalhes">
                                                        <i class="mdi mdi-eye"></i>
                                                    </a>
                                                    @can('edit_clients')
                                                    <button class="btn btn-sm btn-outline-warning" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editClientModal-{{ $client->id }}"
                                                            title="Editar">
                                                        <i class="mdi mdi-pencil"></i>
                                                    </button>
                                                    @endcan
                                                    @can('delete_clients')
                                                    <form action="{{ route('client.destroy', $client->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('Tem certeza que deseja excluir este cliente?')"
                                                                title="Excluir">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        @if($clients->hasPages())
                            <div class="card-footer border-top">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Mostrando {{ $clients->firstItem() }} a {{ $clients->lastItem() }} 
                                        de {{ $clients->total() }} registros
                                    </div>
                                    {{ $clients->links() }}
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="mdi mdi-account-off display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum cliente encontrado</h5>
                            <p class="text-muted mb-4">Comece adicionando seu primeiro cliente</p>
                            @can('create_clients')
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClientModal">
                                <i class="mdi mdi-plus-circle-outline me-1"></i>Adicionar Cliente
                            </button>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Adicionar Cliente -->
@can('create_clients')
<div class="modal fade" id="addClientModal" tabindex="-1" aria-labelledby="addClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-plus-circle-outline me-2"></i>Adicionar Novo Cliente
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('client.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Nome Completo *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-semibold">Telefone *</label>
                            <input type="text" class="form-control" id="phone" name="phone" 
                                   value="{{ old('phone') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label fw-semibold">Endereço</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="{{ old('address') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-1"></i>Salvar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan

<!-- Modais de Edição -->
@foreach($clients as $client)
@can('edit_clients')
<div class="modal fade" id="editClientModal-{{ $client->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="mdi mdi-pencil me-2"></i>Editar Cliente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('client.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_name_{{ $client->id }}" class="form-label fw-semibold">Nome Completo *</label>
                            <input type="text" class="form-control" id="edit_name_{{ $client->id }}" 
                                   name="name" value="{{ old('name', $client->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_email_{{ $client->id }}" class="form-label fw-semibold">Email *</label>
                            <input type="email" class="form-control" id="edit_email_{{ $client->id }}" 
                                   name="email" value="{{ old('email', $client->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_phone_{{ $client->id }}" class="form-label fw-semibold">Telefone *</label>
                            <input type="text" class="form-control" id="edit_phone_{{ $client->id }}" 
                                   name="phone" value="{{ old('phone', $client->phone) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_address_{{ $client->id }}" class="form-label fw-semibold">Endereço</label>
                            <input type="text" class="form-control" id="edit_address_{{ $client->id }}" 
                                   name="address" value="{{ old('address', $client->address) }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="mdi mdi-content-save me-1"></i>Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endforeach
@endsection