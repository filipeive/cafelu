@extends('layouts.app')

@section('content')
    <div class="container-wrapper">
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title mb-0">Gestão de Usuários</h4>
                                <small class="text-muted">Gerencie todos os usuários do sistema</small>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#createUserModal">
                                <i class="mdi mdi-account-plus me-1"></i>
                                Novo Usuário
                            </button>
                        </div>

                        <!-- Barra de Pesquisa -->
                        <div class="row mb-4">
                            <div class="col-lg-4">
                                <form action="{{ route('users.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Buscar usuários..." value="{{ $search ?? '' }}">
                                        <button class="btn btn-outline-primary" type="submit">
                                            <i class="mdi mdi-magnify"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Alertas de Feedback -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-circle-outline me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Tabela de Usuários -->
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Usuário</th>
                                        <th>Função</th>
                                        <th>Status</th>
                                        <th>Último Acesso</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3">
                                                        <div
                                                            class="avatar-title rounded-circle bg-primary-light text-primary">
                                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ get_role_class($user->role) }}">
                                                    {{ ucfirst($user->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($user->email_verified_at)
                                                    <span class="badge bg-success">Ativo</span>
                                                @else
                                                    <span class="badge bg-warning">Pendente</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca acessou' }}
                                                </small>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-outline-info btn-icon btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#viewUserModal{{ $user->id }}" title="Detalhes">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>

                                                <button type="button" class="btn btn-outline-warning btn-icon btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editUserModal{{ $user->id }}" title="Editar">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                @if ($loggedId !== intval($user->id))
                                                    <form class="d-inline" action="{{ route('users.destroy', $user->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            title="Excluir">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="mdi mdi-account-multiple-remove text-muted"
                                                    style="font-size: 3rem;"></i>
                                                <p class="text-muted mt-2">Nenhum usuário encontrado</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <p class="text-muted mb-0">
                                Mostrando {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                                de {{ $users->total() }} usuários
                            </p>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Criação -->
    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Usuário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nome Completo</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nome de Usuário</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Senha</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Função</label>
                            <select name="role" class="form-select" required>
                                <option value="">Selecione uma função</option>
                                <option value="admin">Administrador</option>
                                <option value="manager">Gerente</option>
                                <option value="waiter">Garçom</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-account-plus me-1"></i>
                            Criar Usuário
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal de Edição -->
    @foreach ($users as $user)
        <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Usuário</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nome Completo</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nome de Usuário</label>
                                <input type="text" name="username" class="form-control"
                                    value="{{ $user->username }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Função</label>
                                <select name="role" class="form-select" required>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador
                                    </option>
                                    <option value="manager" {{ $user->role == 'manager' ? 'selected' : '' }}>Gerente
                                    </option>
                                    <option value="waiter" {{ $user->role == 'waiter' ? 'selected' : '' }}>Garçom</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save me-1"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        function confirmDelete(userId) {
            Swal.fire({
                title: 'Tem certeza?',
                text: "Esta ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + userId).submit();
                }
            });
        }

        // Inicializa tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .avatar {
            width: 32px;
            height: 32px;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
        }

        .bg-primary-light {
            background-color: rgba(0, 123, 255, 0.1) !important;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            margin: 0 2px;
        }

        .btn-icon i {
            font-size: 1rem;
        }
    </style>
@endpush
