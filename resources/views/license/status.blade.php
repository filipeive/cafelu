@extends('layouts.app')

@section('title', 'Estado da Licença da Instalação')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-2.5">
                <i class="mdi mdi-certificate-outline text-orange-500"></i>
                <span>Licenciamento do Sistema</span>
            </h1>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                Informações de ativação, validade e limites operacionais da sua instalação.
            </p>
        </div>

        @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
        <a href="{{ route('admin.licenses.index') }}" 
           class="px-4 py-2 rounded-xl bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 text-white text-xs font-bold transition-all flex items-center gap-2">
            <i class="mdi mdi-shield-crown-outline text-base"></i>
            <span>Painel do Dono (Gerar Seriais)</span>
        </a>
        @endif
    </div>

    <!-- Active License Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/60 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-gradient-to-r from-orange-50/50 via-transparent to-transparent dark:from-orange-950/20">
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400">Instalação Licenciada</span>
                <h2 class="text-xl font-black text-gray-900 dark:text-white mt-0.5">
                    {{ $currentLicense->business_name ?? config('app.name', 'CAFELU') }}
                </h2>
                <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                    <span>Chave Serial:</span>
                    <span class="font-mono font-bold text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md">
                        {{ $currentLicense->key_code }}
                    </span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $currentLicense->key_code }}'); alert('Serial copiado!');"
                            class="text-gray-400 hover:text-orange-500 cursor-pointer" title="Copiar">
                        <i class="mdi mdi-content-copy"></i>
                    </button>
                </div>
            </div>

            <div class="text-left sm:text-right">
                @if($currentLicense->isValid())
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-200/60">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Licença Ativa
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300 border border-rose-200/60">
                        <i class="mdi mdi-alert-circle"></i>
                        Expirada / Suspensa
                    </span>
                @endif
                <p class="text-[11px] text-gray-400 mt-1 uppercase font-semibold">
                    Plano {{ $currentLicense->license_type }}
                </p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Validade</span>
                @if($currentLicense->license_type === 'lifetime')
                    <p class="text-base font-black text-emerald-600 dark:text-emerald-400">Vitalícia</p>
                    <span class="text-xs text-gray-500">Sem data limite de renovação</span>
                @elseif($currentLicense->expires_at)
                    <p class="text-base font-black text-gray-900 dark:text-white">
                        {{ $currentLicense->expires_at->format('d/m/Y') }}
                    </p>
                    <span class="text-xs {{ $currentLicense->daysRemaining() <= 15 ? 'text-amber-500 font-bold' : 'text-gray-500' }}">
                        {{ $currentLicense->daysRemaining() }} dias restantes
                    </span>
                @else
                    <p class="text-base font-black text-gray-900 dark:text-white">Sem expiração</p>
                @endif
            </div>

            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Capacidade Operacional</span>
                <p class="text-base font-black text-gray-900 dark:text-white">
                    {{ $currentLicense->max_tables }} Mesas & {{ $currentLicense->max_users }} Utilizadores
                </p>
                <span class="text-xs text-gray-500">Limite de registros simultâneos</span>
            </div>

            <div class="space-y-1">
                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Fingerprint do Servidor</span>
                <p class="text-sm font-mono font-black text-gray-800 dark:text-gray-200 tracking-wider">
                    {{ $fingerprint }}
                </p>
                <span class="text-xs text-gray-500">Assinatura de segurança local</span>
            </div>
        </div>
    </div>

    <!-- Activation Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700/60 shadow-xs p-6 space-y-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-orange-50 dark:bg-orange-950/40 text-orange-500 flex items-center justify-center text-xl">
                <i class="mdi mdi-key-plus"></i>
            </div>
            <div>
                <h3 class="text-base font-black text-gray-900 dark:text-white">Ativar Nova Chave de Licença</h3>
                <p class="text-xs text-gray-500">Insira a chave serial fornecida pelo fornecedor do sistema para renovar ou estender.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('license.activate') }}" class="space-y-4 max-w-lg">
            @csrf
            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                    Chave Serial *
                </label>
                <input type="text" name="key_code" required
                       placeholder="CAFE-XXXX-XXXX-XXXX-XXXX"
                       class="w-full text-sm font-mono tracking-wider font-bold rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white px-4 py-3 focus:ring-2 focus:ring-orange-500 uppercase">
            </div>

            <button type="submit" 
                    class="px-6 py-2.5 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold transition-all flex items-center gap-2 shadow-sm shadow-orange-500/25 cursor-pointer">
                <i class="mdi mdi-check-circle-outline text-base"></i>
                <span>Ativar Chave</span>
            </button>
        </form>
    </div>
</div>
@endsection
