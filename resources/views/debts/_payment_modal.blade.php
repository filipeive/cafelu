<!-- Payment Modal (Alpine.js) -->
<div x-data="{ 
    isOpen: false, 
    debtId: null, 
    remainingAmount: 0, 
    customerName: '',
    amount: 0,
    paymentMethod: 'cash'
}" @open-pay-modal.window="isOpen = true; debtId = $event.detail.id; remainingAmount = $event.detail.amount; customerName = $event.detail.name; amount = $event.detail.amount"
    x-show="isOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-black/50 transition-opacity" @click="isOpen = false"></div>

        <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full p-8 overflow-hidden">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Registrar Pagamento</h3>
                <button @click="isOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <i class="mdi mdi-close text-2xl"></i>
                </button>
            </div>

            <form :action="'/debts/' + debtId + '/pay'" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Cliente</p>
                        <p class="font-bold text-gray-900 dark:text-white" x-text="customerName"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor do
                            Pagamento (MT)</label>
                        <input type="number" name="amount" x-model="amount" step="0.01" :max="remainingAmount"
                            min="0.01"
                            class="w-full bg-gray-50 dark:bg-gray-700 border-none rounded-xl focus:ring-2 focus:ring-orange-500 text-gray-900 dark:text-white p-3">
                        <p class="text-xs text-gray-500 mt-1">Restante: MT <span x-text="remainingAmount"></span></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Método de
                            Pagamento</label>
                        <select name="payment_method" x-model="paymentMethod"
                            class="w-full bg-gray-50 dark:bg-gray-700 border-none rounded-xl focus:ring-2 focus:ring-orange-500 text-gray-900 dark:text-white p-3">
                            <option value="cash">Dinheiro</option>
                            <option value="card">Cartão</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="emola">e-Mola</option>
                            <option value="mkesh">mKesh</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                        <textarea name="notes" rows="2"
                            class="w-full bg-gray-50 dark:bg-gray-700 border-none rounded-xl focus:ring-2 focus:ring-orange-500 text-gray-900 dark:text-white p-3"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="isOpen = false"
                        class="flex-1 px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-all shadow-lg shadow-orange-500/20">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>