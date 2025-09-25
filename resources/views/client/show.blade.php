@extends('layouts.app')

@section('content')
<div class="container-wrapper">
    <div class="page-header">
        <h3 class="page-title"> Detalhes do Cliente </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $client->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="mdi mdi-account text-primary me-2"></i>Informações do Cliente</h5>
        </div>
        <div class="card-body">
            <p><strong>Nome:</strong> {{ $client->name }}</p>
            <p><strong>Email:</strong> {{ $client->email }}</p>
            <p><strong>Telefone:</strong> {{ $client->phone }}</p>
            <p><strong>Endereço:</strong> {{ $client->address ?? 'N/A' }}</p>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left"></i> Voltar
            </a>
            <a href="{{ route('client.edit', $client->id) }}" class="btn btn-warning">
                <i class="mdi mdi-pencil"></i> Editar
            </a>
        </div>
    </div>
</div>
@endsection
