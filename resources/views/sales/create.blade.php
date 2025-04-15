
<!-- resources/views/sales/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create New Sale</h4>
                
                @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif
                
                <form method="POST" action="{{ route('sales.store') }}" id="sale-form">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="product-selection mb-4">
                                <h5>Products</h5>
                                <div class="table-responsive">
                                    <table class="table" id="products-table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="row-template">
                                                <td>
                                                    <select class="form-control product-select" name="products[0][id]" required>
                                                        <option value="">Select Product</option>
                                                        @foreach($products as $product)
                                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                            {{ $product->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="product-price">0.00</td>
                                                <td>
                                                    <input type="number" class="form-control product-quantity" 
                                                           name="products[0][quantity]" min="1" value="1" required>
                                                </td>
                                                <td class="product-subtotal">0.00</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-row">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <button type="button" class="btn btn-success" id="add-product">
                                    <i class="mdi mdi-plus"></i> Add Product
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5>Payment Details</h5>
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select class="form-control" name="payment_method" id="payment-method" required>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="mpesa">MPesa</option>
                                            <option value="emola">Emola</option>
                                            <option value="mixed">Mixed Payment</option>
                                        </select>
                                    </div>
                                    
                                    <div id="mixed-payment" style="display: none;">
                                        <div class="form-group">
                                            <label>Cash Amount</label>
                                            <input type="number" class="form-control" name="cash_amount" value="0" min="0" step="0.01">
                                        </div>
                                        <div class="form-group">
                                            <label>Card Amount</label>
                                            <input type="number" class="form-control" name="card_amount" value="0" min="0" step="0.01">
                                        </div>
                                        <div class="form-group">
                                            <label>MPesa Amount</label>
                                            <input type="number" class="form-control" name="mpesa_amount" value="0" min="0" step="0.01">
                                        </div>
                                        <div class="form-group">
                                            <label>Emola Amount</label>
                                            <input type="number" class="form-control" name="emola_amount" value="0" min="0" step="0.01">
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <h4>Total: <span id="total-amount">0.00</span></h4>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-block mt-4">
                                        Complete Sale
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Continuing from resources/views/sales/create.blade.php (JavaScript) -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsTable = document.getElementById('products-table');
        const addProductBtn = document.getElementById('add-product');
        const rowTemplate = document.getElementById('row-template');
        const paymentMethod = document.getElementById('payment-method');
        const mixedPayment = document.getElementById('mixed-payment');
        const totalAmount = document.getElementById('total-amount');
        
        let rowCount = 1;
        
        // Add product row
        addProductBtn.addEventListener('click', function() {
            const newRow = rowTemplate.cloneNode(true);
            newRow.id = '';
            const inputs = newRow.querySelectorAll('select, input');
            
            inputs.forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace('[0]', '[' + rowCount + ']'));
            });
            
            productsTable.querySelector('tbody').appendChild(newRow);
            rowCount++;
            
            updateTotals();
        });
        
        // Remove product row
        productsTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row') || e.target.parentElement.classList.contains('remove-row')) {
                const row = e.target.closest('tr');
                if (document.querySelectorAll('#products-table tbody tr').length > 1) {
                    row.remove();
                    updateTotals();
                }
            }
        });
        
        // Update price and subtotal when product changes
        productsTable.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const row = e.target.closest('tr');
                const option = e.target.options[e.target.selectedIndex];
                const price = parseFloat(option.getAttribute('data-price'));
                const quantity = parseInt(row.querySelector('.product-quantity').value);
                
                row.querySelector('.product-price').textContent = price.toFixed(2);
                row.querySelector('.product-subtotal').textContent = (price * quantity).toFixed(2);
                
                updateTotals();
            }
        });
        
        // Update subtotal when quantity changes
        productsTable.addEventListener('input', function(e) {
            if (e.target.classList.contains('product-quantity')) {
                const row = e.target.closest('tr');
                const price = parseFloat(row.querySelector('.product-price').textContent);
                const quantity = parseInt(e.target.value);
                
                row.querySelector('.product-subtotal').textContent = (price * quantity).toFixed(2);
                
                updateTotals();
            }
        });
        
        // Toggle mixed payment fields
        paymentMethod.addEventListener('change', function() {
            if (this.value === 'mixed') {
                mixedPayment.style.display = 'block';
            } else {
                mixedPayment.style.display = 'none';
            }
        });
        
        // Calculate total amount
        function updateTotals() {
            let total = 0;
            document.querySelectorAll('.product-subtotal').forEach(function(el) {
                total += parseFloat(el.textContent);
            });
            
            totalAmount.textContent = total.toFixed(2);
        }
        
        // Initialize
        updateTotals();
    });
</script>
@endsection
<!-- End of resources/views/sales/create.blade.php -->
