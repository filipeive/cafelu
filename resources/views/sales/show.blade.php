@extends('layouts.app')

@section('title', $sale->official_invoice_title . ' ' . ($sale->invoice_number ?? '#' . $sale->id))

@section('content')
    <div class="w-full space-y-6" x-data="{ showInvoiceModal: false }">
        <!-- Sale & Fiscal Document Header -->
        <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div class="space-y-2">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="px-3 py-1 rounded-lg bg-orange-100 dark:bg-orange-900/40 text-orange-700 dark:text-orange-300 text-xs font-black uppercase tracking-wider">
                            {{ $sale->official_invoice_title }}
                        </span>
                        <h4 class="text-2xl font-black text-gray-900 dark:text-white">
                            {{ $sale->invoice_number ?? '#' . str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                        </h4>
                        <span class="{{ get_status_class_staradmins($sale->status) }} px-3 py-1 rounded-full text-xs font-bold inline-flex items-center">
                            <i class="mdi mdi-circle-medium"></i>
                            {{ ucfirst($sale->status) }}
                        </span>
                    </div>

                    <div class="text-xs text-gray-600 dark:text-gray-300 flex flex-wrap items-center gap-4">
                        <span><strong>Cliente:</strong> {{ $sale->customer_name ?: 'Consumidor Final' }}</span>
                        @if($sale->customer_nuit)
                            <span><strong>NUIT:</strong> {{ $sale->customer_nuit }}</span>
                        @endif
                        @if($sale->customer_phone)
                            <span><strong>Tel:</strong> {{ $sale->customer_phone }}</span>
                        @endif
                        @if($sale->quotation)
                            <a href="{{ route('quotations.show', $sale->quotation) }}" class="text-sky-600 dark:text-sky-400 font-bold hover:underline">
                                <i class="mdi mdi-link-variant"></i> Origem: {{ $sale->quotation->quotation_number }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" @click="showInvoiceModal = true"
                        class="px-4 py-2.5 rounded-xl bg-sky-50 hover:bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 text-xs font-bold border border-sky-200 dark:border-sky-800 transition-all flex items-center gap-1.5">
                        <i class="mdi mdi-file-document-edit-outline text-base"></i>
                        <span>Emitir Factura / Editar NUIT</span>
                    </button>

                    <button type="button" onclick="window.printSaleRecibo({{ $sale->id }})"
                        class="px-4 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                        <i class="mdi mdi-printer text-base"></i>
                        <span>Talão 80mm</span>
                    </button>

                    <a href="{{ route('sales.invoice.pdf', $sale->id) }}"
                        class="px-4 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-bold shadow-sm transition-all flex items-center gap-1.5">
                        <i class="mdi mdi-file-pdf-box text-base"></i>
                        <span>PDF Oficial A4</span>
                    </a>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-5 pt-5 border-t border-gray-200 dark:border-gray-700 text-xs">
                <div>
                    <span class="text-gray-400 uppercase font-bold block">Data de Emissão</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-bold block">Método de Pagamento</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white inline-flex items-center gap-1.5">
                        <i class="mdi {{ get_payment_icon_mdi($sale->payment_method) }} text-green-500"></i>
                        {{ ucfirst($sale->payment_method) }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-bold block">Operador / Caixa</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $sale->user->name ?? 'Sistema' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase font-bold block">Regime Fiscal</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">
                        {{ ($sale->tax_regime ?? 'normal') === 'exempt' ? 'Isento (0%)' : 'IVA ' . number_format($sale->tax_rate ?? 16, 0) . '%' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Sale Items -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h5 class="text-base font-bold text-gray-900 dark:text-white">{{ __('messages.sale_items') }}</h5>
                <span class="text-xs text-gray-500">{{ $sale->saleItems->count() }} itens</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">{{ __('messages.product') }}</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-center">{{ __('messages.quantity') }}</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-right">{{ __('messages.unit_price') }}</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase text-right">{{ __('messages.total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($sale->saleItems as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($item->product && $item->product->image_path)
                                            <img src="{{ Storage::url($item->product->image_path) }}"
                                                class="w-11 h-11 rounded-lg object-cover shadow-sm"
                                                alt="{{ $item->product->name }}">
                                        @endif
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $item->product->name ?? 'Item' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-gray-900 dark:text-white">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-right text-gray-700 dark:text-gray-300">MZN {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">MZN {{ number_format($item->unit_price * $item->quantity, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 text-sm">
                        @if(($sale->subtotal ?? 0) > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-gray-600 dark:text-gray-400">Subtotal Líquido:</td>
                                <td class="px-6 py-2 text-right font-semibold text-gray-800 dark:text-gray-200">MZN {{ number_format($sale->subtotal, 2, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if(($sale->tax_amount ?? 0) > 0)
                            <tr>
                                <td colspan="3" class="px-6 py-2 text-right text-gray-600 dark:text-gray-400">IVA ({{ number_format($sale->tax_rate ?? 16, 0) }}%):</td>
                                <td class="px-6 py-2 text-right font-semibold text-gray-800 dark:text-gray-200">MZN {{ number_format($sale->tax_amount, 2, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th colspan="3" class="px-6 py-4 text-right text-gray-800 dark:text-gray-200 font-black">{{ __('messages.total') }}:</th>
                            <th class="px-6 py-4 text-right text-xl font-black text-orange-600 dark:text-orange-400">
                                MZN {{ number_format($sale->total_amount, 2, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Payment Details (if mixed payment) -->
        @if ($sale->payment_method == 'mixed')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 max-w-lg">
                <div class="p-6">
                    <h6 class="text-base font-bold text-gray-900 dark:text-white mb-4">{{ __('messages.payment_details') }}</h6>
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($sale->cash_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-cash mr-2 text-green-500"></i>{{ __('messages.cash') }}
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->cash_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if ($sale->card_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-credit-card mr-2 text-blue-500"></i>{{ __('messages.card') }}
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->card_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if ($sale->mpesa_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-phone mr-2 text-yellow-500"></i>{{ __('messages.mpesa') }}
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->mpesa_amount, 2) }}</td>
                                </tr>
                            @endif
                            @if ($sale->emola_amount > 0)
                                <tr>
                                    <td class="py-2 text-gray-700 dark:text-gray-300">
                                        <i class="mdi mdi-wallet mr-2 text-cyan-500"></i>{{ __('messages.emola') }}
                                    </td>
                                    <td class="py-2 text-right font-medium text-gray-900 dark:text-white">MZN {{ number_format($sale->emola_amount, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="border-t-2 border-gray-300 dark:border-gray-600">
                                <th class="py-2 text-gray-900 dark:text-white">{{ __('messages.total_paid') }}</th>
                                <th class="py-2 text-right text-green-600 dark:text-green-400">MZN {{ number_format($sale->getTotalPayments(), 2) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Actions Footer -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('sales.index') }}" class="px-5 py-2.5 rounded-xl bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-sm font-bold transition-colors flex items-center gap-2">
                <i class="mdi mdi-arrow-left"></i>
                {{ __('messages.back_to_sales') }}
            </a>
        </div>

        <!-- Modal: Emitir Factura / Editar Dados Fiscais do Cliente -->
        <div x-show="showInvoiceModal" x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4"
            @keydown.escape.window="showInvoiceModal = false">
            <div @click.away="showInvoiceModal = false"
                class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full border border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden">
                <div class="p-5 bg-gradient-to-r from-sky-600 to-blue-700 text-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <i class="mdi mdi-file-document-edit-outline text-2xl"></i>
                        <div>
                            <h3 class="text-base font-black">Configurar Factura / Recibo & NUIT</h3>
                            <p class="text-xs text-sky-100">Converta este registo em Factura-Recibo (FR), Factura Comercial (FT) ou Recibo</p>
                        </div>
                    </div>
                    <button type="button" @click="showInvoiceModal = false" class="text-white/80 hover:text-white">
                        <i class="mdi mdi-close text-xl"></i>
                    </button>
                </div>

                <form method="POST" action="{{ route('sales.update-invoice', $sale->id) }}" class="p-6 space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                            Tipo de Documento Oficial
                        </label>
                        <select name="invoice_type" required
                            class="w-full py-2.5 px-3 text-sm font-semibold rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            <option value="receipt" {{ ($sale->invoice_type ?? 'receipt') === 'receipt' ? 'selected' : '' }}>Recibo de Pagamento (RC)</option>
                            <option value="cash_invoice" {{ ($sale->invoice_type ?? '') === 'cash_invoice' ? 'selected' : '' }}>Factura-Recibo (FR)</option>
                            <option value="invoice" {{ ($sale->invoice_type ?? '') === 'invoice' ? 'selected' : '' }}>Factura Comercial (FT)</option>
                            <option value="proforma" {{ ($sale->invoice_type ?? '') === 'proforma' ? 'selected' : '' }}>Factura Proforma (FP)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Nome do Cliente / Empresa
                            </label>
                            <input type="text" name="customer_name" value="{{ $sale->customer_name }}"
                                placeholder="Ex: Consumidor Final ou Empresa"
                                class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                NUIT do Cliente
                            </label>
                            <input type="text" name="customer_nuit" value="{{ $sale->customer_nuit }}"
                                placeholder="Ex: 400123456"
                                class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Telefone
                            </label>
                            <input type="text" name="customer_phone" value="{{ $sale->customer_phone }}"
                                placeholder="+258 84..."
                                class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                                Regime de IVA
                            </label>
                            <select name="tax_regime"
                                class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                                <option value="normal" {{ ($sale->tax_regime ?? 'normal') === 'normal' ? 'selected' : '' }}>IVA 16% (Incluído)</option>
                                <option value="exempt" {{ ($sale->tax_regime ?? '') === 'exempt' ? 'selected' : '' }}>Isento de IVA (0%)</option>
                                <option value="simplified" {{ ($sale->tax_regime ?? '') === 'simplified' ? 'selected' : '' }}>Simplificado (5%)</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase mb-1">
                            Endereço do Cliente
                        </label>
                        <input type="text" name="customer_address" value="{{ $sale->customer_address }}"
                            placeholder="Morada fiscal do cliente..."
                            class="w-full py-2 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="showInvoiceModal = false"
                            class="px-4 py-2 rounded-xl border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-xs font-bold">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-black shadow-md">
                            Guardar e Numerar Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
