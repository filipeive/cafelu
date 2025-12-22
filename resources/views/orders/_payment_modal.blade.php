<!-- Shared Payment Modal for Orders -->
<div x-data="paymentModalData()" x-show="show" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <!-- Backdrop -->
    <div x-show="show" class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="show = false"></div>

    <!-- Modal Panel -->
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-200 dark:border-gray-700">

            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="mdi mdi-cash-register text-green-500"></i>
                    Registrar Pagamento #<span x-text="orderId"></span>
                </h3>
                <button type="button" @click="show = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                    <i class="mdi mdi-close text-xl"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4">
                <!-- Total Display -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg p-4 flex justify-between items-center">
                    <span class="text-blue-800 dark:text-blue-200 font-medium">Total do Pedido:</span>
                    <span class="text-xl font-bold text-blue-600 dark:text-blue-300">MZN <span x-text="Number(totalAmount).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span></span>
                </div>

                <!-- Amount Paid -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor Pago *</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">MZN</span>
                        <input type="number" step="0.01" x-model="amountPaid" class="w-full pl-12 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="0.00">
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="amountPaid < totalAmount">
                        O restante (MZN <span x-text="Number(totalAmount - amountPaid).toLocaleString('pt-BR', {minimumFractionDigits: 2})"></span>) será registrado como dívida.
                    </p>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de Pagamento *</label>
                    <select x-model="paymentMethod" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm">
                        <option value="">Selecione um método</option>
                        <option value="cash">Dinheiro</option>
                        <option value="card">Cartão</option>
                        <option value="mpesa">M-Pesa</option>
                        <option value="emola">E-Mola</option>
                        <option value="mkesh">M-Kesh</option>
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                    <textarea x-model="notes" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 sm:text-sm" placeholder="Opcional..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                <button type="button" @click="show = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="button" @click="submitPayment()" :disabled="isLoading" class="px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
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
                const response = await fetch(`/orders/${this.orderId}/pay`, {
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
