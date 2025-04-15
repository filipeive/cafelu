@extends('layouts.app')

@section('content')
    <div class="row">
        <!-- Stats Cards Row -->
        <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
            <div class="card card-rounded border-start border-primary border-4">
                <div class="card-body pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title card-title-dash text-muted mb-1">Vendas Totais</h6>
                            <h2 class="rate-percentage text-primary mb-2">MZN {{ number_format($totalSalesAmount, 2) }}</h2>
                            <p class="text-success d-flex align-items-center mb-3">
                                <i class="mdi mdi-trending-up me-1"></i>
                                <span>Total acumulado</span>
                            </p>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="mdi mdi-cash-multiple text-primary icon-md m-0"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
            <div class="card card-rounded border-start border-success border-4">
                <div class="card-body pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title card-title-dash text-muted mb-1">Vendas Hoje</h6>
                            <h2 class="rate-percentage text-success mb-2">MZN {{ number_format($todaySalesAmount, 2) }}</h2>
                            <p class="text-success d-flex align-items-center mb-3">
                                <i class="mdi mdi-clock me-1"></i>
                                <span>Hoje</span>
                            </p>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="mdi mdi-calendar-today text-success icon-md m-0"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
            <div class="card card-rounded border-start border-info border-4">
                <div class="card-body pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title card-title-dash text-muted mb-1">Total Transações</h6>
                            <h2 class="rate-percentage text-info mb-2">{{ $totalSales }}</h2>
                            <p class="text-info d-flex align-items-center mb-3">
                                <i class="mdi mdi-chart-line me-1"></i>
                                <span>Transações realizadas</span>
                            </p>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="mdi mdi-receipt text-info icon-md m-0"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-lg-3 grid-margin stretch-card">
            <div class="card card-rounded border-start border-warning border-4">
                <div class="card-body pb-0">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="card-title card-title-dash text-muted mb-1">Pendentes</h6>
                            <h2 class="rate-percentage text-warning mb-2">{{ $pendingSalesCount }}</h2>
                            <p class="text-warning d-flex align-items-center mb-3">
                                <i class="mdi mdi-alert-circle me-1"></i>
                                <span>Aguardando processamento</span>
                            </p>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="mdi mdi-clock-alert text-warning icon-md m-0"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table Card -->
        <div class="col-lg-12 grid-margin">
            <div class="card card-rounded shadow-sm">
                <div class="card-body">
                    <!-- Header Section -->
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title mb-0 me-2">Histórico de Vendas</h4>
                            <span class="badge bg-primary rounded-pill">{{ $totalSales }} vendas</span>
                        </div>
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <!-- Search Bar -->
                            <div class="search-field d-none d-md-flex">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0">
                                        <i class="mdi mdi-magnify text-primary"></i>
                                    </span>
                                    <input type="text" class="form-control bg-transparent border-start-0 ps-0"
                                        placeholder="Pesquisar vendas..." id="salesSearch">
                                </div>
                            </div>
                            
                            <!-- Date Filter Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dateFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-filter-outline me-1"></i> Filtrar
                                </button>
                                <ul class="dropdown-menu p-3" aria-labelledby="dateFilterDropdown" style="min-width: 250px;">
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dateFilter" id="today" value="today">
                                            <label class="form-check-label" for="today">Hoje</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dateFilter" id="yesterday" value="yesterday">
                                            <label class="form-check-label" for="yesterday">Ontem</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dateFilter" id="lastWeek" value="lastWeek">
                                            <label class="form-check-label" for="lastWeek">Última Semana</label>
                                        </div>
                                    </li>
                                    <li class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dateFilter" id="lastMonth" value="lastMonth">
                                            <label class="form-check-label" for="lastMonth">Último Mês</label>
                                        </div>
                                    </li>
                                    <li class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="dateFilter" id="custom" value="custom">
                                            <label class="form-check-label" for="custom">Personalizado</label>
                                        </div>
                                    </li>
                                    <li id="customDateRange" class="d-none mb-3">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <input type="date" class="form-control form-control-sm" id="startDate">
                                            </div>
                                            <div class="col-6">
                                                <input type="date" class="form-control form-control-sm" id="endDate">
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <button class="btn btn-primary btn-sm w-100" id="applyFilter">Aplicar</button>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- Export Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="mdi mdi-download me-1"></i> Exportar
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <li><a class="dropdown-item" href="#" id="exportExcel"><i class="mdi mdi-file-excel me-2 text-success"></i>Excel</a></li>
                                    <li><a class="dropdown-item" href="#" id="exportPDF"><i class="mdi mdi-file-pdf me-2 text-danger"></i>PDF</a></li>
                                    <li><a class="dropdown-item" href="#" id="exportCSV"><i class="mdi mdi-file-delimited me-2 text-primary"></i>CSV</a></li>
                                </ul>
                            </div>
                            
                            <!-- New Sale Button - Opens POS Modal -->
                           {{--  <button type="button" class="btn btn-primary btn-icon-text px-4" data-bs-toggle="modal" data-bs-target="#posModal">
                                <i class="mdi mdi-plus btn-icon-prepend"></i>
                                Nova Venda
                            </button> --}}
                            {{-- nova venda com rout pos --}}
                            <a href="{{ route('pos.index') }}" class="btn btn-primary btn-icon-text px-4">
                                <i class="mdi mdi-plus btn-icon-prepend"></i>
                                Nova Venda
                            </a>
                        </div>
                    </div>

                    <!-- Enhanced Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle" id="salesTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">ID</th>
                                    <th class="py-3">Data</th>
                                    <th class="py-3">Total</th>
                                    <th class="py-3">Método de Pagamento</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-center" width="200">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $sale)
                                    <tr>
                                        <td class="py-3">
                                            <span class="fw-medium text-primary">
                                                #{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                    <i class="mdi mdi-calendar text-primary"></i>
                                                </span>
                                                <div>
                                                    <div class="fw-medium">
                                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                                                    </div>
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="fw-medium">MZN {{ number_format($sale->total_amount, 2) }}</div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <span class="bg-success bg-opacity-10 p-2 rounded me-2">
                                                    <i class="mdi {{ get_payment_icon_mdi($sale->payment_method) }} text-success"></i>
                                                </span>
                                                <span class="fw-medium">{{ ucfirst($sale->payment_method) }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="badge {{ get_status_class_staradmins($sale->status) }} rounded-pill px-3">
                                                <i class="mdi mdi-circle-medium me-1"></i>
                                                {{ ucfirst($sale->status) }}
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-primary btn-icon btn-sm" data-bs-toggle="tooltip"
                                                    title="Ver Detalhes" onclick="viewSaleDetails({{ $sale->id }})">
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <button class="btn btn-info btn-icon btn-sm" data-bs-toggle="tooltip"
                                                    title="Imprimir" onclick="printReceipt({{ $sale->id }})">
                                                    <i class="mdi mdi-printer"></i>
                                                </button>
                                                <button class="btn btn-warning btn-icon btn-sm" data-bs-toggle="tooltip"
                                                    title="Exportar PDF" onclick="exportSalePDF({{ $sale->id }})">
                                                    <i class="mdi mdi-download"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                        <div class="text-muted">
                            Mostrando <span class="fw-medium">{{ $sales->firstItem() }}</span> até
                            <span class="fw-medium">{{ $sales->lastItem() }}</span> de
                            <span class="fw-medium">{{ $sales->total() }}</span> registros
                        </div>
                        {{ $sales->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- POS Modal -->
    <div class="modal fade" id="posModal" tabindex="-1" aria-labelledby="posModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="posModalLabel">
                        <i class="mdi mdi-cart-plus me-2"></i>Nova Venda (POS)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Products Section -->
                            <div class="col-lg-8 p-4 border-end">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="card-title mb-0">Produtos</h6>
                                    <div class="input-group" style="max-width: 300px;">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="mdi mdi-magnify text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control bg-transparent border-start-0 ps-0"
                                            placeholder="Pesquisar produtos..." id="productSearch">
                                    </div>
                                </div>
                                
                                <div class="row" id="productsContainer">
                                    <!-- Products will be loaded here via JS -->
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="card-title mb-3">Itens Selecionados</h6>
                                    <div class="table-responsive">
                                        <table class="table table-hover" id="saleItemsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Produto</th>
                                                    <th>Quantidade</th>
                                                    <th>Preço</th>
                                                    <th>Total</th>
                                                    <th>Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Sale items will be loaded here via JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Payment Section -->
                            <div class="col-lg-4 p-4 bg-light">
                                <h6 class="card-title mb-4">Pagamento</h6>
                                
                                <div class="d-flex justify-content-between mb-3">
                                    <h5>Total:</h5>
                                    <h5 class="text-primary" id="saleTotalAmount">MZN 0.00</h5>
                                </div>
                                
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title text-muted mb-3">Métodos de Pagamento</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Dinheiro</label>
                                            <div class="input-group">
                                                <span class="input-group-text">MZN</span>
                                                <input type="number" class="form-control payment-input" id="cashPayment" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Cartão</label>
                                            <div class="input-group">
                                                <span class="input-group-text">MZN</span>
                                                <input type="number" class="form-control payment-input" id="cardPayment" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">M-Pesa</label>
                                            <div class="input-group">
                                                <span class="input-group-text">MZN</span>
                                                <input type="number" class="form-control payment-input" id="mpesaPayment" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">e-Mola</label>
                                            <div class="input-group">
                                                <span class="input-group-text">MZN</span>
                                                <input type="number" class="form-control payment-input" id="emolaPayment" value="0" min="0" step="0.01">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Troco</label>
                                    <input type="text" class="form-control" id="change" value="MZN 0.00" readonly>
                                </div>
                                
                                <button id="finalizeSaleButton" class="btn btn-primary w-100 mt-3">
                                    <i class="mdi mdi-check-circle me-1"></i>
                                    Finalizar Venda
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sale Details Modal -->
    <div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-labelledby="saleDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="saleDetailsModalLabel">Detalhes da Venda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="saleDetailsContent">
                    <!-- Sale details will be loaded here via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="printDetailsBtn">
                        <i class="mdi mdi-printer me-1"></i> Imprimir
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('assets/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ asset('assets/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/sweetalert2@10.js') }}"></script>
        <script src="{{ asset('assets/chart.js') }}"></script>
        <!-- Added PDF Export Libraries -->
        <script src="{{ asset('assets/jspdf.umd.min.js') }}"></script>
        <script src="{{ asset('assets/jspdf.plugin.autotable.min.js') }}"></script>
        <script src="{{ asset('assets/xlsx.full.min.js') }}"></script>
        
        <script>
            // Global variables
            let saleItems = [];
            let products = [];
            
            $(document).ready(function() {
                // Initialize DataTable
                $('#salesTable').DataTable({
                    paging: false,
                    info: false,
                    searching: true,
                    order: [[0, 'desc']]
                });
                
                // Initialize tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
                
                // Show/hide custom date range inputs
                $('input[name="dateFilter"]').change(function() {
                    if ($(this).val() === 'custom') {
                        $('#customDateRange').removeClass('d-none');
                    } else {
                        $('#customDateRange').addClass('d-none');
                    }
                });
                
                // Handle search
                $('#salesSearch').on('keyup', function() {
                    $('#salesTable').DataTable().search($(this).val()).draw();
                });
                
                // Load products when POS modal is opened
                $('#posModal').on('shown.bs.modal', function() {
                    loadProducts();
                });
                
                // Calculate change when payment values change
                $('.payment-input').on('input', function() {
                    calculateChange();
                });
                
                // Handle export buttons
                $('#exportExcel').click(function(e) {
                    e.preventDefault();
                    exportTableToExcel('salesTable', 'Vendas_' + formatDate(new Date()));
                });
                
                $('#exportPDF').click(function(e) {
                    e.preventDefault();
                    exportTableToPDF();
                });
                
                $('#exportCSV').click(function(e) {
                    e.preventDefault();
                    exportTableToCSV('salesTable', 'Vendas_' + formatDate(new Date()) + '.csv');
                });
                
                // Set up date filter event listener
                $('#applyFilter').click(function() {
                    applyDateFilter();
                });
                
                // Handle finalize sale button
                $('#finalizeSaleButton').click(function() {
                    finalizeSale();
                });
            });
            
            // Load products for POS
            function loadProducts() {
                $.ajax({
                    url: '{{ route("products.api.list") }}',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        products = response;
                        renderProducts(products);
                    },
                    error: function(error) {
                        console.error('Error loading products:', error);
                        showAlert('Erro', 'Não foi possível carregar os produtos.', 'error');
                    }
                });
            }
            
            // Render products in the POS modal
            function renderProducts(productList) {
                const container = $('#productsContainer');
                container.empty();
                
                productList.forEach(product => {
                    const productCard = `
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100 product-card" data-id="${product.id}" data-name="${product.name}" data-price="${product.price}">
                                <div class="card-body text-center">
                                    <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="mdi mdi-food text-primary" style="font-size: 32px;"></i>
                                    </div>
                                    <h6 class="card-title mb-1">${product.name}</h6>
                                    <p class="text-primary fw-bold mb-0">MZN ${parseFloat(product.price).toFixed(2)}</p>
                                </div>
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <button class="btn btn-sm btn-outline-primary w-100 add-product-btn">
                                        <i class="mdi mdi-plus me-1"></i> Adicionar
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.append(productCard);
                });
                
                // Add event listener for product cards
                $('.add-product-btn').on('click', function() {
                    const card = $(this).closest('.product-card');
                    const productId = card.data('id');
                    const productName = card.data('name');
                    const productPrice = parseFloat(card.data('price'));
                    
                    addItemToSale(productId, productName, productPrice);
                });
                
                // Set up product search
                $('#productSearch').on('keyup', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    $('.product-card').each(function() {
                        const productName = $(this).data('name').toLowerCase();
                        if (productName.includes(searchTerm)) {
                            $(this).closest('.col-lg-3').show();
                        } else {
                            $(this).closest('.col-lg-3').hide();
                        }
                    });
                });
            }
            
            // Add item to sale
            function addItemToSale(productId, productName, productPrice) {
                // Check if product already exists in the sale
                const existingItemIndex = saleItems.findIndex(item => item.id === productId);
                
                if (existingItemIndex !== -1) {
                    // Increment quantity if product already exists
                    saleItems[existingItemIndex].quantity += 1;
                    saleItems[existingItemIndex].total = saleItems[existingItemIndex].quantity * saleItems[existingItemIndex].price;
                } else {
                    // Add new item if product doesn't exist in the sale
                    const item = {
                        id: productId,
                        name: productName,
                        quantity: 1,
                        price: productPrice,
                        total: productPrice
                    };
                    saleItems.push(item);
                }
                
                updateSaleItemsTable();
            }
            
            // Update sale items table
            function updateSaleItemsTable() {
                const tableBody = $('#saleItemsTable tbody');
                let totalAmount = 0;
                
                tableBody.empty();
                
                if (saleItems.length === 0) {
                    tableBody.append('<tr><td colspan="5" class="text-center py-4">Nenhum item adicionado</td></tr>');
                } else {
                    saleItems.forEach((item, index) => {
                        const row = `
                            <tr>
                                <td>${item.name}</td>
                                <td>
                                    <div class="input-group input-group-sm" style="width: 120px;">
                                        <button class="btn btn-outline-secondary btn-decrease" data-index="${index}">-</button>
                                        <input type="number" class="form-control text-center" value="${item.quantity}" min="1" data-index="${index}">
                                        <button class="btn btn-outline-secondary btn-increase" data-index="${index}">+</button>
                                    </div>
                                </td>
                                <td>MZN ${item.price.toFixed(2)}</td>
                               <td>MZN ${item.price.toFixed(2)}</td>
                                <td>MZN ${item.total.toFixed(2)}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm btn-remove" data-index="${index}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                        totalAmount += item.total;
                    });
                }
                
                // Update total amount display
                $('#saleTotalAmount').text(`MZN ${totalAmount.toFixed(2)}`);
                
                // Attach event handlers for quantity controls
                $('.btn-increase').on('click', function() {
                    const index = $(this).data('index');
                    saleItems[index].quantity += 1;
                    saleItems[index].total = saleItems[index].quantity * saleItems[index].price;
                    updateSaleItemsTable();
                });
                
                $('.btn-decrease').on('click', function() {
                    const index = $(this).data('index');
                    if (saleItems[index].quantity > 1) {
                        saleItems[index].quantity -= 1;
                        saleItems[index].total = saleItems[index].quantity * saleItems[index].price;
                        updateSaleItemsTable();
                    }
                });
                
                $('.btn-remove').on('click', function() {
                    const index = $(this).data('index');
                    saleItems.splice(index, 1);
                    updateSaleItemsTable();
                });
                
                $('input[type="number"]').on('change', function() {
                    const index = $(this).data('index');
                    const newQuantity = parseInt($(this).val());
                    
                    if (newQuantity > 0) {
                        saleItems[index].quantity = newQuantity;
                        saleItems[index].total = saleItems[index].quantity * saleItems[index].price;
                        updateSaleItemsTable();
                    } else {
                        $(this).val(1);
                    }
                });
                
                // Calculate change
                calculateChange();
            }
            
            // Calculate change based on payment inputs
            function calculateChange() {
                const totalAmount = saleItems.reduce((sum, item) => sum + item.total, 0);
                const cashPayment = parseFloat($('#cashPayment').val()) || 0;
                const cardPayment = parseFloat($('#cardPayment').val()) || 0;
                const mpesaPayment = parseFloat($('#mpesaPayment').val()) || 0;
                const emolaPayment = parseFloat($('#emolaPayment').val()) || 0;
                
                const totalPayment = cashPayment + cardPayment + mpesaPayment + emolaPayment;
                const change = totalPayment - totalAmount;
                
                $('#change').val(`MZN ${Math.max(0, change).toFixed(2)}`);
            }
            
            // View sale details
            function viewSaleDetails(saleId) {
                $.ajax({
                    url: `{{ url('/sales') }}/${saleId}/details`,
                    type: 'GET',
                    success: function(response) {
                        $('#saleDetailsContent').html(response);
                        $('#saleDetailsModal').modal('show');
                        
                        // Store the sale ID for printing
                        $('#printDetailsBtn').data('sale-id', saleId);
                    },
                    error: function(error) {
                        console.error('Error loading sale details:', error);
                        showAlert('Erro', 'Não foi possível carregar os detalhes da venda.', 'error');
                    }
                });
            }
            
            // Print receipt
            function printReceipt(saleId) {
                const receiptUrl = `{{ url('/sales') }}/${saleId}/receipt`;
                const receiptWindow = window.open(receiptUrl, '_blank', 'width=400,height=600');
                
                if (receiptWindow) {
                    receiptWindow.focus();
                    receiptWindow.onload = function() {
                        setTimeout(function() {
                            receiptWindow.print();
                        }, 1000);
                    };
                } else {
                    showAlert('Aviso', 'Por favor, permita popups para imprimir o recibo.', 'warning');
                }
            }
            
            // Export single sale to PDF
            function exportSalePDF(saleId) {
                $.ajax({
                    url: `{{ url('/sales') }}/${saleId}/export-pdf`,
                    type: 'GET',
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob) {
                        const link = document.createElement('a');
                        link.href = window.URL.createObjectURL(blob);
                        link.download = `Venda_${saleId}.pdf`;
                        link.click();
                    },
                    error: function(error) {
                        console.error('Error exporting sale:', error);
                        showAlert('Erro', 'Não foi possível exportar a venda para PDF.', 'error');
                    }
                });
            }
            
            // Finalize sale
            function finalizeSale() {
                if (saleItems.length === 0) {
                    showAlert('Erro', 'Por favor, adicione pelo menos um item à venda.', 'error');
                    return;
                }
                
                const totalAmount = saleItems.reduce((sum, item) => sum + item.total, 0);
                const cashPayment = parseFloat($('#cashPayment').val()) || 0;
                const cardPayment = parseFloat($('#cardPayment').val()) || 0;
                const mpesaPayment = parseFloat($('#mpesaPayment').val()) || 0;
                const emolaPayment = parseFloat($('#emolaPayment').val()) || 0;
                
                const totalPayment = cashPayment + cardPayment + mpesaPayment + emolaPayment;
                
                if (totalPayment < totalAmount) {
                    showAlert('Erro', 'O valor pago é menor que o total da venda.', 'error');
                    return;
                }
                
                // Prepare sale data
                const saleData = {
                    items: saleItems.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        unit_price: item.price
                    })),
                    cashPayment: cashPayment,
                    cardPayment: cardPayment,
                    mpesaPayment: mpesaPayment,
                    emolaPayment: emolaPayment
                };
                
                // Process sale
                $.ajax({
                    url: '{{ route("sales.process") }}',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(saleData),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Venda Realizada!',
                                text: 'A venda foi processada com sucesso.',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Imprimir Recibo',
                                cancelButtonText: 'Fechar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    printReceipt(response.saleId);
                                }
                                // Reset sale and close modal
                                resetSale();
                                $('#posModal').modal('hide');
                                // Reload page to show the new sale
                                location.reload();
                            });
                        } else {
                            showAlert('Erro', response.message || 'Erro ao processar a venda.', 'error');
                        }
                    },
                    error: function(error) {
                        console.error('Error processing sale:', error);
                        showAlert('Erro', 'Ocorreu um erro ao processar a venda.', 'error');
                    }
                });
            }
            
            // Reset sale
            function resetSale() {
                saleItems = [];
                updateSaleItemsTable();
                $('#cashPayment, #cardPayment, #mpesaPayment, #emolaPayment').val(0);
                calculateChange();
            }
            
            // Apply date filter
            function applyDateFilter() {
                const selectedFilter = $('input[name="dateFilter"]:checked').val();
                let url = '{{ route("sales.index") }}';
                
                switch (selectedFilter) {
                    case 'today':
                        url += '?filter=today';
                        break;
                    case 'yesterday':
                        url += '?filter=yesterday';
                        break;
                    case 'lastWeek':
                        url += '?filter=lastWeek';
                        break;
                    case 'lastMonth':
                        url += '?filter=lastMonth';
                        break;
                    case 'custom':
                        const startDate = $('#startDate').val();
                        const endDate = $('#endDate').val();
                        
                        if (startDate && endDate) {
                            url += `?start_date=${startDate}&end_date=${endDate}`;
                        } else {
                            showAlert('Erro', 'Por favor, selecione as datas de início e fim.', 'error');
                            return;
                        }
                        break;
                }
                
                window.location.href = url;
            }
            
            // Export all sales to Excel
            function exportTableToExcel(tableId, filename) {
                const table = document.getElementById(tableId);
                const ws = XLSX.utils.table_to_sheet(table);
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Vendas");
                XLSX.writeFile(wb, filename + '.xlsx');
            }
            
            // Export all sales to PDF
            function exportTableToPDF() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF('l', 'pt', 'a4');
                
                // Add title
                doc.setFontSize(18);
                doc.text('Relatório de Vendas', 40, 40);
                doc.setFontSize(12);
                doc.text(`Gerado em: ${formatDate(new Date())}`, 40, 60);
                
                // Create table data
                const tableData = [];
                const headers = ['ID', 'Data', 'Total', 'Método', 'Status'];
                
                // Get table rows
                $('#salesTable tbody tr').each(function() {
                    const rowData = [];
                    $(this).find('td').each(function(index) {
                        if (index <= 4) { // Only get the first 5 columns
                            if (index === 1) { // Format date column
                                const dateText = $(this).find('div.fw-medium').text().trim();
                                const timeText = $(this).find('small').text().trim();
                                rowData.push(`${dateText} ${timeText}`);
                            } else if (index === 4) { // Status column
                                rowData.push($(this).find('div.badge').text().trim());
                            } else {
                                rowData.push($(this).text().trim());
                            }
                        }
                    });
                    tableData.push(rowData);
                });
                
                // Add table to PDF
                doc.autoTable({
                    head: [headers],
                    body: tableData,
                    startY: 80,
                    styles: {
                        fontSize: 10,
                        cellPadding: 6,
                        overflow: 'linebreak'
                    },
                    headStyles: {
                        fillColor: [41, 128, 185],
                        textColor: 255
                    },
                    alternateRowStyles: {
                        fillColor: [245, 245, 245]
                    }
                });
                
                // Save PDF
                doc.save('Relatório_Vendas_' + formatDate(new Date()) + '.pdf');
            }
            
            // Export all sales to CSV
            function exportTableToCSV(tableId, filename) {
                const table = document.getElementById(tableId);
                const rows = table.querySelectorAll('tr');
                const csvContent = [];
                
                // Process each row
                rows.forEach(row => {
                    const rowData = [];
                    const cells = row.querySelectorAll('th, td');
                    
                    cells.forEach((cell, index) => {
                        if (index <= 4) { // Only include the first 5 columns
                            let cellText = '';
                            
                            if (cell.querySelector('.fw-medium')) {
                                cellText = cell.querySelector('.fw-medium').textContent.trim();
                            } else if (cell.querySelector('.badge')) {
                                cellText = cell.querySelector('.badge').textContent.trim();
                            } else {
                                cellText = cell.textContent.trim();
                            }
                            
                            // Clean up the text and handle commas
                            cellText = cellText.replace(/\s+/g, ' ').trim();
                            if (cellText.includes(',')) {
                                cellText = `"${cellText}"`;
                            }
                            
                            rowData.push(cellText);
                        }
                    });
                    
                    if (rowData.length > 0) {
                        csvContent.push(rowData.join(','));
                    }
                });
                
                // Create and download CSV file
                const csvData = csvContent.join('\n');
                const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
                const link = document.createElement('a');
                
                if (navigator.msSaveBlob) {
                    // For IE
                    navigator.msSaveBlob(blob, filename);
                } else {
                    // For other browsers
                    link.href = URL.createObjectURL(blob);
                    link.setAttribute('download', filename);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
            
            // Helper function to format date
            function formatDate(date) {
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                return `${day}-${month}-${year}`;
            }
            
            // Show alert
            function showAlert(title, text, icon) {
                return Swal.fire({
                    title,
                    text,
                    icon,
                    confirmButtonColor: '#3085d6'
                });
            }
        </script>
    @endpush
@endsection