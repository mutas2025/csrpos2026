<?php
// ProductController.php
// This class is responsible for handling product-related requests.

require_once dirname(__DIR__) . '/config/db_connect.php';

class ProductController {
    // store the PDO database connection
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * Create a new product.
     */
    public function createProduct(array $data) {
        // 1. Extract and sanitise input
        $product_code   = trim($data['product_code'] ?? '');
        $product_name   = trim($data['product_name'] ?? '');
        $category       = trim($data['category'] ?? '');
        $price          = trim($data['price'] ?? '');
        $stock          = trim($data['stock'] ?? '');

        // 2. Validation
        if (empty($product_code) || empty($product_name) || empty($category) || empty($price) || empty($stock)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        if (!is_numeric($price) || !is_numeric($stock)) {
            return ['status' => 'error', 'message' => 'Price and Stock must be numeric values.'];
        }

        // 3. Check for duplicate product code
        $stmt = $this->con->prepare("SELECT COUNT(*) FROM tbl_products WHERE product_code = ?");
        $stmt->execute([$product_code]);
        if ($stmt->fetchColumn() > 0) {
            return ['status' => 'error', 'message' => 'Product code already exists.'];
        }

        // 4. Insert
        $sql = "INSERT INTO tbl_products
                   (product_code, product_name, category, price, stock, date_created)
                VALUES
                   (?, ?, ?, ?, ?, NOW())";
        try {
            $ins = $this->con->prepare($sql);
            $ins->execute([$product_code, $product_name, $category, $price, $stock]);
            return ['status' => 'success', 'message' => 'Product added successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Retrieve all products.
     */
    public function getProducts() {
        try {
            $stmt = $this->con->prepare("SELECT objid, product_code, product_name, category, price, stock, date_created FROM tbl_products");
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $products];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update an existing product.
     */
    public function updateProduct(array $data) {
        // 1. Extract ID and data
        $objid = $data['objid'] ?? '';
        $product_code = trim($data['product_code'] ?? '');
        $product_name = trim($data['product_name'] ?? '');
        $category = trim($data['category'] ?? '');
        $price = trim($data['price'] ?? '');
        $stock = trim($data['stock'] ?? '');

        if (empty($objid)) {
            return ['status' => 'error', 'message' => 'Product ID is required.'];
        }

        if (empty($product_code) || empty($product_name) || empty($category) || empty($price) || empty($stock)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        try {
            $sql = "UPDATE tbl_products SET 
                        product_code = ?, 
                        product_name = ?, 
                        category = ?, 
                        price = ?, 
                        stock = ? 
                    WHERE objid = ?";
            
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$product_code, $product_name, $category, $price, $stock, $objid]);

            return ['status' => 'success', 'message' => 'Product updated successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a product by ID.
     */
    public function deleteProduct($id) {
        if (empty($id)) {
            return ['status' => 'error', 'message' => 'Product ID is required.'];
        }

        try {
            $sql = "DELETE FROM tbl_products WHERE objid = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Product deleted successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Product not found.'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>