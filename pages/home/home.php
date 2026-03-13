<?php 
include('../../config/config.php');

// Initialize variables 
 $success_message = ''; 
 $error_message = ''; 
 $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'users'; // Default tab

// Handle form submissions (Delete Actions)
if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    if (isset($_POST['action'])) { 
        try {
            $pdo->beginTransaction(); // Optional: for transaction safety
            
            switch ($_POST['action']) { 
                case 'delete_user': 
                    $stmt = $pdo->prepare("DELETE FROM tbl_users WHERE objid=?"); 
                    $stmt->execute([$_POST['objid']]); 
                    $success_message = "User deleted successfully!"; 
                    $active_tab = 'users';
                    break; 
                case 'delete_product':
                    $stmt = $pdo->prepare("DELETE FROM tbl_products WHERE objid=?"); 
                    $stmt->execute([$_POST['objid']]); 
                    $success_message = "Product deleted successfully!"; 
                    $active_tab = 'products';
                    break;
                case 'delete_customer':
                    $stmt = $pdo->prepare("DELETE FROM tbl_customers WHERE objid=?"); 
                    $stmt->execute([$_POST['objid']]); 
                    $success_message = "Customer deleted successfully!"; 
                    $active_tab = 'customers';
                    break;
            }
            
            $pdo->commit();
        } catch(PDOException $e) { 
            $pdo->rollBack();
            $error_message = "Error: " . $e->getMessage(); 
        } 
    } 
} 

// Fetch all data
 $users = $pdo->query("SELECT * FROM tbl_users ORDER BY objid DESC")->fetchAll(PDO::FETCH_ASSOC); 
 $products = $pdo->query("SELECT * FROM tbl_products ORDER BY objid DESC")->fetchAll(PDO::FETCH_ASSOC); 
 $customers = $pdo->query("SELECT * FROM tbl_customers ORDER BY objid DESC")->fetchAll(PDO::FETCH_ASSOC); 
?>  
<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Management System</title> 

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../../dist/css/font.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap4.css"> 

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.min.css">

    <style>
        .overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.9); z-index: 9999;
            display: none; opacity: 0;
            transition: opacity .3s ease-in-out;
        } 
        .overlay.active { display: block; opacity: 1; } 
        .overlay-content { position: absolute; top: 50%; left: 60%; transform: translate(-50%, -50%); } 
        .imageSpinner { filter: invert(1); mix-blend-mode: multiply; width: 30%; } 

        /* Table Styles */
        table.dataTable thead th { background-color: #343a40; border-color: #4b545c; color: white; text-align: center; } 
        table.dataTable tbody td { text-align: center; vertical-align: middle !important; } 
        .action-buttons { display: flex; justify-content: center; gap: 5px; } 
        
        /* Tab Styling */
        .nav-tabs .nav-link { color: #495057; font-weight: 600; }
        .nav-tabs .nav-link.active { color: #fff; background-color: #343a40; border-color: #343a40 #343a40 #fff; }
        
        /* Fix user image in nav */
        .navbar-nav .user-menu .user-image { height: 25px; width: 25px; border-radius: 50%; margin-right: 5px; }
    </style> 
</head> 

<body class="sidebar-mini layout-fixed" style="height: auto"> 

    <div class="wrapper">
        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img src="../../dist/img/itcsologo.webp" alt="Logo" height="60" width="60">
        </div> 

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
                <li class="nav-item dropdown user-menu">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                        <img src="../../dist/img/default.jfif" class="user-image img-circle" alt="User Image">
                        <span class="d-none d-md-inline">ADMIN USER</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <li class="user-header bg-secondary">
                            <img src="../../dist/img/default.jfif" class="img-circle elevation-2" alt="User Image">
                            <p class="mt-2">ADMIN USER</p>
                        </li>
                        <li class="user-footer">
                            <a href="#" class="btn btn-default btn-flat" onclick="logout()">Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <!-- Include Sidebar -->
        <?php include '../../pages/sidebar/sidebar.php'; ?> 

        <!-- Body Content --> 
        <div id="body_wrapper" class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Management Dashboard</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Management</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div> 

            <!-- Main Content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Display Messages -->
                    <?php if ($success_message): ?>
                        <input type="hidden" id="success_msg" value="<?php echo $success_message; ?>">
                    <?php endif; ?> 
                    <?php if ($error_message): ?>
                        <input type="hidden" id="error_msg" value="<?php echo $error_message; ?>">
                    <?php endif; ?> 

        
                                <div class="card-body">
                                    <div class="tab-content" id="mainTabContent">
                                        
                                        <!-- USERS TAB -->
                                        <div class="tab-pane fade <?php echo $active_tab == 'users' ? 'show active' : ''; ?>" id="users" role="tabpanel">
                                            <table id="userTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>ID No.</th>
                                                        <th>Full Name</th>
                                                        <th>Department</th>
                                                        <th>User Type</th>
                                                        <th>Options</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($users as $user): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($user['idno']); ?></td>
                                                            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                                                            <td><?php echo htmlspecialchars($user['department']); ?></td>
                                                            <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $user['objid']; ?>, 'delete_user')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- PRODUCTS TAB -->
                                        <div class="tab-pane fade <?php echo $active_tab == 'products' ? 'show active' : ''; ?>" id="products" role="tabpanel">
                                            <table id="productTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th>Product Name</th>
                                                        <th>Category</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Options</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($products as $prod): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($prod['product_code']); ?></td>
                                                            <td><?php echo htmlspecialchars($prod['product_name']); ?></td>
                                                            <td><?php echo htmlspecialchars($prod['category']); ?></td>
                                                            <td><?php echo number_format($prod['price'], 2); ?></td>
                                                            <td><?php echo htmlspecialchars($prod['stock']); ?></td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $prod['objid']; ?>, 'delete_product')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- CUSTOMERS TAB -->
                                        <div class="tab-pane fade <?php echo $active_tab == 'customers' ? 'show active' : ''; ?>" id="customers" role="tabpanel">
                                            <table id="customerTable" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th>Full Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Options</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($customers as $cust): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($cust['customer_code']); ?></td>
                                                            <td><?php echo htmlspecialchars($cust['fullname']); ?></td>
                                                            <td><?php echo htmlspecialchars($cust['email']); ?></td>
                                                            <td><?php echo htmlspecialchars($cust['phone']); ?></td>
                                                            <td>
                                                                <div class="action-buttons">
                                                                    <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?php echo $cust['objid']; ?>, 'delete_customer')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div> 

    </div> 

    <div class="overlay" id="myOverlay">
        <div class="overlay-content">
            <img src="../../dist/img/load.gif" class="imageSpinner" alt="Loading">
        </div>
    </div> 

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="post" action="" style="display: inline;">
                        <input type="hidden" name="action" id="delete_action">
                        <input type="hidden" name="objid" id="delete_objid">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div> 

    <!-- REQUIRED SCRIPTS --> 
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../dist/js/adminlte.min.js"></script> 
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script> 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            $('#userTable').DataTable({ "responsive": true, "autoWidth": false });
            $('#productTable').DataTable({ "responsive": true, "autoWidth": false });
            $('#customerTable').DataTable({ "responsive": true, "autoWidth": false });

            // Handle Success/Error Messages via SweetAlert
            var successMsg = $('#success_msg').val();
            var errorMsg = $('#error_msg').val();

            if (successMsg) {
                Swal.fire({ icon: 'success', title: 'Success!', text: successMsg, timer: 3000 });
            }
            if (errorMsg) {
                Swal.fire({ icon: 'error', title: 'Error!', text: errorMsg });
            }
        }); 

        // Delete Confirmation Function
        function confirmDelete(objid, action) {
            $('#delete_objid').val(objid);
            $('#delete_action').val(action);
            $('#deleteModal').modal('show');
        } 

        function logout() {
            Swal.fire({
                title: 'Logout',
                text: 'Are you sure you want to logout?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'logout.php';
                }
            });
        } 

        // Show overlay when form is submitted
        $('form').on('submit', function() {
            $('#myOverlay').addClass('active');
        });
    </script> 

</body> 
</html>