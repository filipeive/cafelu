<!-- Shared Payment Modal for Orders -->
<div x-data="paymentModalData()" x-show="show" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <!-- Backdrop -->
    <div x-show="show" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="show = false"></div>

    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 dark:border-gray-700">

            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700/80 bg-gray-50/70 dark:bg-gray-900/50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 flex items-center justify-center text-xl">
                        <i class="mdi mdi-cash-register"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-black text-gray-900 dark:text-white">
                            Registrar Pagamento
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                            Pedido #<span x-text="orderId" class="font-bold text-gray-700 dark:text-gray-200"></span>
                        </p>
                    </div>
                </div>
                <button type="button" @click="show = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-xl transition-colors cursor-pointer">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-5">
                <!-- Total Display -->
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-gray-900/60 dark:to-gray-900/40 border border-orange-200/70 dark:border-gray-700 rounded-2xl p-4 flex justify-between items-center">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-orange-800 dark:text-orange-400 block">Total a Cobrar</span>
                        <span class="text-[11px] text-gray-500 dark:text-gray-400">Valor líquido comanda</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-orange-600 dark:text-orange-400 mr-1">MZN</span>
                        <span class="text-2xl font-black text-gray-900 dark:text-white" x-text="Number(totalAmount).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>
                    </div>
                </div>

                <!-- Amount Paid -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                            Valor Pago <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="amountPaid = totalAmount"
                                    class="text-[11px] font-bold text-orange-600 hover:text-orange-700 dark:text-orange-400 hover:underline cursor-pointer">
                                Total (100%)
                            </button>
                            <span class="text-gray-300 dark:text-gray-600">|</span>
                            <button type="button" @click="amountPaid = (totalAmount / 2).toFixed(2)"
                                    class="text-[11px] font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 cursor-pointer">
                                50%
                            </button>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-xs font-black text-gray-500 dark:text-gray-400">
                            MZN
                        </span>
                        <input type="number" step="0.01" min="0" x-model="amountPaid"
                               class="w-full pl-14 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-base font-black focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 focus:outline-none transition-all"
                               placeholder="0.00">
                    </div>

                    <!-- Debt notice if partial -->
                    <template x-if="amountPaid < totalAmount && amountPaid > 0">
                        <div class="mt-2 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 text-xs flex items-center gap-2">
                            <i class="mdi mdi-alert-circle text-base text-amber-600 flex-shrink-0"></i>
                            <span class="leading-tight">
                                Restante de <strong>MZN <span x-text="Number(totalAmount - amountPaid).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span></strong> será lançado como dívida do cliente.
                            </span>
                        </div>
                    </template>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        Método de Pagamento <span class="text-rose-500">*</span>
                    </label>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            <i class="mdi mdi-credit-card-outline text-lg"></i>
                        </span>
                        <select x-model="paymentMethod"
                                class="w-full pl-10 pr-10 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs font-bold focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 focus:outline-none transition-all cursor-pointer">
                            <option value="">Selecione o método de pagamento...</option>
                            <option value="cash">Dinheiro em Espécie (Caixa)</option>
                            <option value="card">Cartão POS (Débito / Crédito)</option>
                            <option value="mpesa">M-Pesa (Móvel)</option>
                            <option value="emola">E-Mola (Móvel)</option>
                            <option value="mkesh">M-Kesh</option>
                        </select>
                    </div>

                    <!-- Quick Click Buttons -->
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        <button type="button" @click="paymentMethod = 'cash'"
                                :class="paymentMethod === 'cash' ? 'bg-orange-500 text-white font-black shadow-sm shadow-orange-500/20' : 'bg-gray-100 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-200'"
                                class="py-2 px-1 text-center rounded-xl text-xs transition-all flex flex-col items-center gap-0.5 cursor-pointer border border-transparent">
                            <i class="mdi mdi-cash text-base"></i>
                            <span class="text-[11px]">Dinheiro</span>
                        </button>
                        <button type="button" @click="paymentMethod = 'card'"
                                :class="paymentMethod === 'card' ? 'bg-orange-500 text-white font-black shadow-sm shadow-orange-500/20' : 'bg-gray-100 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-200'"
                                class="py-2 px-1 text-center rounded-xl text-xs transition-all flex flex-col items-center gap-0.5 cursor-pointer border border-transparent">
                            <i class="mdi mdi-credit-card text-base"></i>
                            <span class="text-[11px]">Cartão</span>
                        </button>
                        <button type="button" @click="paymentMethod = 'mpesa'"
                                :class="paymentMethod === 'mpesa' ? 'bg-red-600 text-white font-black shadow-sm shadow-red-600/20' : 'bg-gray-100 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-200'"
                                class="py-2 px-1 text-center rounded-xl text-xs transition-all flex flex-col items-center gap-0.5 cursor-pointer border border-transparent">
                            <i class="mdi mdi-cellphone text-base"></i>
                            <span class="text-[11px]">M-Pesa</span>
                        </button>
                        <button type="button" @click="paymentMethod = 'emola'"
                                :class="paymentMethod === 'emola' ? 'bg-orange-600 text-white font-black shadow-sm shadow-orange-600/20' : 'bg-gray-100 dark:bg-gray-700/80 text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-200'"
                                class="py-2 px-1 text-center rounded-xl text-xs transition-all flex flex-col items-center gap-0.5 cursor-pointer border border-transparent">
                            <i class="mdi mdi-cellphone-message text-base"></i>
                            <span class="text-[11px]">E-Mola</span>
                        </button>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                        Observações <span class="text-gray-400 font-normal lowercase">(opcional)</span>
                    </label>
                    <textarea x-model="notes" rows="2"
                              class="w-full px-3.5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-xs font-medium focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 focus:outline-none transition-all"
                              placeholder="Referência de comprovativo, dados de recibo..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700/80 bg-gray-50/70 dark:bg-gray-900/50 flex justify-end gap-3">
                <button type="button" @click="show = false"
                        class="px-4 py-2.5 text-xs font-bold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors cursor-pointer">
                    Cancelar
                </button>
                <button type="button" @click="submitPayment()" :disabled="isLoading"
                        class="px-5 py-2.5 text-xs font-black text-white bg-emerald-600 border border-transparent rounded-xl hover:bg-emerald-700 focus:outline-none shadow-md shadow-emerald-600/20 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all cursor-pointer">
                    <i class="mdi mdi-check-circle" x-show="!isLoading"></i>
                    <i class="mdi mdi-loading mdi-spin" x-show="isLoading"></i>
                    <span x-text="isLoading ? 'Processando...' : 'Confirmar Pagamento'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function paymentModalData() {
    return {
        show: false,
        orderId: null,
        totalAmount: 0,
        amountPaid: 0,
        paymentMethod: '',
        notes: '',
        isLoading: false,
        
        init() {
            window.addEventListener('open-payment-modal', (e) => {
                this.orderId = e.detail.orderId;
                this.totalAmount = e.detail.total;
                this.amountPaid = e.detail.total;
                this.show = true;
                this.paymentMethod = '';
                this.notes = '';
            });
        },
        
        async submitPayment() {
            if (!this.paymentMethod) {
                showToast('Selecione um método de pagamento', 'warning');
                return;
            }

            if (this.amountPaid <= 0) {
                showToast('O valor pago deve ser maior que zero', 'warning');
                return;
            }

            if (this.amountPaid < this.totalAmount) {
                const userRole = @json(auth()->user()->role);
                if (userRole !== 'admin' && userRole !== 'manager') {
                    showToast('Apenas administradores e gerentes podem registrar pagamentos parciais (dívidas).', 'error');
                    return;
                }

                const confirmed = await Swal.fire({
                    title: 'Pagamento Parcial',
                    text: `O valor pago (MZN ${Number(this.amountPaid).toLocaleString('pt-BR', {minimumFractionDigits: 2})}) é menor que o total. Deseja registrar o restante como dívida?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sim, registrar dívida',
                    cancelButtonText: 'Não, corrigir valor',
                    confirmButtonColor: '#F97316',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#FFFFFF' : '#1F2937',
                });

                if (!confirmed.isConfirmed) return;
            }

            this.isLoading = true;

            try {
                const response = await fetch(`{{ url('/orders') }}/${this.orderId}/pay`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        payment_method: this.paymentMethod,
                        amount_paid: this.amountPaid,
                        notes: this.notes
                    })
                });

                const data = await response.json();
                this.isLoading = false;

                if (data.success) {
                    this.show = false;
                    showToast('Pagamento registrado com sucesso!', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showToast(data.message || 'Erro ao registrar pagamento', 'error');
                }
            } catch (error) {
                this.isLoading = false;
                console.error('Error:', error);
                showToast('Ocorreu um erro ao processar o pagamento', 'error');
            }
        }
    };
}
</script>
