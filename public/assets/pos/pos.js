/**
 * POS System JavaScript
 * Zalala Beach Bar Restaurant Management System
 */

// ===== GLOBAL VARIABLES =====
let cart = [];
let selectedPaymentMethod = null;
let currentTotal = 0;

// ===== CART MANAGEMENT =====
function addToCart(product) {
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
        showToast(`${product.name} - Quantidade atualizada!`, 'success');
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: parseFloat(product.price),
            quantity: 1
        });
        showToast(`${product.name} adicionado ao carrinho!`, 'success');
    }
    
    updateCartDisplay();
    updateCartTotal();
    
    // Add bounce animation to cart icon
    const cartHeader = document.querySelector('.cart-header');
    if (cartHeader) {
        cartHeader.classList.add('bounce-in');
        setTimeout(() => cartHeader.classList.remove('bounce-in'), 400);
    }
}

function removeFromCart(productId) {
    const itemIndex = cart.findIndex(item => item.id === productId);
    if (itemIndex > -1) {
        const itemName = cart[itemIndex].name;
        cart.splice(itemIndex, 1);
        updateCartDisplay();
        updateCartTotal();
        showToast(`${itemName} removido do carrinho`, 'warning');
        calculateChange();
    }
}

function updateQuantity(productId, newQuantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (newQuantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = newQuantity;
            updateCartDisplay();
            updateCartTotal();
            calculateChange();
        }
    }
}

function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('cartItems');
    if (!cartItemsContainer) return;

    if (cart.length === 0) {
        cartItemsContainer.innerHTML = `
            <div class="empty-cart">
                <i class="mdi mdi-cart-outline"></i>
                <p class="mb-1">Carrinho vazio</p>
                <small>Clique nos produtos para adicionar</small>
            </div>
        `;
        return;
    }

    cartItemsContainer.innerHTML = cart.map(item => `
        <div class="cart-item fade-in">
            <div class="item-name" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</div>
            <div class="item-controls">
                <div class="quantity-controls">
                    <button class="qty-btn" 
                            onclick="updateQuantity(${item.id}, ${item.quantity - 1})"
                            title="Diminuir quantidade"
                            ${item.quantity <= 1 ? 'style="background: #ef4444;"' : ''}>
                        <i class="mdi mdi-minus"></i>
                    </button>
                    <span class="qty-display" title="Quantidade atual">${item.quantity}</span>
                    <button class="qty-btn" 
                            onclick="updateQuantity(${item.id}, ${item.quantity + 1})"
                            title="Aumentar quantidade">
                        <i class="mdi mdi-plus"></i>
                    </button>
                </div>
                <div class="d-flex flex-column align-items-end gap-2">
                    <div class="item-price" title="Preço total do item">
                        MZN ${formatCurrency(item.price * item.quantity)}
                    </div>
                    <button class="remove-btn" 
                            onclick="removeFromCart(${item.id})" 
                            title="Remover item completamente">
                        <i class="mdi mdi-trash-can"></i>
                        Remover
                    </button>
                </div>
            </div>
            <div class="mt-2 d-flex justify-content-between align-items-center text-muted small">
                <span>Preço unitário: MZN ${formatCurrency(item.price)}</span>
                <span>${item.quantity} × MZN ${formatCurrency(item.price)}</span>
            </div>
        </div>
    `).join('');
    
    // Add scroll to bottom for new items
    setTimeout(() => {
        cartItemsContainer.scrollTop = cartItemsContainer.scrollHeight;
    }, 100);
}

function updateCartTotal() {
    currentTotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    
    const subtotalElement = document.getElementById('subtotal');
    const totalElement = document.getElementById('total');
    
    if (subtotalElement) {
        subtotalElement.textContent = `MZN ${formatCurrency(currentTotal)}`;
    }
    
    if (totalElement) {
        totalElement.textContent = `MZN ${formatCurrency(currentTotal)}`;
    }
    
    updateFinalizeButton();
}

function resetSale() {
    if (cart.length === 0) {
        showToast('Carrinho já está vazio', 'info');
        return;
    }
    
    if (confirm('Tem certeza que deseja limpar o carrinho?')) {
        cart = [];
        selectedPaymentMethod = null;
        currentTotal = 0;
        
        updateCartDisplay();
        updateCartTotal();
        clearPaymentInputs();
        clearChangeAmount();
        
        showToast('Carrinho limpo com sucesso', 'success');
    }
}

// ===== PAYMENT MANAGEMENT =====
function selectPayment(method) {
    selectedPaymentMethod = method;
    
    // Update UI
    document.querySelectorAll('.payment-card').forEach(card => {
        card.classList.remove('selected');
    });
    
    event.currentTarget.classList.add('selected');
    showToast(`Método ${getPaymentMethodName(method)} selecionado`, 'info');
}

function getPaymentMethodName(method) {
    const names = {
        'cash': 'Dinheiro',
        'card': 'Cartão',
        'mpesa': 'M-Pesa',
        'emola': 'E-mola'
    };
    return names[method] || method;
}

function calculateChange() {
    const cashAmount = parseFloat(document.getElementById('cashAmount')?.value || 0);
    const cardAmount = parseFloat(document.getElementById('cardAmount')?.value || 0);
    const mpesaAmount = parseFloat(document.getElementById('mpesaAmount')?.value || 0);
    const emolaAmount = parseFloat(document.getElementById('emolaAmount')?.value || 0);

    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;
    const nonCashPayments = cardAmount + mpesaAmount + emolaAmount;

    let change = 0;
    let displayText = '';

    // Apenas dinheiro gera troco
    if (cashAmount > 0) {
        const remainingAfterNonCash = Math.max(0, currentTotal - nonCashPayments);
        if (cashAmount > remainingAfterNonCash) {
            change = cashAmount - remainingAfterNonCash;
        }
    }

    // Determina o que exibir no campo de troco
    if (totalPaid >= currentTotal) {
        displayText = `MZN ${formatCurrency(change)}`;
    } else {
        displayText = 'Pagamento insuficiente';
        change = -1; // flag para desabilitar botão
    }

    const changeElement = document.getElementById('changeAmount');
    if (changeElement) {
        changeElement.value = displayText;

        // Feedback visual
        if (change > 0) {
            changeElement.classList.add('text-success');
            changeElement.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
        } else {
            changeElement.classList.remove('text-success');
            changeElement.style.backgroundColor = '';
        }
    }

    // Atualiza estado do botão Finalizar
    const btnFinalize = document.getElementById('btnFinalizeOrder');
    if (btnFinalize) {
        btnFinalize.disabled = change < 0;
    }

    return change;
}


function clearPaymentInputs() {
    document.getElementById('cashAmount').value = '';
    document.getElementById('cardAmount').value = '';
    document.getElementById('mpesaAmount').value = '';
    document.getElementById('emolaAmount').value = '';
    
    document.querySelectorAll('.payment-card').forEach(card => {
        card.classList.remove('selected');
    });
}

function clearChangeAmount() {
    const changeElement = document.getElementById('changeAmount');
    if (changeElement) {
        changeElement.value = 'MZN 0.00';
        changeElement.classList.remove('text-success');
        changeElement.style.backgroundColor = '';
    }
}

function updateFinalizeButton() {
    const finalizeBtn = document.getElementById('btnFinalizeOrder');
    if (!finalizeBtn) return;
    
    const canFinalize = cart.length > 0 && validatePayment();
    
    if (canFinalize) {
        finalizeBtn.disabled = false;
        finalizeBtn.classList.remove('btn-secondary');
        finalizeBtn.classList.add('btn-success');
    } else {
        finalizeBtn.disabled = true;
        finalizeBtn.classList.remove('btn-success');
        finalizeBtn.classList.add('btn-secondary');
    }
}

function validatePayment() {
    const cashAmount = parseFloat(document.getElementById('cashAmount')?.value || 0);
    const cardAmount = parseFloat(document.getElementById('cardAmount')?.value || 0);
    const mpesaAmount = parseFloat(document.getElementById('mpesaAmount')?.value || 0);
    const emolaAmount = parseFloat(document.getElementById('emolaAmount')?.value || 0);
    
    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;
    const nonCashPayments = cardAmount + mpesaAmount + emolaAmount;
    
    // Check if non-cash payments exceed total
    if (nonCashPayments > currentTotal) {
        return false;
    }
    
    // Check if total payment covers the sale amount
    const remainingAmount = currentTotal - nonCashPayments;
    if (cashAmount < remainingAmount) {
        return false;
    }
    
    return true;
}

// ===== SALE PROCESSING =====
function processSale() {
    if (cart.length === 0) {
        showToast('Adicione produtos ao carrinho primeiro', 'warning');
        return;
    }
    
    if (!validatePayment()) {
        showToast('Pagamento insuficiente ou inválido', 'error');
        return;
    }
    
    const finalizeBtn = document.getElementById('btnFinalizeOrder');
    if (finalizeBtn) {
        finalizeBtn.disabled = true;
        finalizeBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Processando...';
    }
    
    const saleData = {
        items: cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            unit_price: item.price
        })),
        cashPayment: parseFloat(document.getElementById('cashAmount')?.value || 0),
        cardPayment: parseFloat(document.getElementById('cardAmount')?.value || 0),
        mpesaPayment: parseFloat(document.getElementById('mpesaAmount')?.value || 0),
        emolaPayment: parseFloat(document.getElementById('emolaAmount')?.value || 0)
    };
    
    // Send to backend
    fetch('/pos/checkout', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        },
        body: JSON.stringify(saleData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Venda concluída com sucesso!', 'success');
            
            // Show change if applicable
            if (data.change > 0) {
                showToast(`Troco: MZN ${formatCurrency(data.change)}`, 'info');
            }
            
            // Open receipt in new tab
            setTimeout(() => {
                window.open(`/pos/receipt/${data.sale_id}`, '_blank');
            }, 1000);
            
            // Reset the sale
            resetSaleAfterSuccess();
            
        } else {
            showToast(data.message || 'Erro ao processar venda', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Erro de conexão. Tente novamente.', 'error');
    })
    .finally(() => {
        // Reset finalize button
        if (finalizeBtn) {
            finalizeBtn.disabled = false;
            finalizeBtn.innerHTML = '<i class="mdi mdi-check-circle-outline"></i> Finalizar Pedido';
        }
    });
}

function resetSaleAfterSuccess() {
    cart = [];
    selectedPaymentMethod = null;
    currentTotal = 0;
    
    updateCartDisplay();
    updateCartTotal();
    clearPaymentInputs();
    clearChangeAmount();
    
    // Show success animation
    const cartHeader = document.querySelector('.cart-header');
    if (cartHeader) {
        cartHeader.classList.add('slide-up');
        setTimeout(() => cartHeader.classList.remove('slide-up'), 500);
    }
}

function previewReceipt() {
    if (cart.length === 0) {
        showToast('Adicione produtos ao carrinho primeiro', 'warning');
        return;
    }
    
    const cashAmount = parseFloat(document.getElementById('cashAmount')?.value || 0);
    const cardAmount = parseFloat(document.getElementById('cardAmount')?.value || 0);
    const mpesaAmount = parseFloat(document.getElementById('mpesaAmount')?.value || 0);
    const emolaAmount = parseFloat(document.getElementById('emolaAmount')?.value || 0);
    
    const change = calculateChange();
    
    let receiptContent = `
        <div class="receipt-preview">
            <div class="text-center mb-3">
                <h4>ZALALA BEACH BAR</h4>
                <p class="mb-1">Bairro de Zalala, ER470</p>
                <p class="mb-1">Tel: +258 846 885 214</p>
                <p class="mb-3">NUIT: 110735901</p>
                <small>${new Date().toLocaleString('pt-BR')}</small>
            </div>
            
            <hr>
            
            <div class="items">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-end">Qtd</th>
                            <th class="text-end">Preço</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
    `;
    
    cart.forEach(item => {
        receiptContent += `
            <tr>
                <td>${escapeHtml(item.name)}</td>
                <td class="text-end">${item.quantity}</td>
                <td class="text-end">MZN ${formatCurrency(item.price)}</td>
                <td class="text-end">MZN ${formatCurrency(item.price * item.quantity)}</td>
            </tr>
        `;
    });
    
    receiptContent += `
                    </tbody>
                </table>
            </div>
            
            <hr>
            
            <div class="totals">
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>MZN ${formatCurrency(currentTotal)}</strong>
                </div>
            </div>
            
            <div class="payment-info mt-3">
                <h6>Métodos de Pagamento:</h6>
    `;
    
    if (cashAmount > 0) {
        receiptContent += `<p>Dinheiro: MZN ${formatCurrency(cashAmount)}</p>`;
    }
    if (cardAmount > 0) {
        receiptContent += `<p>Cartão: MZN ${formatCurrency(cardAmount)}</p>`;
    }
    if (mpesaAmount > 0) {
        receiptContent += `<p>M-Pesa: MZN ${formatCurrency(mpesaAmount)}</p>`;
    }
    if (emolaAmount > 0) {
        receiptContent += `<p>E-mola: MZN ${formatCurrency(emolaAmount)}</p>`;
    }
    
    if (change > 0) {
        receiptContent += `<p class="text-success"><strong>Troco: MZN ${formatCurrency(change)}</strong></p>`;
    }
    
    receiptContent += `
            </div>
            
            <hr>
            
            <div class="text-center">
                <p class="text-danger"><strong>Obrigado Pela Preferência!</strong></p>
                <p>Volte Sempre!</p>
            </div>
        </div>
    `;
    
    // Show in modal or new window
    showReceiptPreview(receiptContent);
}

function showReceiptPreview(content) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('receiptPreviewModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'receiptPreviewModal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="mdi mdi-receipt me-2"></i>
                            Pré-visualização do Recibo
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="receiptPreviewContent">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="mdi mdi-printer me-1"></i>
                            Imprimir
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    document.getElementById('receiptPreviewContent').innerHTML = content;
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// ===== PRODUCT FILTERING =====
function filterProducts() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const categoryId = document.getElementById('categorySelect')?.value || '';
    
    const productItems = document.querySelectorAll('.product-item');
    let visibleCount = 0;
    
    productItems.forEach(item => {
        const productName = item.querySelector('.card-title')?.textContent.toLowerCase() || '';
        const productCategory = item.dataset.category || '';
        
        const matchesSearch = !searchTerm || productName.includes(searchTerm);
        const matchesCategory = !categoryId || productCategory === categoryId;
        
        if (matchesSearch && matchesCategory) {
            item.style.display = 'block';
            item.classList.add('fade-in');
            visibleCount++;
        } else {
            item.style.display = 'none';
            item.classList.remove('fade-in');
        }
    });
    
    // Show no results message
    showNoResultsMessage(visibleCount === 0);
}

function showNoResultsMessage(show) {
    let noResultsDiv = document.getElementById('noResults');
    
    if (show && !noResultsDiv) {
        noResultsDiv = document.createElement('div');
        noResultsDiv.id = 'noResults';
        noResultsDiv.className = 'col-12 text-center py-5';
        noResultsDiv.innerHTML = `
            <div class="text-muted">
                <i class="mdi mdi-magnify-close" style="font-size: 4rem;"></i>
                <h5 class="mt-3">Nenhum produto encontrado</h5>
                <p>Tente ajustar os filtros de busca</p>
            </div>
        `;
        document.getElementById('productsGrid').appendChild(noResultsDiv);
    } else if (!show && noResultsDiv) {
        noResultsDiv.remove();
    }
}

// ===== CATEGORY FILTERING =====
function setupCategoryFilters() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active state
            categoryButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update select dropdown
            const categorySelect = document.getElementById('categorySelect');
            if (categorySelect) {
                categorySelect.value = category === 'all' ? '' : category;
            }
            
            filterProducts();
        });
    });
}

// ===== KEYBOARD SHORTCUTS =====
function setupPOSKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Only trigger when not typing in inputs
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
            return;
        }
        
        switch(e.key.toLowerCase()) {
            case 'f1': // Help
                e.preventDefault();
                showPOSHelp();
                break;
                
            case 'f2': // New Sale
                e.preventDefault();
                resetSale();
                break;
                
            case 'f3': // Focus search
                e.preventDefault();
                document.getElementById('searchInput')?.focus();
                break;
                
            case 'f4': // Quick cash payment
                e.preventDefault();
                setQuickCashPayment();
                break;
                
            case 'escape': // Clear search or close modals
                e.preventDefault();
                const searchInput = document.getElementById('searchInput');
                if (searchInput && searchInput.value) {
                    searchInput.value = '';
                    filterProducts();
                } else {
                    // Close any open modals
                    const openModal = document.querySelector('.modal.show');
                    if (openModal) {
                        bootstrap.Modal.getInstance(openModal)?.hide();
                    }
                }
                break;
                
            case 'enter': // Finalize if ready
                if (e.ctrlKey) {
                    e.preventDefault();
                    const finalizeBtn = document.getElementById('btnFinalizeOrder');
                    if (finalizeBtn && !finalizeBtn.disabled) {
                        processSale();
                    }
                }
                break;
        }
    });
}

function setQuickCashPayment() {
    if (currentTotal > 0) {
        const cashInput = document.getElementById('cashAmount');
        if (cashInput) {
            cashInput.value = currentTotal.toFixed(2);
            selectPayment('cash');
            cashInput.focus();
            calculateChange();
            showToast('Pagamento exato em dinheiro definido', 'success');
        }
    } else {
        showToast('Adicione produtos ao carrinho primeiro', 'warning');
    }
}

function setExactCashPayment() {
    return setQuickCashPayment();
}

function showPOSHelp() {
    const helpContent = `
        <div class="pos-help">
            <h6>Atalhos de Teclado:</h6>
            <ul class="list-unstyled">
                <li><kbd>F1</kbd> - Mostrar ajuda</li>
                <li><kbd>F2</kbd> - Nova venda</li>
                <li><kbd>F3</kbd> - Buscar produtos</li>
                <li><kbd>F4</kbd> - Pagamento rápido em dinheiro</li>
                <li><kbd>Esc</kbd> - Limpar busca / Fechar modais</li>
                <li><kbd>Ctrl+Enter</kbd> - Finalizar venda</li>
            </ul>
            
            <h6 class="mt-3">Dicas de Uso:</h6>
            <ul class="list-unstyled">
                <li>• Clique nos produtos para adicionar ao carrinho</li>
                <li>• Use os botões +/- para ajustar quantidades</li>
                <li>• Combine diferentes métodos de pagamento</li>
                <li>• O troco é calculado automaticamente</li>
                <li>• Use a busca para encontrar produtos rapidamente</li>
            </ul>
        </div>
    `;
    
    showInfoModal('Ajuda do POS', helpContent);
}

function showInfoModal(title, content) {
    let modal = document.getElementById('infoModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'infoModal';
        modal.className = 'modal fade';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="infoModalContent"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    document.getElementById('infoModalTitle').textContent = title;
    document.getElementById('infoModalContent').innerHTML = content;
    
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// ===== UTILITY FUNCTIONS =====
function formatCurrency(amount) {
    return new Intl.NumberFormat('pt-MZ', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount);
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function showToast(message, type = 'success') {
    // Use the global toast function if available
    if (typeof window.ZalalaSystem?.showToast === 'function') {
        window.ZalalaSystem.showToast(message, type);
    } else {
        // Fallback simple toast
        console.log(`${type.toUpperCase()}: ${message}`);
        
        // Create simple toast element
        const toast = document.createElement('div');
        toast.className = `toast-simple toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#06b6d4'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            z-index: 9999;
            animation: slideIn 0.3s ease-out;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    // Setup event listeners
    setupCategoryFilters();
    setupPOSKeyboardShortcuts();
    
    // Setup payment input listeners
    ['cashAmount', 'cardAmount', 'mpesaAmount', 'emolaAmount'].forEach(id => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener('input', function() {
                calculateChange();
                updateFinalizeButton();
            });
            
            input.addEventListener('focus', function() {
                // Auto-select payment method when focusing input
                const paymentCard = this.closest('.payment-card');
                if (paymentCard) {
                    const method = id.replace('Amount', '').toLowerCase();
                    selectPayment(method);
                }
            });
        }
    });
    
    // Initial setup
    updateCartDisplay();
    updateCartTotal();
    
    // Auto-focus search input
    setTimeout(() => {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.focus();
        }
    }, 500);
    
    console.log('🏖️ POS System initialized successfully!');
});

// ===== EXPORT FOR GLOBAL ACCESS =====
window.POSSystem = {
    addToCart,
    removeFromCart,
    updateQuantity,
    resetSale,
    processSale,
    previewReceipt,
    filterProducts,
    calculateChange,
    selectPayment
};