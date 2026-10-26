@extends('layouts.app')

@section('title', 'Detalhes do Restaurante - ' . $tenant->name)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tenants.index') }}" 
               class="p-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-500 hover:text-gray-900 dark:hover:text-white transition">
                <i class="mdi mdi-arrow-left text-base"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white">{{ $tenant->name }}</h1>
                    @if($tenant->status === 'active')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">Ativo</span>
                    @elseif($tenant->status === 'trial')
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400">Trial</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 dark:bg-rose-950/40 dark:text-rose-400">Suspenso</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 font-mono">
                    ID: #{{ $tenant->id }} • Subdomínio: {{ $tenant->slug }} • NUIT: {{ $tenant->nuit ?? 'N/A' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <!-- Impersonate Button -->
            <form action="{{ route('admin.tenants.impersonate', $tenant) }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition flex items-center gap-2 shadow-xs">
                    <i class="mdi mdi-login-variant"></i>
                    <span>Entrar no Restaurante (Suporte)</span>
                </button>
            </form>

            <!-- Certificate Button -->
            <a href="{{ route('admin.tenants.certificate', $tenant) }}" 
               class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-bold transition flex items-center gap-2">
                <i class="mdi mdi-certificate-outline text-emerald-500"></i>
                <span>Ver Certificado</span>
            </a>

            <!-- Suspend / Reactivate -->
            @if($tenant->status === 'suspended')
                <form action="{{ route('admin.tenants.reactivate', $tenant) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5">
                        <i class="mdi mdi-play-circle-outline"></i>
                        <span>Reativar</span>
                    </button>
                </form>
            @else
                <form action="{{ route('admin.tenants.suspend', $tenant) }}" method="POST" class="inline" onsubmit="return confirm('Suspender este restaurante bloqueará novas operações no caixa.');">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 text-xs font-bold transition flex items-center gap-1.5">
                        <i class="mdi mdi-pause-circle-outline"></i>
                        <span>Suspender</span>
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-gray-400">Total Faturado</span>
            <div class="mt-1 text-xl font-black text-emerald-600 dark:text-emerald-400 font-mono">
                MZN {{ number_format($stats['sales_total'], 2, ',', '.') }}
            </div>
            <span class="text-[10px] text-gray-400">{{ $stats['sales_count'] }} vendas concluídas</span>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-gray-400">Utilizadores Ativos</span>
            <div class="mt-1 text-xl font-black text-gray-900 dark:text-white">
                {{ $stats['users_count'] }} <span class="text-xs text-gray-400 font-normal">/ {{ $tenant->max_users }} máx</span>
            </div>
            <span class="text-[10px] text-gray-400">Operadores e gerentes</span>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-gray-400">Mesas Cadastradas</span>
            <div class="mt-1 text-xl font-black text-gray-900 dark:text-white">
                {{ $stats['tables_count'] }} <span class="text-xs text-gray-400 font-normal">/ {{ $tenant->max_tables }} máx</span>
            </div>
            <span class="text-[10px] text-gray-400">Capacidade de salão</span>
        </div>

        <div class="p-4 rounded-2xl bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 shadow-xs">
            <span class="text-[10px] font-bold uppercase text-gray-400">Cardápio Ativo</span>
            <div class="mt-1 text-xl font-black text-gray-900 dark:text-white">
                {{ $stats['products_count'] }} <span class="text-xs text-gray-400 font-normal">produtos</span>
            </div>
            <span class="text-[10px] text-gray-400">Itens disponíveis</span>
        </div>
    </div>

    <!-- Edit Form & License Keys Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Edit Details Form -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/60 p-6 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-pencil-box-outline text-orange-500 text-lg"></i>
                    <span>Configuração & Limites Operacionais</span>
                </h3>
            </div>

            <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Nome do Restaurante</label>
                        <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">NUIT</label>
                        <input type="text" name="nuit" value="{{ old('nuit', $tenant->nuit) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white font-mono">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Telefone</label>
                        <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Cidade / Província</label>
                        <input type="text" name="city" value="{{ old('city', $tenant->city) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Endereço</label>
                        <input type="text" name="address" value="{{ old('address', $tenant->address) }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Plano</label>
                        <select name="plan" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                            <option value="trial" {{ $tenant->plan === 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="monthly" {{ $tenant->plan === 'monthly' ? 'selected' : '' }}>Mensal</option>
                            <option value="quarterly" {{ $tenant->plan === 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                            <option value="annual" {{ $tenant->plan === 'annual' ? 'selected' : '' }}>Anual</option>
                            <option value="lifetime" {{ $tenant->plan === 'lifetime' ? 'selected' : '' }}>Vitalício</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Modo de Instalação</label>
                        <select name="installation_mode" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                            <option value="cloud" {{ $tenant->installation_mode === 'cloud' ? 'selected' : '' }}>Cloud</option>
                            <option value="local_online" {{ $tenant->installation_mode === 'local_online' ? 'selected' : '' }}>Local + Sync</option>
                            <option value="offline" {{ $tenant->installation_mode === 'offline' ? 'selected' : '' }}>Offline Local</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Limite de Mesas</label>
                        <input type="number" name="max_tables" value="{{ old('max_tables', $tenant->max_tables) }}" required
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Limite de Utilizadores</label>
                        <input type="number" name="max_users" value="{{ old('max_users', $tenant->max_users) }}" required
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Validade da Licença</label>
                        <input type="date" name="license_expires_at" value="{{ $tenant->license_expires_at?->format('Y-m-d') }}"
                               class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[11px] font-bold text-gray-700 dark:text-gray-300 uppercase">Estado Geral</label>
                        <select name="status" class="w-full px-3 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-xs text-gray-900 dark:text-white">
                            <option value="active" {{ $tenant->status === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="trial" {{ $tenant->status === 'trial' ? 'selected' : '' }}>Trial</option>
                            <option value="suspended" {{ $tenant->status === 'suspended' ? 'selected' : '' }}>Suspenso</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                        <i class="mdi mdi-content-save"></i>
                        <span>Guardar Alterações</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Users in Tenant Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/60 p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-3">
                <h3 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-account-group-outline text-orange-500 text-lg"></i>
                    <span>Utilizadores ({{ $tenant->users->count() }})</span>
                </h3>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700/50 max-h-[350px] overflow-y-auto">
                @forelse($tenant->users as $u)
                <div class="py-3 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-xs text-gray-900 dark:text-white">{{ $u->name }}</div>
                        <div class="text-[11px] text-gray-400 font-mono">{{ $u->username }} • {{ $u->role }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $u->status === 'active' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($u->status) }}
                    </span>
                </div>
                @empty
                <div class="py-4 text-center text-xs text-gray-400">Nenhum utilizador vinculado.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

