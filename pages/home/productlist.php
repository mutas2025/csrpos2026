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
        
        /* Status Badges */
        .badge-in-stock { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.85rem; }
        .badge-low-stock { background-color: #ffc107; color: black; padding: 5px 10px; border-radius: 3px; font-size: 0.85rem; }
        .badge-out-of-stock { background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 3px; font-size: 0.85rem; }
        
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
        
        .product-image-placeholder {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .product-image-placeholder i {
            font-size: 80px;
            color: #6c757d;
        }
        
        .price-tag {
            font-size: 20px;
            font-weight: bold;
            color: #28a745;
        }
        
        .stock-indicator {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
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
                        <h1 class="m-0">Product Management</h1>
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
                                        32
                                            <th>Code</th>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
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
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="productForm">
                    <div class="modal-body">
                        <input type="hidden" name="objid" id="prod_objid">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Code <span class="text-danger">*</span></label>
                                    <input type="text" name="product_code" id="prod_code" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Product Name <span class="text-danger">*</span></label>
                                    <input type="text" name="product_name" id="prod_name" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Category <span class="text-danger">*</span></label>
                                    <select name="category" id="prod_category" class="form-control" required>
                                        <option value="">Select Category</option>
                                        <option value="Electronics">Electronics</option>
                                        <option value="Clothing">Clothing</option>
                                        <option value="Food & Beverage">Food & Beverage</option>
                                        <option value="Furniture">Furniture</option>
                                        <option value="Office Supplies">Office Supplies</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="price" id="prod_price" class="form-control" required min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Stock Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="stock" id="prod_stock" class="form-control" required min="0">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" id="prod_description" class="form-control" rows="3" placeholder="Enter product description (optional)..."></textarea>
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

    <!-- Modal for View Product -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title text-white">Product Details</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="product-image-placeholder">
                                <i class="fas fa-box-open"></i>
                                <h5 id="v_product_name" class="mt-2">-</h5>
                                <p class="text-muted" id="v_product_code">-</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless view-table">
                                <tr>
                                    <th>Product Code:</th>
                                    <td id="v_code">-</td>
                                </tr>
                                <tr>
                                    <th>Product Name:</th>
                                    <td id="v_name">-</td>
                                </tr>
                                <tr>
                                    <th>Category:</th>
                                    <td id="v_category">-</td>
                                </tr>
                                <tr>
                                    <th>Price:</th>
                                    <td id="v_price" class="price-tag">-</td>
                                </tr>
                                <tr>
                                    <th>Stock Quantity:</th>
                                    <td id="v_stock">-</td>
                                </tr>
                                <tr>
                                    <th>Stock Status:</th>
                                    <td id="v_stock_status">-</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td id="v_description">-</td>
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
                    <button type="button" class="btn btn-primary" onclick="editFromView()">Edit Product</button>
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
    var productsData = []; // Store products data globally
    var currentViewProductId = null; // Store current viewed product ID
    var table;

    // Helper function to get product data by ID
    function getProductDataById(objid) {
        return productsData.find(product => product.objid == objid);
    }

    // Helper function to get stock status badge
    function getStockStatusBadge(stock) {
        if(stock <= 0) {
            return '<span class="badge-out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock</span>';
        } else if(stock < 10) {
            return '<span class="badge-low-stock"><i class="fas fa-exclamation-triangle"></i> Low Stock (' + stock + ')</span>';
        } else {
            return '<span class="badge-in-stock"><i class="fas fa-check-circle"></i> In Stock</span>';
        }
    }

    // Initialize DataTable
    function initializeDataTable() {
        table = $('#products-table').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "pageLength": 10,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            "ajax": {
                "url": API_URL + "/products",
                "type": "GET",
                "dataSrc": function(json) {
                    console.log("API Response:", json);
                    if (json.status === 'success') {
                        productsData = json.data; // Store data globally
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
                { "data": "product_code" },
                { "data": "product_name" },
                { "data": "category" },
                { 
                    "data": "price",
                    "render": function(data, type, row) {
                        if(type === 'display') {
                            return '₱' + parseFloat(data).toFixed(2);
                        }
                        return data;
                    }
                },
                { "data": "stock" },
                { 
                    "data": "stock",
                    "render": function(data, type, row) {
                        if(type === 'display') {
                            return getStockStatusBadge(data);
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
                                <a class="dropdown-item" href="javascript:void(0)" onclick="viewProduct('${row.objid}')">
                                    <i class="fas fa-eye mr-2 text-info"></i>View
                                </a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="updateProduct('${row.objid}')">
                                    <i class="fas fa-edit mr-2 text-primary"></i>Update
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteProduct('${row.objid}')">
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
        table.buttons().container().appendTo('#products-table_wrapper .col-md-6:eq(0)');
    }

    // Initialize DataTable
    initializeDataTable();

    // ------------------------------------------------------------------
    // 1. ADD PRODUCT
    // ------------------------------------------------------------------
    window.openAddModal = function() {
        $('#modalTitle').text('Add New Product');
        $('#productForm')[0].reset();
        $('#prod_objid').val('');
        $('#productModal').modal({
            backdrop: 'static',
            keyboard: false
        });
        $('#productModal').modal('show');
    }

    // ------------------------------------------------------------------
    // 2. VIEW PRODUCT
    // ------------------------------------------------------------------
    window.viewProduct = function(objid) {
        console.log('Viewing product:', objid);
        var productData = getProductDataById(objid);
        currentViewProductId = objid;
        
        if(productData) {
            // Populate the View Modal fields
            $('#v_code').text(productData.product_code || 'N/A');
            $('#v_product_code').text(productData.product_code || 'N/A');
            $('#v_name').text(productData.product_name || 'N/A');
            $('#v_product_name').text(productData.product_name || 'N/A');
            $('#v_category').text(productData.category || 'N/A');
            $('#v_price').html('₱' + (parseFloat(productData.price) || 0).toFixed(2));
            $('#v_stock').text(productData.stock || 0);
            $('#v_stock_status').html(getStockStatusBadge(productData.stock || 0));
            $('#v_description').text(productData.description || 'No description provided.');
            $('#v_created_at').text(productData.created_at || 'N/A');
            $('#v_updated_at').text(productData.updated_at || 'N/A');
            
            // Show the modal
            $('#viewModal').modal('show');
        } else {
            console.error('Product not found:', objid);
            Swal.fire("Error", "Could not retrieve product data. Product ID: " + objid, "error");
        }
    }

    // ------------------------------------------------------------------
    // 3. UPDATE PRODUCT
    // ------------------------------------------------------------------
    window.updateProduct = function(objid) {
        console.log('Updating product:', objid);
        var productData = getProductDataById(objid);
        
        if(productData) {
            $('#modalTitle').text('Update Product');
            
            // Populate the Form fields
            $('#prod_objid').val(productData.objid);
            $('#prod_code').val(productData.product_code);
            $('#prod_name').val(productData.product_name);
            $('#prod_category').val(productData.category);
            $('#prod_price').val(productData.price);
            $('#prod_stock').val(productData.stock);
            $('#prod_description').val(productData.description || '');
            
            // Show the modal
            $('#productModal').modal({
                backdrop: 'static',
                keyboard: false
            });
            $('#productModal').modal('show');
        } else {
            console.error('Product not found:', objid);
            Swal.fire("Error", "Could not retrieve product data for update. Product ID: " + objid, "error");
        }
    }

    // ------------------------------------------------------------------
    // 4. EDIT FROM VIEW
    // ------------------------------------------------------------------
    window.editFromView = function() {
        if(currentViewProductId) {
            $('#viewModal').modal('hide');
            updateProduct(currentViewProductId);
        }
    }

    // ------------------------------------------------------------------
    // 5. DELETE PRODUCT
    // ------------------------------------------------------------------
    window.deleteProduct = function(objid) {
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
                    url: API_URL + '/products/' + objid,
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
                        console.error('Error deleting product:', xhr);
                        Swal.fire('Error!', 'Server communication error: ' + (xhr.statusText || 'Unknown error'), 'error');
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
        
        // Validate required fields
        var product_code = $('#prod_code').val().trim();
        var product_name = $('#prod_name').val().trim();
        var category = $('#prod_category').val();
        var price = $('#prod_price').val();
        var stock = $('#prod_stock').val();
        
        if(!product_code || !product_name || !category || !price || !stock) {
            Swal.fire('Error!', 'All fields are required.', 'error');
            return;
        }
        
        if(parseFloat(price) < 0) {
            Swal.fire('Error!', 'Price cannot be negative.', 'error');
            return;
        }
        
        if(parseInt(stock) < 0) {
            Swal.fire('Error!', 'Stock cannot be negative.', 'error');
            return;
        }
        
        var payload = {
            product_code: product_code,
            product_name: product_name,
            category: category,
            price: parseFloat(price),
            stock: parseInt(stock),
            description: $('#prod_description').val().trim()
        };
        
        var isUpdate = $('#prod_objid').val() !== '';
        var url = API_URL + '/products';
        var method = 'POST';

        if (isUpdate) {
            payload.objid = $('#prod_objid').val();
            method = 'PUT';
            url = API_URL + '/products/' + payload.objid;
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
                    $('#productModal').modal('hide');
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
    $('#productModal').on('hidden.bs.modal', function () {
        $('#productForm')[0].reset();
        $('#prod_objid').val('');
    });
    
    // Handle category input - allow custom category if needed
    $('#prod_category').on('change', function() {
        if($(this).val() === 'Other') {
            // You can add a prompt to enter custom category
            Swal.fire({
                title: 'Enter Category',
                input: 'text',
                inputLabel: 'Please specify the category',
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'You need to specify a category!';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed && result.value) {
                    // Add new option and select it
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