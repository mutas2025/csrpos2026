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
<!-- Include Sidebar -->
<?php include '../../pages/sidebar/sidebar.php'; ?> 

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User Management</h1>
                    </div>
                </div>
            </div>
        </div>

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
                        <div class="form-group"><label>ID No.</label><input type="text" name="idno" id="user_idno" class="form-control" required></div>
                        <div class="form-group"><label>Full Name</label><input type="text" name="fullname" id="user_fullname" class="form-control" required></div>
                        <div class="form-group"><label>Username</label><input type="text" name="username" id="user_username" class="form-control" required></div>
                        <div class="form-group"><label>Email</label><input type="email" name="email" id="user_email" class="form-control" required></div>
                        <div class="form-group"><label>Contact No.</label><input type="text" name="contactno" id="user_contactno" class="form-control" required></div>
                        <div class="form-group"><label>Department</label><input type="text" name="department" id="user_department" class="form-control" required></div>
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
    // ===================================================================
    // IMPORTANT: CHECK THIS PATH
    // If this file is in /pages/users.php, and api is in /api/routes.php
    // then the path should be correct.
    // If your folder structure is different, fix this line:
    // ===================================================================
    const API_URL = '../../api/routes.php';
    
    var table;

    $(function () {
        table = $('#users-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": API_URL + "/users",
                "type": "GET",
                // This function handles the response before DataTables uses it
                "dataSrc": function(json) {
                    console.log("API Response:", json); // DEBUG: Check your browser console (F12)
                    
                    if (json.status === 'success') {
                        return json.data;
                    } else {
                        console.error("API returned error:", json.message);
                        // Alert user if there is a backend error message
                        Swal.fire("Backend Error", json.message, "error"); 
                        return []; // Return empty array to prevent crash
                    }
                },
                // This catches connection errors (404, 500, etc.)
                "error": function (xhr, error, thrown) {
                    console.error("AJAX Error:", error, thrown);
                    Swal.fire("Connection Error", "Could not connect to API: " + API_URL, "error");
                }
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
                        return `
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
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

    function openAddModal() {
        $('#modalTitle').text('Add New User');
        $('#userForm')[0].reset();
        $('#user_objid').val('');
        $('#user_password').prop('required', true);
        $('#userModal').modal('show');
    }

    function updateUser(objid) {
        $('#modalTitle').text('Update User');
        $('#user_password').prop('required', false);
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
            $('#user_password').val(''); 
            $('#userModal').modal('show');
        }
    }

    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        var payload = {};
        $.each($(this).serializeArray(), function(i, field) {
            payload[field.name] = field.value;
        });

        var isUpdate = payload.objid !== '';
        var url = API_URL + '/users';
        var method = 'POST';

        if (isUpdate) {
            method = 'PUT';
            url = API_URL + '/users/' + payload.objid;
            if(payload.password === '') delete payload.password;
        } else {
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
            }
        });
    });

    function deleteUser(objid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
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
</script>
</body>
</html>