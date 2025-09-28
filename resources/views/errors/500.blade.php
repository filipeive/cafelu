@extends('layouts.app')

@section('title', '500 - Erro Interno')
@section('title-icon', 'mdi-server-off')
@section('page-title', 'Erro Interno do Servidor')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ url('/') }}" class="text-decoration-none">
            <i class="mdi mdi-home"></i> Início
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Erro 500</li>
@endsection

@section('content')
<div class="container-wrapper py-5">
    <div class="row justify-content-center">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="error-icon mb-4">
                        <i class="mdi mdi-server-off display-1 text-danger"></i>
                    </div>
                    
                    <h1 class="display-4 fw-bold text-danger mb-3">500</h1>
                    <h3 class="mb-3">Erro Interno do Servidor</h3>
                    
                    <div class="alert alert-info mb-4">
                        <i class="mdi mdi-information-outline me-2"></i>
                        <strong>Estamos cientes do problema!</strong> Nossa equipe já foi notificada automaticamente.
                    </div>
                    
                    <!-- Opções de Ação -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <i class="mdi mdi-refresh display-4 text-primary mb-3"></i>
                                    <h5>Tentar Recarregar</h5>
                                    <p class="text-muted small">Às vezes o erro é temporário</p>
                                    <button onclick="window.location.reload()" class="btn btn-outline-primary w-100">
                                        <i class="mdi mdi-refresh me-2"></i>Recarregar Página
                                    </button>
                                </div>
                            </div>
                        </div>

                        @auth
                        @if(auth()->user()->role === 'admin')
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <i class="mdi mdi-cloud-download display-4 text-success mb-3"></i>
                                    <h5>Verificar Atualizações</h5>
                                    <p class="text-muted small">Buscar correções automáticas</p>
                                    <button onclick="checkForUpdates()" class="btn btn-outline-success w-100" id="updateBtn">
                                        <i class="mdi mdi-cloud-download me-2"></i>Verificar Atualizações
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @endauth
                    <!-- Informações Técnicas (Expandível) -->
                    <div class="mb-4">
                        <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#techDetails">
                            <i class="mdi mdi-bug-outline me-2"></i>Detalhes Técnicos
                        </button>
                        
                        <div class="collapse mt-3" id="techDetails">
                            <div class="card card-body text-start">
                                <h6 class="mb-3">Informações do Erro:</h6>
                                
                                @if(app()->environment('local') && isset($exception))
                                    <div class="mb-2">
                                        <strong>Mensagem:</strong> 
                                        <code class="text-danger">{{ $exception->getMessage() }}</code>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Arquivo:</strong> 
                                        <code>{{ $exception->getFile() }}:{{ $exception->getLine() }}</code>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Data/Hora:</strong> 
                                        <span>{{ now()->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                @else
                                    <div class="mb-2">
                                        <strong>ID do Erro:</strong> 
                                        <code>ERR-{{ now()->timestamp }}</code>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Data/Hora:</strong> 
                                        <span>{{ now()->format('d/m/Y H:i:s') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Status:</strong> 
                                        <span class="badge bg-warning">Reportado à equipe</span>
                                    </div>
                                @endif
                                
                                <!-- Botão de Copiar Detalhes -->
                                <button onclick="copyErrorDetails()" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="mdi mdi-content-copy me-1"></i>Copiar Detalhes
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Ações de Contato -->
                    <div class="border-top pt-4">
                        <h6 class="mb-3">Precisa de ajuda imediata?</h6>
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">
                                <i class="mdi mdi-home me-2"></i>Voltar ao Início
                            </a>
                            <button onclick="showContactOptions()" class="btn btn-outline-warning">
                                <i class="mdi mdi-headset me-2"></i>Suporte Técnico
                            </button>
                            <a href="mailto:jvquelimane@gmail.com?subject=Erro 500 - Sistema&body=Encontrei um erro no sistema. ID do erro: ERR-{{ now()->timestamp }}" 
                            class="btn btn-outline-info">
                                <i class="mdi mdi-email me-2"></i>Enviar Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Modal de Atualizações -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-cloud-download me-2"></i>Verificar Atualizações
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="updateProgress">
                    <div class="text-center mb-3">
                        <div class="spinner-border text-success mb-3" role="status">
                            <span class="visually-hidden">Carregando...</span>
                        </div>
                        <p>Verificando por atualizações disponíveis...</p>
                    </div>
                    <div class="progress mb-3" style="height: 6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             style="width: 100%"></div>
                    </div>
                </div>
                <div id="updateResult" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Contato -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="mdi mdi-headset me-2"></i>Suporte Técnico
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <h6>Entre em contato diretamente com o desenvolvedor:</h6>
                </div>
                <div class="list-group">
                    <a href="https://wa.me/248847240296?text=Encontrei um erro no sistema (ID: ERR-{{ now()->timestamp }})" target="_blank" class="list-group-item list-group-item-action">
                        <i class="mdi mdi-whatsapp text-success me-2"></i>
                        <strong>WhatsApp:</strong> +248 84 724 0296
                    </a>
                    <a href="https://wa.me/258862134230?text=Encontrei um erro no sistema (ID: ERR-{{ now()->timestamp }})" target="_blank" class="list-group-item list-group-item-action">
                        <i class="mdi mdi-whatsapp text-success me-2"></i>
                        <strong>WhatsApp:</strong> +258 86 213 4230
                    </a>
                    <a href="mailto:jvquelimane@gmail.com?subject=Erro 500 - Sistema&body=Encontrei um erro no sistema. ID do erro: ERR-{{ now()->timestamp }}" class="list-group-item list-group-item-action">
                        <i class="mdi mdi-email text-primary me-2"></i>
                        <strong>Email:</strong> jvquelimane@gmail.com
                    </a>
                </div>
                <div class="mt-3 p-3 bg-light rounded">
                    <small class="text-muted">
                        <i class="mdi mdi-information-outline me-1"></i>
                        Ao entrar em contato, mencione o ID do erro: <strong>ERR-{{ now()->timestamp }}</strong>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Verificar conexão com a internet
function hasInternetConnection() {
    return new Promise((resolve) => {
        fetch("https://www.google.com/favicon.ico", { mode: "no-cors" })
            .then(() => resolve(true))
            .catch(() => resolve(false));
    });
}

// Verificar atualizações (com backend)
async function checkForUpdates() {
    const updateBtn = document.getElementById('updateBtn');
    const updateModal = new bootstrap.Modal(document.getElementById('updateModal'));
    updateModal.show();
    updateBtn.disabled = true;
    updateBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Verificando...';

    const updateResult = document.getElementById('updateResult');
    const updateProgress = document.getElementById('updateProgress');
    updateProgress.style.display = 'block';
    updateResult.style.display = 'none';

    // Verificar internet antes
    const online = await hasInternetConnection();
    if (!online) {
        updateProgress.style.display = 'none';
        updateResult.style.display = 'block';
        updateResult.innerHTML = `
            <div class="alert alert-danger">
                <i class="mdi mdi-wifi-off me-2"></i>
                <strong>Sem conexão com a Internet</strong>
                <p class="mb-0 mt-2">Conecte-se à internet e tente novamente.</p>
            </div>
        `;
        updateBtn.disabled = false;
        updateBtn.innerHTML = '<i class="mdi mdi-cloud-download me-2"></i>Verificar Atualizações';
        return;
    }

    // Requisitar atualização ao backend
    try {
        const response = await fetch("{{ route('system.update') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        });

        const data = await response.json();
        updateProgress.style.display = 'none';
        updateResult.style.display = 'block';

        if (data.success) {
            updateResult.innerHTML = `
                <div class="alert alert-success">
                    <i class="mdi mdi-check-circle me-2"></i>
                    <strong>${data.message}</strong>
                </div>
                <pre class="bg-light p-2 small text-start">${JSON.stringify(data.details, null, 2)}</pre>
                <button onclick="location.reload()" class="btn btn-success w-100">
                    <i class="mdi mdi-refresh me-2"></i>Reiniciar Sistema
                </button>
            `;
        } else {
            updateResult.innerHTML = `
                <div class="alert alert-danger">
                    <i class="mdi mdi-alert me-2"></i>
                    <strong>Erro ao atualizar:</strong>
                    <p class="mb-0 mt-2">${data.message || 'Erro desconhecido.'}</p>
                </div>
            `;
        }
    } catch (err) {
        updateProgress.style.display = 'none';
        updateResult.style.display = 'block';
        updateResult.innerHTML = `
            <div class="alert alert-danger">
                <i class="mdi mdi-alert me-2"></i>
                <strong>Erro na requisição</strong>
                <p class="mb-0 mt-2">${err.message}</p>
            </div>
        `;
    } finally {
        updateBtn.disabled = false;
        updateBtn.innerHTML = '<i class="mdi mdi-cloud-download me-2"></i>Verificar Atualizações';
    }
}

// Aplicar atualizações (simulação)
function applyUpdates() {
    const updateResult = document.getElementById('updateResult');
    updateResult.innerHTML = `
        <div class="text-center">
            <div class="spinner-border text-success mb-3" role="status"></div>
            <p>Aplicando atualizações... Não feche esta página.</p>
            <div class="progress mb-3" style="height: 6px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     style="width: 100%"></div>
            </div>
        </div>
    `;
    
    // Simular processo de atualização
    setTimeout(() => {
        updateResult.innerHTML = `
            <div class="alert alert-success">
                <i class="mdi mdi-check-all me-2"></i>
                <strong>Atualização concluída!</strong>
                <p class="mb-0 mt-2">O sistema foi atualizado com sucesso.</p>
            </div>
            <button onclick="location.reload()" class="btn btn-success w-100">
                <i class="mdi mdi-refresh me-2"></i>Reiniciar Sistema
            </button>
        `;
    }, 4000);
}

// Mostrar opções de contato
function showContactOptions() {
    const contactModal = new bootstrap.Modal(document.getElementById('contactModal'));
    contactModal.show();
}

// Copiar detalhes do erro
function copyErrorDetails() {
    const errorDetails = `
Erro: 500 - Erro Interno do Servidor
ID: ERR-${new Date().getTime()}
Data: ${new Date().toLocaleString('pt-PT')}
URL: ${window.location.href}
    `.trim();
    
    navigator.clipboard.writeText(errorDetails).then(() => {
        // Feedback visual
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="mdi mdi-check me-1"></i>Copiado!';
        btn.classList.remove('btn-outline-primary');
        btn.classList.add('btn-success');
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-primary');
        }, 2000);
    });
}

// Auto-tentar após 30 segundos
setTimeout(() => {
    const shouldRetry = confirm('Deseja tentar carregar a página novamente?');
    if (shouldRetry) {
        window.location.reload();
    }
}, 30000);
</script>
@endpush

@push('styles')
<style>
.error-icon {
    opacity: 0.8;
}
.card {
    border-radius: 12px;
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.progress {
    border-radius: 10px;
}
.list-group-item {
    border: none;
    border-left: 3px solid transparent;
}
.list-group-item:hover {
    border-left-color: #0d6efd;
}
</style>
@endpush