<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 z-[60] overflow-y-auto" x-data="{ 
            open: false, 
            orderId: null, 
            amount: 0,
            method: 'mpesa',
            phone: '{{ Auth::user()->phone }}',
            processing: false,
            init() {
                window.openPaymentModal = (id, amt) => {
                    this.orderId = id;
                    this.amount = amt;
                    this.open = true;
                    document.body.style.overflow = 'hidden';
                };
            },
            closeModal() {
                this.open = false;
                document.body.style.overflow = 'auto';
            },
            async submitPayment() {
                this.processing = true;
                try {
                    const response = await fetch(`/customer/order/${this.orderId}/pay`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            payment_method: this.method,
                            phone: this.phone
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        window.showToast(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        window.showToast(data.message, 'error');
                    }
                } catch (error) {
                    window.showToast('Erro ao processar pagamento', 'error');
                } finally {
                    this.processing = false;
                }
            }
        }" x-show="open" x-cloak>
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900/50 backdrop-blur-sm" @click="closeModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div
            class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Finalizar Pagamento</h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="mdi mdi-close text-2xl"></i>
                    </button>
                </div>

                <div
                    class="mb-6 p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl border border-orange-100 dark:border-orange-800">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-orange-800 dark:text-orange-300">Total a Pagar:</span>
                        <span class="text-xl font-black text-orange-600 dark:text-orange-400"
                            x-text="new Intl.NumberFormat('pt-MZ', { style: 'currency', currency: 'MZN' }).format(amount)"></span>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Método de Pagamento</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button @click="method = 'mpesa'"
                            :class="method === 'mpesa' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20 text-orange-600' : 'border-gray-200 dark:border-gray-700 text-gray-500'"
                            class="flex flex-col items-center gap-2 p-3 border-2 rounded-xl transition-all">
                            <i class="mdi mdi-cellphone-nfc text-xl"></i>
                            <span class="text-[10px] font-bold">M-Pesa</span>
                        </button>
                        <button @click="method = 'emola'"
                            :class="method === 'emola' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20 text-orange-600' : 'border-gray-200 dark:border-gray-700 text-gray-500'"
                            class="flex flex-col items-center gap-2 p-3 border-2 rounded-xl transition-all">
                            <i class="mdi mdi-wallet text-xl"></i>
                            <span class="text-[10px] font-bold">E-Mola</span>
                        </button>
                        <button @click="method = 'card'"
                            :class="method === 'card' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20 text-orange-600' : 'border-gray-200 dark:border-gray-700 text-gray-500'"
                            class="flex flex-col items-center gap-2 p-3 border-2 rounded-xl transition-all">
                            <i class="mdi mdi-credit-card-outline text-xl"></i>
                            <span class="text-[10px] font-bold">Cartão</span>
                        </button>
                        <button @click="method = 'transfer'"
                            :class="method === 'transfer' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20 text-orange-600' : 'border-gray-200 dark:border-gray-700 text-gray-500'"
                            class="flex flex-col items-center gap-2 p-3 border-2 rounded-xl transition-all">
                            <i class="mdi mdi-bank-transfer text-xl"></i>
                            <span class="text-[10px] font-bold">Transf.</span>
                        </button>
                    </div>

                    <!-- USSD Instructions -->
                    <div x-show="method === 'mpesa'"
                        class="p-4 bg-red-50 dark:bg-red-900/10 rounded-xl border border-red-100 dark:border-red-900/30">
                        <p class="text-xs text-red-800 dark:text-red-400 font-medium mb-2">Instruções M-Pesa:</p>
                        <ol class="text-[11px] text-red-700 dark:text-red-500 space-y-1 list-decimal ml-4">
                            <li>Disque <b>*150#</b></li>
                            <li>Selecione <b>Pagar com M-Pesa</b></li>
                            <li>Introduza o código do serviço ou número</li>
                            <li>Confirme o valor e seu PIN</li>
                        </ol>
                    </div>

                    <div x-show="method === 'emola'"
                        class="p-4 bg-orange-50 dark:bg-orange-900/10 rounded-xl border border-orange-100 dark:border-orange-900/30">
                        <p class="text-xs text-orange-800 dark:text-orange-400 font-medium mb-2">Instruções E-Mola:</p>
                        <ol class="text-[11px] text-orange-700 dark:text-orange-500 space-y-1 list-decimal ml-4">
                            <li>Disque <b>*898#</b></li>
                            <li>Selecione <b>Pagamentos</b></li>
                            <li>Introduza o código do serviço</li>
                            <li>Confirme o valor e seu PIN</li>
                        </ol>
                    </div>

                    <div x-show="method === 'transfer'"
                        class="p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-900/30">
                        <p class="text-xs text-blue-800 dark:text-blue-400 font-medium mb-2">Dados Bancários:</p>
                        <div class="text-[11px] text-blue-700 dark:text-blue-500 space-y-1">
                            <p>Banco: <b>BCI / Standard Bank</b></p>
                            <p>Conta: <b>123456789</b></p>
                            <p>NIB: <b>0001 0000 1234 5678 9012 3</b></p>
                            <p class="mt-2 italic">Envie o comprovativo para o nosso WhatsApp.</p>
                        </div>
                    </div>

                    <div x-show="method === 'mpesa' || method === 'emola'" x-transition>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Número de
                            Telefone</label>
                        <input type="text" x-model="phone"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 outline-none transition-all"
                            placeholder="84XXXXXXX">
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 dark:bg-gray-900/50 flex gap-3">
                <button @click="closeModal()"
                    class="flex-1 px-6 py-3 border border-gray-200 dark:border-gray-700 rounded-xl font-bold text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    Cancelar
                </button>
                <button @click="submitPayment()" :disabled="processing"
                    class="flex-1 px-6 py-3 bg-orange-500 text-white rounded-xl font-bold hover:bg-orange-600 transition-colors shadow-lg shadow-orange-500/30 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <template x-if="processing">
                        <i class="mdi mdi-loading animate-spin"></i>
                    </template>
                    <span x-text="processing ? 'Processando...' : 'Confirmar Pagamento'"></span>
                </button>
            </div>
        </div>
    </div>
</div>