<?php
// users.php
// Display page for User Management - Split View Layout
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Management</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="icon" type="image/png" sizes="40x16" href="../../dist/img/splogo.png">
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
        /* Fix layout height */
        .content-wrapper { min-height: 100vh; }
        
        /* Custom Split View Styling */
        .split-container {
            display: flex;
            height: calc(100vh - 120px);
            gap: 20px;
            padding: 10px;
        }
        
        .form-panel {
            flex: 0 0 400px; /* Slightly wider for User form */
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        
        .form-panel-header {
            padding: 15px 20px;
            background-color: #343a40;
            color: white;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        
        .form-panel-body {
            padding: 20px;
            overflow-y: auto;
            flex-grow: 1;
        }
        
        .table-panel {
            flex: 1;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid #dee2e6;
        }
        
        .table-panel-header {
            padding: 15px 20px;
            background-color: #343a40;
            color: white;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }
        
        .table-panel-body {
            padding: 15px;
            overflow-y: auto;
            flex-grow: 1;
        }
        
        /* Inline Form Adjustments */
        .form-group {
            margin-bottom: 12px;
        }
        
        .form-group label {
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 4px;
            color: #495057;
        }
        
        .form-control:focus, .custom-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .form-control-sm, .custom-select-sm {
            font-size: 0.875rem;
        }
        
        /* Status Badges */
        .badge-approved { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; white-space: nowrap; }
        .badge-disapproved { background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; white-space: nowrap; }
        .badge-pending { background-color: #ffc107; color: black; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; white-space: nowrap; }
        
        /* DataTable Specifics */
        table.dataTable thead th { background-color: #f8f9fa; color: #343a40; border-bottom: 2px solid #dee2e6; font-size: 0.8rem; }
        table.dataTable tbody td { font-size: 0.85rem; vertical-align: middle; padding: 8px; }
        .dataTables_wrapper .dataTables_filter input { margin-left: 0; }
        
        /* Action Buttons */
        .btn-action {
            padding: 4px 8px;
            font-size: 0.8rem;
            border-radius: 4px;
            margin: 0 2px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .split-container {
                flex-direction: column;
                height: auto;
            }
            .form-panel {
                flex: none;
            }
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
                        <h1 class="m-0 text-dark">User Management</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="split-container">
                    
                    <!-- LEFT PANEL: INLINE FORM -->
                    <div class="form-panel">
                        <div class="form-panel-header">
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-user-shield mr-2"></i><span id="formTitle">Add New User</span></h5>
                            <button type="button" class="btn btn-sm btn-light" id="btnReset" onclick="resetForm()" title="Clear Form">
                                <i class="fas fa-eraser"></i> Clear
                            </button>
                        </div>
                        <div class="form-panel-body">
                            <form id="userForm" autocomplete="off">
                                <input type="hidden" name="objid" id="user_objid">
                                
                                <div class="form-group">
                                    <label>ID No. <span class="text-danger">*</span></label>
                                    <input type="text" name="idno" id="user_idno" class="form-control form-control-sm" required>
                                </div>

                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="fullname" id="user_fullname" class="form-control form-control-sm" required>
                                </div>

                                <div class="form-group">
                                    <label>Username <span class="text-danger">*</span></label>
                                    <input type="text" name="username" id="user_username" class="form-control form-control-sm" required>
                                </div>

                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="user_email" class="form-control form-control-sm" required>
                                </div>

                                <div class="form-group">
                                    <label>Contact No. <span class="text-danger">*</span></label>
                                    <input type="text" name="contactno" id="user_contactno" class="form-control form-control-sm" required>
                                </div>

                                <div class="form-group">
                                    <label>Department <span class="text-danger">*</span></label>
                                    <input type="text" name="department" id="user_department" class="form-control form-control-sm" required>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Type <span class="text-danger">*</span></label>
                                            <select name="user_type" id="user_user_type" class="form-control form-control-sm custom-select-sm" required>
                                                <option value="admin">Admin</option>
                                                <option value="manager">Manager</option>
                                                <option value="cashier">Cashier</option>
                                                <option value="staff">Staff</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" id="user_status" class="form-control form-control-sm custom-select-sm">
                                                <option value="APPROVED">APPROVED</option>
                                                <option value="DISAPPROVED">DISAPPROVED</option>
                                                <option value="PENDING">PENDING</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Password <span class="text-danger" id="passwordRequired">*</span></label>
                                    <input type="password" name="password" id="user_password" class="form-control form-control-sm">
                                    <small class="text-muted" style="font-size: 0.75rem;" id="passwordHint">Required for new users.</small>
                                </div>

                                <div class="mt-4 d-flex">
                                    <button type="submit" class="btn btn-primary btn-block btn-sm" id="btnSubmit">
                                        <i class="fas fa-save mr-1"></i> Save User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIGHT PANEL: DATA TABLE -->
                    <div class="table-panel">
                        <div class="table-panel-header">
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-users-cog mr-2"></i>User List</h5>
                            <span class="badge badge-light" id="totalCount">0 Users</span>
                        </div>
                        <div class="table-panel-body">
                            <table id="users-table" class="table table-bordered table-hover mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ID No.</th>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-center" style="width: 110px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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

<!-- DataTables & Plugins -->
<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="../../plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="../../plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.0/dist/sweetalert2.all.min.js"></script>

<script>
 $(document).ready(function() {
    const API_URL = '../../api/routes.php';
    var usersData = []; // Store users data globally
    var table;

    // Helper function to get user data by ID
    function getUserDataById(objid) {
        return usersData.find(user => user.objid == objid);
    }

    // Initialize DataTable
    function initializeDataTable() {
        table = $('#users-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "pageLength": 15,
            "searching": true,
            "ordering": true,
            "info": true,
            "dom": '<"row mb-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"f>>rt<"row mt-2"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7"p>>',
            "language": {
                "search": "<div class='input-group input-group-sm'><div class='input-group-prepend'><span class='input-group-text'><i class='fas fa-search'></i></span></div>",
                "searchPlaceholder": "Search users...",
            },
            "ajax": {
                "url": API_URL + "/users",
                "type": "GET",
                "dataSrc": function(json) {
                    if (json.status === 'success') {
                        usersData = json.data; // Store data globally
                        updateTotalCount(json.data.length);
                        return json.data;
                    } else {
                        console.error("API returned error:", json.message);
                        Swal.fire("Error", json.message, "error"); 
                        return [];
                    }
                },
                "error": function (xhr, error, thrown) {
                    console.error("AJAX Error:", error, thrown);
                    Swal.fire("Connection Error", "Could not connect to API.", "error");
                }
            },
            "columns": [
                { "data": "idno", "className": "align-middle" },
                { "data": "fullname", "className": "align-middle" },
                { "data": "username", "className": "align-middle" },
                { "data": "user_type", "className": "align-middle text-capitalize" },
                { 
                    "data": "status",
                    "className": "align-middle",
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
                    "className": "align-middle text-center",
                    "render": function(data, type, row) {
                        // Edit Button
                        var editBtn = `<button type="button" class="btn btn-warning btn-action" onclick="updateUser('${row.objid}')" title="Edit User"><i class="fas fa-pen"></i></button>`;
                        
                        // Quick Status Buttons (Toggle)
                        var statusBtn = '';
                        if(row.status === 'APPROVED') {
                            statusBtn = `<button type="button" class="btn btn-danger btn-action" onclick="disapproveUser('${row.objid}')" title="Disapprove"><i class="fas fa-thumbs-down"></i></button>`;
                        } else {
                            statusBtn = `<button type="button" class="btn btn-success btn-action" onclick="approveUser('${row.objid}')" title="Approve"><i class="fas fa-thumbs-up"></i></button>`;
                        }

                        // Delete Button
                        var deleteBtn = `<button type="button" class="btn btn-danger btn-action" onclick="deleteUser('${row.objid}')" title="Delete User"><i class="fas fa-trash"></i></button>`;

                        return `<div class="btn-group btn-group-sm">${editBtn}${statusBtn}${deleteBtn}</div>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
    }

    function updateTotalCount(count) {
        $('#totalCount').text(count + ' Users');
    }

    // Initialize DataTable
    initializeDataTable();

    // ------------------------------------------------------------------
    // RESET FORM
    // ------------------------------------------------------------------
    window.resetForm = function() {
        $('#userForm')[0].reset();
        $('#user_objid').val('');
        $('#formTitle').text('Add New User');
        $('#btnSubmit').html('<i class="fas fa-save mr-1"></i> Save User');
        $('#btnSubmit').removeClass('btn-warning').addClass('btn-primary');
        
        // Reset Password fields
        $('#user_password').prop('required', true);
        $('#passwordRequired').show();
        $('#passwordHint').text('Required for new users.');
        $('#user_password').val('');
    }

    // ------------------------------------------------------------------
    // UPDATE USER (Populate Form)
    // ------------------------------------------------------------------
    window.updateUser = function(objid) {
        var userData = getUserDataById(objid);
        
        if(userData) {
            $('#formTitle').text('Update User');
            $('#btnSubmit').html('<i class="fas fa-check mr-1"></i> Update User');
            $('#btnSubmit').removeClass('btn-primary').addClass('btn-warning');
            
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
            
            // Handle Password Field
            $('#user_password').val(''); // Clear password field
            $('#user_password').prop('required', false);
            $('#passwordRequired').hide();
            $('#passwordHint').text('Leave blank to keep current password.');
            
            // Scroll to top of form on mobile
            $('.form-panel').scrollTop(0);
        } else {
            Swal.fire("Error", "Could not retrieve user data.", "error");
        }
    }

    // ------------------------------------------------------------------
    // APPROVE USER
    // ------------------------------------------------------------------
    window.approveUser = function(objid) {
        Swal.fire({
            title: 'Approve User?',
            text: "This will set the user status to APPROVED.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                $.ajax({
                    url: API_URL + '/users/status/' + objid, 
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify({ status: 'APPROVED' }),
                    success: function(res) {
                        Swal.close();
                        if(res.status === 'success') {
                            Swal.fire('Approved!', res.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire('Error!', 'Server communication error.', 'error');
                    }
                });
            }
        });
    }

    // ------------------------------------------------------------------
    // DISAPPROVE USER
    // ------------------------------------------------------------------
    window.disapproveUser = function(objid) {
        Swal.fire({
            title: 'Disapprove User?',
            text: "This will set the user status to DISAPPROVED.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, disapprove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                $.ajax({
                    url: API_URL + '/users/status/' + objid, 
                    type: 'PUT',
                    contentType: 'application/json',
                    data: JSON.stringify({ status: 'DISAPPROVED' }),
                    success: function(res) {
                        Swal.close();
                        if(res.status === 'success') {
                            Swal.fire('Disapproved!', res.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire('Error!', 'Server communication error.', 'error');
                    }
                });
            }
        });
    }

    // ------------------------------------------------------------------
    // DELETE USER
    // ------------------------------------------------------------------
    window.deleteUser = function(objid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                $.ajax({
                    url: API_URL + '/users/' + objid,
                    type: 'DELETE',
                    success: function(res) {
                        Swal.close();
                        if(res.status === 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            // If the deleted user was being edited, reset the form
                            if($('#user_objid').val() == objid) {
                                resetForm();
                            }
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        Swal.fire('Error!', 'Server communication error.', 'error');
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

        Swal.fire({
            title: isUpdate ? 'Updating...' : 'Saving...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                Swal.close();
                if(res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: isUpdate ? 'Updated!' : 'Saved!',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                    resetForm();
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
});
</script>
</body>
</html>