<?php
// productlist.php
// Display page for Product Management
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management</title>

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
                        <h1 class="m-0">Product Management</h1>
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
                                <h3 class="card-title">List of Products</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="openAddModal()">
                                        <i class="fas fa-plus"></i> Add Product
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="products-table" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
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

    <!-- Modal for Add/Edit Product -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="productForm">
                    <div class="modal-body">
                        <input type="hidden" name="objid" id="prod_objid">
                        <div class="form-group">
                            <label>Product Code</label>
                            <input type="text" name="product_code" id="prod_code" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" id="prod_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" id="prod_category" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" id="prod_price" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" id="prod_stock" class="form-control" required>
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
        table = $('#products-table').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": API_URL + "/products",
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
                { "data": "product_code" },
                { "data": "product_name" },
                { "data": "category" },
                { 
                    "data": "price",
                    "render": function(data, type, row) {
                        return parseFloat(data).toFixed(2);
                    }
                },
                { "data": "stock" },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#" onclick="updateProduct('${row.objid}')"><i class="fas fa-edit mr-2"></i>Update</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="#" onclick="deleteProduct('${row.objid}')"><i class="fas fa-trash mr-2"></i>Delete</a>
                            </div>
                        </div>`;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ]
        }).buttons().container().appendTo('#products-table_wrapper .col-md-6:eq(0)');
    });

    function openAddModal() {
        $('#modalTitle').text('Add New Product');
        $('#productForm')[0].reset();
        $('#prod_objid').val('');
        $('#productModal').modal('show');
    }

    function updateProduct(objid) {
        $('#modalTitle').text('Update Product');
        var rowData = table.rows().data().toArray().find(r => r.objid == objid);
        
        if(rowData) {
            $('#prod_objid').val(rowData.objid);
            $('#prod_code').val(rowData.product_code);
            $('#prod_name').val(rowData.product_name);
            $('#prod_category').val(rowData.category);
            $('#prod_price').val(rowData.price);
            $('#prod_stock').val(rowData.stock);
            $('#productModal').modal('show');
        }
    }

    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        var payload = {};
        $.each($(this).serializeArray(), function(i, field) {
            payload[field.name] = field.value;
        });

        var isUpdate = payload.objid !== '';
        var url = API_URL + '/products';
        var method = 'POST';

        if (isUpdate) {
            method = 'PUT';
            url = API_URL + '/products/' + payload.objid;
        }

        $.ajax({
            url: url,
            type: method,
            contentType: 'application/json',
            data: JSON.stringify(payload),
            success: function(res) {
                if(res.status === 'success') {
                    Swal.fire('Success!', res.message, 'success');
                    $('#productModal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire('Error!', res.message, 'error');
                }
            }
        });
    });

    function deleteProduct(objid) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: API_URL + '/products/' + objid,
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