<?php
// users.php
// Display page for User Management
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management</title>

    <!-- Google Font: Source Sans Pro -->
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
        .content-wrapper { min-height: 100vh; }
        table.dataTable thead th { background-color: #343a40; color: white; }
        .dropdown-menu { min-width: 8rem; }
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
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="#" class="brand-link">
            <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
        </a>
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List of Users</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="openAddModal()">
                                        <i class="fas fa-plus"></i> Add User
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="users-table" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID No.</th>
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Department</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal for Add/Edit User -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="userForm">
                    <div class="modal-body">
                        <input type="hidden" name="objid" id="user_objid">
                        <div class="form-group">
                            <label>ID No.</label>
                            <input type="text" name="idno" id="user_idno" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" id="user_fullname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" id="user_username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="user_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contact No.</label>
                            <input type="text" name="contactno" id="user_contactno" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <input type="text" name="department" id="user_department" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>User Type</label>
                            <select name="user_type" id="user_user_type" class="form-control" required>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                            </select>
                        </div>
                        <div class="form-group" id="passwordGroup">
                            <label>Password</label>
                            <input type="password" name="password" id="user_password" class="form-control">
                            <small class="text-muted">Leave blank to keep current password when editing.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<!-- REQUIRED SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- DataTables & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="../../plugins/jszip/jszip.min.js"></script>
<script src="../../plugins/pdfmake/pdfmake.min.js"></script>
<script src="../../plugins/pdfmake/vfs_fonts.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="../../plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
    const API_URL = '../../api/routes.php';
    var table;

    $(function () {
        // Initialize DataTable
        table = $('#users-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": API_URL + "/users",
                "type": "GET",
                "dataSrc": "data"
            },
            "columns": [
                { "data": "idno" },
                { "data": "fullname" },
                { "data": "username" },
                { "data": "email" },
                { "data": "contactno" },
                { "data": "department" },
                { "data": "user_type" },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        // Using objid for actions as it is the primary key
                        return `
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="approveUser('${row.objid}')"><i class="fas fa-check mr-2"></i>Approve</a>
                                <a class="dropdown-item" href="#" onclick="updateUser('${row.objid}')"><i class="fas fa-edit mr-2"></i>Update</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteUser('${row.objid}')"><i class="fas fa-trash mr-2"></i>Delete</a>
                            </div>
                        </div>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        }).buttons().container().appendTo('#users-table_wrapper .col-md-6:eq(0)');
    });

    // Open Add Modal
    function openAddModal() {
        $('#modalTitle').text('Add New User');
        $('#userForm')[0].reset();
        $('#user_objid').val('');
        $('#passwordGroup').show();
        $('#user_password').prop('required', true);
        $('#userModal').modal('show');
    }

    // Update User (Load Data to Modal)
    function updateUser(objid) {
        $('#modalTitle').text('Update User');
        $('#user_password').prop('required', false);
        
        // Fetch user details from current table data
        var rowData = table.rows().data().toArray().find(r => r.objid == objid);
        
        if(rowData) {
            $('#user_objid').val(rowData.objid);
            $('#user_idno').val(rowData.idno);
            $('#user_fullname').val(rowData.fullname);
            $('#user_username').val(rowData.username);
            $('#user_email').val(rowData.email);
            $('#user_contactno').val(rowData.contactno);
            $('#user_department').val(rowData.department);
            $('#user_user_type').val(rowData.user_type);
            $('#user_password').val(''); // Clear password field
            
            $('#userModal').modal('show');
        }
    }

    // Save User (Add or Update)
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serializeArray();
        var payload = {};
        $.each(formData, function(i, field) {
            payload[field.name] = field.value;
        });

        var isUpdate = payload.objid !== '';
        var url = API_URL + '/users';
        var method = 'POST';

        if (isUpdate) {
            method = 'PUT';
            url = API_URL + '/users/' + payload.objid;
            // Remove password if empty on update
            if(payload.password === '') {
                delete payload.password;
            }
        } else {
            // Add repassword for registration logic
            payload.repassword = payload.password;
        }

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('Success!', res.message, 'success');
                    $('#userModal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Error!', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error!', 'Connection error', 'error');
            }
        });
    });

    // Delete User
    function deleteUser(objid) {
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
                $.ajax({
                    url: API_URL + '/users/' + objid,
                    type: 'DELETE',
                    success: function(res) {
                        if(res.status === 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    }
                });
            }
        });
    }

    // Placeholder for Approve User
    function approveUser(objid) {
        Swal.fire('Info', 'Approval logic for user ID: ' + objid + ' can be implemented here.', 'info');
        // You would typically send a POST/PUT request to update a 'status' field in the DB
    }
</script>
</body>
</html>