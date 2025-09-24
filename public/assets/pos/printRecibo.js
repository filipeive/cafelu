// Função para imprimir o recibo
function printRecibo(orderId) {
    // Captura o botão que foi clicado (se disponível)
    const button = event?.target?.closest('button') || event?.target;

    // Mostra feedback visual
    let originalButtonText = '';
    if (button) {
        originalButtonText = button.innerHTML;
        button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Imprimindo...';
        button.disabled = true;
    }

    fetch(`/orders/data/${orderId}`)
        .then((response) => {
            if (!response.ok) throw new Error("Erro ao carregar os dados do pedido.");
            return response.json();
        })
        .then((orderData) => {
            const receiptHTML = generateReceiptHTML(orderData);
            openPrintWindow(receiptHTML, orderId);
        })
        .catch((error) => {
            console.error("Erro ao carregar o recibo:", error);
            showToast("Erro ao imprimir o recibo. Verifique a conexão e tente novamente.", "error");
        })
        .finally(() => {
            // Restaura o botão original
            if (button) {
                button.innerHTML = originalButtonText;
                button.disabled = false;
            }
        });
}

function generateReceiptHTML(orderData) {
    // Formata a data no formato moçambicano
    const date = new Date(orderData.created_at);
    const formattedDate = date.toLocaleDateString('pt-MZ', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    // Formata os itens do pedido
    const itemsHtml = orderData.items.map(item => `
        <tr>
            <td class="item-name">${item.product.name}</td>
            <td class="item-qty">${item.quantity}x</td>
            <td class="item-total">MZN ${parseFloat(item.total_price).toFixed(2).replace('.', ',')}</td>
        </tr>
    `).join('');

    return `
        <div class="receipt">
            <div class="header">
                <img src="${orderData.logo}" alt="ZALALA BEACH BAR Logo" class="logo">
                <div class="company-name">ZALALA BEACH BAR</div>
                <div class="company-subtitle">BEACH BAR & RESTAURANT</div>
                <div class="beach-wave"></div>
                <div class="company-info">Bairro de Zalala, ER470</div>
                <div class="company-info">Quelimane, Zambézia</div>
                <div class="company-info">Tel: (+258) 846 885 214</div>
                <div class="company-info">NUIT: 110735901</div>
                <div class="company-info">Email: info@zalalabeach.com</div>
            </div>

            <div class="divider"></div>

            <div class="order-info">
                <p><strong>Recibo #${String(orderData.id).padStart(6, '0')}</strong></p>
                <p><strong>Data:</strong> ${formattedDate}</p>
                ${orderData.table ? `<p><strong>Mesa:</strong> ${orderData.table.number}</p>` : ''}
                <p><strong>Atend:</strong> ${orderData.user.name || 'Sistema'}</p>
            </div>

            <div class="divider"></div>

            <table>
                <thead>
                    <tr>
                        <th class="item-name">Item</th>
                        <th class="item-qty">Qtd</th>
                        <th class="item-total">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ${itemsHtml}
                </tbody>
            </table>

            <div class="divider"></div>

            <div class="total">
                <p>TOTAL: MZN ${parseFloat(orderData.total_amount).toFixed(2).replace('.', ',')}</p>
            </div>

            <div class="divider"></div>

            <div class="footer">
                <div class="thank-you">✨ OBRIGADO PELA PREFERÊNCIA! ✨</div>
                <p>VOLTE SEMPRE AO ZALALA BEACH BAR!</p>
                <small>Este documento serve como comprovante de consumo</small>
                <p>${new Date().toLocaleDateString('pt-MZ', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                <small>Sistema de Gestão ZALALA v1.0</small>
            </div>
        </div>
    `;
}

function openPrintWindow(content, orderId) {
    // Configurações da janela de impressão
    const printWindow = window.open("", "_blank", "width=400,height=600,scrollbars=yes,resizable=yes");
    if (!printWindow) {
        showToast("Não foi possível abrir a janela de impressão. Verifique se o bloqueador de pop-ups está desativado.", "warning");
        return;
    }

    const printStyles = `
        <style>
            body {
                font-family: 'Arial', monospace;
                margin: 0;
                padding: 0;
                background: white;
                font-size: 14px;
                line-height: 1.4;
            }
            .receipt {
                width: 250px;
                margin: 0 auto;
                padding: 15px;
                background: white;
            }
            .header {
                text-align: center;
                margin-bottom: 10px;
            }
            .logo {
                max-width: 80px;
                margin: 0 auto 8px;
                display: block;
            }
            .company-name {
                font-weight: bold;
                font-size: 18px;
                margin: 3px 0;
                color: #4f46e5;
            }
            .company-subtitle {
                font-size: 14px;
                color: #6b7280;
                margin: 3px 0;
            }
            .beach-wave {
                height: 3px;
                background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
                margin: 8px 0;
                border-radius: 2px;
            }
            .company-info {
                font-size: 12px;
                margin: 3px 0;
                color: #555;
            }
            .divider {
                border-top: 1px dashed #000;
                margin: 12px 0;
            }
            .order-info p {
                margin: 3px 0;
                font-size: 13px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 13px;
                margin: 8px 0;
            }
            th, td {
                padding: 5px 0;
                text-align: left;
            }
            .item-name {
                width: 55%;
                word-wrap: break-word;
            }
            .item-qty {
                width: 20%;
                text-align: center;
            }
            .item-total {
                width: 25%;
                text-align: right;
            }
            .total {
                text-align: right;
                font-weight: bold;
                font-size: 16px;
                margin: 8px 0;
                color: #ef4444;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                margin-top: 15px;
                color: #555;
            }
            .thank-you {
                font-weight: bold;
                color: #ef4444;
                font-size: 16px;
                margin: 15px 0;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            .footer small {
                display: block;
                margin-top: 5px;
                font-size: 10px;
            }
            
            @media print {
                body {
                    -webkit-print-color-adjust: exact;
                }
                .no-print {
                    display: none !important;
                }
                .receipt {
                    padding: 10px;
                    width: 250px;
                }
            }
        </style>
    `;

    const printScript = `
        <script>
            // Variáveis de controle
            let printInitiated = false;
            let closeTimeout = null;
            
            // Função principal de impressão
            function handlePrint() {
                if (printInitiated) return;
                printInitiated = true;
                
                try {
                    window.print();
                } catch (e) {
                    console.error('Erro na impressão:', e);
                }
            }
            
            // Função de fechamento segura
            function safeClose() {
                try {
                    window.close();
                } catch (e) {
                    // Se não puder fechar, redireciona para uma página em branco
                    window.location.href = 'about:blank';
                }
            }
            
            // Configuração inicial
            window.onload = function() {
                // Inicia impressão automática após carregar
                setTimeout(handlePrint, 300);
                
                // Configura fechamento automático
                closeTimeout = setTimeout(safeClose, 3000);
                
                // Evento afterprint para fechamento imediato
                if (window.matchMedia) {
                    const mediaQueryList = window.matchMedia('print');
                    mediaQueryList.addListener(function(mql) {
                        if (!mql.matches) {
                            clearTimeout(closeTimeout);
                            setTimeout(safeClose, 500);
                        }
                    });
                }
                
                // Fallback para browsers antigos
                window.addEventListener('afterprint', function() {
                    clearTimeout(closeTimeout);
                    setTimeout(safeClose, 500);
                });
            };
            
            // Previne fechamento prematuro
            window.onbeforeunload = function() {
                if (!printInitiated) {
                    return 'A impressão ainda não foi concluída.';
                }
            };
        </script>
    `;

    printWindow.document.open();
    printWindow.document.write(`
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Recibo #${String(orderId).padStart(6, '0')}</title>
            ${printStyles}
        </head>
        <body>
            ${content}
            ${printScript}
        </body>
        </html>
    `);
    printWindow.document.close();
}