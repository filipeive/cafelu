@extends('layouts.app')

@section('title', 'Cotação ' . $quotation->quotation_number)
@section('title-icon', 'mdi-file-document-outline')
@section('page-title', 'Cotação ' . $quotation->quotation_number)

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('dashboard') }}" class="hover:text-sky-600 transition-colors">Início</a>
        <i class="mdi mdi-chevron-right text-gray-400 mx-1"></i>
    </li>
    <li class="flex items-center">
        <a href="{{ route('quotations.index') }}" class="hover:text-sky-600 transition-colors">Cotações</a>
        <i class="mdi mdi-chevron-right text-gray-400 mx-1"></i>
    </li>
    <li class="text-gray-800 dark:text-gray-200 font-semibold">{{ $quotation->quotation_number }}</li>
@endsection

@section('actions')
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('quotations.index') }}"
            class="inline-flex items-center gap-1.5 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 font-semibold py-2 px-3.5 rounded-xl text-xs shadow-sm transition-all">
            <i class="mdi mdi-arrow-left"></i> Voltar
        </a>

        <a href="{{ route('quotations.pdf', $quotation) }}"
            class="inline-flex items-center gap-1.5 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl text-xs shadow-sm transition-all">
            <i class="mdi mdi-file-pdf-box text-base"></i> Descarregar PDF A4
        </a>

        @if($quotation->customer_phone)
            @php
                $cleanPhone = preg_replace('/[^0-9]/', '', $quotation->customer_phone);
                if (strlen($cleanPhone) === 9) {
                    $cleanPhone = '258' . $cleanPhone;
                }
                $waMessage = urlencode("Olá {$quotation->customer_name}, segue os dados da Cotação {$quotation->quotation_number} no valor total de " . number_format($quotation->total_amount, 2, ',', '.') . " MT.");
            @endphp
            <a href="https://wa.me/{{ $cleanPhone }}?text={{ $waMessage }}" target="_blank"
                class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl text-xs shadow-sm transition-all">
                <i class="mdi mdi-whatsapp text-base"></i> Enviar WhatsApp
            </a>
        @endif
    </div>
@endsection

@section('content')
    @php
        $statusBadges = [
            'draft'     => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200',
            'sent'      => 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-300',
            'approved'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300',
            'rejected'  => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300',
            'converted' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300',
        ];
        $badgeClass = $statusBadges[$quotation->status] ?? $statusBadges['draft'];
    @endphp

    <div x-data="{ showConvertModal: false, invoiceType: 'cash_invoice', paymentMethod: 'cash' }" class="space-y-6">
        <!-- Top Action Banner -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                    <i class="mdi mdi-file-document-check-outline text-2xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-lg font-black text-gray-900 dark:text-white">{{ $quotation->quotation_number }}</h2>
                        <span class="px-3 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                            {{ $quotation->status_label }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Emitida em {{ $quotation->date?->format('d/m/Y') }} por {{ $quotation->user->name ?? 'Sistema' }}
                        @if($quotation->valid_until)
                            &bull; Válida até {{ $quotation->valid_until->format('d/m/Y') }}
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($quotation->status !== 'converted')
                    <form method="POST" action="{{ route('quotations.status', $quotation) }}" class="inline-flex items-center gap-1.5">
                        @csrf
                        @method('PATCH')
                        @if($quotation->status === 'draft')
                            <button type="submit" name="status" value="sent"
                                class="px-3 py-2 rounded-xl bg-sky-50 hover:bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 text-xs font-bold transition-colors">
                                <i class="mdi mdi-send-outline"></i> Marcar Enviada
                            </button>
                        @endif
                        @if($quotation->status !== 'approved')
                            <button type="submit" name="status" value="approved"
                                class="px-3 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-bold transition-colors">
                                <i class="mdi mdi-check-decagram-outline"></i> Aprovar Cotação
                            </button>
                        @endif
                        @if($quotation->status !== 'rejected')
                            <button type="submit" name="status" value="rejected"
                                class="px-3 py-2 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs font-bold transition-colors">
                                <i class="mdi mdi-close-circle-outline"></i> Rejeitar
                            </button>
                        @endif
                    </form>

                    @if($quotation->canBeConverted())
                        <button type="button" @click="showConvertModal = true"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-black py-2.5 px-4 rounded-xl text-xs shadow-md transition-all">
                            <i class="mdi mdi-receipt-text-check-outline text-base"></i>
                            <span>Converter em Factura / Recibo</span>
                        </button>
                    @endif
                @elseif($quotation->convertedSale)
                    <a href="{{ route('sales.show', $quotation->convertedSale) }}"
                        class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-sm transition-all">
                        <i class="mdi mdi-open-in-new"></i>
                        <span>Ver {{ $quotation->convertedSale->official_invoice_title }} ({{ $quotation->convertedSale->invoice_number }})</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Document Preview Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <!-- Header Empresa & Cliente -->
            <div class="p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-orange-600 dark:text-orange-400 block mb-1">Entidade Emitente</span>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ current_tenant()?->name ?? config('app.name', 'RestPOS') }}</h3>
                    @if(current_tenant()?->nuit)
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5 font-semibold">NUIT: {{ current_tenant()->nuit }}</p>
                    @endif
                    @if(current_tenant()?->address)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ current_tenant()->address }}</p>
                    @endif
                    @if(current_tenant()?->phone)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tel: {{ current_tenant()->phone }}</p>
                    @endif
                </div>

                <div class="md:text-right">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-sky-600 dark:text-sky-400 block mb-1">Destinatário / Cliente</span>
                    <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $quotation->customer_name }}</h3>
                    @if($quotation->customer_nuit)
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5 font-semibold">NUIT: {{ $quotation->customer_nuit }}</p>
                    @endif
                    @if($quotation->customer_phone)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Tel: {{ $quotation->customer_phone }}</p>
                    @endif
                    @if($quotation->customer_email)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $quotation->customer_email }}</p>
                    @endif
                    @if($quotation->customer_address)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $quotation->customer_address }}</p>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/60">
                        <tr>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">#</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Descrição do Item / Serviço</th>
                            <th class="px-6 py-3.5 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Qtd</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Preço Unit.</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Desconto</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">IVA</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Total Linha</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60">
                        @foreach($quotation->items as $idx => $item)
                            <tr>
                                <td class="px-6 py-4 text-xs text-gray-400 font-bold">{{ $idx + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->item_name }}</div>
                                    @if($item->description)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $item->description }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                                    {{ rtrim(rtrim(number_format($item->quantity, 2, ',', '.'), '0'), ',') }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-700 dark:text-gray-300">
                                    {{ number_format($item->unit_price, 2, ',', '.') }} MT
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-gray-500">
                                    {{ $item->discount_amount > 0 ? '-' . number_format($item->discount_amount, 2, ',', '.') . ' MT' : '—' }}
                                </td>
                                <td class="px-6 py-4 text-right text-xs text-gray-500">
                                    {{ number_format($item->tax_amount, 2, ',', '.') }} MT
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-black text-gray-900 dark:text-white">
                                    {{ number_format($item->total, 2, ',', '.') }} MT
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals & Notes Footer -->
            <div class="p-6 md:p-8 bg-gray-50/60 dark:bg-gray-900/40 border-t border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-12 gap-6">
                <div class="md:col-span-7 space-y-4">
                    @if($quotation->notes)
                        <div>
                            <h4 class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Observações</h4>
                            <p class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $quotation->notes }}</p>
                        </div>
                    @endif
                    @if($quotation->terms_conditions)
                        <div>
                            <h4 class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Termos & Condições</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $quotation->terms_conditions }}</p>
                        </div>
                    @endif
                </div>

                <div class="md:col-span-5">
                    <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>Subtotal Líquido:</span>
                            <span class="font-bold">{{ number_format($quotation->subtotal, 2, ',', '.') }} MT</span>
                        </div>
                        @if($quotation->discount_amount > 0)
                            <div class="flex justify-between text-amber-600">
                                <span>Descontos:</span>
                                <span class="font-bold">- {{ number_format($quotation->discount_amount, 2, ',', '.') }} MT</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-600 dark:text-gray-300">
                            <span>IVA ({{ $quotation->tax_regime === 'exempt' ? 'Isento 0%' : number_format($quotation->tax_rate, 0) . '%' }}):</span>
                            <span class="font-bold">{{ number_format($quotation->tax_amount, 2, ',', '.') }} MT</span>
                        </div>
                        <div class="flex justify-between items-baseline border-t border-gray-200 dark:border-gray-700 pt-2.5 text-base font-black text-gray-900 dark:text-white">
                            <span>TOTAL DA PROPOSTA:</span>
                            <span class="text-xl text-orange-600 dark:text-orange-400">{{ number_format($quotation->total_amount, 2, ',', '.') }} MT</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Converter em Factura / Recibo -->
        <div x-show="showConvertModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            @keydown.escape.window="showConvertModal = false">
            <div @click.away="showConvertModal = false"
                class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full border border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden">
                <div class="p-5 bg-gradient-to-r from-orange-500 to-amber-600 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="mdi mdi-receipt-text-check-outline text-2xl"></i>
                        <div>
                            <h3 class="text-base font-black">Converter Cotação em Factura / Venda</h3>
                            <p class="text-xs text-orange-100">{{ $quotation->quotation_number }} &bull; {{ number_format($quotation->total_amount, 2, ',', '.') }} MT</p>
                        </div>
                    </div>
                    <button type="button" @click="showConvertModal = false" class="text-white/80 hover:text-white">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('quotations.convert', $quotation) }}" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">
                            Tipo de Documento Fiscal a Emitir
                        </label>
                        <select name="invoice_type" x-model="invoiceType" required
                            class="w-full py-2.5 px-3 text-sm font-semibold rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            <option value="cash_invoice">Factura-Recibo (FR) — Pagamento Imediato</option>
                            <option value="invoice">Factura Comercial (FT) — Com Prazo / Conta Corrente</option>
                            <option value="receipt">Recibo de Pagamento (RC)</option>
                            <option value="proforma">Factura Proforma (FP)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">
                            Método de Pagamento
                        </label>
                        <select name="payment_method" x-model="paymentMethod" required
                            class="w-full py-2.5 px-3 text-sm font-semibold rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            <option value="cash">Numerário (Dinheiro)</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">e-Mola</option>
                            <option value="card">POS / Cartão Bancário</option>
                            <option value="transfer">Transferência Bancária / BIM / BCI</option>
                            <option value="credit">A Crédito / Pagar Depois (Gera Dívida)</option>
                        </select>
                    </div>

                    <div x-show="invoiceType === 'invoice' || paymentMethod === 'credit'">
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1.5">
                            Data de Vencimento da Factura
                        </label>
                        <input type="date" name="due_date" value="{{ now()->addDays(30)->format('Y-m-d') }}"
                            class="w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                    </div>

                    <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-300 flex items-start gap-2">
                        <i class="mdi mdi-information-outline text-base mt-0.5"></i>
                        <span>Ao confirmar, o sistema irá gerar o documento oficial numerado, atualizar o stock dos produtos vinculados e disponibilizar tanto o PDF A4 como o talão térmico 80mm.</span>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showConvertModal = false"
                            class="px-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-bold">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white text-xs font-black shadow-md">
                            Confirmar e Emitir Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

