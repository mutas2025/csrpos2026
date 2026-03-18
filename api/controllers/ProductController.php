<?php
// ProductController.php
// This class is responsible for handling product-related requests that
// arrive via the API. It performs validation, checks for duplicates, and
// inserts new product records into the database.

// We need access to the database connection, which is created in db_connect.php.
// `dirname(__DIR__)` moves up one directory (from controllers to api) to locate
// the config folder regardless of the current working directory.
require_once dirname(__DIR__) . '/config/db_connect.php';

class ProductController {
    // store the PDO database connection here so other methods can use it
    private $con;

    public function __construct() {
        // the global $con variable is defined in db_connect.php; capture it
        global $con;
        $this->con = $con;
    }

    /**
     * Create a new product.
     *
     * This method expects an associative array `$data` containing the product
     * fields sent by the client. It returns an array describing the result
     * (status and message), which the router will encode as JSON.
     *
     * @param array $data Associative array containing product fields.
     * @return array Response array with status and message.
     */
    public function createProduct(array $data) {
        // ------------------------------------------------------------------
        // 1. Extract and sanitise input values.
        $product_code   = trim($data['product_code'] ?? '');
        $product_name   = trim($data['product_name'] ?? '');
        $category       = trim($data['category'] ?? '');
        $price          = trim($data['price'] ?? '');
        $stock          = trim($data['stock'] ?? '');

        // ------------------------------------------------------------------
        // 2. Basic validation: make sure none of the required fields are empty.
        if (empty($product_code) || empty($product_name) || empty($category) || empty($price) || empty($stock)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        // Validate numeric fields
        if (!is_numeric($price) || !is_numeric($stock)) {
            return ['status' => 'error', 'message' => 'Price and Stock must be numeric values.'];
        }

        // ------------------------------------------------------------------
        // 3. Ensure product_code is unique in the database.
        $stmt = $this->con->prepare("SELECT COUNT(*) FROM tbl_products WHERE product_code = ?");
        $stmt->execute([$product_code]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => 'Product code already exists.'];
        }

        // ------------------------------------------------------------------
        // 4. Insert the new product record.
        $sql = "INSERT INTO tbl_products
                   (product_code, product_name, category, price, stock, date_created)
                VALUES
                   (?, ?, ?, ?, ?, NOW())";
        try {
            $ins = $this->con->prepare($sql);
            $ins->execute([$product_code, $product_name, $category, $price, $stock]);

            return ['status' => 'success', 'message' => 'Product added successfully.'];
        } catch (PDOException $e) {
            // In production you would log this error instead of exposing it.
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Retrieve all products.
     *
     * Fetches all product records from the database.
     *
     * @return array Response array with status and data.
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
}
?>