<?php
// pos.php
// Point of Sale Interface - Barcode Scanner & Name Entry (Enter Key Only)
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Point of Sale</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="../../dist/css/font.css">
    
    <!-- DataTables & Bootstrap 4 -->
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

    <style>
        .content-wrapper { min-height: 100vh; overflow-x: hidden; }
        
        /* Product Grid/List Styles */
        .product-card {
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 15px;
            background: #fff;
            height: 100%; /* Ensure cards stretch to equal height */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-color: #007bff;
        }
        .product-card .card-body {
            padding: 10px;
            text-align: center;
            width: 100%;
        }
        .product-price {
            font-size: 1.1rem;
            font-weight: bold;
            color: #28a745;
        }
        .product-stock {
            font-size: 0.8rem;
            color: #6c757d;
        }

        /* Cart Styles */
        .cart-container {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            height: calc(100vh - 150px);
            display: flex;
            flex-direction: column;
        }
        .cart-items {
            flex-grow: 1;
            overflow-y: auto;
            margin-bottom: 15px;
        }
        .cart-summary {
            border-top: 2px solid #eee;
            padding-top: 10px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-row {
            font-size: 1.2rem;
            font-weight: bold;
            color: #343a40;
            border-top: 1px solid #ddd;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        /* Receipt Modal Styles */
        .receipt-box {
            font-family: 'Courier New', Courier, monospace;
            background: #fff;
            padding: 20px;
            border: 1px dashed #333;
            margin: 0 auto;
            width: 100%;
            max-width: 400px;
        }
        .receipt-header { text-align: center; margin-bottom: 20px; }
        .receipt-items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .receipt-items th { text-align: left; border-bottom: 1px dashed #000; }
        .receipt-items td { padding: 5px 0; }
        .receipt-totals { text-align: right; }
        .receipt-line { border-bottom: 1px dashed #000; margin: 10px 0; }
        
        .qty-control {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        /* Quick Add / Barcode Styles */
        .quick-add-section {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        /* 5 Column Grid Logic */
        .col-5-cols {
            position: relative;
            width: 100%;
            padding-right: 5px;
            padding-left: 5px;
            flex: 0 0 20%; /* 100% / 5 = 20% */
            max-width: 20%;
        }

        /* Responsive adjustments for the 5-column grid */
        @media (max-width: 1200px) {
            .col-5-cols { flex: 0 0 25%; max-width: 25%; } /* 4 columns on smaller desktops */
        }
        @media (max-width: 992px) {
            .col-5-cols { flex: 0 0 33.333333%; max-width: 33.333333%; } /* 3 columns on tablets */
        }
        @media (max-width: 576px) {
            .col-5-cols { flex: 0 0 50%; max-width: 50%; } /* 2 columns on mobile */
        }

        /* Toast Notification Styles */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast-item {
            background: #333;
            color: #fff;
            padding: 12px 24px;
            border-radius: 4px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .toast-item.show { opacity: 1; }
        .toast-item.success { border-left: 5px solid #28a745; }
        .toast-item.error { border-left: 5px solid #dc3545; }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
             <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button"><i class="fas fa-expand-arrows-alt"></i></a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar -->
    <?php include '../../pages/sidebar/sidebar.php'; ?> 

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Point of Sale</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">POS</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    
                    <!-- Left Column: Products -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Products</h3>
                                <div class="card-tools">
                                    <input type="text" id="productSearch" class="form-control form-control-sm" placeholder="Filter grid..." style="width: 200px;">
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="row p-2" id="productGrid" style="max-height: 75vh; overflow-y: auto;">
                                    <!-- Products will be injected here via JS -->
                                    <div class="col-12 text-center p-5">
                                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                                        <p class="mt-2">Loading products...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Cart -->
                    <div class="col-md-4">
                        <div class="cart-container">
                            <h4 class="mb-3"><i class="fas fa-shopping-cart"></i> Current Order</h4>
                            
                            <!-- Quick Add / Barcode Section -->
                            <div class="quick-add-section">
                                <label class="small text-muted">Scan Barcode or Type Code & Press Enter</label>
                                <div class="input-group mb-1">
                                    <input type="text" id="quickAddInput" class="form-control" placeholder="Scan code here..." autocomplete="off" autofocus>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="button" onclick="triggerManualAdd()"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <small class="text-muted">System prioritizes Product Code (Barcode)</small>
                            </div>

                            <div class="cart-items">
                                <table class="table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th width="60">Qty</th>
                                            <th class="text-right">Price</th>
                                            <th width="30"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="cartTableBody">
                                        <!-- Cart items go here -->
                                    </tbody>
                                </table>
                                <div id="emptyCartMsg" class="text-center text-muted mt-5">
                                    <i class="fas fa-basket-shopping fa-3x mb-3"></i>
                                    <p>Cart is empty</p>
                                </div>
                            </div>

                            <div class="cart-summary">
                                <div class="summary-row">
                                    <span>Subtotal:</span>
                                    <span id="lblSubtotal">₱0.00</span>
                                </div>
                                <div class="summary-row text-muted">
                                    <span>Tax (12%):</span>
                                    <span id="lblTax">₱0.00</span>
                                </div>
                                <div class="summary-row text-danger">
                                    <span>Discount:</span>
                                    <span id="lblDiscount">- ₱0.00</span>
                                </div>
                                <div class="summary-row total-row">
                                    <span>Total:</span>
                                    <span id="lblTotal">₱0.00</span>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="button" class="btn btn-success btn-block btn-lg" id="btnCheckout" onclick="openCheckoutModal()" disabled>
                                        <i class="fas fa-money-bill-wave"></i> Checkout
                                    </button>
                                    <button type="button" class="btn btn-danger btn-block btn-sm mt-2" onclick="clearCart()">
                                        <i class="fas fa-trash"></i> Cancel Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Process Payment</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="checkoutForm">
                        <div class="form-group">
                            <label>Customer</label>
                            <select id="posCustomer" class="form-control select2">
                                <option value="">Walk-in Customer</option>
                                <!-- Customers loaded via JS -->
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select id="posPaymentMethod" class="form-control" onchange="togglePaymentInput()">
                                        <option value="Cash">Cash</option>
                                        <option value="E-Wallet">E-Wallet</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Discount Amount (₱)</label>
                                    <input type="number" id="posDiscount" class="form-control" value="0" min="0" step="0.01" oninput="updateModalTotals()">
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between">
                                <span>Total Due:</span>
                                <strong id="modalTotalDue">₱0.00</strong>
                            </div>
                        </div>

                        <div class="form-group" id="tenderedGroup">
                            <label>Amount Tendered (₱)</label>
                            <input type="number" id="posAmountTendered" class="form-control" placeholder="0.00" step="0.01" oninput="calculateChange()">
                            <small class="text-danger" id="changeError"></small>
                        </div>

                        <div class="d-flex justify-content-between font-weight-bold mt-2">
                            <span>Change:</span>
                            <span id="modalChange">₱0.00</span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="processPayment()">
                        <i class="fas fa-check"></i> Complete Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

</div>

<!-- REQUIRED SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
 $(document).ready(function() {
    const API_URL = '../../api/routes.php';
    
    // State
    let products = [];
    let customers = [];
    let cart = [];
    let currentTotals = { subtotal: 0, tax: 0, discount: 0, total: 0 };

    // 1. Load Initial Data
    function loadPosData() {
        $.get(API_URL + '/pos', function(res) {
            if(res.status === 'success') {
                products = res.data.products;
                customers = res.data.customers;
                renderProducts(products);
                populateCustomerSelect(customers);
                // Keep focus on input for scanner
                $("#quickAddInput").focus();
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }).fail(function() {
            Swal.fire('Connection Error', 'Could not load POS data', 'error');
        });
    }

    // 2. Render Products (Grid View - 5 Columns)
    function renderProducts(data) {
        const grid = $('#productGrid');
        grid.empty();
        
        if(data.length === 0) {
            grid.html('<div class="col-12 text-center p-5">No products found</div>');
            return;
        }

        data.forEach(prod => {
            let card = `
                <div class="col-5-cols">
                    <div class="product-card" onclick="addToCart(${prod.objid})">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-truncate" title="${prod.product_name}">${prod.product_name}</h6>
                            <div class="product-price">₱${parseFloat(prod.price).toFixed(2)}</div>
                            <div class="product-stock">Stock: ${prod.stock}</div>
                            <div class="text-muted small">Code: ${prod.product_code}</div>
                        </div>
                    </div>
                </div>
            `;
            grid.append(card);
        });
    }

    // 3. Populate Customer Select
    function populateCustomerSelect(data) {
        const select = $('#posCustomer');
        data.forEach(cust => {
            let option = `<option value="${cust.objid}">${cust.fullname}</option>`;
            select.append(option);
        });
    }

    // 4. Manual Add / Barcode Search Logic (ENTER KEY TRIGGERED)
    window.triggerManualAdd = function() {
        const input = $('#quickAddInput');
        const term = input.val().trim();
        
        if (!term) return;

        // 1. Exact Match: Product Code (Barcode)
        let product = products.find(p => p.product_code === term);
        
        // 2. Fallback: Exact Match: Product Name
        if (!product) {
            product = products.find(p => p.product_name.toLowerCase() === term.toLowerCase());
        }

        if (product) {
            addToCart(product.objid);
            input.val(''); // Clear input for next scan
            input.focus(); // Keep focus
            
            // Show small toast notification instead of alert
            showToast(`Added: ${product.product_name}`, 'success');
        } else {
            showToast(`Product not found: ${term}`, 'error');
            input.select(); // Select text for easy retry
        }
    };

    // Listen for Enter Key on the input
    $('#quickAddInput').on('keypress', function(e) {
        if(e.which == 13) {
            e.preventDefault();
            triggerManualAdd();
        }
    });

    // Refocus input when clicking anywhere in the cart area (except buttons)
    $('.cart-container').on('click', function(e) {
        if (!$(e.target).is('button, input, select')) {
            $("#quickAddInput").focus();
        }
    });

    // Toast Notification Helper
    function showToast(message, type = 'success') {
        const toast = $(`<div class="toast-item ${type}">${message}</div>`);
        $('#toastContainer').append(toast);
        
        // Trigger reflow
        void toast[0].offsetWidth;
        toast.addClass('show');

        setTimeout(() => {
            toast.removeClass('show');
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    // 5. Cart Logic
    window.addToCart = function(objid) {
        const product = products.find(p => p.objid == objid);
        if (!product) return;

        // Check if already in cart
        const existingItem = cart.find(i => i.objid == objid);
        if (existingItem) {
            if (existingItem.qty < product.stock) {
                existingItem.qty++;
            } else {
                showToast('Max stock reached for ' + product.product_name, 'error');
                return;
            }
        } else {
            cart.push({
                objid: product.objid,
                product_code: product.product_code,
                product_name: product.product_name,
                price: parseFloat(product.price),
                qty: 1
            });
        }
        renderCart();
    };

    window.updateQty = function(objid, change) {
        const item = cart.find(i => i.objid == objid);
        const product = products.find(p => p.objid == objid);
        
        if (item) {
            const newQty = item.qty + change;
            if (newQty > 0 && newQty <= product.stock) {
                item.qty = newQty;
            } else if (newQty > product.stock) {
                showToast('Only ' + product.stock + ' items available', 'error');
            } else if (newQty <= 0) {
                // Optional: remove item if qty goes to 0
                removeFromCart(objid);
                return; 
            }
        }
        renderCart();
    };

    window.removeFromCart = function(objid) {
        cart = cart.filter(i => i.objid != objid);
        renderCart();
    };

    window.clearCart = function() {
        if(cart.length === 0) return;
        Swal.fire({
            title: 'Clear Cart?',
            text: "Are you sure you want to remove all items?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, clear it!'
        }).then((result) => {
            if (result.isConfirmed) {
                cart = [];
                renderCart();
            }
        });
    };

    function renderCart() {
        const tbody = $('#cartTableBody');
        const emptyMsg = $('#emptyCartMsg');
        const btnCheckout = $('#btnCheckout');
        
        tbody.empty();

        if (cart.length === 0) {
            emptyMsg.show();
            btnCheckout.prop('disabled', true);
        } else {
            emptyMsg.hide();
            btnCheckout.prop('disabled', false);
            
            cart.forEach(item => {
                const subtotal = item.price * item.qty;
                const row = `
                    <tr>
                        <td>
                            <div class="font-weight-bold">${item.product_name}</div>
                            <small class="text-muted">Code: ${item.product_code}</small>
                        </td>
                        <td>
                            <div class="qty-control">
                                <button class="btn btn-xs btn-secondary" onclick="updateQty(${item.objid}, -1)">-</button>
                                <span>${item.qty}</span>
                                <button class="btn btn-xs btn-secondary" onclick="updateQty(${item.objid}, 1)">+</button>
                            </div>
                        </td>
                        <td class="text-right">₱${subtotal.toFixed(2)}</td>
                        <td>
                            <button class="btn btn-xs btn-danger" onclick="removeFromCart(${item.objid})"><i class="fas fa-times"></i></button>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }
        calculateTotals();
    }

    function calculateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        const tax = subtotal * 0.12;
        const total = subtotal + tax; 
        const discount = 0; // Default visual discount

        currentTotals = { subtotal, tax, discount, total };

        $('#lblSubtotal').text('₱' + subtotal.toFixed(2));
        $('#lblTax').text('₱' + tax.toFixed(2));
        $('#lblDiscount').text('- ₱' + discount.toFixed(2));
        $('#lblTotal').text('₱' + total.toFixed(2));
    }

    // 6. Checkout Modal Logic
    window.openCheckoutModal = function() {
        // Reset Modal Inputs
        $('#posDiscount').val(0);
        $('#posAmountTendered').val('');
        $('#modalChange').text('₱0.00');
        $('#changeError').text('');
        $('#posPaymentMethod').val('Cash').trigger('change');
        
        updateModalTotals();
        $('#checkoutModal').modal('show');
    };

    window.togglePaymentInput = function() {
        const method = $('#posPaymentMethod').val();
        if(method === 'E-Wallet') {
            $('#tenderedGroup').hide();
            $('#modalChange').text('₱0.00');
        } else {
            $('#tenderedGroup').show();
        }
    };

    window.updateModalTotals = function() {
        const discount = parseFloat($('#posDiscount').val()) || 0;
        const netTotal = currentTotals.total - discount;
        
        if (netTotal < 0) {
            $('#modalTotalDue').text('₱0.00');
        } else {
            $('#modalTotalDue').text('₱' + netTotal.toFixed(2));
        }
        calculateChange();
    };

    window.calculateChange = function() {
        const discount = parseFloat($('#posDiscount').val()) || 0;
        const netTotal = currentTotals.total - discount;
        const tendered = parseFloat($('#posAmountTendered').val()) || 0;
        
        const change = tendered - netTotal;
        
        if (change < 0) {
            $('#modalChange').text('₱0.00').addClass('text-danger');
            $('#changeError').text('Insufficient amount');
        } else {
            $('#modalChange').text('₱' + change.toFixed(2)).removeClass('text-danger');
            $('#changeError').text('');
        }
    };

    window.processPayment = function() {
        const customerId = $('#posCustomer').val();
        const discount = parseFloat($('#posDiscount').val()) || 0;
        const paymentMethod = $('#posPaymentMethod').val();
        let amountTendered = parseFloat($('#posAmountTendered').val()) || 0;
        
        const netTotal = currentTotals.total - discount;

        if (paymentMethod === 'Cash' && amountTendered < netTotal) {
            Swal.fire('Error', 'Amount tendered is less than total due.', 'error');
            return;
        }
        
        if (paymentMethod === 'E-Wallet') {
            amountTendered = netTotal;
        }

        const payload = {
            customer_id: customerId,
            discount: discount,
            payment_method: paymentMethod,
            amount_tendered: amountTendered,
            cart: cart
        };

        // AJAX Call
        $.ajax({
            url: API_URL + '/pos',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                if (res.status === 'success') {
                    $('#checkoutModal').modal('hide');
                    showReceipt(res.receipt);
                    cart = [];
                    renderCart();
                } else {
                    Swal.fire('Transaction Failed', res.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Server Error', 'Could not process transaction.', 'error');
            }
        });
    };

    window.showReceipt = function(data) {
        let itemsHtml = '';
        data.items.forEach(item => {
            itemsHtml += `
                <tr>
                    <td colspan="2">${item.product_name}</td>
                    <td class="text-right">${item.qty} x ${item.price.toFixed(2)}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">${(item.price * item.qty).toFixed(2)}</td>
                </tr>
            `;
        });

        const receiptHtml = `
            <div class="receipt-box">
                <div class="receipt-header">
                    <h3>STORE RECEIPT</h3>
                    <p>Order #: ${data.order_id}</p>
                    <p>Date: ${data.date}</p>
                    <p>Customer: ${data.customer}</p>
                </div>
                <table class="receipt-items">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th class="text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsHtml}
                    </tbody>
                </table>
                <div class="receipt-line"></div>
                <div class="receipt-totals">
                    <p>Subtotal: ...... ${data.subtotal}</p>
                    <p>Tax (12%): .... ${data.tax}</p>
                    <p>Discount: ..... -${data.discount}</p>
                    <p><strong>TOTAL: ...... ${data.total}</strong></p>
                    <div class="receipt-line"></div>
                    <p>Payment: ...... ${data.payment_method}</p>
                    <p>Tendered: ..... ${data.tendered}</p>
                    <p>Change: ...... ${data.change}</p>
                </div>
                <div class="receipt-line"></div>
                <div class="text-center mt-3">
                    <p>Thank you for shopping!</p>
                </div>
            </div>
        `;

        Swal.fire({
            title: 'Payment Success!',
            html: receiptHtml,
            width: '500px',
            showCancelButton: true,
            confirmButtonText: 'Print',
            cancelButtonText: 'Close',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.print();
            }
        });
    };

    // Initialize
    loadPosData();
});
</script>
</body>
</html>