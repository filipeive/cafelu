@extends('layouts.app')

@section('title', 'Certificado de Licenciamento - ' . $tenant->name)

@php
    $targetPhone = preg_replace('/[^0-9]/', '', $tenant->phone ?? '');
    if (strlen($targetPhone) === 9 && str_starts_with($targetPhone, '8')) {
        $targetPhone = '258' . $targetPhone;
    }
    $whatsappMsg = "Olá {$tenant->name}, a sua licença do RestPOS (" . strtoupper($license->license_type) . ") foi emitida com sucesso!\n\nChave Serial de Ativação:\n{$license->key_code}\n\nValidade: " . ($license->expires_at ? $license->expires_at->format('d/m/Y') : 'Vitalício') . "\nAtivar em: " . url('/license/status');
@endphp

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    copiedKey: false,
    copiedMsg: false,
    copyText(text, type) {
        navigator.clipboard.writeText(text);
        if (type === 'key') {
            this.copiedKey = true;
            setTimeout(() => this.copiedKey = false, 2500);
        } else {
            this.copiedMsg = true;
            setTimeout(() => this.copiedMsg = false, 2500);
        }
    }
}">
    <!-- Actions Toolbar (Hidden on print) -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700/60 rounded-3xl p-4 shadow-xs print:hidden">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tenants.show', $tenant) }}" 
               class="px-3.5 py-2 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 text-xs font-bold transition flex items-center gap-1.5">
                <i class="mdi mdi-arrow-left"></i>
                <span>Voltar</span>
            </a>
            <span class="text-xs text-gray-500">Restaurante: <strong class="text-gray-900 dark:text-white">{{ $tenant->name }}</strong></span>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <button type="button" @click="copyText('{{ $license->key_code }}', 'key')" 
                    class="px-3.5 py-2 rounded-xl bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 text-white font-bold text-xs transition flex items-center gap-1.5">
                <i class="mdi" :class="copiedKey ? 'mdi-check text-emerald-400' : 'mdi-content-copy'"></i>
                <span x-text="copiedKey ? 'Chave Copiada!' : 'Copiar Serial'"></span>
            </button>

            @if($targetPhone)
                <a href="https://wa.me/{{ $targetPhone }}?text={{ rawurlencode($whatsappMsg) }}" target="_blank"
                   class="px-3.5 py-2 rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/40 font-bold text-xs transition flex items-center gap-1.5">
                    <i class="mdi mdi-whatsapp text-sm"></i>
                    <span>Enviar no WhatsApp</span>
                </a>
            @endif

            <a href="{{ route('admin.tenants.certificate-pdf', $tenant) }}" 
               class="px-4 py-2 rounded-xl bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-xs">
                <i class="mdi mdi-file-pdf-box text-base"></i>
                <span>Descarregar PDF</span>
            </a>

            <button type="button" onclick="window.print()" 
                    class="px-3.5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs transition flex items-center gap-1.5">
                <i class="mdi mdi-printer"></i>
                <span>Imprimir</span>
            </button>
        </div>
    </div>

    <!-- Official Certificate Frame -->
    <div class="bg-white text-gray-900 rounded-3xl border-4 border-double border-amber-600/40 p-8 sm:p-12 shadow-2xl relative overflow-hidden print:p-6 print:border-2 print:shadow-none print:m-0">
        <!-- Background watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none select-none">
            <i class="mdi mdi-shield-check text-[450px]"></i>
        </div>

        <div class="relative z-10 space-y-8 text-center">
            <!-- Header -->
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 text-amber-700 font-black text-[11px] uppercase tracking-widest">
                    <i class="mdi mdi-certificate"></i>
                    <span>Certificado Oficial de Licenciamento de Software</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black text-gray-950 tracking-tight">
                    RestPOS Restaurant Management System
                </h1>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">
                    Documento de Autenticidade, Conformidade e Cessão de Uso de Software
                </p>
            </div>

            <div class="w-24 h-0.5 bg-gradient-to-r from-transparent via-amber-600 to-transparent mx-auto"></div>

            <!-- Body Statement -->
            <div class="max-w-2xl mx-auto space-y-4 text-xs sm:text-sm text-gray-700 leading-relaxed text-justify">
                <p>
                    Certifica-se pelo presente documento que o estabelecimento comercial abaixo qualificado está devidamente licenciado e autorizado a utilizar a plataforma de Gestão de Restauração e Frente de Caixa <strong>RestPOS (v1.3)</strong>, em conformidade com as cláusulas de licenciamento e limites operacionais estipulados.
                </p>
            </div>

            <!-- Beneficiary & License Details Box -->
            <div class="bg-amber-50/50 border border-amber-200/80 rounded-2xl p-6 text-left grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-[10px] font-black uppercase text-amber-800 tracking-wider">Entidade Licenciada (Cliente):</span>
                    <h3 class="text-base font-black text-gray-900 mt-0.5">{{ $tenant->name }}</h3>
                    <p class="text-gray-600 mt-0.5 font-mono">
                        NUIT: {{ $tenant->nuit ?? 'Registado' }}<br>
                        Localização: {{ $tenant->address ?? $tenant->city }} ({{ $tenant->city }})
                    </p>
                </div>

                <div>
                    <span class="text-[10px] font-black uppercase text-amber-800 tracking-wider">Plano & Modo:</span>
                    <h3 class="text-base font-black text-gray-900 mt-0.5 uppercase">{{ $license->license_type }}</h3>
                    <p class="text-gray-600 mt-0.5">
                        Instalação: {{ strtoupper($tenant->installation_mode) }}<br>
                        Capacidade: {{ $license->max_tables }} Mesas • {{ $license->max_users }} Operadores
                    </p>
                </div>

                <div class="sm:col-span-2 pt-3 border-t border-amber-200/60 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <span class="text-[10px] font-black uppercase text-amber-800 tracking-wider">Chave Serial Oficial:</span>
                        <div class="text-base sm:text-lg font-black font-mono text-gray-950 mt-0.5 tracking-wider bg-white px-3 py-1.5 rounded-lg border border-amber-300 inline-block">
                            {{ $license->key_code }}
                        </div>
                    </div>
                    <div class="text-left sm:text-right">
                        <span class="text-[10px] font-black uppercase text-amber-800 tracking-wider">Validade da Licença:</span>
                        <div class="text-sm font-bold text-gray-900 mt-0.5">
                            @if($license->expires_at)
                                {{ $license->expires_at->format('d/m/Y') }}
                                <span class="text-[11px] text-emerald-700 font-normal">({{ $tenant->daysRemaining() }} dias restantes)</span>
                            @else
                                <span class="text-emerald-700">Licença Vitalícia</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signatures & Validation Footer -->
            <div class="pt-8 grid grid-cols-2 gap-8 text-center text-xs">
                <div>
                    <div class="w-48 border-b border-gray-400 mx-auto mb-2"></div>
                    <span class="font-bold text-gray-900 block">Pelo Fornecedor da Plataforma</span>
                    <span class="text-[10px] text-gray-500 font-mono">RestPOS Systems Mozambique</span>
                </div>
                <div>
                    <div class="w-48 border-b border-gray-400 mx-auto mb-2"></div>
                    <span class="font-bold text-gray-900 block">Pelo Restaurante / Cliente</span>
                    <span class="text-[10px] text-gray-500 font-mono">{{ $tenant->name }}</span>
                </div>
            </div>

            <div class="pt-4 text-[10px] text-gray-400 font-mono">
                Documento emitido eletronicamente em {{ now()->format('d/m/Y H:i:s') }} • Código de Verificação: {{ substr($license->key_hash ?? md5($license->key_code), 0, 16) }}
            </div>
        </div>
    </div>
</div>
@endsection

