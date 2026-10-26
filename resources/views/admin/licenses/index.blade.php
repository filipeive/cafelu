@extends('layouts.app')

@section('title', 'Painel de Licenciamento & Facturação')

@section('content')
<div class="space-y-6" x-data="{ showCreateModal: false, showExtendModal: false, selectedLicense: null, extendDays: 365 }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2.5">
                <i class="mdi mdi-certificate-outline text-orange-500"></i>
                <span>Painel de Licenças & Facturação</span>
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Controle central do dono do sistema: gere seriais, configure planos, defina limites e controle ativações.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('license.status') }}" 
               class="px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold transition-all flex items-center gap-1.5">
                <i class="mdi mdi-key-variant"></i>
                <span>Minha Licença</span>
            </a>

            <button type="button" @click="showCreateModal = true"
               class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all flex items-center gap-2 shadow-sm shadow-orange-500/25 cursor-pointer">
                <i class="mdi mdi-plus-circle-outline text-base"></i>
                <span>Gerar Nova Licença</span>
            </button>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total de Licenças</p>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-1">{{ $totalLicenses }}</h3>
                <span class="text-[11px] text-gray-500">Emitidas no sistema</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-key-chain"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Licenças Ativas</p>
                <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1">{{ $activeLicenses }}</h3>
                <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium">Em vigor regular</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-check-decagram"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">A Expirar em 30d</p>
                <h3 class="text-2xl font-black text-amber-500 dark:text-amber-400 mt-1">{{ $expiringSoon }}</h3>
                <span class="text-[11px] text-gray-500">Oportunidade de renovação</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-clock-alert-outline"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Fingerprint Servidor</p>
                <h3 class="text-sm font-black text-gray-900 dark:text-white mt-1 font-mono tracking-wider">{{ $fingerprint }}</h3>
                <span class="text-[11px] text-gray-500">ID de hardware local</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl">
                <i class="mdi mdi-server-security"></i>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs">
        <form method="GET" action="{{ route('admin.licenses.index') }}" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative flex-1 w-full">
                <i class="mdi mdi-magnify absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white text-xs font-medium focus:ring-2 focus:ring-orange-500"
                       placeholder="Buscar por cliente, empresa ou serial...">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="status" class="text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-orange-500">
                    <option value="">Todos os Estados</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativa</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirada</option>
                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspensa</option>
                    <option value="revoked" {{ request('status') === 'revoked' ? 'selected' : '' }}>Revogada</option>
                </select>

                <button type="submit" 
                        class="px-4 py-2 rounded-xl bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer">
                    <i class="mdi mdi-filter-variant"></i>
                    <span>Filtrar</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Licenses Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700/60 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50/80 dark:bg-gray-900/40 border-b border-gray-100 dark:border-gray-700 text-[11px] uppercase tracking-wider text-gray-400 font-bold">
                    <tr>
                        <th class="py-3 px-4">Serial Key</th>
                        <th class="py-3 px-4">Empresa / Cliente</th>
                        <th class="py-3 px-4">Plano</th>
                        <th class="py-3 px-4">Validade & Expiração</th>
                        <th class="py-3 px-4 text-center">Limites</th>
                        <th class="py-3 px-4 text-center">Estado</th>
                        <th class="py-3 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 font-medium">
                    @forelse($licenses as $lic)
                    <tr class="hover:bg-gray-50/60 dark:hover:bg-gray-750/50 transition-colors {{ $lic->is_current ? 'bg-orange-50/20 dark:bg-orange-950/20' : '' }}">
                        <td class="py-3.5 px-4 font-mono font-bold text-gray-900 dark:text-white">
                            <div class="flex items-center gap-2">
                                <span class="bg-gray-100 dark:bg-gray-700 px-2.5 py-1 rounded-lg text-xs font-black tracking-wider text-gray-800 dark:text-gray-200">
                                    {{ $lic->key_code }}
                                </span>
                                <button type="button" onclick="navigator.clipboard.writeText('{{ $lic->key_code }}'); alert('Serial copiado!');"
                                        class="p-1 text-gray-400 hover:text-orange-500 cursor-pointer" title="Copiar Chave Serial">
                                    <i class="mdi mdi-content-copy text-sm"></i>
                                </button>
                                @if($lic->is_current)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-orange-100 text-orange-700 dark:bg-orange-950/60 dark:text-orange-300">
                                        Instalação Atual
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-gray-900 dark:text-white block">{{ $lic->business_name }}</span>
                            <span class="text-[11px] text-gray-500 block">{{ $lic->customer_name }} {{ $lic->customer_phone ? ' • ' . $lic->customer_phone : '' }}</span>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider
                                {{ $lic->license_type === 'lifetime' ? 'bg-purple-50 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300 border border-purple-200/60' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200/60' }}">
                                {{ $lic->license_type }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($lic->license_type === 'lifetime')
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">Vitalícia (Sem expiração)</span>
                            @elseif($lic->expires_at)
                                <span class="font-semibold text-gray-800 dark:text-gray-200 block">
                                    Até {{ $lic->expires_at->format('d/m/Y') }}
                                </span>
                                <span class="text-[10px] {{ $lic->isExpired() ? 'text-rose-500 font-bold' : ($lic->daysRemaining() <= 30 ? 'text-amber-500 font-bold' : 'text-gray-400') }}">
                                    {{ $lic->isExpired() ? 'Expirada há ' . abs($lic->daysRemaining()) . ' dias' : $lic->daysRemaining() . ' dias restantes' }}
                                </span>
                            @else
                                <span class="text-gray-400">Indefinido</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="text-gray-700 dark:text-gray-300 font-semibold text-[11px]">
                                {{ $lic->max_tables }} mesas / {{ $lic->max_users }} utilizadores
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            @if($lic->status === 'active' && !$lic->isExpired())
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60">
                                    Ativa
                                </span>
                            @elseif($lic->isExpired())
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60">
                                    Expirada
                                </span>
                            @elseif($lic->status === 'suspended')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 border border-amber-200/60">
                                    Suspensa
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200">
                                    Revogada
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-1.5">
                                <button type="button" @click="selectedLicense = {{ $lic->id }}; showExtendModal = true"
                                        class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition-colors"
                                        title="Estender / Renovar Validade">
                                    <i class="mdi mdi-calendar-plus text-base"></i>
                                </button>

                                @if($lic->status === 'active')
                                <form method="POST" action="{{ route('admin.licenses.status', $lic->id) }}" class="inline" onsubmit="return confirm('Deseja suspender esta licença?')">
                                    @csrf
                                    <input type="hidden" name="status" value="suspended">
                                    <button type="submit" class="p-1.5 rounded-lg text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-950/40 transition-colors" title="Suspender">
                                        <i class="mdi mdi-pause-circle-outline text-base"></i>
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.licenses.status', $lic->id) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="p-1.5 rounded-lg text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-950/40 transition-colors" title="Reativar">
                                        <i class="mdi mdi-play-circle-outline text-base"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <i class="mdi mdi-certificate-outline text-4xl block mb-2 opacity-40"></i>
                            <p class="text-sm font-semibold">Nenhuma licença cadastrada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($licenses->hasPages())
        <div class="p-4 border-t border-gray-100 dark:border-gray-700/60 bg-gray-50/50 dark:bg-gray-900/20">
            {{ $licenses->links() }}
        </div>
        @endif
    </div>

    <!-- Modal: Gerar Nova Licença -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/80 dark:bg-gray-900/40">
                    <h3 class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="mdi mdi-plus-circle text-orange-500 text-lg"></i>
                        <span>Gerar Nova Chave de Licença</span>
                    </h3>
                    <button type="button" @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <i class="mdi mdi-close text-lg"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.licenses.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                            Nome do Restaurante / Empresa *
                        </label>
                        <input type="text" name="business_name" required placeholder="Ex: Café Luanda"
                               class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Tipo de Licença *
                            </label>
                            <select name="license_type" required
                                    class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                                <option value="trial">Trial (15 Dias)</option>
                                <option value="monthly">Mensal (30 Dias)</option>
                                <option value="quarterly">Trimestral (90 Dias)</option>
                                <option value="annual" selected>Anual (365 Dias)</option>
                                <option value="lifetime">Vitalícia (Sem Expiração)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Nome do Contato / Cliente
                            </label>
                            <input type="text" name="customer_name" placeholder="Ex: Dr. António"
                                   class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Telefone do Cliente
                            </label>
                            <input type="text" name="customer_phone" placeholder="+258 84 000 0000"
                                   class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Email do Cliente
                            </label>
                            <input type="email" name="customer_email" placeholder="cliente@restaurante.com"
                                   class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Limite de Mesas
                            </label>
                            <input type="number" name="max_tables" value="50" min="1" max="500" required
                                   class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                                Limite de Operadores
                            </label>
                            <input type="number" name="max_users" value="10" min="1" max="100" required
                                   class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1">
                            Notas / Condições Contratuais
                        </label>
                        <textarea name="notes" rows="2" placeholder="Ex: Pacote Premium com suporte incluído"
                                  class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2 focus:ring-2 focus:ring-orange-500"></textarea>
                    </div>

                    <div class="pt-2 flex justify-end gap-2">
                        <button type="button" @click="showCreateModal = false"
                                class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-5 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all shadow-sm shadow-orange-500/25 cursor-pointer">
                            Gerar Chave Serial
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Estender Validade -->
    <div x-show="showExtendModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-sm w-full overflow-hidden shadow-2xl border border-gray-100 dark:border-gray-700 p-6 space-y-4">
                <h3 class="text-sm font-black text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-calendar-plus text-emerald-500 text-lg"></i>
                    <span>Renovar / Estender Validade</span>
                </h3>
                <p class="text-xs text-gray-500">
                    Selecione o período adicional a ser acrescentado à licença selecionada:
                </p>

                <form :action="`/admin/licenses/${selectedLicense}/extend`" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <select name="days" x-model="extendDays"
                                class="w-full text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2.5 focus:ring-2 focus:ring-orange-500">
                            <option value="30">+30 Dias (1 Mês)</option>
                            <option value="90">+90 Dias (3 Meses)</option>
                            <option value="180">+180 Dias (6 Meses)</option>
                            <option value="365" selected>+365 Dias (1 Ano)</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="showExtendModal = false"
                                class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-xs font-bold hover:bg-gray-100 cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-sm cursor-pointer">
                            Confirmar Renovação
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
