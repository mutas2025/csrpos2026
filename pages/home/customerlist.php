<?php
// customerlist.php
// Display page for Customer Management - Split View Layout
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer Management</title>

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
            flex: 0 0 380px;
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
            margin-bottom: 15px;
        }
        
        .form-group label {
            font-weight: 600;
            font-size: 0.875rem;
            margin-bottom: 5px;
            color: #495057;
        }
        
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        /* DataTable Specifics */
        table.dataTable thead th { background-color: #f8f9fa; color: #343a40; border-bottom: 2px solid #dee2e6; font-size: 0.875rem; }
        table.dataTable tbody td { font-size: 0.875rem; vertical-align: middle; }
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
                        <h1 class="m-0 text-dark">Customer Management</h1>
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
                <div class="split-container">
                    
                    <!-- LEFT PANEL: INLINE FORM -->
                    <div class="form-panel">
                        <div class="form-panel-header">
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-users mr-2"></i><span id="formTitle">Add New Customer</span></h5>
                            <button type="button" class="btn btn-sm btn-light" id="btnReset" onclick="resetForm()" title="Clear Form">
                                <i class="fas fa-eraser"></i> Clear
                            </button>
                        </div>
                        <div class="form-panel-body">
                            <form id="customerForm" autocomplete="off">
                                <input type="hidden" name="objid" id="cust_objid">
                                
                                <div class="form-group">
                                    <label>Customer Code <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_code" id="cust_code" class="form-control form-control-sm" placeholder="e.g., CUST-001" required>
                                </div>

                                <div class="form-group">
                                    <label>Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="fullname" id="cust_fullname" class="form-control form-control-sm" placeholder="Enter full name" required>
                                </div>

                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="cust_email" class="form-control form-control-sm" placeholder="name@example.com" required>
                                </div>

                                <div class="form-group">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" id="cust_phone" class="form-control form-control-sm" placeholder="e.g., 0912-345-6789" required>
                                </div>

                                <div class="form-group">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <textarea name="address" id="cust_address" class="form-control form-control-sm" rows="4" placeholder="Enter complete address..." required></textarea>
                                </div>

                                <div class="mt-4 d-flex">
                                    <button type="submit" class="btn btn-primary btn-block" id="btnSubmit">
                                        <i class="fas fa-save mr-1"></i> Save Customer
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIGHT PANEL: DATA TABLE -->
                    <div class="table-panel">
                        <div class="table-panel-header">
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Customer List</h5>
                            <span class="badge badge-light" id="totalCount">0 Items</span>
                        </div>
                        <div class="table-panel-body">
                            <table id="customers-table" class="table table-bordered table-hover mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th class="text-center" style="width: 100px;">Actions</th>
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
    var customersData = []; // Store customers data globally
    var table;

    // Helper function to get customer data by ID
    function getCustomerDataById(objid) {
        return customersData.find(customer => customer.objid == objid);
    }

    // Initialize DataTable
    function initializeDataTable() {
        table = $('#customers-table').DataTable({
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
                "searchPlaceholder": "Search customers...",
            },
            "ajax": {
                "url": API_URL + "/customers",
                "type": "GET",
                "dataSrc": function(json) {
                    if (json.status === 'success') {
                        customersData = json.data; // Store data globally
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
                { "data": "customer_code", "className": "align-middle" },
                { "data": "fullname", "className": "align-middle" },
                { "data": "email", "className": "align-middle" },
                { "data": "phone", "className": "align-middle" },
                { 
                    "data": "address",
                    "className": "align-middle",
                    "render": function(data, type, row) {
                        if(type === 'display') {
                            if(data && data.length > 30) {
                                return '<span title="' + data + '">' + data.substring(0, 27) + '...</span>';
                            }
                            return data || 'N/A';
                        }
                        return data;
                    }
                },
                { 
                    "data": null,
                    "className": "align-middle text-center",
                    "render": function(data, type, row) {
                        return `
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-warning btn-action" onclick="updateCustomer('${row.objid}')" title="Edit Customer">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-action" onclick="deleteCustomer('${row.objid}')" title="Delete Customer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        });
    }

    function updateTotalCount(count) {
        $('#totalCount').text(count + ' Customers');
    }

    // Initialize DataTable
    initializeDataTable();

    // ------------------------------------------------------------------
    // RESET FORM
    // ------------------------------------------------------------------
    window.resetForm = function() {
        $('#customerForm')[0].reset();
        $('#cust_objid').val('');
        $('#formTitle').text('Add New Customer');
        $('#btnSubmit').html('<i class="fas fa-save mr-1"></i> Save Customer');
        $('#btnSubmit').removeClass('btn-warning').addClass('btn-primary');
    }

    // ------------------------------------------------------------------
    // UPDATE CUSTOMER (Populate Form)
    // ------------------------------------------------------------------
    window.updateCustomer = function(objid) {
        var customerData = getCustomerDataById(objid);
        
        if(customerData) {
            $('#formTitle').text('Update Customer');
            $('#btnSubmit').html('<i class="fas fa-check mr-1"></i> Update Customer');
            $('#btnSubmit').removeClass('btn-primary').addClass('btn-warning');
            
            // Populate the Form fields
            $('#cust_objid').val(customerData.objid);
            $('#cust_code').val(customerData.customer_code);
            $('#cust_fullname').val(customerData.fullname);
            $('#cust_email').val(customerData.email);
            $('#cust_phone').val(customerData.phone);
            $('#cust_address').val(customerData.address);
            
            // Scroll to top of form on mobile
            $('.form-panel').scrollTop(0);
        } else {
            Swal.fire("Error", "Could not retrieve customer data.", "error");
        }
    }

    // ------------------------------------------------------------------
    // DELETE CUSTOMER
    // ------------------------------------------------------------------
    window.deleteCustomer = function(objid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                
                $.ajax({
                    url: API_URL + '/customers/' + objid,
                    type: 'DELETE',
                    success: function(res) {
                        Swal.close();
                        if(res.status === 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            // If the deleted customer was being edited, reset the form
                            if($('#cust_objid').val() == objid) {
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
                console.error('Form submission error:', xhr);
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