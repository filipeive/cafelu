@extends('layouts.app')

@section('title', 'Cotações & Propostas Comerciais')
@section('title-icon', 'mdi-file-document-edit-outline')
@section('page-title', 'Cotações & Proformas')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('dashboard') }}" class="hover:text-sky-600 transition-colors">Início</a>
        <i class="mdi mdi-chevron-right text-gray-400 mx-1"></i>
    </li>
    <li class="text-gray-800 dark:text-gray-200 font-semibold">Cotações & Propostas</li>
@endsection

@section('actions')
    <div class="flex items-center gap-2">
        <a href="{{ route('sales.index') }}"
            class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 font-semibold py-2 px-4 rounded-xl text-sm shadow-sm transition-all">
            <i class="mdi mdi-receipt-text-outline text-sky-600"></i>
            <span>Facturas & Recibos</span>
        </a>
        <a href="{{ route('quotations.create') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold py-2 px-4 rounded-xl text-sm shadow-md transition-all">
            <i class="mdi mdi-plus-circle-outline text-lg"></i>
            <span>Nova Cotação</span>
        </a>
    </div>
@endsection

@section('content')
    <div class="space-y-6" x-data="{ viewMode: localStorage.getItem('restpos_quotations_view') || 'table' }"
         x-init="$watch('viewMode', val => localStorage.setItem('restpos_quotations_view', val))">

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <a href="{{ route('quotations.index') }}"
                class="bg-white dark:bg-gray-800 rounded-2xl p-4 border {{ !request('status') ? 'border-orange-500 ring-2 ring-orange-500/20' : 'border-gray-200 dark:border-gray-700' }} shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Emitidas</span>
                    <span class="w-9 h-9 rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 flex items-center justify-center">
                        <i class="mdi mdi-file-document-multiple-outline text-lg"></i>
                    </span>
                </div>
                <p class="text-2xl font-black text-gray-900 dark:text-white mt-2">{{ $counts['all'] ?? 0 }}</p>
            </a>

            <a href="{{ route('quotations.index', ['status' => 'draft']) }}"
                class="bg-white dark:bg-gray-800 rounded-2xl p-4 border {{ request('status') === 'draft' ? 'border-gray-500 ring-2 ring-gray-500/20' : 'border-gray-200 dark:border-gray-700' }} shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Rascunhos</span>
                    <span class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center">
                        <i class="mdi mdi-file-edit-outline text-lg"></i>
                    </span>
                </div>
                <p class="text-2xl font-black text-gray-900 dark:text-white mt-2">{{ $counts['draft'] ?? 0 }}</p>
            </a>

            <a href="{{ route('quotations.index', ['status' => 'sent']) }}"
                class="bg-white dark:bg-gray-800 rounded-2xl p-4 border {{ request('status') === 'sent' ? 'border-sky-500 ring-2 ring-sky-500/20' : 'border-gray-200 dark:border-gray-700' }} shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400">Enviadas</span>
                    <span class="w-9 h-9 rounded-xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                        <i class="mdi mdi-send-check-outline text-lg"></i>
                    </span>
                </div>
                <p class="text-2xl font-black text-sky-600 dark:text-sky-400 mt-2">{{ $counts['sent'] ?? 0 }}</p>
            </a>

            <a href="{{ route('quotations.index', ['status' => 'approved']) }}"
                class="bg-white dark:bg-gray-800 rounded-2xl p-4 border {{ request('status') === 'approved' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-gray-200 dark:border-gray-700' }} shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Aprovadas</span>
                    <span class="w-9 h-9 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <i class="mdi mdi-check-decagram-outline text-lg"></i>
                    </span>
                </div>
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ $counts['approved'] ?? 0 }}</p>
            </a>

            <a href="{{ route('quotations.index', ['status' => 'converted']) }}"
                class="bg-white dark:bg-gray-800 rounded-2xl p-4 border {{ request('status') === 'converted' ? 'border-purple-500 ring-2 ring-purple-500/20' : 'border-gray-200 dark:border-gray-700' }} shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400">Facturadas</span>
                    <span class="w-9 h-9 rounded-xl bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                        <i class="mdi mdi-receipt-text-check-outline text-lg"></i>
                    </span>
                </div>
                <p class="text-2xl font-black text-purple-600 dark:text-purple-400 mt-2">{{ $counts['converted'] ?? 0 }}</p>
            </a>
        </div>

        <!-- Filter & View Switcher Bar -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <form method="GET" action="{{ route('quotations.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                <div class="md:col-span-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Pesquisar</label>
                    <div class="relative">
                        <i class="mdi mdi-magnify absolute left-3 top-2.5 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Nº Cotação, Nome do Cliente, NUIT ou Telefone..."
                            class="w-full pl-9 pr-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Estado</label>
                    <select name="status"
                        class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                        <option value="">Todos os Estados</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                        <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Enviada</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovada</option>
                        <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Convertida em Factura</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitada</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">De</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Até</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                </div>
                <div class="md:col-span-2 flex items-center gap-2">
                    <button type="submit"
                        class="flex-1 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-3 rounded-xl text-sm transition-colors flex items-center justify-center gap-1">
                        <i class="mdi mdi-filter-variant"></i> Filtrar
                    </button>
                    <a href="{{ route('quotations.index') }}" title="Limpar Filtros"
                        class="py-2 px-3 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm">
                        <i class="mdi mdi-refresh"></i>
                    </a>
                    <div class="inline-flex rounded-xl border border-gray-300 dark:border-gray-600 p-0.5 bg-gray-100 dark:bg-gray-900">
                        <button type="button" @click="viewMode = 'table'"
                            :class="viewMode === 'table' ? 'bg-white dark:bg-gray-700 text-orange-600 shadow-sm' : 'text-gray-500'"
                            class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all" title="Vista Lista">
                            <i class="mdi mdi-format-list-bulleted"></i>
                        </button>
                        <button type="button" @click="viewMode = 'grid'"
                            :class="viewMode === 'grid' ? 'bg-white dark:bg-gray-700 text-orange-600 shadow-sm' : 'text-gray-500'"
                            class="px-2.5 py-1.5 rounded-lg text-xs font-bold transition-all" title="Vista Grelha">
                            <i class="mdi mdi-view-grid-outline"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($quotations->isEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center shadow-sm">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 dark:bg-orange-900/20 text-orange-500 flex items-center justify-center mx-auto mb-4">
                    <i class="mdi mdi-file-document-edit-outline text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Nenhuma cotação encontrada</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-6">
                    Crie propostas comerciais, cotações para eventos, catering ou fornecimento corporativo e converta-as em Factura ou Recibo com 1 clique.
                </p>
                <a href="{{ route('quotations.create') }}"
                    class="inline-flex items-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-bold py-2.5 px-5 rounded-xl text-sm shadow transition-all">
                    <i class="mdi mdi-plus"></i> Emitir Primeira Cotação
                </a>
            </div>
        @else
            <!-- Table View -->
            <div x-show="viewMode === 'table'" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/60">
                            <tr>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Nº Cotação</th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Cliente / NUIT</th>
                                <th class="px-5 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Emissão / Validade</th>
                                <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Total (MT)</th>
                                <th class="px-5 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                                <th class="px-5 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Acções</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                            @foreach($quotations as $quotation)
                                @php
                                    $statusBadges = [
                                        'draft'     => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                        'sent'      => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
                                        'approved'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                                        'rejected'  => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                                        'converted' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                                        'expired'   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300',
                                    ];
                                    $badgeClass = $statusBadges[$quotation->status] ?? $statusBadges['draft'];
                                @endphp
                                <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition-colors">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <a href="{{ route('quotations.show', $quotation) }}" class="font-bold text-sm text-sky-600 dark:text-sky-400 hover:underline flex items-center gap-1.5">
                                            <i class="mdi mdi-file-document-outline"></i>
                                            {{ $quotation->quotation_number }}
                                        </a>
                                        <span class="text-xs text-gray-400 block mt-0.5">Por {{ $quotation->user->name ?? 'Sistema' }}</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="font-semibold text-sm text-gray-900 dark:text-white">{{ $quotation->customer_name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-3 mt-0.5">
                                            @if($quotation->customer_nuit)
                                                <span><i class="mdi mdi-card-account-details-outline"></i> NUIT: {{ $quotation->customer_nuit }}</span>
                                            @endif
                                            @if($quotation->customer_phone)
                                                <span><i class="mdi mdi-phone-outline"></i> {{ $quotation->customer_phone }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-600 dark:text-gray-300">
                                        <div><span class="font-semibold">Emissão:</span> {{ $quotation->date?->format('d/m/Y') }}</div>
                                        @if($quotation->valid_until)
                                            <div class="mt-0.5 {{ $quotation->valid_until->isPast() && $quotation->status !== 'converted' ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                                                Válida até: {{ $quotation->valid_until->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-black text-gray-900 dark:text-white">
                                            {{ number_format($quotation->total_amount, 2, ',', '.') }} MT
                                        </div>
                                        <div class="text-[11px] text-gray-400">
                                            IVA: {{ number_format($quotation->tax_amount, 2, ',', '.') }} MT
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                            {{ $quotation->status_label }}
                                        </span>
                                        @if($quotation->convertedSale)
                                            <a href="{{ route('sales.show', $quotation->convertedSale) }}"
                                                class="block text-[11px] text-purple-600 dark:text-purple-400 font-semibold hover:underline mt-1">
                                                {{ $quotation->convertedSale->invoice_number ?? '#' . $quotation->convertedSale->id }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-right">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('quotations.show', $quotation) }}"
                                                class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-semibold transition-colors"
                                                title="Ver Detalhes / Converter">
                                                <i class="mdi mdi-eye-outline text-base"></i>
                                            </a>
                                            <a href="{{ route('quotations.pdf', $quotation) }}"
                                                class="p-2 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-600 dark:text-red-400 text-xs font-semibold transition-colors"
                                                title="Descarregar PDF A4">
                                                <i class="mdi mdi-file-pdf-box text-base"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grid View -->
            <div x-show="viewMode === 'grid'" x-cloak class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($quotations as $quotation)
                    @php
                        $statusBadges = [
                            'draft'     => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                            'sent'      => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
                            'approved'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
                            'rejected'  => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
                            'converted' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
                        ];
                        $badgeClass = $statusBadges[$quotation->status] ?? $statusBadges['draft'];
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-all flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-2 mb-3">
                                <div>
                                    <a href="{{ route('quotations.show', $quotation) }}" class="text-base font-black text-sky-600 dark:text-sky-400 hover:underline">
                                        {{ $quotation->quotation_number }}
                                    </a>
                                    <p class="text-xs text-gray-400">{{ $quotation->date?->format('d/m/Y') }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                    {{ $quotation->status_label }}
                                </span>
                            </div>

                            <div class="mb-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $quotation->customer_name }}</p>
                                @if($quotation->customer_nuit)
                                    <p class="text-xs text-gray-500">NUIT: {{ $quotation->customer_nuit }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div>
                                <span class="text-[11px] uppercase text-gray-400 font-bold block">Valor Total</span>
                                <span class="text-lg font-black text-gray-900 dark:text-white">{{ number_format($quotation->total_amount, 2, ',', '.') }} MT</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('quotations.pdf', $quotation) }}"
                                    class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-bold">
                                    <i class="mdi mdi-file-pdf-box"></i> PDF
                                </a>
                                <a href="{{ route('quotations.show', $quotation) }}"
                                    class="px-3 py-1.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold">
                                    Abrir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $quotations->links() }}
            </div>
        @endif
    </div>
@endsection
