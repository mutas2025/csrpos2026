<?php
// transactions.php
// Transaction History Page - View all transactions and reprint receipts
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../../index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaction History</title>

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
        
        /* Receipt Styles */
        .receipt-container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            font-family: 'Courier New', Courier, monospace;
        }
        
        .receipt {
            background: white;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 1px dashed #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .receipt-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        
        .receipt-header p {
            margin: 5px 0;
            font-size: 12px;
        }
        
        .receipt-info {
            margin-bottom: 15px;
            font-size: 12px;
            border-bottom: 1px dashed #333;
            padding-bottom: 10px;
        }
        
        .receipt-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .receipt-items {
            width: 100%;
            margin-bottom: 15px;
            font-size: 12px;
            border-bottom: 1px dashed #333;
        }
        
        .receipt-items th {
            text-align: left;
            border-bottom: 1px dotted #333;
            padding-bottom: 5px;
        }
        
        .receipt-items td {
            padding: 5px 0;
        }
        
        .receipt-totals {
            text-align: right;
            font-size: 12px;
            margin-bottom: 15px;
        }
        
        .receipt-totals p {
            margin: 5px 0;
        }
        
        .receipt-footer {
            text-align: center;
            font-size: 11px;
            border-top: 1px dashed #333;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .status-badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
            display: inline-block;
        }
        
        .status-completed {
            background: #d4edda;
            color: #155724;
        }
        
        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .transaction-row {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .transaction-row:hover {
            background-color: #f5f5f5;
        }
        
        .filter-card {
            margin-bottom: 20px;
        }
        
        .stat-card {
            transition: transform 0.2s;
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }

        /* PRINT FIX STYLES */
        @media print {
            /* Hide all elements by default */
            body * {
                visibility: hidden;
            }
            
            /* Show the specific receipt container and its children */
            #receiptToPrint, #receiptToPrint * {
                visibility: visible;
            }
            
            /* Position the receipt to take up the full page */
            #receiptToPrint {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
            }

            /* Hide backgrounds to ensure clean printing (optional) */
            .receipt {
                box-shadow: none;
                border: none;
            }
        }
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
                        <h1 class="m-0">Transaction History</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Transactions</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                
              

                <!-- Transactions Table -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i> All Transactions
                        </h3>
                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 300px;">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search by Order # or Customer...">
                                <div class="input-group-append">
                                    <button type="button" id="searchBtn" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button type="button" id="clearSearchBtn" class="btn btn-default">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="transactionsTable">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Date & Time</th>
                                    <th>Total Amount</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsTableBody">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Loading transactions...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer clearfix">
                        <div class="row">
                            <div class="col-sm-6">
                                <div id="paginationInfo" class="text-muted"></div>
                            </div>
                            <div class="col-sm-6">
                                <nav aria-label="Page navigation">
                                    <ul class="pagination justify-content-end" id="pagination"></ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Reprint Receipt Modal -->
<div class="modal fade" id="reprintReceiptModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-receipt"></i> Reprint Receipt
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" id="receiptModalBody">
                <!-- Receipt will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="printReceiptBtn">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle"></i> Transaction Details
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Details will be loaded here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="reprintFromDetailsBtn">
                    <i class="fas fa-print"></i> Reprint Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<!-- REQUIRED SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
 $(document).ready(function() {
    const API_URL = '../../api/routes.php';
    let currentPage = 1;
    let currentSearch = '';
    let currentOrderId = null;
    
    // Load statistics
    function loadStatistics() {
        $.get(API_URL + '/transactions/count', function(res) {
            if (res.status === 'success') {
                $('#todayCount').text(res.data.today.count);
                $('#todayTotal').text('₱' + res.data.today.total.toFixed(2));
                $('#monthCount').text(res.data.this_month.count);
                $('#totalCount').text(res.data.total_all.count);
            }
        }).fail(function() {
            console.error('Failed to load statistics');
        });
    }
    
    // Load transactions
    function loadTransactions(page = 1, search = '') {
        let url = API_URL + '/transactions?page=' + page + '&limit=20';
        
        if (search) {
            url = API_URL + '/transactions/search?keyword=' + encodeURIComponent(search);
        }
        
        $('#transactionsTableBody').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading...</td></tr>');
        
        $.get(url, function(res) {
            if (res.status === 'success') {
                if (search) {
                    renderTransactions(res.data.transactions);
                    updatePagination(null, search);
                    $('#paginationInfo').text('Found ' + res.data.count + ' transaction(s)');
                } else {
                    renderTransactions(res.data.transactions);
                    updatePagination(res.data.pagination, '');
                    $('#paginationInfo').text('Showing ' + res.data.transactions.length + ' of ' + res.data.pagination.total + ' transactions');
                }
            } else {
                $('#transactionsTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error: ' + (res.message || 'Unknown error') + '</td></tr>');
            }
        }).fail(function(xhr) {
            let errorMsg = 'Failed to load transactions';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            $('#transactionsTableBody').html('<tr><td colspan="7" class="text-center text-danger">' + errorMsg + '</td></tr>');
        });
    }
    
    // Render transactions table
    function renderTransactions(transactions) {
        if (!transactions || transactions.length === 0) {
            $('#transactionsTableBody').html('<tr><td colspan="7" class="text-center">No transactions found</td></tr>');
            return;
        }
        
        let html = '';
        transactions.forEach(trans => {
            let statusClass = '';
            let statusText = trans.status || 'COMPLETED';
            
            if (statusText === 'COMPLETED') statusClass = 'status-completed';
            else if (statusText === 'CANCELLED') statusClass = 'status-cancelled';
            else statusClass = 'status-pending';
            
            let paymentIcon = trans.payment_method === 'Cash' ? 'fa-money-bill' : 'fa-mobile-alt';
            
            html += `
                <tr class="transaction-row" data-order-id="${trans.order_id}">
                    <td><strong>#${trans.order_id}</strong></td>
                    <td>${escapeHtml(trans.customer_name)}</td>
                    <td>${formatDate(trans.date_created)}</td>
                    <td><strong>₱${parseFloat(trans.net_amount).toFixed(2)}</strong></td>
                    <td><i class="fas ${paymentIcon}"></i> ${trans.payment_method}</td>
                    <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-info view-details-btn" data-order-id="${trans.order_id}" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-success reprint-receipt-btn" data-order-id="${trans.order_id}" title="Reprint Receipt">
                            <i class="fas fa-print"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        
        $('#transactionsTableBody').html(html);
        
        // Attach event handlers
        $('.view-details-btn').click(function(e) {
            e.stopPropagation();
            let orderId = $(this).data('order-id');
            viewTransactionDetails(orderId);
        });
        
        $('.reprint-receipt-btn').click(function(e) {
            e.stopPropagation();
            let orderId = $(this).data('order-id');
            reprintReceipt(orderId);
        });
        
        // Make row clickable to view details
        $('.transaction-row').click(function() {
            let orderId = $(this).data('order-id');
            viewTransactionDetails(orderId);
        });
    }
    
    // Update pagination
    function updatePagination(pagination, search) {
        if (!pagination || search) {
            $('#pagination').html('');
            return;
        }
        
        let html = '';
        
        // Previous button
        if (pagination.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page - 1}">« Previous</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">« Previous</span></li>`;
        }
        
        // Page numbers
        let startPage = Math.max(1, pagination.current_page - 2);
        let endPage = Math.min(pagination.total_pages, pagination.current_page + 2);
        
        if (startPage > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
            if (startPage > 2) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === pagination.current_page) {
                html += `<li class="page-item active"><span class="page-link">${i}</span></li>`;
            } else {
                html += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
        }
        
        if (endPage < pagination.total_pages) {
            if (endPage < pagination.total_pages - 1) html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.total_pages}">${pagination.total_pages}</a></li>`;
        }
        
        // Next button
        if (pagination.current_page < pagination.total_pages) {
            html += `<li class="page-item"><a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next »</a></li>`;
        } else {
            html += `<li class="page-item disabled"><span class="page-link">Next »</span></li>`;
        }
        
        $('#pagination').html(html);
        
        // Bind click events
        $('#pagination .page-link').click(function(e) {
            e.preventDefault();
            let page = $(this).data('page');
            if (page) {
                currentPage = page;
                loadTransactions(currentPage, currentSearch);
            }
        });
    }
    
    // View transaction details
    function viewTransactionDetails(orderId) {
        $.get(API_URL + '/transactions/details/' + orderId, function(res) {
            if (res.status === 'success') {
                let order = res.data.order;
                let items = res.data.items;
                
                let itemsHtml = '';
                items.forEach(item => {
                    itemsHtml += `
                        <tr>
                            <td>${escapeHtml(item.product_name)}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-right">₱${parseFloat(item.price).toFixed(2)}</td>
                            <td class="text-right">₱${parseFloat(item.subtotal).toFixed(2)}</td>
                        </tr>
                    `;
                });
                
                let modalHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Order #:</strong> ${order.order_id}</p>
                            <p><strong>Customer:</strong> ${escapeHtml(order.customer_name)}</p>
                            <p><strong>Date:</strong> ${formatDate(order.date_created)}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Payment Method:</strong> ${order.payment_method}</p>
                            <p><strong>Status:</strong> <span class="status-badge status-completed">${order.status}</span></p>
                        </div>
                    </div>
                    <hr>
                    <h6>Order Items</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center" width="80">Qty</th>
                                    <th class="text-right" width="100">Price</th>
                                    <th class="text-right" width="100">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${itemsHtml}
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                                    <td class="text-right">₱${parseFloat(order.total_amount).toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Tax (12%):</strong></td>
                                    <td class="text-right">₱${parseFloat(order.tax_amount).toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Discount:</strong></td>
                                    <td class="text-right">- ₱${parseFloat(order.discount_amount).toFixed(2)}</td>
                                </tr>
                                <tr class="table-success">
                                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                                    <td class="text-right"><strong>₱${parseFloat(order.net_amount).toFixed(2)}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Amount Tendered:</strong></td>
                                    <td class="text-right">₱${parseFloat(order.amount_tendered).toFixed(2)}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right"><strong>Change:</strong></td>
                                    <td class="text-right">₱${parseFloat(order.change_amount).toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
                
                $('#detailsModalBody').html(modalHtml);
                $('#reprintFromDetailsBtn').off('click').on('click', function() {
                    $('#viewDetailsModal').modal('hide');
                    setTimeout(function() {
                        reprintReceipt(orderId);
                    }, 300);
                });
                $('#viewDetailsModal').modal('show');
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }).fail(function() {
            Swal.fire('Error', 'Could not load transaction details', 'error');
        });
    }
    
    // Reprint receipt function
    window.reprintReceipt = function(orderId) {
        currentOrderId = orderId;
        
        Swal.fire({
            title: 'Loading Receipt...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.get(API_URL + '/transactions/reprint/' + orderId, function(res) {
            Swal.close();
            
            if (res.status === 'success') {
                displayReceipt(res.receipt);
            } else {
                Swal.fire('Error', res.message || 'Could not retrieve receipt', 'error');
            }
        }).fail(function(xhr) {
            Swal.close();
            let errorMsg = 'Could not reprint receipt';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            Swal.fire('Error', errorMsg, 'error');
        });
    };
    
    // Display receipt in modal
    function displayReceipt(data) {
        let itemsHtml = '';
        
        if (data.items && data.items.length > 0) {
            data.items.forEach(item => {
                itemsHtml += `
                    <tr>
                        <td style="padding: 4px 0;">${escapeHtml(item.product_name)}</td>
                        <td style="padding: 4px 8px; text-align: center;">${item.quantity}</td>
                        <td style="padding: 4px 0; text-align: right;">${parseFloat(item.price).toFixed(2)}</td>
                        <td style="padding: 4px 0; text-align: right;">${parseFloat(item.subtotal).toFixed(2)}</td>
                    </tr>
                `;
            });
        }
        
        const receiptHtml = `
            <div class="receipt-container" id="receiptToPrint">
                <div class="receipt">
                    <div class="receipt-header">
                        <h3>CODE WARRIORS STORE</h3>
                        <p>123 Main Street, City</p>
                        <p>Tel: (123) 456-7890</p>
                        <p>VAT Reg TIN: 123-456-789-000</p>
                    </div>
                    
                    <div class="receipt-info">
                        <div class="receipt-info-row">
                            <span>Order #:</span>
                            <span><strong>${data.order_id}</strong></span>
                        </div>
                        <div class="receipt-info-row">
                            <span>Date:</span>
                            <span>${data.date}</span>
                        </div>
                        <div class="receipt-info-row">
                            <span>Cashier:</span>
                            <span>${escapeHtml(data.cashier || 'Administrator')}</span>
                        </div>
                        <div class="receipt-info-row">
                            <span>Customer:</span>
                            <span>${escapeHtml(data.customer)}</span>
                        </div>
                    </div>
                    
                    <table class="receipt-items">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th style="text-align: center;">Qty</th>
                                <th style="text-align: right;">Price</th>
                                <th style="text-align: right;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                    </table>
                    
                    <div class="receipt-totals">
                        <p>Subtotal: ................ ₱${data.subtotal}</p>
                        <p>Tax (12% VAT): .......... ₱${data.tax}</p>
                        ${parseFloat(data.discount) > 0 ? `<p>Discount: ............... -₱${data.discount}</p>` : ''}
                        <p><strong>TOTAL: ................ ₱${data.total}</strong></p>
                        <p>Payment: ................ ${data.payment_method}</p>
                        <p>Tendered: ............... ₱${data.tendered}</p>
                        <p>Change: ................. ₱${data.change}</p>
                    </div>
                    
                    <div class="receipt-footer">
                        <p>Thank you for shopping with us!</p>
                        <p>This serves as your official receipt</p>
                        <p>No return/refund without receipt</p>
                        <p>--- End of Receipt ---</p>
                    </div>
                </div>
            </div>
        `;
        
        $('#receiptModalBody').html(receiptHtml);
        $('#reprintReceiptModal').modal('show');
    }
    
    // Print receipt function - FIXED
    $('#printReceiptBtn').click(function() {
        window.print();
    });
    
    // Search functionality
    $('#searchBtn').click(function() {
        currentSearch = $('#searchInput').val().trim();
        currentPage = 1;
        if (currentSearch) {
            loadTransactions(currentPage, currentSearch);
        } else {
            loadTransactions(currentPage, '');
        }
    });
    
    $('#searchInput').keypress(function(e) {
        if (e.which === 13) {
            $('#searchBtn').click();
        }
    });
    
    $('#clearSearchBtn').click(function() {
        $('#searchInput').val('');
        currentSearch = '';
        currentPage = 1;
        loadTransactions(currentPage, '');
    });
    
    // Helper functions
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        let date = new Date(dateString);
        return date.toLocaleString('en-PH', {
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        });
    }
    
    function escapeHtml(text) {
        if (!text) return '';
        let div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialize
    loadStatistics();
    loadTransactions(1, '');
    
    // Auto refresh every 30 seconds
    setInterval(function() {
        if (!currentSearch) {
            loadTransactions(currentPage, '');
        }
        loadStatistics();
    }, 30000);
});
</script>
</body>
</html>