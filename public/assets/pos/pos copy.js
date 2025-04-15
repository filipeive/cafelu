// pos.js - Arquivo principal de JavaScript 
let saleItems = [];
let selectedPaymentMethod = null;

// Função para adicionar item ao carrinho
function addToCart(product) {
    const existingItem = saleItems.find(item => item.id === product.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
        existingItem.total = existingItem.quantity * existingItem.price;
    } else {
        saleItems.push({
            id: product.id,
            name: product.name,
            quantity: 1,
            price: parseFloat(product.price),
            total: parseFloat(product.price)
        });
    }
    
    updateCartDisplay();
    calculateTotals();
}

// Função para atualizar a exibição do carrinho
function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    cartItems.innerHTML = '';
    
    saleItems.forEach((item, index) => {
        const itemElement = document.createElement('div');
        itemElement.className = 'cart-item';
        itemElement.innerHTML = `
            <div class="cart-item-info">
                <h6 class="mb-0">${item.name}</h6>
                <small class="text-muted"> ${item.price.toFixed(2)} x ${item.quantity}</small>
            </div>
            <div class="cart-item-controls">
                <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, -1)">-</button>
                <span class="mx-2">${item.quantity}</span>
                <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${index}, 1)">+</button>
                <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeItem(${index})">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>
        `;
        cartItems.appendChild(itemElement);
    });
}

// Função para atualizar quantidade
function updateQuantity(index, change) {
    const item = saleItems[index];
    const newQuantity = item.quantity + change;
    
    if (newQuantity > 0) {
        item.quantity = newQuantity;
        item.total = item.price * newQuantity;
        updateCartDisplay();
        calculateTotals();
    } else if (newQuantity === 0) {
        removeItem(index);
    }
}

// Função para remover item
function removeItem(index) {
    saleItems.splice(index, 1);
    updateCartDisplay();
    calculateTotals();
}

// Função para calcular totais
function calculateTotals() {
    const subtotal = saleItems.reduce((sum, item) => sum + item.total, 0);
    document.getElementById('subtotal').textContent = ` ${subtotal.toFixed(2)}`;
    document.getElementById('total').textContent = ` ${subtotal.toFixed(2)}`;
    calculateChange();
}

// Função para selecionar método de pagamento
function selectPayment(method) {
    selectedPaymentMethod = method;
    document.querySelectorAll('.payment-card').forEach(card => {
        card.classList.remove('selected');
    });
    document.querySelector(`[onclick="selectPayment('${method}')"]`).classList.add('selected');
}

// Função para calcular troco
function calculateChange() {
    const total = parseFloat(document.getElementById('total').textContent.replace(' ', ''));
    const cashAmount = parseFloat(document.getElementById('cashAmount').value) || 0;
    const cardAmount = parseFloat(document.getElementById('cardAmount').value) || 0;
    const mpesaAmount = parseFloat(document.getElementById('mpesaAmount').value) || 0;
    const emolaAmount = parseFloat(document.getElementById('emolaAmount').value) || 0;
    
    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;
    const change = totalPaid - total;
    
    document.getElementById('changeAmount').value = change >= 0 ? ` ${change.toFixed(2)}` : 'Pagamento insuficiente';
    document.getElementById('btnFinalizeOrder').disabled = change < 0;
}

// Função para processar a venda utilizando o controller Laravel
/* async function processSale() {
    if (saleItems.length === 0) {
        showNotification('Erro', 'Adicione itens ao carrinho antes de finalizar.', 'error');
        return;
    }

    const total = parseFloat(document.getElementById('total').textContent.replace(' ', ''));
    const saleData = {
        items: saleItems,
        cashPayment: parseFloat(document.getElementById('cashAmount').value) || 0,
        cardPayment: parseFloat(document.getElementById('cardAmount').value) || 0,
        mpesaPayment: parseFloat(document.getElementById('mpesaAmount').value) || 0,
        emolaPayment: parseFloat(document.getElementById('emolaAmount').value) || 0
    };

    // Obter o token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('/pos/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(saleData)
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Sucesso', 'Venda realizada com sucesso!', 'success');
            printReceipt(result.saleId);
            resetSale();
        } else {
            showNotification('Erro', result.message || 'Erro ao processar a venda.', 'error');
        }
    } catch (error) {
        showNotification('Erro', 'Erro ao processar a venda.', 'error');
        console.error('Error:', error);
    }
}
 */
async function processSale() {
    if (saleItems.length === 0) {
        showNotification('Erro', 'Adicione itens ao carrinho antes de finalizar.', 'error');
        return;
    }

    // Verificar o conteúdo de saleItems
    console.log('Itens no carrinho:', saleItems);

    const total = parseFloat(document.getElementById('total').textContent.replace(' ', ''));
    const saleData = {
        items: saleItems,
        cashPayment: parseFloat(document.getElementById('cashAmount').value) || 0,
        cardPayment: parseFloat(document.getElementById('cardAmount').value) || 0,
        mpesaPayment: parseFloat(document.getElementById('mpesaAmount').value) || 0,
        emolaPayment: parseFloat(document.getElementById('emolaAmount').value) || 0
    };

    // Obter o token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    try {
        const response = await fetch('/pos/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(saleData)
        });

        const responseText = await response.text();
        console.log('Resposta bruta do servidor:', responseText);

        let result;
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('Erro ao converter resposta em JSON:', parseError);
            showNotification('Erro', 'Resposta inválida do servidor. Veja o console para detalhes.', 'error');
            return;
        }

        if (result.success) {
            showNotification('Sucesso', 'Venda realizada com sucesso!', 'success');
            printReceipt(result.saleId);
            resetSale();
        } else {
            showNotification('Erro', result.message || 'Erro ao processar a venda.', 'error');
        }

    } catch (error) {
        console.error('Erro na requisição fetch:', error);
        showNotification('Erro', 'Erro ao processar a venda.', 'error');
    }
}


// Função para limpar a venda
function resetSale() {
    saleItems = [];
    updateCartDisplay();
    calculateTotals();
    document.querySelectorAll('#cashAmount, #cardAmount, #mpesaAmount, #emolaAmount').forEach(input => {
        input.value = '';
    });
    document.getElementById('changeAmount').value = '';
    document.querySelectorAll('.payment-card').forEach(card => {
        card.classList.remove('selected');
    });
}

// Função para mostrar notificações
function showNotification(title, message, type) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="mdi mdi-${type === 'success' ? 'check-circle' : 'alert-circle'}"></i>
        <div>
            <h6 class="mb-1">${title}</h6>
            <p class="mb-0">${message}</p>
        </div>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.classList.add('show'), 100);
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Função para filtrar produtos através do Laravel
function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryId = document.getElementById('categorySelect').value;
    
    // Construir URL com parâmetros
    let url = '/pos';
    const params = new URLSearchParams();
    
    if (searchTerm) {
        params.append('search', searchTerm);
    }
    
    if (categoryId) {
        params.append('category', categoryId);
    }
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    // Redirecionar para a URL filtrada 
    window.location.href = url;
}

// Função para carregar mais produtos (paginação)
function loadMoreProducts(page) {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryId = document.getElementById('categorySelect').value;
    
    // Construir URL com parâmetros
    let url = '/pos';
    const params = new URLSearchParams();
    
    params.append('page', page);
    
    if (searchTerm) {
        params.append('search', searchTerm);
    }
    
    if (categoryId) {
        params.append('category', categoryId);
    }
    
    if (params.toString()) {
        url += '?' + params.toString();
    }
    
    // Redirecionar para a URL paginada
    window.location.href = url;
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar listeners para inputs de pagamento
    document.querySelectorAll('#cashAmount, #cardAmount, #mpesaAmount, #emolaAmount')
        .forEach(input => input.addEventListener('input', calculateChange));

    // Inicializar filtros de categoria
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const categoryId = btn.dataset.category === 'all' ? '' : btn.dataset.category;
            
            // Redirecionar para a categoria selecionada
            let url = '/pos';
            if (categoryId) {
                url += `?category=${categoryId}`;
            }
            window.location.href = url;
        });
    });
    
    // Adicionar listener para o botão de pesquisa
    const searchButton = document.getElementById('searchButton');
    if (searchButton) {
        searchButton.addEventListener('click', filterProducts);
    }
    
    // Permitir pesquisar ao pressionar Enter no campo de pesquisa
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                filterProducts();
            }
        });
    }
});

// Função para gerar o conteúdo do recibo
function generateReceiptContent(isPreview = false) {
    const date = new Date().toLocaleString('pt-BR');
    const total = saleItems.reduce((sum, item) => sum + item.total, 0);
    
    const cashAmount = parseFloat(document.getElementById('cashAmount').value) || 0;
    const cardAmount = parseFloat(document.getElementById('cardAmount').value) || 0;
    const mpesaAmount = parseFloat(document.getElementById('mpesaAmount').value) || 0;
    const emolaAmount = parseFloat(document.getElementById('emolaAmount').value) || 0;
    
    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;
    const change = totalPaid - total;

    let content = `
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>${isPreview ? 'Pré-visualização do Recibo' : 'Recibo'}</title>
            <style>
                body {
                    font-family: 'arial', monospace;
                    margin: 0;
                    padding: 10px;
                    font-size: 15px;
                }
                .receipt {
                    max-width: 250px;
                    margin: 0 auto;
                }
                .header {
                    text-align: center;
                    margin-bottom: 5px;
                }
                .logo {
                    max-width: 100px;
                    margin-bottom: 5px;
                }
                .header img {
                    max-width: 120px;
                }
                .divider {
                    border-top: 1px dashed #000;
                    margin: 10px 0;
                }
                .items, .totals, .payment-methods, .footer {
                    margin-top: 15px;
                }
                .item {
                    display: flex;
                    justify-content: space-between;
                }
                .totals strong {
                    font-weight: bold;
                }
                .footer {
                    text-align: center;
                    font-size: 12px;
                    margin-top: 20px;
                }
                @media print {
                    @page {
                        margin: 0;
                        size: 80mm 297mm;
                    }
                    body {
                        margin: 0;
                    }
                    .no-print {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="receipt">
                <div class="header">
                    <img src="/assets/images/Logo.png" alt="Lu & Yosh Catering Logo">
                    <h2>Lu & Yosh Catering</h2>
                    <div class="company-info">
                        <p>Av. Eduardo Mondlane, 1234<br>Quelimane, Moçambique<br>Tel: +258 21 123 456<br>NUIT: 123456789</p>
                    </div>
                    <p>Data: ${date}</p>
                    ${isPreview ? '<h3 style="color: red;">PRÉ-VISUALIZAÇÃO</h3>' : ''}
                </div>
                
                <div class="divider"></div>
                
                <div class="items">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Item</th>
                                <th style="text-align: right;">Qtd</th>
                                <th style="text-align: right;">Preço</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${saleItems.map(item => `
                                <tr>
                                    <td>${item.name}</td>
                                    <td style="text-align: right;">${item.quantity}</td>
                                    <td style="text-align: right;"> ${item.price.toFixed(2)}</td>
                                    <td style="text-align: right;"> ${item.total.toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                
                <div class="totals">
                    <div class="item">
                        <strong>Total:</strong>
                        <span> ${total.toFixed(2)}</span>
                    </div>
                </div>
                
                <div class="payment-methods">
                    <h4>Método de Pagamento:</h4>
                    ${cashAmount > 0 ? `<div class="item">Dinheiro: <span> ${cashAmount.toFixed(2)}</span></div>` : ''}
                    ${cardAmount > 0 ? `<div class="item">Cartão: <span> ${cardAmount.toFixed(2)}</span></div>` : ''}
                    ${mpesaAmount > 0 ? `<div class="item">M-Pesa: <span> ${mpesaAmount.toFixed(2)}</span></div>` : ''}
                    ${emolaAmount > 0 ? `<div class="item">E-mola: <span> ${emolaAmount.toFixed(2)}</span></div>` : ''}
                    <div class="item"><strong>Total Pago:</strong><span> ${totalPaid.toFixed(2)}</span></div>
                    ${change > 0 ? `<div class="item">Troco: <span> ${change.toFixed(2)}</span></div>` : ''}
                </div>
                
                <div class="divider"></div>
                
                <div class="footer">
                    <p>Obrigado pela preferência!</p>
                    <p>www.luyoshcatering.co.mz</p>
                    ${isPreview ? '<p style="color: red;">ESTE É UM EXEMPLO - NÃO É UM RECIBO VÁLIDO</p>' : '<p>Este documento não serve como fatura</p>'}
                </div>
                
                ${isPreview ? `
                    <div class="no-print" style="margin-top: 20px; text-align: center;">
                        <button onclick="window.print()" style="padding: 10px 20px;">
                            Imprimir Pré-visualização
                        </button>
                    </div>
                ` : ''}
            </div>
        </body>
        </html>
    `;

    return content;
}

// Função para pré-visualizar o recibo
function previewReceipt() {
    if (saleItems.length === 0) {
        showNotification('Erro', 'Adicione itens ao carrinho antes de pré-visualizar o recibo.', 'error');
        return;
    }

    const receiptContent = generateReceiptContent(true);
    const previewWindow = window.open('', '_blank', 'width=400,height=600');
    previewWindow.document.write(receiptContent);
    previewWindow.document.close();
}

// Função para imprimir o recibo final
function printReceipt(saleId) {
    if (!saleId) {
        const receiptContent = generateReceiptContent(false);
        const printWindow = window.open('', '_blank', 'width=400,height=600');
        printWindow.document.write(receiptContent);
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    } else {
        // Se tiver um ID de venda, usa a rota do Laravel
        window.open(`/pos/receipt/${saleId}`, '_blank');
    }
}