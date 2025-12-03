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
                <small class="text-muted">MZN ${item.price.toFixed(2)} x ${item.quantity}</small>
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
    document.getElementById('subtotal').textContent = `MZN ${subtotal.toFixed(2)}`;
    document.getElementById('total').textContent = `MZN ${subtotal.toFixed(2)}`;
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
    const totalText = document.getElementById('total').textContent;
    const total = parseFloat(totalText.replace('MZN ', '')) || 0;
    const cashAmount = parseFloat(document.getElementById('cashAmount').value) || 0;
    const cardAmount = parseFloat(document.getElementById('cardAmount').value) || 0;
    const mpesaAmount = parseFloat(document.getElementById('mpesaAmount').value) || 0;
    const emolaAmount = parseFloat(document.getElementById('emolaAmount').value) || 0;

    const totalPaid = cashAmount + cardAmount + mpesaAmount + emolaAmount;
    const change = totalPaid - total;

    document.getElementById('changeAmount').value = change >= 0 ? `MZN ${change.toFixed(2)}` : 'Pagamento insuficiente';
    document.getElementById('btnFinalizeOrder').disabled = change < 0;
}

// Função para processar a venda utilizando o controller Laravel
// Função para processar a venda utilizando o controller Laravel
async function processSale() {
    if (saleItems.length === 0) {
        showNotification('Erro', 'Adicione itens ao carrinho antes de finalizar.', 'error');
        return;
    }

    // Formatar os itens para o formato que o controller espera
    const formattedItems = saleItems.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        unit_price: item.price
    }));

    const saleData = {
        items: formattedItems,
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

        // Tentar processar a resposta como JSON
        let result;
        try {
            result = await response.json();
        } catch (parseError) {
            console.error('Erro ao converter resposta em JSON:', parseError);
            const responseText = await response.text();
            console.log('Resposta bruta do servidor:', responseText);
            showNotification('Erro', 'Resposta inválida do servidor.', 'error');
            return;
        }

        if (result.success) {
            // Se houver troco, mostra uma notificação com o valor
            if (result.change && result.change > 0) {
                showNotification('Troco', `Devolva ao cliente: MZN ${result.change.toFixed(2)}`, 'info');
            }

            showNotification('Sucesso', 'Venda realizada com sucesso!', 'success');
            printReceipt(result.sale_id);
            resetSale();
        } else {
            showNotification('Erro', result.message || 'Erro ao processar a venda.', 'error');
        }

    } catch (error) {
        console.error('Erro na requisição fetch:', error);
        showNotification('Erro', 'Erro ao processar a venda.', 'error');
    }
}

// Função para imprimir recibo
function printReceipt(saleId) {
    // Redirecionar para a página de impressão do recibo
    window.open(`/pos/receipt/${saleId}`, '_blank');
}

// Função para pré-visualizar recibo
function previewReceipt() {
    if (saleItems.length === 0) {
        showNotification('Erro', 'Adicione itens ao carrinho antes de visualizar o recibo.', 'error');
        return;
    }

    // Você pode implementar a pré-visualização sem salvar a venda
    // Por simplicidade, apenas notificaremos o usuário
    showNotification('Info', 'Esta função será implementada em breve.', 'info');
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
        <i class="mdi mdi-${type === 'success' ? 'check-circle' : type === 'info' ? 'information' : 'alert-circle'}"></i>
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

            // Redirecionar com filtro de categoria
            window.location.href = `/pos?category=${categoryId}`;
        });
    });
});
/* // Função para imprimir recibo
function printReceipt(saleId) {
    // Redirecionar para a página de impressão do recibo
    window.open(`/pos/receipt/${saleId}`, '_blank');
}
 */
// Função para imprimir o recibo final
function printReceipt(saleId) {
    if (!saleId) {
        const receiptContent = generateReceiptContent(false);
        const printWindow = window.open('', '', 'width=400,height=600');
        printWindow.document.write(receiptContent);
        printWindow.document.close();
        printWindow.print();
        printWindow.close();
    } else {
        // Se tiver um ID de venda, usa a rota do Laravel
        window.open(`/pos/receipt/${saleId}`, '_blank');
    }
}
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
                    font-family: 'Arial', sans-serif;
                    margin: 0;
                    padding: 10px;
                    font-size: 12px;
                }
                .receipt {
                    max-width: 80mm;
                    margin: 0 auto;
                    padding: 10px;
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
                }
                .totals {
                    margin: 10px 0;
                }
                .item {
                    display: flex;
                    justify-content: space-between;
                    margin: 3px 0;
                }
                .footer {
                    text-align: center;
                    font-size: 11px;
                    margin-top: 15px;
                }
                @media print {
                    @page {
                        margin: 0;
                        size: 80mm auto;
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
                    <img src="/assets/images/Logo.png" alt="zalalabeachbar Logo" class="logo">
                    <h2 class="company-name">ZALALA BEACH BAR</h2>
                    <p class="company-info">Bairro de Zalala, ER470</p>
                    <p class="company-info">Quelimane, Zambézia</p>
                    <p class="company-info">Tel: (+258) 846 885 214</p>
                    <p class="company-info">NUIT: 110735901</p>
                    <p class="company-info">Email: zalalabeachbar@gmail.com</p>
                    <p>Data: ${date}</p>
                    ${isPreview ? '<div style="color: red; font-weight: bold;">PRÉ-VISUALIZAÇÃO</div>' : ''}
                </div>
                
                <div class="divider"></div>
                
                <div class="items">
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
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
                                    <td style="text-align: right;">MZN ${item.price.toFixed(2)}</td>
                                    <td style="text-align: right;">MZN ${item.total.toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
                
                <div class="divider"></div>
                
                <div class="totals">
                    <div class="item">
                        <strong>Total:</strong>
                        <span>MZN ${total.toFixed(2)}</span>
                    </div>
                </div>
                
                <div class="payment-methods">
                    <div class="item"><strong>Forma de Pagamento:</strong></div>
                    ${cashAmount > 0 ? `<div class="item">Dinheiro: <span>MZN ${cashAmount.toFixed(2)}</span></div>` : ''}
                    ${cardAmount > 0 ? `<div class="item">Cartão: <span>MZN ${cardAmount.toFixed(2)}</span></div>` : ''}
                    ${mpesaAmount > 0 ? `<div class="item">M-Pesa: <span>MZN ${mpesaAmount.toFixed(2)}</span></div>` : ''}
                    ${emolaAmount > 0 ? `<div class="item">E-mola: <span>MZN ${emolaAmount.toFixed(2)}</span></div>` : ''}
                    <div class="item"><strong>Total Pago: </strong><span>MZN ${totalPaid.toFixed(2)}</span></div>
                    ${change > 0 ? `<div class="item"><strong>Troco: </strong><span>MZN ${change.toFixed(2)}</span></div>` : ''}
                </div>
                
                <div class="divider"></div>
                
                <div class="footer">
                    <p>Obrigado pela preferência!</p>
                    ${isPreview ?
            '<p style="color: red; font-weight: bold;">ESTE É UM EXEMPLO - NÃO É UM RECIBO VÁLIDO</p>' :
            '<p>Este documento não serve como fatura</p>'}
                </div>
                
                ${isPreview ? `
                    <div class="no-print" style="margin-top: 20px; text-align: center;">
                        <button onclick="window.print()" class="btn btn-primary">
                            Imprimir Pré-visualização
                        </button>
                        <button onclick="closeAndReturn()" class="btn btn-secondary">
                            Fechar e Voltar
                        </button>
                    </div>
                ` : ''}
            </div>
            
            <script>
                // Auto-print para recibos não preview
                ${!isPreview ? 'window.onload = function() { window.print(); };' : ''}
            </script>
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