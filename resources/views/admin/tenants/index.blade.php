@extends('layouts.app')

@section('title', 'Control Center SaaS - Gestão de Restaurantes')

@section('content')
<div class="space-y-6" x-data="{
    createModalOpen: false,
    selectedTenant: null,
    copiedKey: null,
    copyToClipboard(text, id) {
        navigator.clipboard.writeText(text);
        this.copiedKey = id;
        setTimeout(() => this.copiedKey = null, 2500);
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/10 border border-orange-500/20 text-orange-600 dark:text-orange-400 text-xs font-bold mb-1">
                <i class="mdi mdi-shield-crown-outline"></i>
                <span>Painel do Dono da Plataforma</span>
            </div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2.5">
                <i class="mdi mdi-store-cog-outline text-orange-500"></i>
                <span>Gestão Multi-Restaurantes (Tenants)</span>
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                Cadastre restaurantes clientes, controle planos, limites operacionais e acesse qualquer ambiente com 1 clique.
            </p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('admin.licenses.index') }}" 
               class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i class="mdi mdi-key-chain text-base text-gray-500"></i>
                <span>Gerador de Seriais</span>
            </a>
            <button type="button" @click="createModalOpen = true"
                    class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-xs font-bold shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2">
                <i class="mdi mdi-plus-circle text-base"></i>
                <span>Registar Restaurante</span>
            </button>
        </div>
    </div>

    <!-- Executive SaaS KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3.5">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Total Restaurantes</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-gray-900 dark:text-white">{{ $stats['total'] }}</span>
                <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">{{ $stats['active'] }} ativos</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Em Avaliação (Trial)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black {{ $stats['trial'] > 0 ? 'text-amber-500' : 'text-gray-900 dark:text-white' }}">{{ $stats['trial'] }}</span>
                <span class="text-[11px] font-bold text-amber-500">testes</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">MRR (Mensal Estimado)</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-xl font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ number_format($stats['mrr'], 0, ',', '.') }} <span class="text-xs">MT</span></span>
            </div>
            <span class="text-[9px] text-gray-400 font-mono mt-0.5">ARR: {{ number_format($stats['arr'], 0, ',', '.') }} MT</span>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Instalações Offline</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black text-sky-600 dark:text-sky-400">{{ $stats['offline'] }}</span>
                <span class="text-[11px] text-gray-400">terminais</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">A Expirar em 30d</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-2xl font-black {{ $stats['expiring'] > 0 ? 'text-rose-500' : 'text-gray-400' }}">{{ $stats['expiring'] }}</span>
                <span class="text-[11px] text-gray-400">renovações</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs flex flex-col justify-between">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Ecossistema</span>
            <div class="mt-2 flex items-baseline justify-between">
                <span class="text-lg font-black text-gray-900 dark:text-white">{{ $stats['total_tables'] }} <span class="text-xs text-gray-400 font-normal">mesas</span></span>
            </div>
            <span class="text-[9px] text-gray-400 font-mono mt-0.5">{{ $stats['total_users'] }} utilizadores</span>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 p-4 shadow-xs">
        <form method="GET" action="{{ route('admin.tenants.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <div class="relative flex-1">
                <i class="mdi mdi-magnify absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar por nome do restaurante, subdomínio, NUIT, email ou telefone..."
                       class="w-full pl-9 pr-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>

            <div class="flex items-center gap-2">
                <select name="status" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    <option value="">Todos os Estados</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="trial" {{ request('status') === 'trial' ? 'selected' : '' }}>Trial</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                </select>

                <select name="installation_mode" class="px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white text-xs focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    <option value="">Todos os Modos</option>
                    <option value="cloud" {{ request('installation_mode') === 'cloud' ? 'selected' : '' }}>Cloud (Nuvem)</option>
                    <option value="local_online" {{ request('installation_mode') === 'local_online' ? 'selected' : '' }}>Local com Sync</option>
                    <option value="offline" {{ request('installation_mode') === 'offline' ? 'selected' : '' }}>Offline Nativo</option>
                </select>

                <button type="submit" class="px-4 py-2 rounded-xl bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold transition flex items-center gap-1.5">
                    <i class="mdi mdi-filter-variant"></i>
                    <span>Filtrar</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Tenants Table -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/60 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-gray-50/75 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700/60 text-[10px] font-bold uppercase tracking-wider text-gray-400">
                        <th class="py-3.5 px-4">Restaurante / Empresa</th>
                        <th class="py-3.5 px-4">Plano & Vigência</th>
                        <th class="py-3.5 px-4">Modo & Limites</th>
                        <th class="py-3.5 px-4">Métricas</th>
                        <th class="py-3.5 px-4">Estado</th>
                        <th class="py-3.5 px-4 text-right">Ações de Gestão</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/50">
                    @forelse($tenants as $tenant)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/20 transition">
                        <!-- Restaurant Name & Details -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center font-black text-sm flex-shrink-0">
                                    <i class="mdi mdi-silverware-fork-knife"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.tenants.show', $tenant) }}" class="font-bold text-gray-900 dark:text-white hover:text-orange-500 transition">
                                            {{ $tenant->name }}
                                        </a>
                                        @if($tenant->id === 1)
                                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-blue-500/10 text-blue-600 dark:text-blue-400 uppercase">Matriz</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-gray-400 font-mono flex items-center gap-2 mt-0.5">
                                        <span>slug: {{ $tenant->slug }}</span>
                                        @if($tenant->nuit)
                                            <span>• NUIT: {{ $tenant->nuit }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Plan & Validity -->
                        <td class="py-3.5 px-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-white uppercase text-[11px]">
                                    {{ $tenant->plan }}
                                </span>
                                <span class="text-[11px] text-gray-400 mt-0.5">
                                    @if($tenant->license_expires_at)
                                        Até {{ $tenant->license_expires_at->format('d/m/Y') }}
                                        <span class="font-semibold text-emerald-600 dark:text-emerald-400">({{ $tenant->daysRemaining() }}d)</span>
                                    @else
                                        Vitalício
                                    @endif
                                </span>
                            </div>
                        </td>

                        <!-- Mode & Operational Limits -->
                        <td class="py-3.5 px-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="inline-flex items-center gap-1 font-bold text-gray-700 dark:text-gray-300 text-[11px]">
                                    @if($tenant->installation_mode === 'cloud')
                                        <i class="mdi mdi-cloud text-sky-500"></i> Cloud
                                    @elseif($tenant->installation_mode === 'local_online')
                                        <i class="mdi mdi-sync text-emerald-500"></i> Local + Sync
                                    @else
                                        <i class="mdi mdi-lan-disconnect text-amber-500"></i> Offline Local
                                    @endif
                                </span>
                                <span class="text-[10px] text-gray-400 font-mono">
                                    {{ $tenant->max_tables }} mesas • {{ $tenant->max_users }} operadores
                                </span>
                            </div>
                        </td>

                        <!-- Metrics -->
                        <td class="py-3.5 px-4">
                            <div class="flex items-center gap-3 text-gray-500 dark:text-gray-400">
                                <div title="Mesas Cadastradas" class="flex items-center gap-1">
                                    <i class="mdi mdi-table-furniture text-gray-400"></i>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $tenant->tables_count }}</span>
                                </div>
                                <div title="Produtos Cadastrados" class="flex items-center gap-1">
                                    <i class="mdi mdi-food-fork-drink text-gray-400"></i>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $tenant->products_count }}</span>
                                </div>
                                <div title="Total de Vendas" class="flex items-center gap-1">
                                    <i class="mdi mdi-point-of-sale text-gray-400"></i>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $tenant->sales_count }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3.5 px-4">
                            @if($tenant->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
                                    <i class="mdi mdi-check-circle text-xs"></i> Ativo
                                </span>
                            @elseif($tenant->status === 'trial')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400">
                                    <i class="mdi mdi-clock-outline text-xs"></i> Avaliação
                                </span>
                            @elseif($tenant->status === 'suspended')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400">
                                    <i class="mdi mdi-cancel text-xs"></i> Suspenso
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-4 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Impersonate (Entrar no Restaurante) -->
                                <form action="{{ route('admin.tenants.impersonate', $tenant) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1.5 rounded-lg bg-orange-500/10 hover:bg-orange-500/20 text-orange-600 dark:text-orange-400 font-bold text-[11px] transition flex items-center gap-1"
                                            title="Entrar no ambiente deste restaurante em Modo de Suporte Técnico">
                                        <i class="mdi mdi-login-variant"></i>
                                        <span>Entrar</span>
                                    </button>
                                </form>

                                <!-- Certificate -->
                                <a href="{{ route('admin.tenants.certificate', $tenant) }}" 
                                   class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 hover:text-emerald-500 transition"
                                   title="Certificado Oficial de Licenciamento">
                                    <i class="mdi mdi-certificate-outline text-base"></i>
                                </a>

                                <!-- Details / Edit -->
                                <a href="{{ route('admin.tenants.show', $tenant) }}" 
                                   class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 hover:text-blue-500 transition"
                                   title="Ver Detalhes do Restaurante">
                                    <i class="mdi mdi-eye-outline text-base"></i>
                                </a>

                                <!-- Suspend / Reactivate -->
                                @if($tenant->status === 'suspended')
                                    <form action="{{ route('admin.tenants.reactivate', $tenant) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 hover:text-emerald-500 transition" title="Reativar Restaurante">
                                            <i class="mdi mdi-play-circle-outline text-base"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.tenants.suspend', $tenant) }}" method="POST" class="inline" onsubmit="return confirm('Deseja realmente suspender o acesso deste restaurante?');">
                                        @csrf
                                        <button type="submit" class="p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 hover:text-rose-500 transition" title="Suspender Restaurante">
                                            <i class="mdi mdi-pause-circle-outline text-base"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="mdi mdi-store-off-outline text-4xl block mb-2 opacity-50"></i>
                            <p class="text-xs font-semibold">Nenhum restaurante encontrado com os filtros aplicados.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tenants->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700/60">
            {{ $tenants->links() }}
        </div>
        @endif
    </div>

    <!-- Modal: Registar Novo Restaurante -->
    <div x-show="createModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-2xl max-w-2xl w-full p-6 space-y-6 relative max-h-[90vh] overflow-y-auto"
             @click.away="createModalOpen = false">
            
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 text-orange-500 flex items-center justify-center text-xl">
                        <i class="mdi mdi-store-plus-outline"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900 dark:text-white">Registar Novo Restaurante (Tenant)</h3>
                        <p class="text-xs text-gray-400">Cria o ambiente isolado do restaurante, gera a chave de licença e cria a conta admin.</p>
                    </div>
                </div>
                <button type="button" @click="createModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>

            <form action="{{ route('admin.tenants.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Dados do Restaurante -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Nome do Restaurante *</label>
                        <input type="text" name="name" required placeholder="Ex: Restaurante Mar Azul"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Subdomínio / Slug (Opcional)</label>
                        <input type="text" name="slug" placeholder="Ex: mar-azul (gerado auto)"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 font-mono">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">NUIT</label>
                        <input type="text" name="nuit" placeholder="Ex: 400123456"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 font-mono">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Telefone / WhatsApp</label>
                        <input type="text" name="phone" placeholder="+258 84 000 0000"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Cidade / Província</label>
                        <input type="text" name="city" value="Quelimane"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Endereço Físico</label>
                        <input type="text" name="address" placeholder="Av. Marginal, nº 42"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-orange-500/20">
                    </div>
                </div>

                <!-- Plano & Modo Operacional -->
                <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700/50 space-y-3">
                    <h4 class="text-xs font-bold text-gray-900 dark:text-white flex items-center gap-1.5">
                        <i class="mdi mdi-certificate-outline text-orange-500"></i>
                        <span>Configurações de Licença & Capacidade</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Plano *</label>
                            <select name="plan" required class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                                <option value="trial">Trial (15 Dias)</option>
                                <option value="monthly">Mensal</option>
                                <option value="quarterly">Trimestral</option>
                                <option value="annual" selected>Anual (365 Dias)</option>
                                <option value="lifetime">Vitalício</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Modo Instalação *</label>
                            <select name="installation_mode" required class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                                <option value="cloud" selected>Cloud (Nuvem)</option>
                                <option value="local_online">Local com Sincronização</option>
                                <option value="offline">Offline Nativo</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Duração (Meses)</label>
                            <select name="duration_months" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                                <option value="1">1 Mês</option>
                                <option value="3">3 Meses</option>
                                <option value="6">6 Meses</option>
                                <option value="12" selected>12 Meses (1 Ano)</option>
                                <option value="24">24 Meses (2 Anos)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Limite de Mesas</label>
                            <input type="number" name="max_tables" value="50" min="1" max="500" required
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-500 uppercase">Limite de Operadores</label>
                            <input type="number" name="max_users" value="15" min="1" max="100" required
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Conta de Administrador do Restaurante -->
                <div class="p-4 rounded-2xl bg-orange-50/50 dark:bg-orange-950/20 border border-orange-200 dark:border-orange-800/40 space-y-3">
                    <h4 class="text-xs font-bold text-orange-900 dark:text-orange-300 flex items-center gap-1.5">
                        <i class="mdi mdi-account-key-outline text-orange-500"></i>
                        <span>Conta do Administrador do Restaurante</span>
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase">Nome Completo *</label>
                            <input type="text" name="admin_name" required placeholder="Ex: Carlos Macuácua"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase">Nome de Utilizador *</label>
                            <input type="text" name="admin_username" required placeholder="Ex: admin_marazul"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white font-mono">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase">E-mail *</label>
                            <input type="email" name="admin_email" required placeholder="admin@marazul.co.mz"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                        </div>

                        <div class="space-y-1">
                            <label class="text-[10px] font-bold text-gray-700 dark:text-gray-300 uppercase">Palavra-passe Inicial *</label>
                            <input type="password" name="admin_password" required minlength="6" placeholder="Mínimo 6 caracteres"
                                   class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-xs text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Footer buttons -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="createModalOpen = false" 
                            class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                        <i class="mdi mdi-check-circle"></i>
                        <span>Criar Restaurante & Ativar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

