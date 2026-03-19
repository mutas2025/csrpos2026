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
                        <h1 class="m-0">Customer Management</h1>
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
                                        <tr>
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
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="customerForm">
                    <div class="modal-body">
                        <input type="hidden" name="objid" id="cust_objid">
                        <div class="form-group">
                            <label>Customer Code</label>
                            <input type="text" name="customer_code" id="cust_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" id="cust_fullname" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="cust_email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" id="cust_phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" id="cust_address" class="form-control" required></textarea>
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
        table = $('#customers-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": API_URL + "/customers",
                "type": "GET",
                "dataSrc": function(json) {
                    console.log("API Response:", json);
                    if (json.status === 'success') {
                        return json.data;
                    } else {
                        console.error("API returned error:", json.message);
                        Swal.fire("Backend Error", json.message, "error"); 
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
                { "data": "address" },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="updateCustomer('${row.objid}')"><i class="fas fa-edit mr-2"></i>Update</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteCustomer('${row.objid}')"><i class="fas fa-trash mr-2"></i>Delete</a>
                            </div>
                        </div>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        }).buttons().container().appendTo('#customers-table_wrapper .col-md-6:eq(0)');
    });

    function openAddModal() {
        $('#modalTitle').text('Add New Customer');
        $('#customerForm')[0].reset();
        $('#cust_objid').val('');
        $('#customerModal').modal('show');
    }

    function updateCustomer(objid) {
        $('#modalTitle').text('Update Customer');
        var rowData = table.rows().data().toArray().find(r => r.objid == objid);
        
        if(rowData) {
            $('#cust_objid').val(rowData.objid);
            $('#cust_code').val(rowData.customer_code);
            $('#cust_fullname').val(rowData.fullname);
            $('#cust_email').val(rowData.email);
            $('#cust_phone').val(rowData.phone);
            $('#cust_address').val(rowData.address);
            $('#customerModal').modal('show');
        }
    }

    $('#customerForm').on('submit', function(e) {
        e.preventDefault();
        var payload = {};
        $.each($(this).serializeArray(), function(i, field) {
            payload[field.name] = field.value;
        });

        var isUpdate = payload.objid !== '';
        var url = API_URL + '/customers';
        var method = 'POST';

        if (isUpdate) {
            method = 'PUT';
            url = API_URL + '/customers/' + payload.objid;
        }

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('Success!', res.message, 'success');
                    $('#customerModal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Error!', res.message, 'error');
                }
            }
        });
    });

    function deleteCustomer(objid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: API_URL + '/customers/' + objid,
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