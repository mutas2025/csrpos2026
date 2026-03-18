<?php 

// This file is now a pure frontend view. 
// No database connections or SQL queries are handled here directly.
// All data is fetched from the API (routes.php) via JavaScript.
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

        /* Inline Edit Styles */
        .inline-form { display: none; }
        .inline-form input, .inline-form select { 
            width: 100%; 
            padding: 0.2rem; 
            font-size: 0.85rem; 
            height: calc(1.5em + 0.5rem + 2px);
        }
        .edit-mode .view-mode { display: none; }
        .edit-mode .inline-form { display: block; }
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
                    
                    <!-- Tabs Card -->
                    <div class="card">
                        <div class="card-header p-0 pt-1">
                            <ul class="nav nav-tabs" id="mainTab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#users" role="tab" data-toggle="tab">Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#products" role="tab" data-toggle="tab">Products</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#customers" role="tab" data-toggle="tab">Customers</a>
                                </li>
                            </ul>
                        </div>
        
                        <div class="card-body">
                            <div class="tab-content" id="mainTabContent">
                                
                                <!-- USERS TAB -->
                                <div class="tab-pane fade show active" id="users" role="tabpanel">
                                    <button class="btn btn-success btn-sm mb-3" onclick="openAddModal('user')"><i class="fas fa-plus"></i> Add User</button>
                                    <table id="userTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID No.</th>
                                                <th>Full Name</th>
                                                <th>Username</th>
                                                <th>Department</th>
                                                <th>Type</th>
                                                <th>Options</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userTableBody">
                                            <!-- Data loaded via API -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- PRODUCTS TAB -->
                                <div class="tab-pane fade" id="products" role="tabpanel">
                                    <button class="btn btn-success btn-sm mb-3" onclick="openAddModal('product')"><i class="fas fa-plus"></i> Add Product</button>
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
                                        <tbody id="productTableBody">
                                            <!-- Data loaded via API -->
                                        </tbody>
                                    </table>
                                </div>

                                <!-- CUSTOMERS TAB -->
                                <div class="tab-pane fade" id="customers" role="tabpanel">
                                    <button class="btn btn-success btn-sm mb-3" onclick="openAddModal('customer')"><i class="fas fa-plus"></i> Add Customer</button>
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
                                        <tbody id="customerTableBody">
                                            <!-- Data loaded via API -->
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

    <div class="overlay" id="myOverlay">
        <div class="overlay-content">
            <img src="../../dist/img/load.gif" class="imageSpinner" alt="Loading">
        </div>
    </div> 

    <!-- Generic Add/Edit Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="formModalTitle">Add New</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="mainForm">
                    <div class="modal-body" id="formModalBody">
                        <!-- Dynamic content goes here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitForm()">Save</button>
                    </div>
                </form>
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
        // API Endpoint
        const API_URL = '../../api/routes.php';
        var userTable, productTable, customerTable;

        $(document).ready(function() {
            // Initialize DataTables
            userTable = $('#userTable').DataTable({ "responsive": true, "autoWidth": false });
            productTable = $('#productTable').DataTable({ "responsive": true, "autoWidth": false });
            customerTable = $('#customerTable').DataTable({ "responsive": true, "autoWidth": false });

            // Initial Data Load
            loadData('users');
            loadData('products');
            loadData('customers');

            // Tab change event to ensure proper rendering (optional fix for hidden tabs)
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
            });
        }); 

        // ==========================================
        // DATA LOADING (GET REQUESTS)
        // ==========================================
        function loadData(type) {
            fetch(`${API_URL}/${type}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        if (type === 'users') populateUserTable(data.data);
                        else if (type === 'products') populateProductTable(data.data);
                        else if (type === 'customers') populateCustomerTable(data.data);
                    } else {
                        console.error('Error loading ' + type, data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        function populateUserTable(data) {
            userTable.clear();
            data.forEach(user => {
                userTable.row.add([
                    `<div class="view-mode">${user.idno}</div><div class="inline-form"><input type="text" value="${user.idno}" data-field="idno"></div>`,
                    `<div class="view-mode">${user.fullname}</div><div class="inline-form"><input type="text" value="${user.fullname}" data-field="fullname"></div>`,
                    `<div class="view-mode">${user.username}</div><div class="inline-form"><input type="text" value="${user.username}" data-field="username"></div>`,
                    `<div class="view-mode">${user.department}</div><div class="inline-form"><input type="text" value="${user.department}" data-field="department"></div>`,
                    `<div class="view-mode">${user.user_type}</div><div class="inline-form"><input type="text" value="${user.user_type}" data-field="user_type"></div>`,
                    `<div class="action-buttons" data-id="${user.objid}" data-type="user">
                        <button class="btn btn-primary btn-sm btn-edit" onclick="toggleEdit(this)"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-success btn-sm btn-save" style="display:none;" onclick="saveInline(this)"><i class="fas fa-check"></i></button>
                        <button class="btn btn-secondary btn-sm btn-cancel" style="display:none;" onclick="cancelEdit(this)"><i class="fas fa-times"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord('user', ${user.objid})"><i class="fas fa-trash"></i></button>
                    </div>`
                ]).draw(false);
            });
        }

        function populateProductTable(data) {
            productTable.clear();
            data.forEach(prod => {
                productTable.row.add([
                    `<div class="view-mode">${prod.product_code}</div><div class="inline-form"><input type="text" value="${prod.product_code}" data-field="product_code"></div>`,
                    `<div class="view-mode">${prod.product_name}</div><div class="inline-form"><input type="text" value="${prod.product_name}" data-field="product_name"></div>`,
                    `<div class="view-mode">${prod.category}</div><div class="inline-form"><input type="text" value="${prod.category}" data-field="category"></div>`,
                    `<div class="view-mode">${prod.price}</div><div class="inline-form"><input type="number" step="0.01" value="${prod.price}" data-field="price"></div>`,
                    `<div class="view-mode">${prod.stock}</div><div class="inline-form"><input type="number" value="${prod.stock}" data-field="stock"></div>`,
                    `<div class="action-buttons" data-id="${prod.objid}" data-type="product">
                        <button class="btn btn-primary btn-sm btn-edit" onclick="toggleEdit(this)"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-success btn-sm btn-save" style="display:none;" onclick="saveInline(this)"><i class="fas fa-check"></i></button>
                        <button class="btn btn-secondary btn-sm btn-cancel" style="display:none;" onclick="cancelEdit(this)"><i class="fas fa-times"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord('product', ${prod.objid})"><i class="fas fa-trash"></i></button>
                    </div>`
                ]).draw(false);
            });
        }

        function populateCustomerTable(data) {
            customerTable.clear();
            data.forEach(cust => {
                customerTable.row.add([
                    `<div class="view-mode">${cust.customer_code}</div><div class="inline-form"><input type="text" value="${cust.customer_code}" data-field="customer_code"></div>`,
                    `<div class="view-mode">${cust.fullname}</div><div class="inline-form"><input type="text" value="${cust.fullname}" data-field="fullname"></div>`,
                    `<div class="view-mode">${cust.email}</div><div class="inline-form"><input type="email" value="${cust.email}" data-field="email"></div>`,
                    `<div class="view-mode">${cust.phone}</div><div class="inline-form"><input type="text" value="${cust.phone}" data-field="phone"></div>`,
                    `<div class="action-buttons" data-id="${cust.objid}" data-type="customer">
                        <button class="btn btn-primary btn-sm btn-edit" onclick="toggleEdit(this)"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-success btn-sm btn-save" style="display:none;" onclick="saveInline(this)"><i class="fas fa-check"></i></button>
                        <button class="btn btn-secondary btn-sm btn-cancel" style="display:none;" onclick="cancelEdit(this)"><i class="fas fa-times"></i></button>
                        <button class="btn btn-danger btn-sm" onclick="deleteRecord('customer', ${cust.objid})"><i class="fas fa-trash"></i></button>
                    </div>`
                ]).draw(false);
            });
        }

        // ==========================================
        // INLINE EDIT FUNCTIONS
        // ==========================================
        function toggleEdit(btn) {
            const row = $(btn).closest('tr');
            row.addClass('edit-mode');
            $(btn).hide();
            row.find('.btn-save').show();
            row.find('.btn-cancel').show();
        }

        function cancelEdit(btn) {
            const row = $(btn).closest('tr');
            row.removeClass('edit-mode');
            $(btn).hide();
            row.find('.btn-save').hide();
            row.find('.btn-edit').show();
            
            // Reset values (simple reload for simplicity)
            const type = $(btn).closest('.action-buttons').data('type');
            loadData(type + 's'); // reload specific table
        }

        function saveInline(btn) {
            const actionBtns = $(btn).closest('.action-buttons');
            const id = actionBtns.data('id');
            const type = actionBtns.data('type'); // user, product, customer
            const row = $(btn).closest('tr');

            // Gather data from inputs in this row
            let payload = { objid: id };
            row.find('input').each(function() {
                payload[$(this).data('field')] = $(this).val();
            });

            // Determine API action
            let endpoint = type; // api/users, api/products, etc.
            // Note: Your API currently uses POST for creation. You might need an 'update' endpoint logic
            // or send a specific JSON body. Assuming we send the whole object and the controller handles it.
            // Since your routes switch on method, and POST calls createProduct, we need to verify if your 
            // API handles updates via POST or if we need to simulate an update logic.
            // For this demonstration, we will assume the backend has an 'edit_' logic or you will adjust the API.
            // Ideally, the API should handle PUT for updates, but for simplicity, let's assume we need a different way
            // OR we simply use POST with an action flag if your API supports it.
            
            // **IMPORTANT**: Your current API setup in `routes.php` maps POST /products to `createProduct`.
            // It does not have a route for UPDATE. 
            // To make this work without changing your previous prompt's API structure, 
            // we would need an 'update' route. 
            
            // I will proceed by sending a POST request, but you must add an 'update' case 
            // in your routes.php for this to function fully.
            
            // For now, I will log a warning and use POST.
            console.warn("Update logic requires API implementation for PUT/POST update actions.");

            fetch(`${API_URL}/${type}`, {
                method: 'POST', // Ideally should be PUT or handled by logic
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire('Updated!', data.message, 'success');
                    loadData(type + 's');
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }

        // ==========================================
        // DELETE FUNCTION
        // ==========================================
        function deleteRecord(type, id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Note: Your current API routes.php does not have a DELETE handler.
                    // You need to add a case for REQUEST_METHOD === 'DELETE' in routes.php
                    // and corresponding methods in controllers.
                    
                    // For demonstration, this code assumes the endpoint exists.
                    fetch(`${API_URL}/${type}/${id}`, { method: 'DELETE' })
                        .then(res => res.json())
                        .then(data => {
                            if(data.status === 'success') {
                                Swal.fire('Deleted!', data.message, 'success');
                                loadData(type + 's');
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                }
            });
        }

        // ==========================================
        // ADD MODAL FUNCTIONS
        // ==========================================
        function openAddModal(type) {
            var title = '';
            var fields = '';
            
            if (type === 'user') {
                title = 'Add New User';
                fields = `
                    <div class="form-group"><label>ID No.</label><input type="text" name="idno" class="form-control" required></div>
                    <div class="form-group"><label>Full Name</label><input type="text" name="fullname" class="form-control" required></div>
                    <div class="form-group"><label>Username</label><input type="text" name="username" class="form-control" required></div>
                    <div class="form-group"><label>Password</label><input type="password" name="password" class="form-control" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label>Contact No</label><input type="text" name="contactno" class="form-control" required></div>
                    <div class="form-group"><label>Department</label>
                        <select name="department" class="form-control" required>
                            <option value="IT">IT</option>
                            <option value="HR">HR</option>
                            <option value="Finance">Finance</option>
                        </select>
                    </div>
                    <div class="form-group"><label>User Type</label>
                        <select name="user_type" class="form-control" required>
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                `;
            } else if (type === 'product') {
                title = 'Add New Product';
                fields = `
                    <div class="form-group"><label>Product Code</label><input type="text" name="product_code" class="form-control" required></div>
                    <div class="form-group"><label>Product Name</label><input type="text" name="product_name" class="form-control" required></div>
                    <div class="form-group"><label>Category</label><input type="text" name="category" class="form-control" required></div>
                    <div class="form-group"><label>Price</label><input type="number" step="0.01" name="price" class="form-control" required></div>
                    <div class="form-group"><label>Stock</label><input type="number" name="stock" class="form-control" required></div>
                `;
            } else if (type === 'customer') {
                title = 'Add New Customer';
                fields = `
                    <div class="form-group"><label>Customer Code</label><input type="text" name="customer_code" class="form-control" required></div>
                    <div class="form-group"><label>Full Name</label><input type="text" name="fullname" class="form-control" required></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="form-group"><label>Address</label><textarea name="address" class="form-control" required></textarea></div>
                `;
            }

            $('#formModalTitle').text(title);
            $('#formModalBody').html(fields);
            $('#formModal').data('type', type); // Store type in modal
            $('#formModal').modal('show');
        }

        function submitForm() {
            const type = $('#formModal').data('type');
            const formData = $('#mainForm').serializeArray();
            const payload = {};
            
            $.each(formData, function(i, field) {
                payload[field.name] = field.value;
            });

            // Handle User Registration specific logic (password repassword)
            // Since API expects specific fields, ensure they match
            if(type === 'user') {
                payload.repassword = payload.password; // Simplified for API
            }

            $('#myOverlay').addClass('active');

            fetch(`${API_URL}/${type}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                $('#myOverlay').removeClass('active');
                if(data.status === 'success') {
                    $('#formModal').modal('hide');
                    Swal.fire('Success!', data.message, 'success');
                    loadData(type + 's');
                } else {
                    Swal.fire('Error!', data.message, 'error');
                }
            })
            .catch(err => {
                $('#myOverlay').removeClass('active');
                Swal.fire('Error!', 'Connection error', 'error');
            });
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
    </script> 

</body> 
</html>