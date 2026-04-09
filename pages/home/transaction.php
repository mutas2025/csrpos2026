<?php
// transaction.php
// Transaction History Page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Transaction History</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    <style>
        .content-wrapper { min-height: 100vh; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-dark">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>

    <!-- Main Sidebar -->
    <?php include '../../pages/sidebar/sidebar.php'; ?> 

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0">Transaction History</h1></div>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Sales Orders</h3>
                    </div>
                    <div class="card-body">
                        <table id="transactions-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Total Amount</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
 $(document).ready(function() {
    const API_URL = '../../api/routes.php';
    var table;

    function initTable() {
        table = $('#transactions-table').DataTable({
            "responsive": true,
            "processing": true,
            "serverSide": false,
            "ajax": {
                "url": API_URL + "/transactions",
                "dataSrc": function(json) {
                    return json.status === 'success' ? json.data : [];
                }
            },
            "columns": [
                { "data": "objid" },
                { "data": "order_id" },
                { "data": "customer_name" },
                { 
                    "data": "total_amount",
                    "render": function(data) { return '₱' + parseFloat(data).toFixed(2); }
                },
                { "data": "payment_method" },
                { "data": "date_created" },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `<button class="btn btn-sm btn-info" onclick="viewTransaction(${row.objid})"><i class="fas fa-eye"></i> View</button>`;
                    }
                }
            ]
        });
    }

    window.viewTransaction = function(id) {
        $.get(API_URL + '/transactions/' + id, function(res) {
            if(res.status === 'success') {
                const order = res.data.order;
                const items = res.data.items;
                
                let itemsHtml = '';
                items.forEach(item => {
                    itemsHtml += `
                        <tr>
                            <td>${item.product_name}</td>
                            <td>${item.quantity}</td>
                            <td class="text-right">₱${parseFloat(item.price).toFixed(2)}</td>
                            <td class="text-right">₱${parseFloat(item.subtotal).toFixed(2)}</td>
                        </tr>
                    `;
                });

                const html = `
                    <div style="font-family: monospace;">
                        <h4 class="text-center">Transaction Details</h4>
                        <p><strong>Order #:</strong> ${order.order_id}</p>
                        <p><strong>Customer:</strong> ${order.customer_name}</p>
                        <p><strong>Date:</strong> ${order.date_created}</p>
                        <p><strong>Payment:</strong> ${order.payment_method}</p>
                        <hr>
                        <table class="table table-sm table-bordered">
                            <thead><tr><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
                            <tbody>${itemsHtml}</tbody>
                        </table>
                        <h5 class="text-right">Total: ₱${parseFloat(order.net_amount).toFixed(2)}</h5>
                    </div>
                `;

                Swal.fire({
                    title: 'Order Receipt',
                    html: html,
                    width: '600px',
                    showConfirmButton: false,
                    showCloseButton: true
                });
            }
        });
    };

    initTable();
});
</script>
</body>
</html>