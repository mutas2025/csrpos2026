<?php 
// home.php
// Main Dashboard Page
// Displays summary statistics and quick access links.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Management System</title>

    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../../dist/css/font.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">
    
    <style>
        .content-wrapper { min-height: 100vh; }
        /* Center cards vertically in small boxes */
        .small-box .inner { 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            flex-direction: column; 
            height: 100%; 
        }
        /* Custom styling for stat cards */
        .stat-card {
            border-radius: 0.5rem;
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.3;
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
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                
                <!-- SUMMARY CARDS -->
                <div class="row">
                    <!-- Users Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3 id="count-users"><i class="fas fa-spinner fa-spin"></i></h3>
                                <p>Total Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="users.php" class="small-box-footer">View Users <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <!-- Products Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3 id="count-products"><i class="fas fa-spinner fa-spin"></i></h3>
                                <p>Total Products</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>
                            <a href="productlist.php" class="small-box-footer">View Products <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    
                    <!-- Customers Card -->
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3 id="count-customers"><i class="fas fa-spinner fa-spin"></i></h3>
                                <p>Total Customers</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <a href="customerlist.php" class="small-box-footer">View Customers <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- STATISTICS & QUICK LINKS SECTION -->
                <div class="row">
                    <!-- Statistics Card -->
                    <div class="col-lg-6">
                        <div class="card stat-card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    System Statistics
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-striped table-valign-middle">
                                    <thead>
                                        <tr>
                                            <th>Metric</th>
                                            <th>Count</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Registered Users</td>
                                            <td><span id="stat-users" class="badge bg-info">0</span></td>
                                            <td><span class="text-success"><i class="fas fa-check-circle"></i> Active</span></td>
                                        </tr>
                                        <tr>
                                            <td>Product Inventory</td>
                                            <td><span id="stat-products" class="badge bg-success">0</span></td>
                                            <td><span class="text-muted"><i class="fas fa-box"></i> In Stock</span></td>
                                        </tr>
                                        <tr>
                                            <td>Customer Database</td>
                                            <td><span id="stat-customers" class="badge bg-warning">0</span></td>
                                            <td><span class="text-primary"><i class="fas fa-database"></i> Verified</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


            </div>
        </section>
    </div>

</div>

<!-- REQUIRED SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
    // ===================================================================
    // CONFIGURATION
    // ===================================================================
    const API_URL = '../../api/routes.php';

    $(function () {
        // Load Dashboard Stats
        loadStatistics();
    });

    // ===================================================================
    // STATISTICS LOADER
    // ===================================================================
    function loadStatistics() {
        // 1. Fetch Users
        fetch(`${API_URL}/users`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    const count = data.data.length;
                    $('#count-users').html(count);
                    $('#stat-users').html(count);
                }
            })
            .catch(err => {
                $('#count-users').html('Err');
                $('#stat-users').html('Err');
            });

        // 2. Fetch Products
        fetch(`${API_URL}/products`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    const count = data.data.length;
                    $('#count-products').html(count);
                    $('#stat-products').html(count);
                }
            })
            .catch(err => {
                $('#count-products').html('Err');
                $('#stat-products').html('Err');
            });

        // 3. Fetch Customers
        fetch(`${API_URL}/customers`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    const count = data.data.length;
                    $('#count-customers').html(count);
                    $('#stat-customers').html(count);
                }
            })
            .catch(err => {
                $('#count-customers').html('Err');
                $('#stat-customers').html('Err');
            });
    }
</script>
</body>
</html>