@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="page-title">Detalhes do Usuário</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar avatar-xl me-3">
                            <span class="avatar-initial rounded-circle bg-primary">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted mb-1">Email</label>
                                <p class="font-weight-medium">{{ $user->email ?? '—' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Username</label>
                                <p class="font-weight-medium">{{ $user->username }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Função</label>
                                <p class="font-weight-medium">{{ ucfirst($user->role) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="text-muted mb-1">Último Acesso</label>
                                <p class="font-weight-medium">
                                    <i class="mdi mdi-clock-outline me-1"></i>
                                    {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->format('d/m/Y H:i') : 'Nunca acessou' }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Criado em</label>
                                <p class="font-weight-medium">
                                    <i class="mdi mdi-calendar me-1"></i>
                                    {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '—' }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted mb-1">Última atualização</label>
                                <p class="font-weight-medium">
                                    <i class="mdi mdi-update me-1"></i>
                                    {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('users.index') }}" class="btn btn-light me-2">
                            <i class="mdi mdi-arrow-left me-1"></i> Voltar
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil me-1"></i> Editar
                        </a>
                    </div>
                </div>
            </div>
    </div>
</div>
@endsection