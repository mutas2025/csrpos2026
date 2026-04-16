<?php
// sales.php
// Sales Report Page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sales Report</title>
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="stylesheet" href="../../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    
    <style>
        .content-wrapper { min-height: 100vh; }
        /* Print Styles */
        @media print {
            .no-print, .main-sidebar, .main-header, .content-header { display: none !important; }
            .content-wrapper { margin-left: 0 !important; padding-top: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
            body { background-color: white; }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-dark no-print">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>

    <!-- Main Sidebar -->
    <?php include '../../pages/sidebar/sidebar.php'; ?> 

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header no-print">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1 class="m-0">Sales Report</h1></div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Filters -->
                <div class="card no-print mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Start Date</label>
                                <input type="date" id="startDate" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label>End Date</label>
                                <input type="date" id="endDate" class="form-control">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" onclick="generateReport()">
                                    <i class="fas fa-search"></i> Generate Report
                                </button>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-secondary w-100" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-shopping-cart"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Transactions</span>
                                <span class="info-box-number" id="summary-trans">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success"><i class="fas fa-money-bill"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Revenue</span>
                                <span class="info-box-number" id="summary-rev">₱0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-tags"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Discount</span>
                                <span class="info-box-number" id="summary-disc">₱0.00</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-danger"><i class="fas fa-percent"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Tax Collected</span>
                                <span class="info-box-number" id="summary-tax">₱0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Products Table -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Top Selling Products</h3>
                    </div>
                    <div class="card-body">
                        <table id="topProductsTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity Sold</th>
                                    <th class="text-right">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody id="topProductsBody"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Detailed Sales Table -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h3 class="card-title">Detailed Transactions</h3>
                    </div>
                    <div class="card-body">
                        <table id="salesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th class="text-right">Amount</th>
                                    <th>Payment</th>
                                </tr>
                            </thead>
                            <tbody id="salesTableBody"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>

<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
 $(document).ready(function() {
    const API_URL = '../../api/routes.php';

    // Set default dates (Today)
    const today = new Date().toISOString().split('T')[0];
    $('#startDate').val(today);
    $('#endDate').val(today);

    window.generateReport = function() {
        const start = $('#startDate').val();
        const end = $('#endDate').val();

        if(!start || !end) {
            Swal.fire('Error', 'Please select both dates', 'warning');
            return;
        }

        Swal.fire({
            title: 'Generating Report...',
            didOpen: () => Swal.showLoading()
        });

        $.get(API_URL + '/sales', { start_date: start, end_date: end }, function(res) {
            Swal.close();
            if(res.status === 'success') {
                updateUI(res);
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        }).fail(function() {
            Swal.close();
            Swal.fire('Error', 'Failed to connect to server.', 'error');
        });
    };

    function updateUI(data) {
        // Helper function to safely parse numbers and prevent NaN
        const safeFloat = (val) => parseFloat(val) || 0;
        const safeInt = (val) => parseInt(val) || 0;

        // 1. Summary
        // Check if summary exists to avoid errors if structure changes
        const summary = data.summary || {};
        
        $('#summary-trans').text(safeInt(summary.total_transactions));
        $('#summary-rev').text('₱' + safeFloat(summary.total_revenue).toFixed(2));
        $('#summary-disc').text('₱' + safeFloat(summary.total_discount).toFixed(2));
        $('#summary-tax').text('₱' + safeFloat(summary.total_tax).toFixed(2));

        // 2. Top Products
        const topBody = $('#topProductsBody');
        topBody.empty();
        
        // Check if top_products is an array before looping
        if (Array.isArray(data.top_products) && data.top_products.length > 0) {
            data.top_products.forEach(p => {
                topBody.append(`
                    <tr>
                        <td>${p.product_name || 'Unknown Product'}</td>
                        <td>${safeInt(p.total_qty)}</td>
                        <td class="text-right">₱${safeFloat(p.total_sales).toFixed(2)}</td>
                    </tr>
                `);
            });
        } else {
            topBody.append('<tr><td colspan="3" class="text-center">No sales data available for this period.</td></tr>');
        }

        // 3. Detailed Sales
        const salesBody = $('#salesTableBody');
        salesBody.empty();

        // Check if sales is an array before looping
        if (Array.isArray(data.sales) && data.sales.length > 0) {
            data.sales.forEach(s => {
                salesBody.append(`
                    <tr>
                        <td>${s.date_created}</td>
                        <td>#${s.objid}</td>
                        <td>${s.customer_name || 'Guest'}</td>
                        <td class="text-right">₱${safeFloat(s.net_amount).toFixed(2)}</td>
                        <td>${s.payment_method}</td>
                    </tr>
                `);
            });
        } else {
            salesBody.append('<tr><td colspan="5" class="text-center">No transactions found for this period.</td></tr>');
        }
    }

    // Load initial report
    generateReport();
});
</script>
</body>
</html>