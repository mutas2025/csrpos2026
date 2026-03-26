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
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
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
        .dropdown-menu { min-width: 10rem; }
        
        /* Status Badges */
        .badge-approved { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 3px; }
        .badge-disapproved { background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 3px; }
        .badge-pending { background-color: #ffc107; color: black; padding: 5px 10px; border-radius: 3px; }
        
        /* Modal styles */
        .modal-lg { max-width: 800px; }
        
        .view-table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .view-table th {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
            width: 35%;
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
                                            <th>ID No.</th>
                                            <th>Full Name</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Department</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
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

    <!-- Modal for Add / Update User -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="userForm">
                    <div class="modal-body">
                        <input type="hidden" name="objid" id="user_objid">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>ID No. <span class="text-danger">*</span></label>
                                    <input type="text" name="idno" id="user_idno" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="fullname" id="user_fullname" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="user_username" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="user_email" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact No. <span class="text-danger">*</span></label>
                                    <input type="text" name="contactno" id="user_contactno" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <input type="text" name="department" id="user_department" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>User Type <span class="text-danger">*</span></label>
                                    <select name="user_type" id="user_user_type" class="form-control" required>
                                        <option value="admin">Admin</option>
                                        <option value="manager">Manager</option>
                                        <option value="cashier">Cashier</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" id="user_status" class="form-control">
                                        <option value="APPROVED">APPROVED</option>
                                        <option value="DISAPPROVED">DISAPPROVED</option>
                                        <option value="PENDING">PENDING</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group" id="passwordGroup">
                            <label>Password <span class="text-danger" id="passwordRequired">*</span></label>
                            <input type="password" name="password" id="user_password" class="form-control">
                            <small class="text-muted" id="passwordHint">Leave blank to keep current password when editing.</small>
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

    <!-- Modal for View User (Read Only) -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">User Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-borderless view-table">
                        <tr>
                            <th>ID No.</th>
                            <td id="v_idno">-</td>
                        </tr>
                        <tr>
                            <th>Full Name</th>
                            <td id="v_fullname">-</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td id="v_username">-</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td id="v_email">-</td>
                        </tr>
                        <tr>
                            <th>Contact No.</th>
                            <td id="v_contactno">-</td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td id="v_department">-</td>
                        </tr>
                        <tr>
                            <th>User Type</th>
                            <td id="v_user_type">-</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td id="v_status">-</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td id="v_created_at">-</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- ADDED EDIT BUTTON HERE -->
                    <button type="button" class="btn btn-warning" id="editFromViewBtn">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- REQUIRED SCRIPTS -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
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
<!-- AdminLTE & SweetAlert2 -->
<script src="../../dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
 $(document).ready(function() {
    // ------------------------------------------------------------------
    // CONFIGURATION
    // ------------------------------------------------------------------
    const API_URL = '../../api/routes.php';
    var usersData = []; // Store users data globally for easy access
    var table;

    // ------------------------------------------------------------------
    // INITIALIZATION
    // ------------------------------------------------------------------
    function initializeDataTable() {
        table = $('#users-table').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": API_URL + "/users",
                "type": "GET",
                "dataSrc": function(json) {
                    if (json.status === 'success') {
                        usersData = json.data; // Store data globally
                        return json.data;
                    } else {
                        Swal.fire("Error", json.message, "error");
                        return [];
                    }
                }
            },
            "columns": [
                { "data": "idno" },
                { "data": "fullname" },
                { "data": "username" },
                { "data": "email" },
                { "data": "department" },
                { "data": "user_type" },
                { 
                    "data": "status",
                    "render": function(data, type, row) {
                        var badgeClass = 'badge-pending';
                        var statusText = data || 'PENDING';
                        if(statusText === 'APPROVED') badgeClass = 'badge-approved';
                        if(statusText === 'DISAPPROVED') badgeClass = 'badge-disapproved';
                        return '<span class="' + badgeClass + '">' + statusText + '</span>';
                    }
                },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        // Build Action Dropdown
                        var approveBtn = '';
                        if(row.status !== 'APPROVED') {
                            approveBtn = '<a class="dropdown-item" href="javascript:void(0)" onclick="approveUser(\'' + row.objid + '\')"><i class="fas fa-check-circle mr-2 text-success"></i>Approve</a>';
                        }

                        var disapproveBtn = '';
                        if(row.status !== 'DISAPPROVED') {
                            disapproveBtn = '<a class="dropdown-item" href="javascript:void(0)" onclick="disapproveUser(\'' + row.objid + '\')"><i class="fas fa-times-circle mr-2 text-danger"></i>Disapprove</a>';
                        }

                        // REMOVED UPDATE BUTTON FROM DROPDOWN
                        return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                ${approveBtn}
                                ${disapproveBtn}
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="viewUser('${row.objid}')"><i class="fas fa-eye mr-2 text-info"></i>View</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="deleteUser('${row.objid}')"><i class="fas fa-trash mr-2 text-danger"></i>Delete</a>
                            </div>
                        </div>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
        
        // Add buttons after table is initialized
        table.buttons().container().appendTo('#users-table_wrapper .col-md-6:eq(0)');
    }

    // Initialize DataTable
    initializeDataTable();

    // Helper function to get user data by ID
    function getUserDataById(objid) {
        return usersData.find(user => user.objid == objid);
    }

    // ------------------------------------------------------------------
    // 1. ADD USER
    // ------------------------------------------------------------------
    window.openAddModal = function() {
        $('#modalTitle').text('Add New User');
        $('#userForm')[0].reset();
        $('#user_objid').val('');
        $('#user_password').prop('required', true);
        $('#passwordRequired').show();
        $('#passwordHint').text('Password is required for new users.');
        $('#user_password').val('');
        $('#user_status').val('APPROVED');
        $('#userModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#userModal').modal('show');
    }

    // ------------------------------------------------------------------
    // 2. VIEW USER
    // ------------------------------------------------------------------
    window.viewUser = function(objid) {
        var userData = getUserDataById(objid);
        
        if(userData) {
            // Populate the View Modal fields
            $('#v_idno').text(userData.idno || 'N/A');
            $('#v_fullname').text(userData.fullname || 'N/A');
            $('#v_username').text(userData.username || 'N/A');
            $('#v_email').text(userData.email || 'N/A');
            $('#v_contactno').text(userData.contactno || 'N/A');
            $('#v_department').text(userData.department || 'N/A');
            
            var userTypeText = userData.user_type ? userData.user_type.charAt(0).toUpperCase() + userData.user_type.slice(1) : 'N/A';
            $('#v_user_type').text(userTypeText);
            
            var statusText = userData.status || 'N/A';
            var statusHtml = '';
            if(statusText === 'APPROVED') {
                statusHtml = '<span class="badge-approved">' + statusText + '</span>';
            } else if(statusText === 'DISAPPROVED') {
                statusHtml = '<span class="badge-disapproved">' + statusText + '</span>';
            } else {
                statusHtml = '<span class="badge-pending">' + statusText + '</span>';
            }
            $('#v_status').html(statusHtml);
            $('#v_created_at').text(userData.created_at || 'N/A');
            
            // Store the ID on the Edit button for reference
            $('#editFromViewBtn').data('objid', objid);
            
            // Show the modal
            $('#viewModal').modal('show');
        } else {
            Swal.fire("Error", "Could not retrieve user data.", "error");
        }
    }

    // ------------------------------------------------------------------
    // 3. UPDATE USER (Logic to populate form)
    // ------------------------------------------------------------------
    window.updateUser = function(objid) {
        var userData = getUserDataById(objid);
        
        if(userData) {
            $('#modalTitle').text('Update User');
            $('#user_password').prop('required', false);
            $('#passwordRequired').hide();
            $('#passwordHint').text('Leave blank to keep current password.');
            $('#user_password').val('');
            
            // Populate the Form fields
            $('#user_objid').val(userData.objid);
            $('#user_idno').val(userData.idno);
            $('#user_fullname').val(userData.fullname);
            $('#user_username').val(userData.username);
            $('#user_email').val(userData.email);
            $('#user_contactno').val(userData.contactno);
            $('#user_department').val(userData.department);
            
            // Set User Type dropdown
            var userType = userData.user_type ? userData.user_type.toLowerCase() : 'staff';
            $('#user_user_type').val(userType);
            
            // Set Status dropdown
            $('#user_status').val(userData.status);
            
            // Show the modal
            $('#userModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#userModal').modal('show');
        } else {
            Swal.fire("Error", "Could not retrieve user data for update.", "error");
        }
    }

    // ------------------------------------------------------------------
    // EDIT BUTTON CLICK HANDLER (Inside View Modal)
    // ------------------------------------------------------------------
    $('#editFromViewBtn').on('click', function() {
        var objid = $(this).data('objid');
        $('#viewModal').modal('hide'); // Hide the View Modal
        
        // Small delay to allow modal transition
        setTimeout(function() {
            updateUser(objid); // Open the Edit Modal
        }, 300);
    });

    // ------------------------------------------------------------------
    // 4. APPROVE USER
    // ------------------------------------------------------------------
    window.approveUser = function(objid) {
        Swal.fire({
            title: 'Approve User?',
            text: "This will set the user status to APPROVED.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: API_URL + '/users/status/' + objid, 
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify({ status: 'APPROVED' }),
                    success: function(res) {
                        if(res.status === 'success') {
                            Swal.fire('Approved!', res.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Server communication error: ' + (xhr.statusText || 'Unknown error'), 'error');
                    }
                });
            }
        });
    }

    // ------------------------------------------------------------------
    // 5. DISAPPROVE USER
    // ------------------------------------------------------------------
    window.disapproveUser = function(objid) {
        Swal.fire({
            title: 'Disapprove User?',
            text: "This will set the user status to DISAPPROVED.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, disapprove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: API_URL + '/users/status/' + objid, 
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify({ status: 'DISAPPROVED' }),
                    success: function(res) {
                        if(res.status === 'success') {
                            Swal.fire('Disapproved!', res.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Server communication error: ' + (xhr.statusText || 'Unknown error'), 'error');
                    }
                });
            }
        });
    }

    // ------------------------------------------------------------------
    // 6. DELETE USER
    // ------------------------------------------------------------------
    window.deleteUser = function(objid) {
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
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Server communication error: ' + (xhr.statusText || 'Unknown error'), 'error');
                    }
                });
            }
        });
    }

    // ------------------------------------------------------------------
    // FORM SUBMISSION (Add / Update Logic)
    // ------------------------------------------------------------------
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        var idno = $('#user_idno').val().trim();
        var fullname = $('#user_fullname').val().trim();
        var username = $('#user_username').val().trim();
        var email = $('#user_email').val().trim();
        var contactno = $('#user_contactno').val().trim();
        var department = $('#user_department').val().trim();
        var user_type = $('#user_user_type').val();
        
        if(!idno || !fullname || !username || !email || !contactno || !department || !user_type) {
            Swal.fire('Error!', 'All fields are required.', 'error');
            return;
        }
        
        var isUpdate = $('#user_objid').val() !== '';
        var password = $('#user_password').val();
        
        // Validate password for new users
        if (!isUpdate && !password) {
            Swal.fire('Error!', 'Password is required for new users.', 'error');
            return;
        }
        
        var payload = {
            idno: idno,
            fullname: fullname,
            username: username,
            email: email,
            contactno: contactno,
            department: department,
            user_type: user_type,
            status: $('#user_status').val()
        };
        
        if (isUpdate) {
            payload.objid = $('#user_objid').val();
            if(password) {
                payload.password = password;
            }
        } else {
            payload.password = password;
            payload.repassword = password;
            payload.terms_agreed = 1;
        }

        var url = API_URL + '/users';
        var method = 'POST';

        if (isUpdate) {
            method = 'PUT';
            url = API_URL + '/users/' + payload.objid;
        }

        // Show loading indicator
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                Swal.close();
                if(res.status === 'success') {
                    Swal.fire('Success!', res.message, 'success');
                    $('#userModal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Error!', res.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.close();
                var errorMsg = 'Server communication error.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });
    
    // Clear modal when closed
    $('#userModal').on('hidden.bs.modal', function () {
        $('#userForm')[0].reset();
        $('#user_objid').val('');
        $('#user_password').prop('required', true);
        $('#passwordRequired').show();
        $('#passwordHint').text('Password is required for new users.');
    });
});
</script>
</body>
</html>