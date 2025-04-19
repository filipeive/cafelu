// Função para imprimir o recibo

function printRecibo(orderId) {
    alert("Aguarde, carregando o recibo para impressão...");

    fetch(`/orders/data/${orderId}`)
        .then((response) => {
            if (!response.ok)
                throw new Error("Erro ao carregar os dados do pedido.");
            return response.json();
        })
        .then((orderData) => {
            const receiptHTML = generateReceiptHTML(orderData);
            openPrintWindow(receiptHTML);
        })
        .catch((error) => {
            console.error("Erro ao carregar o recibo:", error);
            alert(
                "Ocorreu um erro ao imprimir o recibo. Por favor, tente novamente."
            );
        });
}

function generateReceiptHTML(orderData) {
    return `
                <div class="receipt">
                    <div class="header">
                        <img src="${orderData.logo}" alt="Logo" class="logo">
                        <div class="company-name">${orderData.companyName}</div>
                        <div class="company-info">${orderData.address}</div>
                        <div class="company-info">${orderData.phone}</div>
                        <div class="company-info">NUIT: ${orderData.nuit}</div>
                        <div class="company-info">Email: ${orderData.email}</div>
                    </div>

                    <div class="divider"></div>

                    <div class="order-info">
                        <p><strong>Recibo #${String(orderData.id).padStart(4, '0')}</strong></p>
                        <p><strong>Data:</strong> ${new Date(orderData.created_at).toLocaleString()}</p>
                        ${orderData.table ? `<p><strong>Mesa:</strong> ${orderData.table.number}</p>` : ''}
                        <p><strong>Atend:</strong> ${orderData.user.name || 'Sistema'}</p>
                    </div>

                    <div class="divider"></div>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50%">Item</th>
                                <th style="width: 20%">Qtd</th>
                                <th style="width: 30%">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${orderData.items.map(item => `
                                <tr>
                                    <td>${item.product.name}</td>
                                    <td>${item.quantity}</td>
                                    <td>${parseFloat(item.total_price).toFixed(2)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>

                    <div class="divider"></div>

                    <div class="total">
                        <p>TOTAL: MZN ${parseFloat(orderData.total_amount).toFixed(2)}</p>
                    </div>

                    <div class="divider"></div>

                    <div class="footer">
                        <p style="font-weight: bold;">Obrigado pela preferência!</p>
                        <p>Volte Sempre!</p>
                        <small>Este documento não serve como fatura</small>
                    </div>
                     <!-- Botoens noprint para fechar -->
                     
                </div>
            `;
}

function openPrintWindow(content) {
    const printWindow = window.open("", "_blank", "width=800,height=600");
    if (!printWindow) {
        alert("Não foi possível abrir a janela de impressão. Verifique se o bloqueador de pop-ups está desativado.");
        return;
    }

    const printStyles = `
        <style>
            body {
                font-family: 'Courier New', Courier, monospace;
                margin: 0;
                padding: 0;
            }
            .logo {
                width: 100px;
                height: auto;
            }
            .company-name {
                font-size: 16pt;
                font-weight: bold;
                text-align: center;
            }
            .company-info {
                font-size: 10pt;
                text-align: center;
            }
            .order-info {
                font-size: 10pt;
                margin: 10px 0;
            }
            .footer {
                font-size: 10pt;
                text-align: center;
                margin-top: 20px;
            }
            .footer small {
                display: block;
                margin-top: 5px;
            }
            .receipt {
                font-size: 12pt;
                width: 80mm;
                margin: 0 auto;
                padding: 10px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                background: white;
            }
            .header {
                text-align: center;
                margin-bottom: 10px;
            }
            .divider {
                border-top: 1px dashed #000;
                margin: 10px 0;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 9pt;
            }
            th, td {
                padding: 5px;
                text-align: left;
            }
            .total {
                text-align: right;
                font-weight: bold;
                margin-top: 10px;
            }
            .receipt {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            
            /* Estilos para controles de impressão */
            .print-controls {
                position: fixed;
                bottom: 20px;
                left: 0;
                width: 100%;
                text-align: center;
                z-index: 1000;
            }
            .print-controls button {
                padding: 8px 16px;
                margin: 0 5px;
                cursor: pointer;
                background: #4CAF50;
                color: white;
                border: none;
                border-radius: 4px;
                font-weight: bold;
            }
            .print-controls button.cancel {
                background: #f44336;
            }
            
            @media print {
                .no-print, .print-controls {
                    display: none !important;
                }
            }
        </style>
    `;

    const printScript = `
        <script>
            let isPrinting = false;
            let closeAfterPrint = true;
            let printTimeout = null;
            
            // Função principal para imprimir
            function startPrint() {
                // Limpa timeout anterior se existir
                if (printTimeout) {
                    clearTimeout(printTimeout);
                }
                
                isPrinting = true;
                try {
                    window.print();
                } catch (e) {
                    console.error("Erro ao imprimir:", e);
                    document.getElementById('manual-print-btn').style.display = 'inline-block';
                }
            }
            
            // Monitora eventos de impressão
            function setupPrintListeners() {
                // Evento que ocorre quando o diálogo de impressão é aberto
                window.addEventListener('beforeprint', function() {
                    isPrinting = true;
                });
                
                // Evento que ocorre após a impressão ou cancelamento
                window.addEventListener('afterprint', function() {
                    if (closeAfterPrint) {
                        closeWindow();
                    }
                });
                
                // Fallback para navegadores que não suportam o evento afterprint
                printTimeout = setTimeout(function() {
                    if (isPrinting && closeAfterPrint) {
                        closeWindow();
                    }
                }, 2000);
            }
            
            // Função para fechar a janela
            function closeWindow() {
                try {
                    window.close();
                } catch (e) {
                    console.warn("Não foi possível fechar a janela automaticamente");
                    document.getElementById('close-message').style.display = 'block';
                }
            }
            
            // Configuração inicial
            window.onload = function() {
                setupPrintListeners();
                
                // Inicia a impressão automaticamente após um breve delay
                setTimeout(startPrint, 500);
                
                // Monitora se a janela perde o foco (possível indicação de que o diálogo de impressão foi aberto)
                window.addEventListener('blur', function() {
                    if (!isPrinting) {
                        isPrinting = true;
                        
                        // Define um timeout para fechar a janela caso o usuário tenha cancelado
                        printTimeout = setTimeout(function() {
                            if (closeAfterPrint) {
                                closeWindow();
                            }
                        }, 1000);
                    }
                });
            };
            
            // Função para imprimir novamente
            function printAgain() {
                startPrint();
            }
            
            // Função para cancelar e fechar
            function cancelPrint() {
                closeAfterPrint = true;
                closeWindow();
            }
            
            // Função para desativar fechamento automático
            function toggleAutoClose() {
                closeAfterPrint = !closeAfterPrint;
                document.getElementById('auto-close-status').textContent = 
                    closeAfterPrint ? 'Ativado' : 'Desativado';
            }
        </script>
    `;

    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>Recibo</title>
                <meta charset="UTF-8">
                ${printStyles}
            </head>
            <body>
                ${content}
                
                <div class="print-controls no-print">
                    <button onclick="printAgain()" id="manual-print-btn">Imprimir</button>
                    <button onclick="cancelPrint()" class="cancel">Fechar</button>
                    <button onclick="toggleAutoClose()">Fechamento Automático: <span id="auto-close-status">Ativado</span></button>
                    <div id="close-message" style="display:none; margin-top:10px; color:#f44336;">
                        Por favor, feche esta janela manualmente.
                    </div>
                </div>
                
                ${printScript}
            </body>
        </html>
    `);
    printWindow.document.close();
}