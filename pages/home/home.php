<?php 
// home.php
// Main Dashboard Page
// Displays summary statistics and recent activities.
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Management System</title>

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
        .small-box .inner { display: flex; align-items: center; justify-content: center; flex-direction: column; height: 100%; }
        /* Loading spinner for widgets */
        .overlay-spinner {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(255,255,255,0.7); display: flex; 
            align-items: center; justify-content: center; z-index: 10;
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

                <!-- RECENT ACTIVITY SECTION -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-box mr-1"></i>
                                    Latest Products
                                </h3>
                                <div class="card-tools">
                                    <span class="badge badge-danger">New</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="products-list product-list-in-card pl-2 pr-2 pt-2 pb-2" id="recent-products-list">
                                    <!-- Filled via JS -->
                                    <li class="text-center text-muted py-3">Loading...</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-users mr-1"></i>
                                    Newest Customers
                                </h3>
                                <div class="card-tools">
                                    <span class="badge badge-info">New</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <ul class="products-list product-list-in-card pl-2 pr-2 pt-2 pb-2" id="recent-customers-list">
                                    <!-- Filled via JS -->
                                    <li class="text-center text-muted py-3">Loading...</li>
                                </ul>
                            </div>
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
        loadCounts();
        loadRecentProducts();
        loadRecentCustomers();
    });

    // ===================================================================
    // STATISTICS COUNTING
    // ===================================================================
    function loadCounts() {
        // Fetch Users Count
        fetch(`${API_URL}/users`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    $('#count-users').html(data.data.length);
                }
            })
            .catch(err => $('#count-users').html('Err'));

        // Fetch Products Count
        fetch(`${API_URL}/products`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    $('#count-products').html(data.data.length);
                }
            })
            .catch(err => $('#count-products').html('Err'));

        // Fetch Customers Count
        fetch(`${API_URL}/customers`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    $('#count-customers').html(data.data.length);
                }
            })
            .catch(err => $('#count-customers').html('Err'));
    }

    // ===================================================================
    // RECENT DATA DISPLAY
    // ===================================================================
    function loadRecentProducts() {
        fetch(`${API_URL}/products`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success' && data.data.length > 0) {
                    let html = '';
                    // Show only the last 5 products
                    // Ideally, your API should have a limit parameter (e.g. ?limit=5), 
                    // but for now we slice the array.
                    let recentItems = data.data.slice(-5).reverse();
                    
                    recentItems.forEach(item => {
                        html += `
                        <li class="item">
                            <div class="product-img">
                                <img src="../../dist/img/default-150x150.png" alt="Product Image" class="img-size-50">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">${item.product_name}</a>
                                <span class="product-description">
                                    Category: ${item.category} | Stock: ${item.stock}
                                </span>
                            </div>
                        </li>`;
                    });
                    $('#recent-products-list').html(html);
                } else {
                    $('#recent-products-list').html('<li class="text-center text-muted py-3">No products found</li>');
                }
            });
    }

    function loadRecentCustomers() {
        fetch(`${API_URL}/customers`)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success' && data.data.length > 0) {
                    let html = '';
                    // Show only the last 5 customers
                    let recentItems = data.data.slice(-5).reverse();
                    
                    recentItems.forEach(item => {
                        html += `
                        <li class="item">
                            <div class="product-img">
                                <img src="../../dist/img/user2-160x160.jpg" alt="User Image" class="img-size-50 img-circle">
                            </div>
                            <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">${item.fullname}</a>
                                <span class="product-description">
                                    ${item.email}
                                </span>
                            </div>
                        </li>`;
                    });
                    $('#recent-customers-list').html(html);
                } else {
                    $('#recent-customers-list').html('<li class="text-center text-muted py-3">No customers found</li>');
                }
            });
    }
</script>
</body>
</html>