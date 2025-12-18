/**
 * ========================================
 * STATE MANAGEMENT
 * ========================================
 */
let saleItems = [];
let selectedPaymentMethod = null;
let currentPage = 1;
const itemsPerPage = 12;

/**
 * ========================================
 * CART OPERATIONS
 * ========================================
 */

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

    if (saleItems.length === 0) {
        cartItems.innerHTML = `
            <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                <i class="mdi mdi-cart-outline text-4xl mb-2"></i>
                <p>Seu carrinho está vazio</p>
            </div>
        `;
        return;
    }

    saleItems.forEach((item, index) => {
        const itemElement = document.createElement('div');
        itemElement.className = 'flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600 animate-fade-in';
        itemElement.innerHTML = `
            <div class="flex-grow min-w-0 mr-3">
                <h6 class="text-sm font-medium text-gray-800 dark:text-gray-100 truncate" title="${item.name}">${item.name}</h6>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    MZN ${item.price.toFixed(2)} x ${item.quantity}
                </div>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex items-center bg-white dark:bg-gray-800 rounded-md border border-gray-200 dark:border-gray-600 shadow-sm">
                    <button class="px-2 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-l-md transition-colors" onclick="updateQuantity(${index}, -1)">-</button>
                    <span class="px-2 text-sm font-medium text-gray-800 dark:text-gray-200 min-w-[1.5rem] text-center border-x border-gray-200 dark:border-gray-600">${item.quantity}</span>
                    <button class="px-2 py-1 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-r-md transition-colors" onclick="updateQuantity(${index}, 1)">+</button>
                </div>
                <button class="p-1.5 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" onclick="removeItem(${index})">
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

/**
 * ========================================
 * CHECKOUT & API
 * ========================================
 */
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
                        <button onclick="window.close()" class="btn btn-secondary">
                            Fechar
                        </button>
                    </div>
                ` : ''}
            </div>
            
            <script>
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

// [REMOVIDO: Função duplicada showNotification. Usando a versão integrada mais abaixo]

/**
 * ========================================
 * FILTERING & PAGINATION
 * ========================================
 */
// Variáveis de paginação (Moved to top)
// let currentPage = 1;
// const itemsPerPage = 12;

// Função de debug para verificar categorias
function debugCategories() {
    const productItems = document.querySelectorAll('.product-item');
    console.log('Total de produtos:', productItems.length);

    const categoriesFound = new Set();
    productItems.forEach(item => {
        const category = item.dataset.category;
        categoriesFound.add(category);
        console.log('Produto:', item.querySelector('.card-title').textContent, 'Categoria ID:', category);
    });

    console.log('Categorias encontradas:', Array.from(categoriesFound));
}

// NOVA FUNÇÃO: Filtrar produtos dinamicamente SEM recarregar a página
function filterProducts(keepPage = false) {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryId = document.getElementById('categorySelect').value;

    const productItems = document.querySelectorAll('.product-item');
    let visibleProducts = [];

    productItems.forEach(item => {
        const productCard = item.querySelector('.product-card');
        const productName = item.querySelector('.card-title').textContent.toLowerCase();
        const productCategory = item.dataset.category;

        const matchesSearch = productName.includes(searchTerm);
        const matchesCategory = !categoryId || productCategory === categoryId;

        if (matchesSearch && matchesCategory) {
            visibleProducts.push(item);
        } else {
            item.style.display = 'none';
        }
    });

    // Reset para primeira página APENAS se não for manter a página (filtro novo)
    if (!keepPage) {
        currentPage = 1;
    }

    // Se não houver produtos visíveis, mostrar mensagem ou lidar com estado vazio
    if (visibleProducts.length === 0) {
        // Opcional: Mostrar mensagem de "Nenhum produto encontrado"
    }

    paginateProducts(visibleProducts);
}

// Função para paginar produtos
function paginateProducts(products) {
    const totalPages = Math.ceil(products.length / itemsPerPage);

    // Garantir que a página atual é válida
    if (currentPage > totalPages && totalPages > 0) {
        currentPage = totalPages;
    }

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;

    // Esconder todos os produtos primeiro (apenas os que correspondem ao filtro)
    // Nota: Os que não correspondem ao filtro já estão hidden pelo filterProducts
    products.forEach(item => item.style.display = 'none');

    // Mostrar apenas os da página atual
    products.slice(startIndex, endIndex).forEach(item => {
        item.style.display = 'block';
        // Animação de entrada suave
        item.style.opacity = '0';
        setTimeout(() => item.style.opacity = '1', 50);
    });

    // Atualizar controles de paginação
    updatePaginationControls(totalPages, products.length);
}

// Função para atualizar os controles de paginação
function updatePaginationControls(totalPages, totalProducts) {
    let paginationContainer = document.getElementById('paginationControls');

    if (!paginationContainer) {
        // Criar container de paginação se não existir
        paginationContainer = document.createElement('div');
        paginationContainer.id = 'paginationControls';
        paginationContainer.className = 'flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700';
        document.getElementById('productsGrid').parentElement.appendChild(paginationContainer);
    } else {
        paginationContainer.className = 'flex justify-between items-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700';
    }

    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }

    const startItem = ((currentPage - 1) * itemsPerPage) + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalProducts);

    paginationContainer.innerHTML = `
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Mostrando ${startItem}-${endItem} de ${totalProducts}
        </div>
        <div class="flex items-center gap-2">
            <button class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" 
                    onclick="changePage(${currentPage - 1})" 
                    ${currentPage === 1 ? 'disabled' : ''}>
                <i class="mdi mdi-chevron-left"></i>
            </button>
            <span class="text-sm text-gray-600 dark:text-gray-400">Pág ${currentPage}/${totalPages}</span>
            <button class="px-3 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors" 
                    onclick="changePage(${currentPage + 1})" 
                    ${currentPage === totalPages ? 'disabled' : ''}>
                <i class="mdi mdi-chevron-right"></i>
            </button>
        </div>
    `;
}

// Função para mudar de página
function changePage(newPage) {
    currentPage = newPage;
    filterProducts(true); // true para manter a página
}

// Função para mostrar notificações (Integrada com o sistema global)
function showNotification(title, message, type) {
    // Mapear tipos do POS para tipos do Toast
    const toastType = type === 'error' ? 'error' : (type === 'success' ? 'success' : 'info');

    if (typeof showToast === 'function') {
        showToast(message, toastType, title);
    } else {
        console.warn('showToast não definido, usando fallback');
        alert(`${title}: ${message}`);
    }
}

// Função para guardar pedido (Hold Order)
function holdOrder() {
    if (saleItems.length === 0) {
        showNotification('Erro', 'Adicione itens ao carrinho antes de guardar o pedido.', 'error');
        return;
    }

    Swal.fire({
        title: 'Guardar Pedido?',
        text: "Deseja guardar este pedido para atender outro cliente?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, guardar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            showLoading();

            const customerName = document.getElementById('customerName') ? document.getElementById('customerName').value : 'Cliente Geral';

            fetch('/pos/hold', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    items: saleItems,
                    customer_name: customerName,
                    total_amount: calculateTotalAmount()
                })
            })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        showNotification('Sucesso', 'Pedido guardado com sucesso!', 'success');
                        resetSale();
                        // Opcional: Atualizar lista de pedidos guardados se houver
                        if (typeof loadHeldOrders === 'function') loadHeldOrders();
                    } else {
                        showNotification('Erro', data.message || 'Erro ao guardar pedido', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Erro:', error);
                    showNotification('Erro', 'Erro ao processar requisição', 'error');
                });
        }
    });
}

function calculateTotalAmount() {
    return saleItems.reduce((total, item) => total + (item.price * item.quantity), 0);
}


// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar listeners para inputs de pagamento
    document.querySelectorAll('#cashAmount, #cardAmount, #mpesaAmount, #emolaAmount')
        .forEach(input => input.addEventListener('input', calculateChange));

    // MODIFICADO: Filtros de categoria agora filtram SEM recarregar
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const categoryId = btn.dataset.category === 'all' ? '' : btn.dataset.category;

            // Atualizar o select de categoria
            document.getElementById('categorySelect').value = categoryId;

            // Filtrar produtos dinamicamente
            filterProducts();
        });
    });

    // Listener para o campo de busca
    document.getElementById('searchInput').addEventListener('input', filterProducts);

    // Listener para o select de categoria
    document.getElementById('categorySelect').addEventListener('change', () => {
        const categoryId = document.getElementById('categorySelect').value;

        // Atualizar botões de categoria
        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.classList.remove('active');
            if ((categoryId === '' && btn.dataset.category === 'all') ||
                btn.dataset.category === categoryId) {
                btn.classList.add('active');
            }
        });

        filterProducts();
    });

    // Inicializar paginação na primeira carga
    filterProducts();

    // Debug: verificar categorias carregadas
    debugCategories();
});