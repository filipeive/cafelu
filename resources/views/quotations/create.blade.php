@extends('layouts.app')

@section('title', 'Emitir Nova Cotação')
@section('title-icon', 'mdi-file-document-plus-outline')
@section('page-title', 'Nova Cotação / Proforma')

@section('breadcrumbs')
    <li class="flex items-center">
        <a href="{{ route('dashboard') }}" class="hover:text-sky-600 transition-colors">Início</a>
        <i class="mdi mdi-chevron-right text-gray-400 mx-1"></i>
    </li>
    <li class="flex items-center">
        <a href="{{ route('quotations.index') }}" class="hover:text-sky-600 transition-colors">Cotações</a>
        <i class="mdi mdi-chevron-right text-gray-400 mx-1"></i>
    </li>
    <li class="text-gray-800 dark:text-gray-200 font-semibold">Emitir Cotação ({{ $nextNumber }})</li>
@endsection

@section('actions')
    <a href="{{ route('quotations.index') }}"
        class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-300 dark:border-gray-600 font-semibold py-2 px-4 rounded-xl text-sm shadow-sm transition-all">
        <i class="mdi mdi-arrow-left"></i>
        <span>Voltar às Cotações</span>
    </a>
@endsection

@section('content')
    <div x-data="quotationForm()" class="space-y-6">
        <form method="POST" action="{{ route('quotations.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Left Column: Dados do Cliente e Produtos -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Identificação do Cliente -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
                            <div class="flex items-center gap-2.5">
                                <span class="w-9 h-9 rounded-xl bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 flex items-center justify-center">
                                    <i class="mdi mdi-account-tie-outline text-xl"></i>
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Dados do Cliente / Entidade</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Selecione um cliente registado ou preencha manualmente para a proposta</p>
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-sky-50 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 text-xs font-black">
                                {{ $nextNumber }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-6">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                    Cliente Registado (Opcional)
                                </label>
                                <select name="client_id" x-model="selectedClientId" @change="fillClientData()"
                                    class="w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                                    <option value="">-- Cliente Avulso / Digitar Manualmente --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}"
                                            data-name="{{ $client->name }}"
                                            data-nuit="{{ $client->nuit ?? '' }}"
                                            data-phone="{{ $client->phone ?? '' }}"
                                            data-email="{{ $client->email ?? '' }}"
                                            data-address="{{ $client->address ?? '' }}">
                                            {{ $client->name }} {{ $client->nuit ? '(NUIT: ' . $client->nuit . ')' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-6">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                    Nome do Cliente / Empresa <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" x-model="customer.name" required
                                    placeholder="Ex: Empresa ABC, Lda ou Nome do Cliente"
                                    class="w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                    NUIT do Cliente
                                </label>
                                <input type="text" name="customer_nuit" x-model="customer.nuit"
                                    placeholder="Ex: 400123456"
                                    class="w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                    Telefone / WhatsApp
                                </label>
                                <input type="text" name="customer_phone" x-model="customer.phone"
                                    placeholder="Ex: +258 84 000 0000"
                                    class="w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>

                            <div class="md:col-span-4">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                    E-mail
                                </label>
                                <input type="email" name="customer_email" x-model="customer.email"
                                    placeholder="cliente@empresa.co.mz"
                                    class="w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>

                            <div class="md:col-span-12">
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                    Endereço / Local do Evento ou Entrega
                                </label>
                                <input type="text" name="customer_address" x-model="customer.address"
                                    placeholder="Av. Julius Nyerere, Maputo..."
                                    class="w-full py-2.5 px-3 text-sm rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>
                        </div>
                    </div>

                    <!-- Itens da Cotação -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 dark:border-gray-700 pb-4 mb-5">
                            <div class="flex items-center gap-2.5">
                                <span class="w-9 h-9 rounded-xl bg-sky-50 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                                    <i class="mdi mdi-format-list-checks text-xl"></i>
                                </span>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">Itens & Serviços da Proposta</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Adicione pratos do menu, bebidas ou serviços personalizados de catering</p>
                                </div>
                            </div>

                            <button type="button" @click="addItem()"
                                class="inline-flex items-center gap-1.5 bg-sky-600 hover:bg-sky-700 text-white font-bold py-2 px-3.5 rounded-xl text-xs shadow-sm transition-all">
                                <i class="mdi mdi-plus text-base"></i> Adicionar Linha
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 space-y-3">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                                        <!-- Catálogo de Produtos -->
                                        <div class="md:col-span-4">
                                            <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                                Produto do Menu (Opcional)
                                            </label>
                                            <select :name="`items[${index}][product_id]`" x-model="item.product_id" @change="onSelectProduct(index)"
                                                class="w-full py-2 px-2.5 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                                                <option value="">-- Item / Serviço Personalizado --</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-name="{{ $product->name }}"
                                                        data-price="{{ $product->price }}">
                                                        {{ $product->name }} ({{ number_format($product->price, 2, ',', '.') }} MT)
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Designação do Item -->
                                        <div class="md:col-span-4">
                                            <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">
                                                Designação na Cotação <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" :name="`items[${index}][item_name]`" x-model="item.item_name" required
                                                placeholder="Nome do prato, bebida ou serviço"
                                                class="w-full py-2 px-2.5 text-xs rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100">
                                        </div>

                                        <!-- Quantidade -->
                                        <div class="md:col-span-1">
                                            <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Qtd</label>
                                            <input type="number" step="0.01" min="0.01" :name="`items[${index}][quantity]`" x-model.number="item.quantity" required
                                                class="w-full py-2 px-2 text-xs text-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 font-bold">
                                        </div>

                                        <!-- Preço Unitário -->
                                        <div class="md:col-span-2">
                                            <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase mb-1">Preço Unit. (MT)</label>
                                            <input type="number" step="0.01" min="0" :name="`items[${index}][unit_price]`" x-model.number="item.unit_price" required
                                                class="w-full py-2 px-2.5 text-xs text-right rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 font-bold">
                                        </div>

                                        <!-- Remover -->
                                        <div class="md:col-span-1 flex justify-end">
                                            <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                                class="p-2 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 transition-colors"
                                                title="Remover Linha">
                                                <i class="mdi mdi-trash-can-outline text-base"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center pt-1">
                                        <div class="md:col-span-6">
                                            <input type="text" :name="`items[${index}][description]`" x-model="item.description"
                                                placeholder="Detalhes adicionais / observações deste item (opcional)..."
                                                class="w-full py-1.5 px-2.5 text-xs rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300">
                                        </div>
                                        <div class="md:col-span-3 flex items-center gap-2">
                                            <span class="text-[11px] text-gray-500 font-semibold">Desconto (MT):</span>
                                            <input type="number" step="0.01" min="0" :name="`items[${index}][discount]`" x-model.number="item.discount"
                                                class="w-28 py-1 px-2 text-xs text-right rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                                        </div>
                                        <div class="md:col-span-3 text-right">
                                            <span class="text-xs text-gray-500">Subtotal Linha:</span>
                                            <span class="text-sm font-black text-gray-900 dark:text-white ml-1" x-text="formatMoney(lineTotal(item)) + ' MT'"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Notas e Termos -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                Notas para o Cliente
                            </label>
                            <textarea name="notes" rows="3" placeholder="Ex: Proposta inclui serviço de buffet e equipa de atendimento..."
                                class="w-full p-3 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">{{ old('notes') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">
                                Termos & Condições de Pagamento
                            </label>
                            <textarea name="terms_conditions" rows="3"
                                class="w-full p-3 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">{{ old('terms_conditions', '50% de adjudicação na confirmação do pedido e 50% na entrega/conclusão. Cotação válida pelo período indicado.') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Datas, Regime de IVA e Resumo Financeiro -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-4">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-3 flex items-center gap-2">
                            <i class="mdi mdi-calendar-clock text-orange-500"></i>
                            Datas & Regime Fiscal (IVA)
                        </h3>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">Data Emissão</label>
                                <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                                    class="w-full py-2 px-3 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">Válida Até</label>
                                <input type="date" name="valid_until" value="{{ old('valid_until', now()->addDays(15)->format('Y-m-d')) }}"
                                    class="w-full py-2 px-3 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">Regime de IVA</label>
                            <select name="tax_regime" x-model="taxRegime" @change="onRegimeChange()"
                                class="w-full py-2.5 px-3 text-xs font-semibold rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                                <option value="normal">Regime Normal (IVA 16%)</option>
                                <option value="exempt">Isento de IVA (0%)</option>
                                <option value="simplified">Regime Simplificado / ISPC (5%)</option>
                            </select>
                        </div>

                        <div x-show="taxRegime !== 'exempt'" class="grid grid-cols-2 gap-3 items-center">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">Taxa IVA (%)</label>
                                <input type="number" step="0.01" name="tax_rate" x-model.number="taxRate"
                                    class="w-full py-2 px-3 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                            </div>
                            <div class="pt-4">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="prices_include_tax" value="1" x-model="pricesIncludeTax"
                                        class="rounded text-orange-600 focus:ring-orange-500">
                                    <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Preços com IVA incluído</span>
                                </label>
                            </div>
                        </div>

                        <div x-show="taxRegime === 'exempt'">
                            <label class="block text-xs font-bold text-gray-600 dark:text-gray-300 uppercase mb-1">Motivo de Isenção</label>
                            <input type="text" name="tax_exemption_reason" placeholder="Ex: Artigo 9 do CIVA"
                                class="w-full py-2 px-3 text-xs rounded-xl border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100">
                        </div>
                    </div>

                    <!-- Resumo Financeiro -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-2xl p-6 shadow-lg space-y-4">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-300 flex items-center justify-between">
                            <span>Resumo da Cotação</span>
                            <i class="mdi mdi-calculator-variant-outline text-lg text-orange-400"></i>
                        </h3>

                        <div class="space-y-2.5 text-sm border-t border-slate-700/80 pt-4">
                            <div class="flex justify-between text-slate-300">
                                <span>Subtotal Líquido:</span>
                                <span class="font-bold" x-text="formatMoney(summary().subtotal) + ' MT'"></span>
                            </div>
                            <div class="flex justify-between text-slate-300" x-show="summary().discount > 0">
                                <span>Descontos:</span>
                                <span class="font-bold text-amber-400" x-text="'- ' + formatMoney(summary().discount) + ' MT'"></span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>IVA (<span x-text="taxRegime === 'exempt' ? 0 : taxRate"></span>%):</span>
                                <span class="font-bold text-sky-400" x-text="formatMoney(summary().tax) + ' MT'"></span>
                            </div>
                            <div class="flex justify-between items-baseline border-t border-slate-700 pt-3 text-lg font-black">
                                <span>TOTAL GERAL:</span>
                                <span class="text-2xl text-orange-400" x-text="formatMoney(summary().total) + ' MT'"></span>
                            </div>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-black py-3.5 px-4 rounded-xl shadow-md transition-all flex items-center justify-center gap-2 text-sm">
                            <i class="mdi mdi-check-circle-outline text-lg"></i>
                            <span>Emitir e Guardar Cotação</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function quotationForm() {
            return {
                selectedClientId: '',
                customer: {
                    name: @json(old('customer_name', '')),
                    nuit: @json(old('customer_nuit', '')),
                    phone: @json(old('customer_phone', '')),
                    email: @json(old('customer_email', '')),
                    address: @json(old('customer_address', '')),
                },
                taxRegime: @json(old('tax_regime', 'normal')),
                taxRate: 16,
                pricesIncludeTax: true,
                items: [
                    { product_id: '', item_name: '', description: '', quantity: 1, unit_price: 0, discount: 0 }
                ],

                fillClientData() {
                    const select = document.querySelector('select[name="client_id"]');
                    const opt = select.options[select.selectedIndex];
                    if (opt && opt.value) {
                        this.customer.name = opt.dataset.name || '';
                        this.customer.nuit = opt.dataset.nuit || '';
                        this.customer.phone = opt.dataset.phone || '';
                        this.customer.email = opt.dataset.email || '';
                        this.customer.address = opt.dataset.address || '';
                    }
                },

                onSelectProduct(index) {
                    const selects = document.querySelectorAll(`select[name="items[${index}][product_id]"]`);
                    if (!selects.length) return;
                    const opt = selects[0].options[selects[0].selectedIndex];
                    if (opt && opt.value) {
                        this.items[index].item_name = opt.dataset.name || '';
                        this.items[index].unit_price = parseFloat(opt.dataset.price || 0);
                    }
                },

                onRegimeChange() {
                    if (this.taxRegime === 'exempt') {
                        this.taxRate = 0;
                    } else if (this.taxRegime === 'simplified') {
                        this.taxRate = 5;
                    } else {
                        this.taxRate = 16;
                    }
                },

                addItem() {
                    this.items.push({ product_id: '', item_name: '', description: '', quantity: 1, unit_price: 0, discount: 0 });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                lineTotal(item) {
                    const gross = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
                    const disc = parseFloat(item.discount) || 0;
                    return Math.max(0, gross - disc);
                },

                summary() {
                    let subtotal = 0;
                    let discount = 0;
                    let tax = 0;
                    let total = 0;
                    const rate = this.taxRegime === 'exempt' ? 0 : (parseFloat(this.taxRate) || 0);

                    this.items.forEach(item => {
                        const gross = (parseFloat(item.quantity) || 0) * (parseFloat(item.unit_price) || 0);
                        const disc = parseFloat(item.discount) || 0;
                        const netLine = Math.max(0, gross - disc);
                        discount += disc;

                        if (rate <= 0) {
                            subtotal += netLine;
                            total += netLine;
                        } else if (this.pricesIncludeTax) {
                            const base = netLine / (1 + (rate / 100));
                            const lineTax = netLine - base;
                            subtotal += base;
                            tax += lineTax;
                            total += netLine;
                        } else {
                            const lineTax = netLine * (rate / 100);
                            subtotal += netLine;
                            tax += lineTax;
                            total += (netLine + lineTax);
                        }
                    });

                    return { subtotal, discount, tax, total };
                },

                formatMoney(value) {
                    return (parseFloat(value) || 0).toLocaleString('pt-MZ', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                }
            };
        }
    </script>
    @endpush
@endsection

