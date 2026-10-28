@extends('layouts.app')

@section('content')
    <div class="w-full space-y-6">
        @if($isOwnerConsole)
            <!-- Enterprise SaaS Owner Security & Audit Banner -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white p-6 md:p-8 shadow-xl border border-indigo-500/20">
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-300 text-xs font-bold uppercase tracking-wider mb-3">
                            <i class="mdi mdi-shield-lock-outline"></i>
                            Governança & Segurança Multi-Tenant (SaaS)
                        </div>
                        <h1 class="text-2xl md:text-3xl font-black tracking-tight flex items-center gap-3">
                            Central de Auditoria da Plataforma
                        </h1>
                        <p class="text-slate-300 text-sm mt-1 max-w-2xl">
                            Monitorização centralizada em tempo real de sessões de suporte técnico, provisionamento de restaurantes, autenticações e operações críticas em toda a rede RestPOS.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('audit_logs.index') }}"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ !request('scope') ? 'bg-white text-slate-900 shadow-md' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                            <i class="mdi mdi-layers-triple-outline mr-1"></i> Todos os Eventos
                        </a>
                        <a href="{{ route('audit_logs.index', array_merge(request()->except('page'), ['scope' => 'saas'])) }}"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('scope') === 'saas' ? 'bg-amber-500 text-slate-950 shadow-md' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                            <i class="mdi mdi-shield-account mr-1"></i> Suporte & Acessos SaaS
                        </a>
                        <a href="{{ route('audit_logs.index', array_merge(request()->except('page'), ['scope' => 'operations'])) }}"
                            class="px-4 py-2 rounded-xl text-xs font-bold transition-all {{ request('scope') === 'operations' ? 'bg-indigo-500 text-white shadow-md' : 'bg-white/10 text-slate-200 hover:bg-white/20' }}">
                            <i class="mdi mdi-store-cog-outline mr-1"></i> Operações nos Restaurantes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Owner KPI Cards -->
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Eventos</span>
                        <span class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-gray-700 text-slate-700 dark:text-slate-200 flex items-center justify-center">
                            <i class="mdi mdi-database-eye-outline text-lg"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_events']) }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Histórico global</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Eventos Hoje</span>
                        <span class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <i class="mdi mdi-pulse text-lg"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($stats['today_events']) }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Registados hoje</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Modo Suporte</span>
                        <span class="w-8 h-8 rounded-xl bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                            <i class="mdi mdi-shield-account text-lg"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ number_format($stats['support_sessions']) }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Acessos técnicos</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Logins (24h)</span>
                        <span class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                            <i class="mdi mdi-login-variant text-lg"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ number_format($stats['logins_24h']) }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Sessões autenticadas</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Ações Críticas</span>
                        <span class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center">
                            <i class="mdi mdi-alert-octagon-outline text-lg"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-red-600 dark:text-red-400">{{ number_format($stats['critical_events']) }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Remoções e suspensões</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Instâncias</span>
                        <span class="w-8 h-8 rounded-xl bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                            <i class="mdi mdi-domain text-lg"></i>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['monitored_tenants']) }}</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Restaurantes auditados</p>
                </div>
            </div>
        @else
            <!-- Restaurant Operational Audit Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-50 dark:bg-orange-950/40 text-orange-600 dark:text-orange-400 text-xs font-bold mb-2">
                        <i class="mdi mdi-storefront-outline"></i>
                        {{ current_tenant()?->name ?? 'Restaurante' }} — Auditoria Interna
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-history text-orange-500"></i>
                        Registo de Atividades do Restaurante
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                        Acompanhe todas as alterações de produtos, pedidos, vendas, configurações e acessos da sua equipa.
                    </p>
                </div>
            </div>

            <!-- Restaurant KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-gray-400 uppercase">Total Registos</span>
                        <i class="mdi mdi-clipboard-text-clock-outline text-orange-500 text-xl"></i>
                    </div>
                    <p class="text-2xl font-black text-gray-900 dark:text-white">{{ number_format($stats['total_events']) }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($stats['today_events']) }} registados hoje</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-gray-400 uppercase">Criações</span>
                        <i class="mdi mdi-plus-circle-outline text-emerald-500 text-xl"></i>
                    </div>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($stats['creations']) }}</p>
                    <p class="text-xs text-gray-500">Novos registos criados</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-gray-400 uppercase">Atualizações</span>
                        <i class="mdi mdi-pencil-circle-outline text-blue-500 text-xl"></i>
                    </div>
                    <p class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ number_format($stats['updates']) }}</p>
                    <p class="text-xs text-gray-500">Alterações efetuadas</p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-gray-400 uppercase">Eliminações</span>
                        <i class="mdi mdi-delete-circle-outline text-red-500 text-xl"></i>
                    </div>
                    <p class="text-2xl font-black text-red-600 dark:text-red-400">{{ number_format($stats['deletions']) }}</p>
                    <p class="text-xs text-gray-500">{{ number_format($stats['logins_24h']) }} logins nas últimas 24h</p>
                </div>
            </div>
        @endif

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
            <form action="{{ route('audit_logs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 {{ $isOwnerConsole ? 'xl:grid-cols-5' : 'xl:grid-cols-4' }} gap-4">
                @if(request('scope'))
                    <input type="hidden" name="scope" value="{{ request('scope') }}">
                @endif

                @if($isOwnerConsole)
                    <div>
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Restaurante / Instância</label>
                        <select name="tenant_id"
                            class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                            <option value="">Todos os Restaurantes & Plataforma</option>
                            <option value="platform" {{ request('tenant_id') === 'platform' ? 'selected' : '' }}>Consola Global (Sem Restaurante)</option>
                            @foreach($tenants as $t)
                                <option value="{{ $t->id }}" {{ (string) request('tenant_id') === (string) $t->id ? 'selected' : '' }}>
                                    {{ $t->name }} ({{ $t->city ?? $t->slug }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('messages.user') }}</label>
                    <select name="user_id"
                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        <option value="">{{ __('messages.all_users') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ (string) request('user_id') === (string) $user->id ? 'selected' : '' }}>
                                {{ $user->name }} {{ $isOwnerConsole && $user->role === 'super_admin' ? '(SuperAdmin)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">Tipo de Evento</label>
                    <select name="action"
                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                        <option value="">{{ __('messages.all_actions') }}</option>
                        <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Criação de Registo</option>
                        <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Atualização de Dados</option>
                        <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Eliminação de Registo</option>
                        <option value="login" {{ request('action') === 'login' ? 'selected' : '' }}>Sessão Iniciada (Login)</option>
                        <option value="logout" {{ request('action') === 'logout' ? 'selected' : '' }}>Sessão Terminada (Logout)</option>
                        @if($isOwnerConsole)
                            <option value="impersonate_start" {{ request('action') === 'impersonate_start' ? 'selected' : '' }}>Acesso Modo Suporte</option>
                            <option value="impersonate_end" {{ request('action') === 'impersonate_end' ? 'selected' : '' }}>Saída Modo Suporte</option>
                            <option value="tenant_created" {{ request('action') === 'tenant_created' ? 'selected' : '' }}>Novo Restaurante Registado</option>
                            <option value="tenant_suspended" {{ request('action') === 'tenant_suspended' ? 'selected' : '' }}>Restaurante Suspenso</option>
                            <option value="tenant_reactivated" {{ request('action') === 'tenant_reactivated' ? 'selected' : '' }}>Restaurante Reativado</option>
                        @endif
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-2">{{ __('messages.search') }}</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar modelo, IP, valor alterado..."
                        class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 {{ $isOwnerConsole ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-500/20' : 'bg-orange-500 hover:bg-orange-600 shadow-orange-500/20' }} text-white font-bold rounded-xl transition-all shadow-lg text-sm">
                        <i class="mdi mdi-filter-variant mr-1"></i> {{ __('messages.filter') }}
                    </button>
                    <a href="{{ route('audit_logs.index') }}"
                        class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 transition-all text-sm">
                        {{ __('messages.clear') }}
                    </a>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.date_time') }}
                            </th>
                            @if($isOwnerConsole)
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Instância / Restaurante
                                </th>
                            @endif
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('messages.user') }}
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Evento
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Módulo / Alvo
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Resumo Técnico
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Origem (IP)
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white whitespace-nowrap">
                                    <div class="font-semibold">{{ $log->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>

                                @if($isOwnerConsole)
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->tenant)
                                            <a href="{{ route('admin.tenants.show', $log->tenant->id) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-gray-200 hover:bg-indigo-50 hover:text-indigo-600 text-xs font-bold transition-colors">
                                                <i class="mdi mdi-storefront-outline text-indigo-500"></i>
                                                {{ $log->tenant->name }}
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 text-xs font-bold">
                                                <i class="mdi mdi-shield-crown-outline"></i>
                                                Plataforma Global
                                            </span>
                                        @endif
                                    </td>
                                @endif

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-300 text-xs font-bold">
                                            {{ $log->user ? strtoupper(substr($log->user->name, 0, 2)) : 'S' }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $log->user->name ?? __('messages.system_user') }}
                                            </div>
                                            @if($log->user)
                                                <div class="text-[11px] text-gray-400">
                                                    {{ $log->user->role === 'super_admin' ? 'Dono do Sistema' : ucfirst($log->user->role) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $log->action_badge_classes }}">
                                        <i class="mdi {{ $log->action_icon }}"></i>
                                        {{ $log->action_label }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white whitespace-nowrap">
                                    <span class="font-bold">{{ $log->model_label }}</span>
                                    @if($log->auditable_id)
                                        <span class="text-gray-400 text-xs ml-0.5">#{{ $log->auditable_id }}</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if($log->action === 'impersonate_start')
                                        <div class="text-xs font-semibold text-amber-700 dark:text-amber-300">
                                            Sessão técnica iniciada em {{ $log->new_values['tenant_name'] ?? ($log->tenant->name ?? 'Restaurante') }}
                                        </div>
                                    @elseif($log->action === 'impersonate_end')
                                        <div class="text-xs font-semibold text-gray-600 dark:text-gray-300">
                                            Saída do modo suporte ({{ $log->new_values['tenant_name'] ?? 'Consola Global' }})
                                        </div>
                                    @elseif($log->action === 'login')
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Autenticação efetuada com sucesso
                                        </div>
                                    @elseif($log->action === 'logout')
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Sessão encerrada pelo utilizador
                                        </div>
                                    @elseif($log->action === 'updated' && $log->new_values)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                            {{ __('messages.changed') }}:
                                            {{ implode(', ', array_map(fn($key) => __('messages.' . $key) !== 'messages.' . $key ? __('messages.' . $key) : $key, array_keys($log->new_values))) }}
                                        </div>
                                    @elseif($log->action === 'created' || $log->action === 'tenant_created')
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->new_values['name'] ?? __('messages.new_record_created') }}
                                        </div>
                                    @elseif($log->action === 'deleted')
                                        <div class="text-xs text-red-600 dark:text-red-400">
                                            {{ __('messages.record_removed') }}
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Registo de auditoria #{{ $log->id }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    <div class="font-mono font-semibold text-gray-700 dark:text-gray-300">{{ $log->ip_address ?? '—' }}</div>
                                    @if($log->user_agent)
                                        <div class="text-[10px] text-gray-400 max-w-[150px] truncate" title="{{ $log->user_agent }}">
                                            {{ $log->user_agent }}
                                        </div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="{{ route('audit_logs.show', $log->id) }}"
                                        class="inline-flex items-center justify-center p-2 bg-gray-50 dark:bg-gray-700 text-gray-500 hover:text-orange-500 rounded-xl transition-colors"
                                        title="{{ __('messages.view_details') }}">
                                        <i class="mdi mdi-eye-outline text-lg"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $isOwnerConsole ? 8 : 7 }}" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="mdi mdi-shield-search text-6xl text-gray-200 dark:text-gray-700"></i>
                                        <p class="text-gray-500 dark:text-gray-400 mt-4 font-medium">{{ __('messages.no_logs_found') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
