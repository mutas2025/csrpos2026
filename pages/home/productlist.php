<?php
// productlist.php
// Display page for Product Management - Split View Layout
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product Management</title>

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
        
        /* Status Badges */
        .badge-in-stock { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; white-space: nowrap; }
        .badge-low-stock { background-color: #ffc107; color: black; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; white-space: nowrap; }
        .badge-out-of-stock { background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.8rem; white-space: nowrap; }
        
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
                        <h1 class="m-0 text-dark">Product Management</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Products</li>
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
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-cube mr-2"></i><span id="formTitle">Add New Product</span></h5>
                            <button type="button" class="btn btn-sm btn-light" id="btnReset" onclick="resetForm()" title="Clear Form">
                                <i class="fas fa-eraser"></i> Clear
                            </button>
                        </div>
                        <div class="form-panel-body">
                            <form id="productForm" autocomplete="off">
                                <input type="hidden" name="objid" id="prod_objid">
                                
                                <div class="form-group">
                                    <label>Product Code <span class="text-danger">*</span></label>
                                    <input type="text" name="product_code" id="prod_code" class="form-control form-control-sm" placeholder="e.g., PRD-001" required>
                                </div>

                                <div class="form-group">
                                    <label>Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" id="prod_name" class="form-control form-control-sm" placeholder="Enter product name" required>
                                </div>

                                <div class="form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select name="category" id="prod_category" class="form-control form-control-sm" required>
                                        <option value="">Select Category</option>
                                        <option value="Electronics">Electronics</option>
                                        <option value="Clothing">Clothing</option>
                                        <option value="Food & Beverage">Food & Beverage</option>
                                        <option value="Furniture">Furniture</option>
                                        <option value="Office Supplies">Office Supplies</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Price <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-sm">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" step="0.01" name="price" id="prod_price" class="form-control" placeholder="0.00" required min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>Stock <span class="text-danger">*</span></label>
                                            <input type="number" name="stock" id="prod_stock" class="form-control form-control-sm" placeholder="0" required min="0">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 d-flex">
                                    <button type="submit" class="btn btn-primary btn-block" id="btnSubmit">
                                        <i class="fas fa-save mr-1"></i> Save Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- RIGHT PANEL: DATA TABLE -->
                    <div class="table-panel">
                        <div class="table-panel-header">
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-list mr-2"></i>Product List</h5>
                            <span class="badge badge-light" id="totalCount">0 Items</span>
                        </div>
                        <div class="table-panel-body">
                            <table id="products-table" class="table table-bordered table-hover mb-0" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Status</th>
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
    var productsData = []; // Store products data globally
    var table;

    // Helper function to get product data by ID
    function getProductDataById(objid) {
        return productsData.find(product => product.objid == objid);
    }

    // Helper function to get stock status badge
    function getStockStatusBadge(stock) {
        if(stock <= 0) {
            return '<span class="badge-out-of-stock"><i class="fas fa-times-circle"></i> Out</span>';
        } else if(stock < 10) {
            return '<span class="badge-low-stock"><i class="fas fa-exclamation-triangle"></i> Low</span>';
        } else {
            return '<span class="badge-in-stock"><i class="fas fa-check-circle"></i> In Stock</span>';
        }
    }

    // Initialize DataTable
    function initializeDataTable() {
        table = $('#products-table').DataTable({
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
                "searchPlaceholder": "Search products...",
            },
            "ajax": {
                "url": API_URL + "/products",
                "type": "GET",
                "dataSrc": function(json) {
                    if (json.status === 'success') {
                        productsData = json.data; // Store data globally
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
                { "data": "product_code", "className": "align-middle" },
                { "data": "product_name", "className": "align-middle" },
                { "data": "category", "className": "align-middle" },
                { 
                    "data": "price",
                    "className": "align-middle text-right",
                    "render": function(data, type, row) {
                        if(type === 'display') {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                        return data;
                    }
                },
                { 
                    "data": "stock",
                    "className": "align-middle text-center",
                    "render": function(data, type, row) {
                        return '<strong>' + data + '</strong>';
                    }
                },
                { 
                    "data": "stock",
                    "className": "align-middle text-center",
                    "render": function(data, type, row) {
                        if(type === 'display') {
                            return getStockStatusBadge(data);
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
                            <button type="button" class="btn btn-warning btn-action" onclick="updateProduct('${row.objid}')" title="Edit Product">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-action" onclick="deleteProduct('${row.objid}')" title="Delete Product">
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
        $('#totalCount').text(count + ' Items');
    }

    // Initialize DataTable
    initializeDataTable();

    // ------------------------------------------------------------------
    // RESET FORM
    // ------------------------------------------------------------------
    window.resetForm = function() {
        $('#productForm')[0].reset();
        $('#prod_objid').val('');
        $('#formTitle').text('Add New Product');
        $('#btnSubmit').html('<i class="fas fa-save mr-1"></i> Save Product');
        $('#btnSubmit').removeClass('btn-warning').addClass('btn-primary');
    }

    // ------------------------------------------------------------------
    // UPDATE PRODUCT (Populate Form)
    // ------------------------------------------------------------------
    window.updateProduct = function(objid) {
        var productData = getProductDataById(objid);
        
        if(productData) {
            $('#formTitle').text('Update Product');
            $('#btnSubmit').html('<i class="fas fa-check mr-1"></i> Update Product');
            $('#btnSubmit').removeClass('btn-primary').addClass('btn-warning');
            
            // Populate the Form fields
            $('#prod_objid').val(productData.objid);
            $('#prod_code').val(productData.product_code);
            $('#prod_name').val(productData.product_name);
            $('#prod_category').val(productData.category);
            $('#prod_price').val(productData.price);
            $('#prod_stock').val(productData.stock);
            
            // Scroll to top of form on mobile
            $('.form-panel').scrollTop(0);
        } else {
            Swal.fire("Error", "Could not retrieve product data.", "error");
        }
    }

    // ------------------------------------------------------------------
    // DELETE PRODUCT
    // ------------------------------------------------------------------
    window.deleteProduct = function(objid) {
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
                    url: API_URL + '/products/' + objid,
                    type: 'DELETE',
                    success: function(res) {
                        Swal.close();
                        if(res.status === 'success') {
                            Swal.fire('Deleted!', res.message, 'success');
                            // If the deleted product was being edited, reset the form
                            if($('#prod_objid').val() == objid) {
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
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        var product_code = $('#prod_code').val().trim();
        var product_name = $('#prod_name').val().trim();
        var category = $('#prod_category').val();
        var price = $('#prod_price').val();
        var stock = $('#prod_stock').val();
        
        if(!product_code || !product_name || !category || price === '' || stock === '') {
            Swal.fire('Validation Error', 'All fields are required.', 'error');
            return;
        }
        
        if(parseFloat(price) < 0) {
            Swal.fire('Validation Error', 'Price cannot be negative.', 'error');
            return;
        }
        
        if(parseInt(stock) < 0) {
            Swal.fire('Validation Error', 'Stock cannot be negative.', 'error');
            return;
        }
        
        var payload = {
            product_code: product_code,
            product_name: product_name,
            category: category,
            price: parseFloat(price),
            stock: parseInt(stock)
        };
        
        var isUpdate = $('#prod_objid').val() !== '';
        var url = API_URL + '/products';
        var method = 'POST';

        if (isUpdate) {
            payload.objid = $('#prod_objid').val();
            method = 'PUT';
            url = API_URL + '/products/' + payload.objid;
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
    
    // Handle category input - allow custom category if needed
    $('#prod_category').on('change', function() {
        if($(this).val() === 'Other') {
            Swal.fire({
                title: 'Enter Category',
                input: 'text',
                inputLabel: 'Please specify the category',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) return 'You need to specify a category!';
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    var newCategory = result.value;
                    $('#prod_category').append(new Option(newCategory, newCategory));
                    $('#prod_category').val(newCategory);
                } else {
                    $('#prod_category').val('');
                }
            });
        }
    });
});
</script>
</body>
</html>