class ProductManager {
    constructor() {
        this.currentProductId = null;
        this.stockChart = null;
        this.salesChart = null;
        
        this.initEventListeners();
        this.initImagePreviews();
    }
    
    initEventListeners() {
        // Pesquisa com debounce
        document.getElementById('search-input')?.addEventListener('input', 
            this.debounce(this.handleSearch.bind(this), 300));
        
        // Botão de edição a partir dos detalhes
        document.getElementById('btn-edit-from-details')?.addEventListener('click', () => {
            $('#productDetailsModal').modal('hide');
            this.editProduct(this.currentProductId);
        });
        
        // Reset do formulário quando o modal é fechado
        $('#addProductModal').on('hidden.bs.modal', () => this.resetForm('productForm'));
    }
    
    initImagePreviews() {
        // Preview para adição de produto
        document.getElementById('image')?.addEventListener('change', (e) => 
            this.handleImagePreview(e, 'imagePreview'));
        
        // Preview para edição de produto
        document.getElementById('edit_image')?.addEventListener('change', (e) => 
            this.handleImagePreview(e, 'edit_imagePreview'));
    }
    
    debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }
    
    async handleSearch(e) {
        const searchTerm = e.target.value.trim();
        if (searchTerm.length < 3 && searchTerm.length !== 0) return;
        
        this.showLoading('#product-grid');
        
        try {
            const url = new URL(window.location.href);
            url.searchParams.set('search', searchTerm);
            
            const response = await fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            document.getElementById('product-grid').innerHTML = 
                doc.getElementById('product-grid').innerHTML;
        } catch (error) {
            console.error('Search error:', error);
            this.showToast('Erro', 'Falha ao buscar produtos', 'error');
        } finally {
            this.hideLoading('#product-grid');
        }
    }
    
    handleImagePreview(event, previewId) {
        const file = event.target.files[0];
        if (!file) return;
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById(previewId);
            preview.src = e.target.result;
            preview.style.display = 'block';
            
            const placeholder = preview.parentElement.querySelector('.preview-placeholder');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
    
    async viewProduct(id) {
        try {
            this.showLoading('#productDetailsModal .modal-body');
            this.currentProductId = id;
            
            const response = await fetch(`/products/${id}`);
            if (!response.ok) throw new Error('Product not found');
            
            const product = await response.json();
            this.populateProductDetails(product);
            
            await Promise.all([
                this.loadStockHistory(id),
                this.loadSalesData(id)
            ]);
            
            this.generateQRCode(id);
            $('#productDetailsModal').modal('show');
        } catch (error) {
            console.error('Error loading product:', error);
            this.showToast('Erro', 'Não foi possível carregar os detalhes do produto', 'error');
        } finally {
            this.hideLoading('#productDetailsModal .modal-body');
        }
    }
    
    populateProductDetails(product) {
        // Dados básicos
        document.getElementById('detail-name').textContent = product.name;
        document.getElementById('detail-description').textContent = product.description || 'Sem descrição';
        document.getElementById('detail-price').textContent = `MZN ${product.price.toFixed(2).replace('.', ',')}`;
        
        // Estoque
        const stockElement = document.getElementById('detail-stock');
        stockElement.textContent = `${product.stock_quantity} unidades`;
        stockElement.className = this.getStockClass(product.stock_quantity);
        
        // Status
        this.setStatusElement('detail-status', product.is_active, 'Ativo', 'Inativo');
        this.setStatusElement('detail-menu', product.add_to_menu, 'Sim', 'Não');
        
        // Categoria
        document.getElementById('detail-category').textContent = product.category?.name || 'Sem categoria';
        
        // Imagem
        this.setProductImage(product);
    }
    
    setStatusElement(elementId, condition, activeText, inactiveText) {
        const element = document.getElementById(elementId);
        if (condition) {
            element.innerHTML = `<i class="ti-check-circle text-success me-1"></i> ${activeText}`;
            element.className = 'mb-0 text-success';
        } else {
            element.innerHTML = `<i class="ti-close-circle text-danger me-1"></i> ${inactiveText}`;
            element.className = 'mb-0 text-danger';
        }
    }
    
    setProductImage(product) {
        const imageContainer = document.getElementById('detail-image-container');
        const placeholder = document.getElementById('detail-placeholder');
        const image = document.getElementById('detail-image');
        
        if (product.image_path) {
            image.src = `/storage/${product.image_path}`;
            imageContainer.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            imageContainer.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }
    
    getStockClass(quantity) {
        if (quantity <= 5) return 'mb-0 text-danger';
        if (quantity <= 10) return 'mb-0 text-warning';
        return 'mb-0 text-success';
    }
    
    async loadStockHistory(productId) {
        try {
            const response = await fetch(`/products/${productId}/stock-history`);
            const data = await response.json();
            
            // Atualizar tabela
            this.updateStockHistoryTable(data.history);
            
            // Renderizar gráfico
            this.renderChart('stockHistoryChart', {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: 'Quantidade em Estoque',
                        data: data.quantities,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: this.getChartOptions('Quantidade em Estoque')
            });
        } catch (error) {
            console.error('Error loading stock history:', error);
        }
    }
    
    updateStockHistoryTable(history) {
        const tableBody = document.getElementById('stock-history-table');
        tableBody.innerHTML = history.map(item => `
            <tr>
                <td>${new Date(item.created_at).toLocaleDateString()}</td>
                <td>${this.getOperationLabel(item.operation)}</td>
                <td>${item.quantity}</td>
                <td>${item.note || '-'}</td>
            </tr>
        `).join('');
    }
    
    getOperationLabel(operation) {
        const operations = {
            'add': 'Adição',
            'subtract': 'Remoção',
            'set': 'Definição'
        };
        return operations[operation] || operation;
    }
    
    async loadSalesData(productId) {
        try {
            const response = await fetch(`/products/${productId}/sales-data`);
            const data = await response.json();
            
            // Atualizar totais
            document.getElementById('total-sold').textContent = data.total_sold;
            document.getElementById('total-revenue').textContent = 
                `MZN ${data.total_revenue.toFixed(2).replace('.', ',')}`;
            
            // Renderizar gráfico
            this.renderChart('salesDataChart', {
                type: 'bar',
                data: {
                    labels: data.months,
                    datasets: [{
                        label: 'Vendas',
                        data: data.sales,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: '#28a745',
                        borderWidth: 1
                    }]
                },
                options: this.getChartOptions('Vendas Mensais')
            });
        } catch (error) {
            console.error('Error loading sales data:', error);
        }
    }
    
    renderChart(canvasId, config) {
        const ctx = document.getElementById(canvasId)?.getContext('2d');
        if (!ctx) return;
        
        // Destruir gráfico anterior se existir
        if (canvasId === 'stockHistoryChart' && this.stockChart) {
            this.stockChart.destroy();
        } else if (canvasId === 'salesDataChart' && this.salesChart) {
            this.salesChart.destroy();
        }
        
        // Criar novo gráfico
        const chart = new Chart(ctx, config);
        
        // Armazenar referência
        if (canvasId === 'stockHistoryChart') {
            this.stockChart = chart;
        } else if (canvasId === 'salesDataChart') {
            this.salesChart = chart;
        }
    }
    
    getChartOptions(title) {
        return {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                title: { 
                    display: true,
                    text: title,
                    padding: { top: 10, bottom: 20 }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    cornerRadius: 4
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false }
                },
                x: {
                    grid: { display: false }
                }
            }
        };
    }
    
    generateQRCode(productId) {
        const qrContainer = document.getElementById('productQRCode');
        if (!qrContainer) return;
        
        qrContainer.innerHTML = '';
        
        new QRCode(qrContainer, {
            text: `${window.location.origin}/products/${productId}`,
            width: 150,
            height: 150,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
    
    printQRCode() {
        const printContent = document.getElementById('productQRCode').innerHTML;
        const productName = document.getElementById('detail-name').textContent;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>QR Code - ${productName}</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; padding: 20px; }
                    .container { max-width: 300px; margin: 0 auto; }
                    .product-name { margin-top: 15px; font-weight: bold; }
                    @media print { button { display: none; } }
                </style>
            </head>
            <body>
                <div class="container">
                    <h3>QR Code do Produto</h3>
                    <div>${printContent}</div>
                    <p class="product-name">${productName}</p>
                    <button onclick="window.print()" class="btn btn-primary mt-3">Imprimir</button>
                </div>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
    
    async editProduct(id) {
        try {
            this.showLoading('#editProductModal .modal-body');
            
            const response = await fetch(`/products/${id}/edit`);
            if (!response.ok) throw new Error('Failed to load product');
            
            const product = await response.json();
            this.populateEditForm(product);
            
            $('#editProductModal').modal('show');
        } catch (error) {
            console.error('Error editing product:', error);
            this.showToast('Erro', 'Não foi possível carregar os dados do produto', 'error');
        } finally {
            this.hideLoading('#editProductModal .modal-body');
        }
    }
    
    populateEditForm(product) {
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_description').value = product.description || '';
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock_quantity').value = product.stock_quantity;
        document.getElementById('edit_category_id').value = product.category_id || '';
        document.getElementById('edit_is_active').checked = product.is_active;
        document.getElementById('edit_add_to_menu').checked = product.add_to_menu;
        
        // Imagem
        const editImagePreview = document.getElementById('edit_imagePreview');
        if (product.image_path) {
            editImagePreview.src = `/storage/${product.image_path}`;
            editImagePreview.style.display = 'block';
        } else {
            editImagePreview.style.display = 'none';
        }
        
        // Atualizar action do formulário
        document.getElementById('editProductForm').action = `/products/${product.id}`;
    }
    
    async updateStock(id) {
        try {
            const response = await fetch(`/products/${id}/edit`);
            if (!response.ok) throw new Error('Failed to load product');
            
            const product = await response.json();
            
            document.getElementById('stock_product_name').textContent = product.name;
            document.getElementById('current_stock').textContent = product.stock_quantity;
            document.getElementById('stock_quantity_update').value = product.stock_quantity;
            document.getElementById('updateStockForm').dataset.productId = id;
            
            $('#updateStockModal').modal('show');
        } catch (error) {
            console.error('Error preparing stock update:', error);
            this.showToast('Erro', 'Não foi possível carregar os dados do produto', 'error');
        }
    }
    
    confirmDelete(id) {
        Swal.fire({
            title: 'Tem certeza?',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.deleteProduct(id);
            }
        });
    }
    
    async deleteProduct(id) {
        try {
            const response = await fetch(`/products/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.showToast('Sucesso', data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error deleting product:', error);
            this.showToast('Erro', error.message || 'Falha ao excluir produto', 'error');
        }
    }
    
    resetForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        form.reset();
        
        // Limpar preview de imagem
        const preview = form.querySelector('.image-preview');
        if (preview) {
            preview.src = '';
            preview.style.display = 'none';
        }
        
        // Restaurar placeholder
        const placeholder = form.querySelector('.preview-placeholder');
        if (placeholder) {
            placeholder.style.display = 'block';
        }
    }
    
    showLoading(selector = 'body') {
        const target = document.querySelector(selector);
        if (!target) return;
        
        target.classList.add('loading');
        target.insertAdjacentHTML('beforeend', `
            <div class="loading-overlay">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
        `);
    }
    
    hideLoading(selector = 'body') {
        const target = document.querySelector(selector);
        if (!target) return;
        
        target.classList.remove('loading');
        const overlay = target.querySelector('.loading-overlay');
        if (overlay) overlay.remove();
    }
    
    showToast(title, message, type = 'success') {
        const toastContainer = document.getElementById('toast-container') || 
            document.body.appendChild(document.createElement('div'));
        
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        const icon = type === 'success' ? 'ti-check-circle' : 'ti-alert-circle';
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="${icon} me-2"></i>
                    <strong>${title}</strong>: ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" 
                        data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        toastContainer.appendChild(toast);
        new bootstrap.Toast(toast, { delay: 3000 }).show();
        
        // Remover toast após desaparecer
        setTimeout(() => toast.remove(), 3500);
    }
}

// Inicialização quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    window.productManager = new ProductManager();
    
    // Funções globais para chamadas a partir do HTML
    window.viewProduct = (id) => productManager.viewProduct(id);
    window.editProduct = (id) => productManager.editProduct(id);
    window.updateStock = (id) => productManager.updateStock(id);
    window.confirmDelete = (id) => productManager.confirmDelete(id);
    window.printQRCode = () => productManager.printQRCode();
});