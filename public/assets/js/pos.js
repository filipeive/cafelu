/**
 * POS System JavaScript - VERSÃO CORRIGIDA
 * Zalala Beach Bar Restaurant Management System
 */

// ===== GLOBAL VARIABLES =====
let saleItems = [];
let cart = [];
let selectedPaymentMethod = null;
let currentTotal = 0;
let lastSaleData = null; // NOVO: Armazenar dados da última venda

// ===== CART MANAGEMENT =====
function addToCart(product) {
    const existingItem = cart.find((item) => item.id === product.id);

    if (existingItem) {
        existingItem.quantity += 1;
        showToast(`${product.name} - Quantidade atualizada!`, "success");
    } else {
        cart.push({
            id: product.id,
            name: product.name,
            price: parseFloat(product.price),
            quantity: 1,
        });
        showToast(`${product.name} adicionado ao carrinho!`, "success");
    }

    updateCartDisplay();
    updateCartTotal();

    // Add bounce animation to cart icon
    const cartHeader = document.querySelector(".cart-header");
    if (cartHeader) {
        cartHeader.classList.add("bounce-in");
        setTimeout(() => cartHeader.classList.remove("bounce-in"), 400);
    }
}

function removeFromCart(productId) {
    const itemIndex = cart.findIndex((item) => item.id === productId);
    if (itemIndex > -1) {
        const itemName = cart[itemIndex].name;
        cart.splice(itemIndex, 1);
        updateCartDisplay();
        updateCartTotal();
        showToast(`${itemName} removido do carrinho`, "warning");
        calculateChange();
    }
}

function showNotification(title, message, type) {
    const notification = document.createElement("div");
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="mdi mdi-${
            type === "success"
                ? "check-circle"
                : type === "info"
                ? "information"
                : "alert-circle"
        }"></i>
        <div>
            <h6 class="mb-1">${title}</h6>
            <p class="mb-0">${message}</p>
        </div>
    `;

    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add("show"), 100);
    setTimeout(() => {
        notification.classList.remove("show");
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function updateQuantity(productId, newQuantity) {
    const item = cart.find((item) => item.id === productId);
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
    const cartItemsContainer = document.getElementById("cartItems");
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

    cartItemsContainer.innerHTML = cart
        .map(
            (item) => `
        <div class="cart-item fade-in">
            <div class="item-name" title="${escapeHtml(
                item.name
            )}">${escapeHtml(item.name)}</div>
            <div class="item-controls">
                <div class="quantity-controls">
                    <button class="qty-btn" 
                            onclick="updateQuantity(${item.id}, ${
                item.quantity - 1
            })"
                            title="Diminuir quantidade"
                            ${
                                item.quantity <= 1
                                    ? 'style="background: #ef4444;"'
                                    : ""
                            }>
                        <i class="mdi mdi-minus"></i>
                    </button>
                    <span class="qty-display" title="Quantidade atual">${
                        item.quantity
                    }</span>
                    <button class="qty-btn" 
                            onclick="updateQuantity(${item.id}, ${
                item.quantity + 1
            })"
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
                <span>${item.quantity} × MZN ${formatCurrency(
                item.price
            )}</span>
            </div>
        </div>
    `
        )
        .join("");

    setTimeout(() => {
        cartItemsContainer.scrollTop = cartItemsContainer.scrollHeight;
    }, 100);
}

function updateCartTotal() {
    currentTotal = cart.reduce(
        (total, item) => total + item.price * item.quantity,
        0
    );

    const subtotalElement = document.getElementById("subtotal");
    const totalElement = document.getElementById("total");

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
        showToast("Carrinho já está vazio", "info");
        return;
    }

    if (confirm("Tem certeza que deseja limpar o carrinho?")) {
        cart = [];
        selectedPaymentMethod = null;
        currentTotal = 0;
        lastSaleData = null; // NOVO: Limpar dados da última venda

        updateCartDisplay();
        updateCartTotal();
        clearPaymentInputs();
        clearChangeAmount();

        showToast("Carrinho limpo com sucesso", "success");
    }
}

// ===== PAYMENT MANAGEMENT =====
function selectPayment(method) {
    selectedPaymentMethod = method;

    document.querySelectorAll(".payment-card").forEach((card) => {
        card.classList.remove("selected");
    });

    event.currentTarget.classList.add("selected");
    showToast(`Método ${getPaymentMethodName(method)} selecionado`, "info");
}

function getPaymentMethodName(method) {
    const names = {
        cash: "Dinheiro",
        card: "Cartão",
        mpesa: "M-Pesa",
        emola: "E-mola",
    };
    return names[method] || method;
}

function calculateChange() {
    const cashAmount = parseFloat(
        document.getElementById("cashAmount")?.value || 0
    );
    const cardAmount = parseFloat(
        document.getElementById("cardAmount")?.value || 0
    );
    const mpesaAmount = parseFloat(
        document.getElementById("mpesaAmount")?.value || 0
    );
    const emolaAmount = parseFloat(
        document.getElementById("emolaAmount")?.value || 0
    );

    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;
    const nonCashPayments = cardAmount + mpesaAmount + emolaAmount;

    let change = 0;
    let displayText = "";

    if (cashAmount > 0) {
        const remainingAfterNonCash = Math.max(
            0,
            currentTotal - nonCashPayments
        );
        if (cashAmount > remainingAfterNonCash) {
            change = cashAmount - remainingAfterNonCash;
        }
    }

    if (totalPaid >= currentTotal) {
        displayText = `MZN ${formatCurrency(change)}`;
    } else {
        displayText = "Pagamento insuficiente";
        change = -1;
    }

    const changeElement = document.getElementById("changeAmount");
    if (changeElement) {
        changeElement.value = displayText;

        if (change > 0) {
            changeElement.classList.add("text-success");
            changeElement.style.backgroundColor = "rgba(16, 185, 129, 0.1)";
        } else {
            changeElement.classList.remove("text-success");
            changeElement.style.backgroundColor = "";
        }
    }

    const btnFinalize = document.getElementById("btnFinalizeOrder");
    if (btnFinalize) {
        btnFinalize.disabled = change < 0;
    }

    return change;
}

function clearPaymentInputs() {
    document.getElementById("cashAmount").value = "";
    document.getElementById("cardAmount").value = "";
    document.getElementById("mpesaAmount").value = "";
    document.getElementById("emolaAmount").value = "";

    document.querySelectorAll(".payment-card").forEach((card) => {
        card.classList.remove("selected");
    });
}

function clearChangeAmount() {
    const changeElement = document.getElementById("changeAmount");
    if (changeElement) {
        changeElement.value = "MZN 0.00";
        changeElement.classList.remove("text-success");
        changeElement.style.backgroundColor = "";
    }
}

function updateFinalizeButton() {
    const finalizeBtn = document.getElementById("btnFinalizeOrder");
    if (!finalizeBtn) return;

    const canFinalize = cart.length > 0 && validatePayment();

    if (canFinalize) {
        finalizeBtn.disabled = false;
        finalizeBtn.classList.remove("btn-secondary");
        finalizeBtn.classList.add("btn-success");
    } else {
        finalizeBtn.disabled = true;
        finalizeBtn.classList.remove("btn-success");
        finalizeBtn.classList.add("btn-secondary");
    }
}

function validatePayment() {
    const cashAmount = parseFloat(
        document.getElementById("cashAmount")?.value || 0
    );
    const cardAmount = parseFloat(
        document.getElementById("cardAmount")?.value || 0
    );
    const mpesaAmount = parseFloat(
        document.getElementById("mpesaAmount")?.value || 0
    );
    const emolaAmount = parseFloat(
        document.getElementById("emolaAmount")?.value || 0
    );

    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;
    const nonCashPayments = cardAmount + mpesaAmount + emolaAmount;

    if (nonCashPayments > currentTotal) {
        return false;
    }

    const remainingAmount = currentTotal - nonCashPayments;
    if (cashAmount < remainingAmount) {
        return false;
    }

    return true;
}

// ===== SALE PROCESSING - VERSÃO CORRIGIDA =====
function processSale() {
    console.log('[POS] Iniciando processo de venda...');

    if (cart.length === 0) {
        showToast('Adicione produtos ao carrinho primeiro', 'warning');
        return false;
    }

    if (!validatePayment()) {
        showToast('Pagamento insuficiente ou inválido', 'error');
        return false;
    }

    const finalizeBtn = document.getElementById('btnFinalizeOrder');
    if (finalizeBtn) {
        finalizeBtn.disabled = true;
        finalizeBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Processando...';
    }

    try {
        const cashAmount = parseFloat(document.getElementById('cashAmount')?.value || 0);
        const cardAmount = parseFloat(document.getElementById('cardAmount')?.value || 0);
        const mpesaAmount = parseFloat(document.getElementById('mpesaAmount')?.value || 0);
        const emolaAmount = parseFloat(document.getElementById('emolaAmount')?.value || 0);

        // Armazenar dados para uso após redirect
        lastSaleData = {
            items: JSON.parse(JSON.stringify(cart)),
            cashPayment: cashAmount,
            cardPayment: cardAmount,
            mpesaPayment: mpesaAmount,
            emolaPayment: emolaAmount,
            total: currentTotal,
            change: calculateChange(),
            timestamp: new Date(),
        };

        const items = cart.map(item => ({
            product_id: item.id,
            quantity: item.quantity,
            unit_price: item.price,
        }));

        // Submeter formulário
        submitFormWithPrintFlag(items, cashAmount, cardAmount, mpesaAmount, emolaAmount);

    } catch (error) {
        console.error('[POS] Erro ao processar venda:', error);
        showToast('Erro ao processar venda. Tente novamente.', 'error');

        if (finalizeBtn) {
            finalizeBtn.disabled = false;
            finalizeBtn.innerHTML = '<i class="mdi mdi-check-circle-outline"></i> Finalizar Pedido';
        }
    }
}

// NOVA FUNÇÃO: Submit com flag de impressão
function submitFormWithPrintFlag(items, cash, card, mpesa, emola) {
    console.log('[POS] Preparando formulário com flag de impressão');

    let form = document.getElementById('checkoutForm');
    
    if (!form) {
        form = document.createElement('form');
        form.id = 'checkoutForm';
        form.method = 'POST';
        form.style.display = 'none';
        document.body.appendChild(form);
    }

    // Definir action com parâmetro de impressão
    form.action = '/pos/checkout?print=true';
    form.innerHTML = '';

    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }

    // Items
    const itemsInput = document.createElement('input');
    itemsInput.type = 'hidden';
    itemsInput.name = 'items';
    itemsInput.value = JSON.stringify(items);
    form.appendChild(itemsInput);

    // Pagamentos
    const paymentFields = {
        cashPayment: cash,
        cardPayment: card,
        mpesaPayment: mpesa,
        emolaPayment: emola,
    };

    Object.entries(paymentFields).forEach(([name, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        input.value = value || '0';
        form.appendChild(input);
    });

    console.log('[POS] Submetendo formulário...');
    form.submit();
}
// Função para abrir a janela do recibo
function openReceiptWindow(saleId) {
    try {
        const receiptUrl = `/pos/receipt/${saleId}`;
        console.log('[POS] Abrindo URL do recibo:', receiptUrl);
        
        const receiptWindow = window.open(
            receiptUrl,
            'receiptWindow',
            'width=400,height=700,scrollbars=yes,resizable=yes,menubar=no,toolbar=no,location=no'
        );
        
        if (!receiptWindow) {
            showToast('Erro: Pop-up bloqueado. Por favor, permita pop-ups para este site.', 'error');
            
            // Oferecer alternativa: abrir em nova aba
            if (confirm('Pop-ups bloqueados. Deseja abrir o recibo em uma nova aba?')) {
                window.open(receiptUrl, '_blank');
            }
        } else {
            receiptWindow.focus();
            showToast('Recibo aberto para impressão', 'success');
        }
    } catch (error) {
        console.error('[POS] Erro ao abrir recibo:', error);
        showToast('Erro ao abrir recibo: ' + error.message, 'error');
    }
}

function submitFormTraditional(items, cash, card, mpesa, emola) {
    console.log("[POS] Usando submit de formulário tradicional");

    let form = document.getElementById("checkoutForm");

    if (!form) {
        form = document.createElement("form");
        form.id = "checkoutForm";
        form.method = "POST";
        form.action = "/pos/checkout";
        form.style.display = "none";
        document.body.appendChild(form);
    }

    form.innerHTML = "";

    // CSRF Token
    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");
    if (csrfToken) {
        const csrfInput = document.createElement("input");
        csrfInput.type = "hidden";
        csrfInput.name = "_token";
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }

    // Items
    const itemsInput = document.createElement("input");
    itemsInput.type = "hidden";
    itemsInput.name = "items";
    itemsInput.value = JSON.stringify(items);
    form.appendChild(itemsInput);

    // Pagamentos
    const paymentFields = {
        cashPayment: cash,
        cardPayment: card,
        mpesaPayment: mpesa,
        emolaPayment: emola,
    };

    Object.entries(paymentFields).forEach(([name, value]) => {
        const input = document.createElement("input");
        input.type = "hidden";
        input.name = name;
        input.value = value || "0";
        form.appendChild(input);
    });

    console.log("[POS] Formulário preparado. Submetendo...");
    form.submit();
}

// ===== FUNÇÕES DE RECIBO CORRIGIDAS =====
function generateReceiptContent(isPreview = false) {
    const date = new Date().toLocaleString("pt-BR");

    // CORREÇÃO: Usar lastSaleData se disponível, senão usar cart atual
    let receiptData;
    if (lastSaleData && lastSaleData.items.length > 0) {
        receiptData = lastSaleData;
    } else if (cart.length > 0) {
        // Fallback para cart atual (para preview)
        const cashAmount = parseFloat(
            document.getElementById("cashAmount")?.value || 0
        );
        const cardAmount = parseFloat(
            document.getElementById("cardAmount")?.value || 0
        );
        const mpesaAmount = parseFloat(
            document.getElementById("mpesaAmount")?.value || 0
        );
        const emolaAmount = parseFloat(
            document.getElementById("emolaAmount")?.value || 0
        );

        receiptData = {
            items: cart,
            cashPayment: cashAmount,
            cardPayment: cardAmount,
            mpesaPayment: mpesaAmount,
            emolaPayment: emolaAmount,
            total: currentTotal,
            change: calculateChange(),
        };
    } else {
        console.error("Nenhum dado disponível para gerar recibo");
        return null;
    }

    const total = receiptData.total;
    const totalPaid =
        receiptData.cashPayment +
        receiptData.cardPayment +
        receiptData.mpesaPayment +
        receiptData.emolaPayment;
    const change = receiptData.change || Math.max(0, totalPaid - total);

    let content = `
    <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>${
                isPreview ? "Pré-visualização do Recibo" : "Recibo"
            }</title>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    margin: 0;
                    padding: 10px;
                    font-size: 12px;
                    background: white;
                }
                .receipt {
                    max-width: 80mm;
                    margin: 0 auto;
                    padding: 10px;
                    background: white;
                }
                .header {
                    text-align: center;
                    margin-bottom: 10px;
                }
                .logo {
                    max-width: 100px;
                    margin-bottom: 5px;
                }
                .company-name {
                    font-size: 16px;
                    font-weight: bold;
                    margin: 5px 0;
                }
                .company-info {
                    font-size: 11px;
                    margin: 5px 0;
                    line-height: 1.4;
                }
                .divider {
                    border-top: 1px dashed #000;
                    margin: 8px 0;
                }
                .items table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 10px 0;
                }
                .items th, .items td {
                    text-align: left;
                    padding: 3px;
                    font-size: 11px;
                }
                .items th {
                    border-bottom: 1px solid #000;
                    font-weight: bold;
                }
                .totals {
                    margin: 10px 0;
                }
                .item {
                    display: flex;
                    justify-content: space-between;
                    margin: 3px 0;
                    font-size: 11px;
                }
                .payment-methods {
                    margin: 10px 0;
                }
                .footer {
                    text-align: center;
                    font-size: 11px;
                    margin-top: 15px;
                }
                .preview-badge {
                    background: #ef4444;
                    color: white;
                    padding: 5px 10px;
                    border-radius: 5px;
                    font-weight: bold;
                    margin: 10px 0;
                }
                .preview-buttons {
                    text-align: center;
                    margin: 20px 0;
                    padding: 20px;
                    border-top: 2px dashed #ccc;
                }
                .btn {
                    padding: 8px 16px;
                    margin: 5px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 12px;
                }
                .btn-primary {
                    background: #3b82f6;
                    color: white;
                }
                .btn-secondary {
                    background: #6b7280;
                    color: white;
                }
                @media print {
                    @page {
                        margin: 0;
                        size: 80mm auto;
                    }
                    body {
                        margin: 0;
                        padding: 0;
                    }
                    .no-print {
                        display: none !important;
                    }
                    .preview-badge {
                        display: none !important;
                    }
                }
            </style>
        </head>
        <body>
            <div class="receipt">
                <div class="header">
                    <img src="/assets/images/logo-zalala.png" alt="Zalala BB Logo" class="logo" onerror="this.style.display='none'">
                    <div class="company-name">ZALALA BEACH BAR</div>
                    <div class="company-info">
                        Bairro de Zalala, ER470<br>
                        Tel: +258 846 885 214<br>
                        Email: zalalabeachbar@gmail.com<br>
                        NUIT: 110735901<br>
                        <small>Data: ${date}</small>
                    </div>
                    ${
                        isPreview
                            ? '<div class="preview-badge">PRÉ-VISUALIZAÇÃO</div>'
                            : ""
                    }
                </div>
                
                <div class="divider"></div>
                
                <div class="items">
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="text-align: center;">Qtd</th>
                                <th style="text-align: right;">Preço</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${receiptData.items
                                .map(
                                    (item) => `
                                <tr>
                                    <td>${escapeHtml(item.name)}</td>
                                    <td style="text-align: center;">${
                                        item.quantity
                                    }</td>
                                    <td style="text-align: right;">MZN ${formatCurrency(
                                        item.price
                                    )}</td>
                                    <td style="text-align: right;">MZN ${formatCurrency(
                                        item.price * item.quantity
                                    )}</td>
                                </tr>
                            `
                                )
                                .join("")}
                        </tbody>
                    </table>
                </div>
                
                <div class="divider"></div>
                
                <div class="totals">
                    <div class="item">
                        <strong>TOTAL GERAL:</strong>
                        <span><strong>MZN ${formatCurrency(
                            total
                        )}</strong></span>
                    </div>
                </div>
                
                <div class="divider"></div>
                
                <div class="payment-methods">
                    <div class="item"><strong>FORMAS DE PAGAMENTO:</strong></div>
                    ${
                        receiptData.cashPayment > 0
                            ? `<div class="item">• Dinheiro: <span>MZN ${formatCurrency(
                                  receiptData.cashPayment
                              )}</span></div>`
                            : ""
                    }
                    ${
                        receiptData.cardPayment > 0
                            ? `<div class="item">• Cartão: <span>MZN ${formatCurrency(
                                  receiptData.cardPayment
                              )}</span></div>`
                            : ""
                    }
                    ${
                        receiptData.mpesaPayment > 0
                            ? `<div class="item">• M-Pesa: <span>MZN ${formatCurrency(
                                  receiptData.mpesaPayment
                              )}</span></div>`
                            : ""
                    }
                    ${
                        receiptData.emolaPayment > 0
                            ? `<div class="item">• E-mola: <span>MZN ${formatCurrency(
                                  receiptData.emolaPayment
                              )}</span></div>`
                            : ""
                    }
                    <div class="item"><strong>Total Pago:</strong><span><strong>MZN ${formatCurrency(
                        totalPaid
                    )}</strong></span></div>
                    ${
                        change > 0
                            ? `<div class="item" style="color: #059669;"><strong>Troco:</strong><span><strong>MZN ${formatCurrency(
                                  change
                              )}</strong></span></div>`
                            : ""
                    }
                </div>
                
                <div class="divider"></div>
                
                <div class="footer">
                    <p><strong>Obrigado pela preferência!</strong></p>
                    <p>Volte sempre!</p>
                    ${
                        isPreview
                            ? '<p style="color: red; font-weight: bold; margin-top: 10px;">⚠️ ESTE É UM EXEMPLO - NÃO É UM RECIBO VÁLIDO ⚠️</p>'
                            : '<p style="margin-top: 10px;"><small>Este documento não serve como fatura</small></p>'
                    }
                </div>
                
                ${
                    isPreview
                        ? `
                    <div class="preview-buttons no-print">
                        <button onclick="window.print()" class="btn btn-primary">
                            🖨️ Imprimir Pré-visualização
                        </button>
                        <button onclick="window.close()" class="btn btn-secondary">
                            ❌ Fechar
                        </button>
                    </div>
                `
                        : ""
                }
            </div>
            
            <script>
                ${
                    !isPreview
                        ? `
                    window.onload = function() { 
                        setTimeout(() => {
                            window.print(); 
                        }, 500);
                    };
                `
                        : ""
                }
                
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        window.close();
                    }
                });
            </script>
        </body>
        </html>
    `;
    return content;
}

// Função corrigida para pré-visualização
function previewReceipt() {
    if (cart.length === 0) {
        showToast(
            "Adicione itens ao carrinho antes de pré-visualizar o recibo.",
            "warning"
        );
        return;
    }

    const cashAmount = parseFloat(
        document.getElementById("cashAmount")?.value || 0
    );
    const cardAmount = parseFloat(
        document.getElementById("cardAmount")?.value || 0
    );
    const mpesaAmount = parseFloat(
        document.getElementById("mpesaAmount")?.value || 0
    );
    const emolaAmount = parseFloat(
        document.getElementById("emolaAmount")?.value || 0
    );

    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;

    if (totalPaid === 0) {
        showToast(
            "Insira pelo menos um valor de pagamento para pré-visualizar o recibo.",
            "warning"
        );
        return;
    }

    try {
        const receiptContent = generateReceiptContent(true);

        if (!receiptContent) {
            showToast("Erro ao gerar dados para o recibo", "error");
            return;
        }

        const previewWindow = window.open(
            "",
            "receiptPreview",
            "width=400,height=700,scrollbars=yes,resizable=yes"
        );

        if (!previewWindow) {
            showToast(
                "Erro: Pop-up bloqueado. Permita pop-ups para este site.",
                "error"
            );
            return;
        }

        previewWindow.document.open();
        previewWindow.document.write(receiptContent);
        previewWindow.document.close();

        previewWindow.focus();
        showToast("Pré-visualização do recibo aberta", "success");
    } catch (error) {
        console.error("Erro ao gerar pré-visualização:", error);
        showToast(
            "Erro ao gerar pré-visualização do recibo. Verifique o console para detalhes.",
            "error"
        );
    }
}

// Função corrigida para verificar se o recibo pode ser gerado
function canGenerateReceipt() {
    return (lastSaleData && lastSaleData.items.length > 0) || cart.length > 0;
}

// Função corrigida para imprimir recibo final
function printFinalReceipt() {
    if (!canGenerateReceipt()) {
        showToast("Nenhum item para imprimir", "warning");
        console.log("DEBUG: canGenerateReceipt falhou");
        console.log("DEBUG: lastSaleData:", lastSaleData);
        console.log("DEBUG: cart:", cart);
        return;
    }

    try {
        const receiptContent = generateReceiptContent(false);

        if (!receiptContent) {
            showToast("Erro ao gerar dados para o recibo", "error");
            console.log("DEBUG: generateReceiptContent retornou null");
            return;
        }

        const printWindow = window.open(
            "",
            "receiptPrint",
            "width=400,height=600,scrollbars=yes"
        );

        if (!printWindow) {
            showToast(
                "Erro: Pop-up bloqueado. Permita pop-ups para este site.",
                "error"
            );
            return;
        }

        printWindow.document.open();
        printWindow.document.write(receiptContent);
        printWindow.document.close();

        printWindow.focus();
        console.log("DEBUG: Recibo enviado para impressão com sucesso");
    } catch (error) {
        console.error("Erro ao imprimir recibo:", error);
        showToast("Erro ao imprimir recibo", "error");
    }
}

// Função para abrir recibo do backend
function openBackendReceipt(saleId) {
    try {
        const receiptWindow = window.open(
            `/pos/receipt/${saleId}`,
            "backendReceipt",
            "width=400,height=600,scrollbars=yes"
        );

        if (!receiptWindow) {
            showToast(
                "Erro: Pop-up bloqueado. Permita pop-ups para este site.",
                "error"
            );
            return;
        }

        receiptWindow.focus();
    } catch (error) {
        console.error("Erro ao abrir recibo do backend:", error);
        showToast("Erro ao abrir recibo", "error");
    }
}

// Versão alternativa usando recibo do backend
function processSaleWithBackendReceipt() {
    if (cart.length === 0) {
        showToast("Adicione produtos ao carrinho primeiro", "warning");
        return;
    }

    if (!validatePayment()) {
        showToast("Pagamento insuficiente ou inválido", "error");
        return;
    }

    const finalizeBtn = document.getElementById("btnFinalizeOrder");
    if (finalizeBtn) {
        finalizeBtn.disabled = true;
        finalizeBtn.innerHTML =
            '<i class="mdi mdi-loading mdi-spin"></i> Processando...';
    }

    const saleData = {
        items: cart.map((item) => ({
            product_id: item.id,
            quantity: item.quantity,
            unit_price: item.price,
        })),
        cashPayment: parseFloat(
            document.getElementById("cashAmount")?.value || 0
        ),
        cardPayment: parseFloat(
            document.getElementById("cardAmount")?.value || 0
        ),
        mpesaPayment: parseFloat(
            document.getElementById("mpesaAmount")?.value || 0
        ),
        emolaPayment: parseFloat(
            document.getElementById("emolaAmount")?.value || 0
        ),
    };

    fetch("/pos/checkout", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute("content"),
        },
        body: JSON.stringify(saleData),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("Venda concluída com sucesso!", "success");

                if (data.change > 0) {
                    showToast(
                        `Troco: MZN ${formatCurrency(data.change)}`,
                        "info"
                    );
                }

                setTimeout(() => {
                    openBackendReceipt(data.sale_id);
                }, 1000);

                resetSaleAfterSuccess();
            } else {
                showToast(data.message || "Erro ao processar venda", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Erro de conexão. Tente novamente.", "error");
        })
        .finally(() => {
            if (finalizeBtn) {
                finalizeBtn.disabled = false;
                finalizeBtn.innerHTML =
                    '<i class="mdi mdi-check-circle-outline"></i> Finalizar Pedido';
            }
        });
}

// ===== PRODUCT FILTERING =====
/*function filterProducts() {
    const searchTerm =
        document.getElementById("searchInput")?.value.toLowerCase() || "";
    const categoryId = document.getElementById("categorySelect")?.value || "";

    const productItems = document.querySelectorAll(".product-item");
    let visibleCount = 0;

    productItems.forEach((item) => {
        const productName =
            item.querySelector(".card-title")?.textContent.toLowerCase() || "";
        const productCategory = item.dataset.category || "";

        const matchesSearch = !searchTerm || productName.includes(searchTerm);
        const matchesCategory = !categoryId || productCategory === categoryId;

        if (matchesSearch && matchesCategory) {
            item.style.display = "block";
            item.classList.add("fade-in");
            visibleCount++;
        } else {
            item.style.display = "none";
            item.classList.remove("fade-in");
        }
    });

    showNoResultsMessage(visibleCount === 0);
}

function showNoResultsMessage(show) {
    let noResultsDiv = document.getElementById("noResults");

    if (show && !noResultsDiv) {
        noResultsDiv = document.createElement("div");
        noResultsDiv.id = "noResults";
        noResultsDiv.className = "col-12 text-center py-5";
        noResultsDiv.innerHTML = `
            <div class="text-muted">
                <i class="mdi mdi-magnify-close" style="font-size: 4rem;"></i>
                <h5 class="mt-3">Nenhum produto encontrado</h5>
                <p>Tente ajustar os filtros de busca</p>
            </div>
        `;
        document.getElementById("productsGrid").appendChild(noResultsDiv);
    } else if (!show && noResultsDiv) {
        noResultsDiv.remove();
    }
}

// ===== CATEGORY FILTERING =====
function setupCategoryFilters() {
    const categoryButtons = document.querySelectorAll(".category-btn");

    categoryButtons.forEach((btn) => {
        btn.addEventListener("click", function () {
            const category = this.dataset.category;

            categoryButtons.forEach((b) => b.classList.remove("active"));
            this.classList.add("active");

            const categorySelect = document.getElementById("categorySelect");
            if (categorySelect) {
                categorySelect.value = category === "all" ? "" : category;
            }

            filterProducts();
        });
    });
}*/
// ===== CATEGORY FILTERING - VERSÃO CORRIGIDA (AGORA USANDO RECARREGAMENTO) =====
function setupCategoryFilters() {
    const categoryButtons = document.querySelectorAll('.category-btn');
    
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Atualizar select
            const categorySelect = document.getElementById('categorySelect');
            if (categorySelect) {
                categorySelect.value = category === 'all' ? '' : category;
            }
            
            // Chamar a função de filtro principal que recarrega a página
            filterProducts();
        });
    });
    
    // O select já tem o onchange="filterProducts()" no HTML, então não precisa de listener aqui.
    // Apenas garantimos que o botão ativo está correto na inicialização (feito no Blade)
}

// NOVA FUNÇÃO: Verificar e abrir recibo automaticamente
function checkAndOpenReceipt() {
    // Verificar se existe flag de impressão na URL
    const urlParams = new URLSearchParams(window.location.search);
    const printReceipt = urlParams.get('print');
    const saleId = urlParams.get('saleId');
    
    if (printReceipt === 'true' && saleId) {
        console.log('[POS] Abrindo recibo automaticamente para venda:', saleId);
        
        // Aguardar um momento para página carregar completamente
        setTimeout(() => {
            openReceiptWindow(saleId);
            
            // Limpar parâmetros da URL sem recarregar
            const cleanUrl = window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }, 800);
    }
}

function filterProducts() {
    const searchTerm = document.getElementById('searchInput')?.value || '';
    const categoryId = document.getElementById('categorySelect')?.value || '';

    // Construir a URL com os parâmetros de filtro
    const url = new URL(window.location.href);
    
    if (categoryId) {
        url.searchParams.set('category', categoryId);
    } else {
        url.searchParams.delete('category');
    }

    if (searchTerm) {
        url.searchParams.set('search', searchTerm);
    } else {
        url.searchParams.delete('search');
    }

    // Redirecionar para a nova URL, forçando o recarregamento dos produtos pelo backend
    window.location.href = url.toString();
}

// ===== KEYBOARD SHORTCUTS =====
function setupPOSKeyboardShortcuts() {
    document.addEventListener("keydown", function (e) {
        if (e.target.tagName === "INPUT" || e.target.tagName === "TEXTAREA") {
            return;
        }

        switch (e.key.toLowerCase()) {
            case "f1":
                e.preventDefault();
                showPOSHelp();
                break;

            case "f2":
                e.preventDefault();
                resetSale();
                break;

            case "f3":
                e.preventDefault();
                document.getElementById("searchInput")?.focus();
                break;

            case "f4":
                e.preventDefault();
                setQuickCashPayment();
                break;

            case "escape":
                e.preventDefault();
                const searchInput = document.getElementById("searchInput");
                if (searchInput && searchInput.value) {
                    searchInput.value = "";
                    filterProducts();
                } else {
                    const openModal = document.querySelector(".modal.show");
                    if (openModal) {
                        bootstrap.Modal.getInstance(openModal)?.hide();
                    }
                }
                break;

            case "enter":
                if (e.ctrlKey) {
                    e.preventDefault();
                    const finalizeBtn =
                        document.getElementById("btnFinalizeOrder");
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
        const cashInput = document.getElementById("cashAmount");
        if (cashInput) {
            cashInput.value = currentTotal.toFixed(2);
            selectPayment("cash");
            cashInput.focus();
            calculateChange();
            showToast("Pagamento exato em dinheiro definido", "success");
        }
    } else {
        showToast("Adicione produtos ao carrinho primeiro", "warning");
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

    showInfoModal("Ajuda do POS", helpContent);
}

function showInfoModal(title, content) {
    let modal = document.getElementById("infoModal");
    if (!modal) {
        modal = document.createElement("div");
        modal.id = "infoModal";
        modal.className = "modal fade";
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

    document.getElementById("infoModalTitle").textContent = title;
    document.getElementById("infoModalContent").innerHTML = content;

    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// ===== UTILITY FUNCTIONS =====
function formatCurrency(amount) {
    return new Intl.NumberFormat("pt-MZ", {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount);
}

function escapeHtml(text) {
    const map = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;",
    };
    return text.replace(/[&<>"']/g, function (m) {
        return map[m];
    });
}


// Função para debug - ajuda a identificar problemas
function debugPOSState() {
    console.log("=== DEBUG POS STATE ===");
    console.log("Cart:", cart);
    console.log("LastSaleData:", lastSaleData);
    console.log("CurrentTotal:", currentTotal);
    console.log("CanGenerateReceipt:", canGenerateReceipt());
    console.log("=====================");
}

// ===== INITIALIZATION =====
document.addEventListener("DOMContentLoaded", function () {
    setupCategoryFilters();
    setupPOSKeyboardShortcuts();

    ["cashAmount", "cardAmount", "mpesaAmount", "emolaAmount"].forEach((id) => {
        const input = document.getElementById(id);
        if (input) {
            input.addEventListener("input", function () {
                calculateChange();
                updateFinalizeButton();
            });

            input.addEventListener("focus", function () {
                const paymentCard = this.closest(".payment-card");
                if (paymentCard) {
                    const method = id.replace("Amount", "").toLowerCase();
                    selectPayment(method);
                }
            });
        }
    });

    updateCartDisplay();
    updateCartTotal();

    setTimeout(() => {
        const searchInput = document.getElementById("searchInput");
        if (searchInput) {
            searchInput.focus();
        }
    }, 500);
    console.log("🏖️ POS System initialized successfully!");
    checkAndOpenReceipt();
});

// ===== EXPORT FOR GLOBAL ACCESS =====
window.POSSystem = {
    // Carrinho
    addToCart,
    removeFromCart,
    updateQuantity,
    resetSale,

    // Pagamentos
    selectPayment,
    calculateChange,

    // Venda e Recibos
    processSale,
    printFinalReceipt,
    openBackendReceipt,
    processSaleWithBackendReceipt,
    canGenerateReceipt,
    generateReceiptContent,
    previewReceipt,

    // Produtos
    filterProducts,

    // Debug
    debugPOSState,

    // Acesso aos dados
    getLastSaleData: () => lastSaleData,
    getCurrentCart: () => cart,
};
