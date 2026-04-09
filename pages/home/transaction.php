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
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
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
                    <div class="col-sm-6"><h1 class="m-0">Transaction History</h1></h1></div>
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
                                    <!-- REMOVED <th>Order #</th> -->
                                    <th>Customer</th>
                                    <th>Total Amount</th>
                                    <th>Payment</th>
                                    <th>Date</th>
                                    <!-- REMOVED <th>Action</th> -->
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
                // REMOVED { "data": "order_id" },
                { "data": "customer_name" },
                { 
                    "data": "total_amount",
                    "render": function(data) { return '₱' + parseFloat(data).toFixed(2); }
                },
                { "data": "payment_method" },
                { "data": "date_created" }
                // REMOVED Action Column Render
            ]
        });
    }

    // REMOVED viewTransaction function

    initTable();
});
</script>
</body>
</html>