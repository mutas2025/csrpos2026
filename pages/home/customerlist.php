<?php
// customerlist.php
// Display page for Customer Management
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Management</title>

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
        
        /* View Modal Styles */
        .view-table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .view-table th {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
            width: 35%;
            font-weight: 600;
        }
        
        .customer-image-placeholder {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .customer-image-placeholder i {
            font-size: 80px;
            color: #6c757d;
        }
        
        .contact-info {
            margin-top: 5px;
            font-size: 0.9em;
        }
        
        .contact-info i {
            width: 20px;
            color: #007bff;
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
                        <h1 class="m-0">Customer Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Customers</li>
                        </ol>
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
                                <h3 class="card-title">List of Customers</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="openAddModal()">
                                        <i class="fas fa-plus"></i> Add Customer
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="customers-table" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        32
                                            <th>Code</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Address</th>
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

    <!-- Modal for Add/Edit Customer -->
    <div class="modal fade" id="customerModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="customerForm">
                    <div class="modal-body">
                        <input type="hidden" name="objid" id="cust_objid">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Customer Code <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_code" id="cust_code" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="fullname" id="cust_fullname" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="cust_email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="cust_phone" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <textarea name="address" id="cust_address" class="form-control" rows="3" required placeholder="Enter complete address..."></textarea>
                                </div>
                            </div>
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

    <!-- Modal for View Customer -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">Customer Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="customer-image-placeholder">
                                <i class="fas fa-user-circle"></i>
                                <h5 id="v_fullname" class="mt-2">-</h5>
                                <p class="text-muted" id="v_customer_code">-</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless view-table">
                                <tr>
                                    <th>Customer Code:</th>
                                    <td id="v_code">-</td>
                                </tr>
                                <tr>
                                    <th>Full Name:</th>
                                    <td id="v_name">-</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td id="v_email">-</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td id="v_phone">-</td>
                                </tr>
                                <tr>
                                    <th>Address:</th>
                                    <td id="v_address">-</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td id="v_created_at">-</td>
                                </tr>
                                <tr>
                                    <th>Last Updated:</th>
                                    <td id="v_updated_at">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="editFromView()">Edit Customer</button>
                </div>
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
$(document).ready(function() {
    const API_URL = '../../api/routes.php';
    var customersData = []; // Store customers data globally
    var currentViewCustomerId = null; // Store current viewed customer ID
    var table;

    // Helper function to get customer data by ID
    function getCustomerDataById(objid) {
        return customersData.find(customer => customer.objid == objid);
    }

    // Initialize DataTable
    function initializeDataTable() {
        table = $('#customers-table').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": API_URL + "/customers",
                "type": "GET",
                "dataSrc": function(json) {
                    console.log("API Response:", json);
                    if (json.status === 'success') {
                        customersData = json.data; // Store data globally
                        return json.data;
                    } else {
                        console.error("API returned error:", json.message);
                        Swal.fire("Error", json.message, "error"); 
                        return [];
                    }
                },
                "error": function (xhr, error, thrown) {
                    console.error("AJAX Error:", error, thrown);
                    Swal.fire("Connection Error", "Could not connect to API: " + API_URL, "error");
                }
            },
            "columns": [
                { "data": "customer_code" },
                { "data": "fullname" },
                { "data": "email" },
                { "data": "phone" },
                { 
                    "data": "address",
                    "render": function(data, type, row) {
                        if(type === 'display') {
                            if(data && data.length > 50) {
                                return '<span title="' + data + '">' + data.substring(0, 47) + '...</span>';
                            }
                            return data || 'N/A';
                        }
                        return data;
                    }
                },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="viewCustomer('${row.objid}')">
                                    <i class="fas fa-eye mr-2 text-info"></i>View
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="updateCustomer('${row.objid}')">
                                    <i class="fas fa-edit mr-2 text-primary"></i>Update
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteCustomer('${row.objid}')">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </a>
                            </div>
                        </div>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ],
            "drawCallback": function() {
                // Re-initialize any tooltips if needed
            }
        });
        
        // Add buttons after table is initialized
        table.buttons().container().appendTo('#customers-table_wrapper .col-md-6:eq(0)');
    }

    // Initialize DataTable
    initializeDataTable();

    // ------------------------------------------------------------------
    // 1. ADD CUSTOMER
    // ------------------------------------------------------------------
    window.openAddModal = function() {
        $('#modalTitle').text('Add New Customer');
        $('#customerForm')[0].reset();
        $('#cust_objid').val('');
        $('#customerModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#customerModal').modal('show');
    }

    // ------------------------------------------------------------------
    // 2. VIEW CUSTOMER
    // ------------------------------------------------------------------
    window.viewCustomer = function(objid) {
        console.log('Viewing customer:', objid);
        var customerData = getCustomerDataById(objid);
        currentViewCustomerId = objid;
        
        if(customerData) {
            // Populate the View Modal fields
            $('#v_code').text(customerData.customer_code || 'N/A');
            $('#v_customer_code').text(customerData.customer_code || 'N/A');
            $('#v_name').text(customerData.fullname || 'N/A');
            $('#v_fullname').text(customerData.fullname || 'N/A');
            $('#v_email').html('<i class="fas fa-envelope mr-2"></i>' + (customerData.email || 'N/A'));
            $('#v_phone').html('<i class="fas fa-phone mr-2"></i>' + (customerData.phone || 'N/A'));
            $('#v_address').text(customerData.address || 'No address provided.');
            $('#v_created_at').text(customerData.created_at || 'N/A');
            $('#v_updated_at').text(customerData.updated_at || 'N/A');
            
            // Show the modal
            $('#viewModal').modal('show');
        } else {
            console.error('Customer not found:', objid);
            Swal.fire("Error", "Could not retrieve customer data. Customer ID: " + objid, "error");
        }
    }

    // ------------------------------------------------------------------
    // 3. UPDATE CUSTOMER
    // ------------------------------------------------------------------
    window.updateCustomer = function(objid) {
        console.log('Updating customer:', objid);
        var customerData = getCustomerDataById(objid);
        
        if(customerData) {
            $('#modalTitle').text('Update Customer');
            
            // Populate the Form fields
            $('#cust_objid').val(customerData.objid);
            $('#cust_code').val(customerData.customer_code);
            $('#cust_fullname').val(customerData.fullname);
            $('#cust_email').val(customerData.email);
            $('#cust_phone').val(customerData.phone);
            $('#cust_address').val(customerData.address);
            
            // Show the modal
            $('#customerModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#customerModal').modal('show');
        } else {
            console.error('Customer not found:', objid);
            Swal.fire("Error", "Could not retrieve customer data for update. Customer ID: " + objid, "error");
        }
    }

    // ------------------------------------------------------------------
    // 4. EDIT FROM VIEW
    // ------------------------------------------------------------------
    window.editFromView = function() {
        if(currentViewCustomerId) {
            $('#viewModal').modal('hide');
            updateCustomer(currentViewCustomerId);
        }
    }

    // ------------------------------------------------------------------
    // 5. DELETE CUSTOMER
    // ------------------------------------------------------------------
    window.deleteCustomer = function(objid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                $.ajax({
                    url: API_URL + '/customers/' + objid,
                    type: 'DELETE',
                    success: function(res) {
                        Swal.close();
                        if(res.status === 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Error!', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        console.error('Error deleting customer:', xhr);
                        Swal.fire('Error!', 'Server communication error: ' + (xhr.statusText || 'Unknown error'), 'error');
                    }
                });
            }
        });
    }

    // ------------------------------------------------------------------
    // FORM SUBMISSION (Add / Update Logic)
    // ------------------------------------------------------------------
    $('#customerForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        var customer_code = $('#cust_code').val().trim();
        var fullname = $('#cust_fullname').val().trim();
        var email = $('#cust_email').val().trim();
        var phone = $('#cust_phone').val().trim();
        var address = $('#cust_address').val().trim();
        
        if(!customer_code || !fullname || !email || !phone || !address) {
            Swal.fire('Error!', 'All fields are required.', 'error');
            return;
        }
        
        // Validate email format
        var emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
        if(!emailRegex.test(email)) {
            Swal.fire('Error!', 'Please enter a valid email address.', 'error');
            return;
        }
        
        // Validate phone (basic validation - at least 7 digits)
        var phoneDigits = phone.replace(/[^0-9]/g, '');
        if(phoneDigits.length < 7) {
            Swal.fire('Error!', 'Please enter a valid phone number (at least 7 digits).', 'error');
            return;
        }
        
        var payload = {
            customer_code: customer_code,
            fullname: fullname,
            email: email,
            phone: phone,
            address: address
        };
        
        var isUpdate = $('#cust_objid').val() !== '';
        var url = API_URL + '/customers';
        var method = 'POST';

        if (isUpdate) {
            payload.objid = $('#cust_objid').val();
            method = 'PUT';
            url = API_URL + '/customers/' + payload.objid;
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
                    $('#customerModal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Error!', res.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.close();
                console.error('Form submission error:', xhr);
                var errorMsg = 'Server communication error.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error!', errorMsg, 'error');
            }
        });
    });
    
    // Clear modal when closed
    $('#customerModal').on('hidden.bs.modal', function () {
        $('#customerForm')[0].reset();
        $('#cust_objid').val('');
    });
});
</script>
</body>
</html>