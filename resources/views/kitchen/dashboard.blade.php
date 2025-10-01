@extends('layouts.app')

@section('title', 'Cozinha')
@section('page-title', 'Dashboard da Cozinha')
@section('title-icon', 'mdi-chef-hat')

@section('breadcrumbs')
    <li class="breadcrumb-item active">Cozinha</li>
@endsection

@push('styles')
    <style>
        .kitchen-dashboard {
            background: linear-gradient(135deg, #f8fafc 0%, #e0f2fe 100%);
            min-height: calc(100vh - 140px);
        }

        .order-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #06b6d4);
        }

        .order-card.urgent::before {
            background: linear-gradient(90deg, #ef4444, #f59e0b);
            animation: pulse 2s infinite;
        }

        .order-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .order-header {
            background: linear-gradient(135deg, #f8fafc, #e0f7fa);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-time {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .order-time.urgent {
            color: #dc2626;
            font-weight: 600;
            animation: blink 1s infinite;
        }

        .item-card {
            background: white;
            border-radius: 10px;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border-left: 4px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .item-card.pending {
            border-left-color: #fbbf24;
            background: #fffbeb;
        }

        .item-card.preparing {
            border-left-color: #3b82f6;
            background: #eff6ff;
        }

        .item-card.ready {
            border-left-color: #10b981;
            background: #ecfdf5;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        .status-preparing {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .status-ready {
            background: #d1fae5;
            color: #065f46;
        }

        .quick-action-btn {
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .quick-action-btn:hover {
            transform: translateY(-1px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }

        .no-orders {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-wrapper card" style="padding: 20px">
        <!-- Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                    <i class="mdi mdi-receipt-text"></i>
                </div>
                <h3 class="mb-1">{{ $stats['active_orders'] }}</h3>
                <p class="text-muted mb-0">Pedidos Ativos</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <i class="mdi mdi-clock-outline"></i>
                </div>
                <h3 class="mb-1">{{ $stats['pending_items'] }}</h3>
                <p class="text-muted mb-0">Itens Pendentes</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1e40af);">
                    <i class="mdi mdi-fire"></i>
                </div>
                <h3 class="mb-1">{{ $stats['preparing_items'] }}</h3>
                <p class="text-muted mb-0">Em Preparo</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="mdi mdi-check-circle"></i>
                </div>
                <h3 class="mb-1">{{ $stats['ready_items'] }}</h3>
                <p class="text-muted mb-0">Prontos</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <i class="mdi mdi-timer-outline"></i>
                </div>
                <h3 class="mb-1">{{ $avgPrepTime }}min</h3>
                <p class="text-muted mb-0">Tempo Médio</p>
            </div>
        </div>

        <!-- Controles -->
        <div class="row g-2 mb-3">
            <!-- Grupo da esquerda -->
            <div class="col-12 col-sm-8 d-flex flex-wrap gap-2">
                <button class="btn btn-outline-primary flex-fill flex-sm-grow-0" id="refreshOrders">
                    <i class="mdi mdi-refresh"></i> Atualizar
                </button>
                <a href="{{ route('kitchen.by-category') }}" class="btn btn-outline-secondary flex-fill flex-sm-grow-0">
                    <i class="mdi mdi-format-list-bulleted"></i> Por Categoria
                </a>
                <a href="{{ route('kitchen.history') }}" class="btn btn-outline-info flex-fill flex-sm-grow-0">
                    <i class="mdi mdi-history"></i> Histórico
                </a>
                <button class="btn btn-outline-secondary flex-fill flex-sm-grow-0" data-bs-toggle="modal"
                    data-bs-target="#settingsModal">
                    <i class="mdi mdi-cog"></i> Configurações
                </button>
            </div>

            <!-- Grupo da direita -->
            <div class="col-12 col-sm-4 d-flex justify-content-sm-end">
                <button class="btn btn-success w-100 w-sm-auto" id="markAllReady"
                    {{ $stats['preparing_items'] == 0 ? 'disabled' : '' }}>
                    <i class="mdi mdi-check-all"></i> Finalizar Todos
                </button>
            </div>
        </div>


        <!-- Pedidos Ativos -->
        <div class="row" id="activeOrders">
            @forelse($activeOrders as $order)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="order-card {{ $order->created_at->diffInMinutes(now()) > 30 ? 'urgent' : '' }}">
                        <div class="order-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="mdi mdi-table-chair me-1"></i>
                                        {{ $order->table ? "Mesa {$order->table->number}" : 'Balcão' }}
                                    </h6>
                                    @if ($order->customer_name)
                                        <small class="text-muted">{{ $order->customer_name }}</small>
                                    @endif
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">{{ $order->id }}</span>
                                    <div
                                        class="order-time {{ $order->created_at->diffInMinutes(now()) > 30 ? 'urgent' : '' }}">
                                        {{ $order->created_at->diffForHumans() }}
                                        <br><small>({{ $order->created_at->diffInMinutes(now()) }}min)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @foreach ($order->items as $item)
                                <div class="item-card {{ $item->status }}" data-item-id="{{ $item->id }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <strong>{{ $item->quantity }}x {{ $item->product->name }}</strong>
                                            @if ($item->notes)
                                                <br><small class="text-muted"><em>{{ $item->notes }}</em></small>
                                            @endif
                                            <div class="mt-1">
                                                <span class="status-badge status-{{ $item->status }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown">
                                                    <i class="mdi mdi-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    @if ($item->status == 'pending')
                                                        <li>
                                                            <button class="dropdown-item update-status"
                                                                data-item-id="{{ $item->id }}" data-status="preparing">
                                                                <i class="mdi mdi-play text-primary me-2"></i>Iniciar
                                                                Preparo
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item update-status"
                                                                data-item-id="{{ $item->id }}" data-status="ready">
                                                                <i class="mdi mdi-check text-success me-2"></i>Marcar Pronto
                                                            </button>
                                                        </li>
                                                    @elseif($item->status == 'preparing')
                                                        <li>
                                                            <button class="dropdown-item update-status"
                                                                data-item-id="{{ $item->id }}" data-status="ready">
                                                                <i class="mdi mdi-check text-success me-2"></i>Marcar Pronto
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="dropdown-item update-status"
                                                                data-item-id="{{ $item->id }}" data-status="pending">
                                                                <i class="mdi mdi-arrow-left text-warning me-2"></i>Voltar
                                                                para Pendente
                                                            </button>
                                                        </li>
                                                    @elseif($item->status == 'ready')
                                                        <li>
                                                            <button class="dropdown-item update-status"
                                                                data-item-id="{{ $item->id }}" data-status="preparing">
                                                                <i class="mdi mdi-arrow-left text-primary me-2"></i>Voltar
                                                                para Preparo
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Ações do Pedido -->
                            <div class="mt-3 d-flex gap-2">
                                @php
                                    $hasPending = $order->items->where('status', 'pending')->count() > 0;
                                    $hasPreparing = $order->items->where('status', 'preparing')->count() > 0;
                                @endphp

                                @if ($hasPending)
                                    <button class="quick-action-btn btn btn-warning btn-sm start-all"
                                        data-order-id="{{ $order->id }}">
                                        <i class="mdi mdi-play"></i> Iniciar Todos
                                    </button>
                                @endif

                                @if ($hasPending || $hasPreparing)
                                    <button class="quick-action-btn btn btn-success btn-sm finish-all"
                                        data-order-id="{{ $order->id }}">
                                        <i class="mdi mdi-check-all"></i> Finalizar Todos
                                    </button>
                                @endif

                                <a href="{{ route('kitchen.order.show', $order) }}"
                                    class="quick-action-btn btn btn-outline-info btn-sm">
                                    <i class="mdi mdi-eye"></i> Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-orders">
                        <i class="mdi mdi-chef-hat" style="font-size: 4rem; color: #d1d5db;"></i>
                        <h4 class="mt-3 mb-2">Nenhum pedido ativo</h4>
                        <p>A cozinha está livre! Todos os pedidos foram processados.</p>
                        <a href="{{ route('orders.index') }}" class="btn btn-primary">
                            <i class="mdi mdi-receipt"></i> Ver Todos os Pedidos
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal de Configurações -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="mdi mdi-cog me-2"></i>Configurações da Cozinha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sons de Notificação</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="soundsEnabled" checked>
                            <label class="form-check-label" for="soundsEnabled">
                                Ativar sons
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="soundVolume" class="form-label">Volume</label>
                        <input type="range" class="form-range" id="soundVolume" min="0" max="1"
                            step="0.1" value="0.5">
                        <small class="text-muted">Volume: <span id="volumeValue">50%</span></small>
                    </div>

                    <div class="mb-3">
                        <label for="refreshInterval" class="form-label">Atualização Automática</label>
                        <select class="form-select" id="refreshInterval">
                            <option value="15000">15 segundos</option>
                            <option value="30000" selected>30 segundos</option>
                            <option value="60000">1 minuto</option>
                            <option value="0">Desativado</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveSettings()">Salvar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /**
         * Sistema de Notificações da Cozinha - Versão Inline
         * TODO: Mover para arquivo separado public/assets/js/kitchen-notification-system.js
         */
        class KitchenNotificationSystem {
            constructor() {
                this.sounds = {
                    success: '/assets/sounds/success.mp3',
                    warning: '/assets/sounds/warning.mp3',
                    error: '/assets/sounds/error.mp3',
                    newOrder: '/assets/sounds/new-order.mp3',
                    urgent: '/assets/sounds/urgent.mp3'
                };

                this.updateInProgress = false;
                this.retryAttempts = 0;
                this.maxRetries = 3;

                this.loadPreferences();
                this.init();
            }

            init() {
                this.setupEventListeners();
                this.checkForSounds();
                this.startAutoRefresh();
            }

            loadPreferences() {
                const prefs = localStorage.getItem('kitchen_preferences');
                if (prefs) {
                    const parsed = JSON.parse(prefs);
                    this.soundsEnabled = parsed.soundsEnabled ?? true;
                    this.soundVolume = parsed.soundVolume ?? 0.5;
                    this.autoRefreshInterval = parsed.autoRefreshInterval ?? 30000;
                } else {
                    this.soundsEnabled = true;
                    this.soundVolume = 0.5;
                    this.autoRefreshInterval = 30000;
                }
            }

            savePreferences() {
                localStorage.setItem('kitchen_preferences', JSON.stringify({
                    soundsEnabled: this.soundsEnabled,
                    soundVolume: this.soundVolume,
                    autoRefreshInterval: this.autoRefreshInterval
                }));
            }

            setupEventListeners() {
                document.querySelectorAll('.update-status').forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        const itemId = button.dataset.itemId;
                        const status = button.dataset.status;
                        this.updateItemStatus(itemId, status, button);
                    });
                });

                document.querySelectorAll('.start-all').forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        const orderId = button.dataset.orderId;
                        this.startAllItems(orderId, button);
                    });
                });

                document.querySelectorAll('.finish-all').forEach(button => {
                    button.addEventListener('click', (e) => {
                        e.preventDefault();
                        const orderId = button.dataset.orderId;
                        this.finishAllItems(orderId, button);
                    });
                });

                document.getElementById('refreshOrders')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.refreshOrders(true);
                });

                document.getElementById('markAllReady')?.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.markAllReady();
                });
            }

            async updateItemStatus(itemId, status, button = null) {
                if (this.updateInProgress) {
                    this.showToast('Aguarde a operação anterior concluir', 'warning');
                    return;
                }

                if (button) {
                    button.disabled = true;
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Processando...';
                    button.dataset.originalHtml = originalHTML;
                }

                this.updateInProgress = true;

                try {
                    const response = await fetch(`/kitchen/items/${itemId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.getCsrfToken(),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            status
                        })
                    });

                    // Verificar se a resposta é JSON
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Resposta inválida do servidor. Esperado JSON, recebido: ' + contentType);
                    }

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.showToast(data.message || 'Status atualizado com sucesso', 'success');
                        this.playSound('success');
                        this.retryAttempts = 0; // Resetar contador de tentativas

                        // Aguardar 500ms antes de recarregar para dar feedback visual
                        setTimeout(() => {
                            this.refreshOrders(false);
                        }, 500);
                    } else {
                        throw new Error(data.message || 'Erro ao atualizar status');
                    }

                } catch (error) {
                    console.error('Erro ao atualizar status:', error);
                    this.handleError(error, () => this.updateItemStatus(itemId, status, button));
                } finally {
                    this.updateInProgress = false;

                    if (button) {
                        button.disabled = false;
                        button.innerHTML = button.dataset.originalHtml || 'Atualizar';
                    }
                }
            }

            async startAllItems(orderId, button = null) {
                if (this.updateInProgress) {
                    this.showToast('Aguarde a operação anterior concluir', 'warning');
                    return;
                }

                const confirmed = confirm('Iniciar preparo de todos os itens pendentes?');
                if (!confirmed) return;

                if (button) {
                    button.disabled = true;
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Iniciando...';
                    button.dataset.originalHtml = originalHTML;
                }

                this.updateInProgress = true;

                try {
                    const response = await fetch(`/kitchen/orders/${orderId}/start-all`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.getCsrfToken(),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Resposta inválida do servidor');
                    }

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.showToast(data.message || 'Itens iniciados com sucesso', 'success');
                        this.playSound('success');
                        this.retryAttempts = 0;

                        setTimeout(() => {
                            this.refreshOrders(false);
                        }, 500);
                    } else {
                        throw new Error(data.message || 'Erro ao iniciar itens');
                    }

                } catch (error) {
                    console.error('Erro ao iniciar itens:', error);
                    this.handleError(error, () => this.startAllItems(orderId, button));
                } finally {
                    this.updateInProgress = false;

                    if (button) {
                        button.disabled = false;
                        button.innerHTML = button.dataset.originalHtml || '<i class="mdi mdi-play"></i> Iniciar Todos';
                    }
                }
            }

            async finishAllItems(orderId, button = null) {
                if (this.updateInProgress) {
                    this.showToast('Aguarde a operação anterior concluir', 'warning');
                    return;
                }

                const confirmed = confirm('Finalizar todos os itens (pendentes e em preparo)?');
                if (!confirmed) return;

                if (button) {
                    button.disabled = true;
                    const originalHTML = button.innerHTML;
                    button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Finalizando...';
                    button.dataset.originalHtml = originalHTML;
                }

                this.updateInProgress = true;

                try {
                    const response = await fetch(`/kitchen/orders/${orderId}/finish-all`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.getCsrfToken(),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Resposta inválida do servidor');
                    }

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.showToast(data.message || 'Itens finalizados com sucesso', 'success');
                        this.playSound('success');
                        this.retryAttempts = 0;

                        setTimeout(() => {
                            this.refreshOrders(false);
                        }, 500);
                    } else {
                        throw new Error(data.message || 'Erro ao finalizar itens');
                    }

                } catch (error) {
                    console.error('Erro ao finalizar itens:', error);
                    this.handleError(error, () => this.finishAllItems(orderId, button));
                } finally {
                    this.updateInProgress = false;

                    if (button) {
                        button.disabled = false;
                        button.innerHTML = button.dataset.originalHtml ||
                            '<i class="mdi mdi-check-all"></i> Finalizar Todos';
                    }
                }
            }

            async markAllReady() {
                const preparingCount = document.querySelectorAll('.item-card.preparing').length;

                if (preparingCount === 0) {
                    this.showToast('Nenhum item em preparo para finalizar', 'info');
                    return;
                }

                const confirmed = confirm(`Finalizar ${preparingCount} itens em preparo?`);
                if (!confirmed) return;

                const finishButtons = document.querySelectorAll('.finish-all');

                for (const button of finishButtons) {
                    const orderId = button.dataset.orderId;
                    await this.finishAllItems(orderId, null);
                }
            }

            async refreshOrders(showMessage = true) {
                try {
                    if (showMessage) {
                        this.showToast('Atualizando pedidos...', 'info');
                    }
                    window.location.reload();
                } catch (error) {
                    console.error('Erro ao atualizar pedidos:', error);
                    this.showToast('Erro ao atualizar pedidos', 'error');
                }
            }

            handleError(error, retryCallback) {
                this.retryAttempts++;

                if (this.retryAttempts < this.maxRetries) {
                    this.showToast(
                        `Tentando novamente... (${this.retryAttempts}/${this.maxRetries})`,
                        'warning'
                    );

                    setTimeout(() => {
                        if (retryCallback && typeof retryCallback === 'function') {
                            retryCallback();
                        }
                    }, 1000 * this.retryAttempts);
                } else {
                    // Resetar tudo após falha máxima
                    this.retryAttempts = 0;
                    this.updateInProgress = false;

                    this.showToast(
                        'Não foi possível completar a operação. Verifique a conexão e tente novamente.',
                        'error'
                    );
                    this.playSound('error');

                    // Recarregar página após 2 segundos para sincronizar
                    setTimeout(() => {
                        this.refreshOrders(false);
                    }, 2000);
                }
            }

            showToast(message, type = 'success') {
                if (typeof window.showToast === 'function') {
                    window.showToast(message, type);
                } else {
                    console.log(`[${type.toUpperCase()}] ${message}`);
                }
            }

            playSound(soundType) {
                if (!this.soundsEnabled) return;

                try {
                    const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                    const oscillator = audioContext.createOscillator();
                    const gainNode = audioContext.createGain();

                    oscillator.connect(gainNode);
                    gainNode.connect(audioContext.destination);

                    const soundConfig = {
                        success: {
                            frequency: 800,
                            duration: 0.15
                        },
                        warning: {
                            frequency: 600,
                            duration: 0.2
                        },
                        error: {
                            frequency: 400,
                            duration: 0.3
                        },
                        newOrder: {
                            frequency: 1000,
                            duration: 0.1
                        },
                        urgent: {
                            frequency: 500,
                            duration: 0.5
                        }
                    };

                    const config = soundConfig[soundType] || soundConfig.success;

                    oscillator.frequency.value = config.frequency;
                    oscillator.type = 'sine';

                    gainNode.gain.setValueAtTime(this.soundVolume * 0.3, audioContext.currentTime);
                    gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + config.duration);

                    oscillator.start(audioContext.currentTime);
                    oscillator.stop(audioContext.currentTime + config.duration);
                } catch (error) {
                    console.warn('Erro ao reproduzir som:', error);
                }
            }

            checkForSounds() {
                this.soundsEnabled = true;
            }

            startAutoRefresh() {
                if (this.refreshInterval) {
                    clearInterval(this.refreshInterval);
                }

                if (this.autoRefreshInterval > 0) {
                    this.refreshInterval = setInterval(() => {
                        if (!this.updateInProgress) {
                            this.refreshOrders(false);
                        }
                    }, this.autoRefreshInterval);
                }
            }

            getCsrfToken() {
                const token = document.querySelector('meta[name="csrf-token"]');
                if (!token) {
                    throw new Error('Token de segurança não encontrado');
                }
                return token.content;
            }
        }

        // Inicializar sistema
        document.addEventListener('DOMContentLoaded', function() {
            window.kitchenSystem = new KitchenNotificationSystem();
            console.log('Sistema da Cozinha inicializado');
        });

        // Função para salvar configurações
        function saveSettings() {
            if (window.kitchenSystem) {
                window.kitchenSystem.soundsEnabled = document.getElementById('soundsEnabled').checked;
                window.kitchenSystem.soundVolume = parseFloat(document.getElementById('soundVolume').value);
                window.kitchenSystem.autoRefreshInterval = parseInt(document.getElementById('refreshInterval').value);
                window.kitchenSystem.savePreferences();

                bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
                showToast('Configurações salvas com sucesso', 'success');

                // Reiniciar auto-refresh se necessário
                if (window.kitchenSystem.autoRefreshInterval > 0) {
                    window.kitchenSystem.startAutoRefresh();
                }
            }
        }

        // Atualizar display do volume
        document.getElementById('soundVolume')?.addEventListener('input', function() {
            document.getElementById('volumeValue').textContent = Math.round(this.value * 100) + '%';
        });

        // Carregar configurações ao abrir modal
        document.getElementById('settingsModal')?.addEventListener('show.bs.modal', function() {
            if (window.kitchenSystem) {
                document.getElementById('soundsEnabled').checked = window.kitchenSystem.soundsEnabled;
                document.getElementById('soundVolume').value = window.kitchenSystem.soundVolume;
                document.getElementById('volumeValue').textContent = Math.round(window.kitchenSystem.soundVolume *
                    100) + '%';
                document.getElementById('refreshInterval').value = window.kitchenSystem.autoRefreshInterval;
            }
        });

        // Inicializar o sistema quando o DOM carregar
        document.addEventListener('DOMContentLoaded', function() {
            // O sistema será inicializado automaticamente pelo kitchen-notification-system.js
            console.log('Dashboard da Cozinha carregado');
        });
    </script>
@endpush
