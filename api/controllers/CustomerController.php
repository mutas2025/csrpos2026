<?php
// CustomerController.php
// This class is responsible for handling customer-related requests that
// arrive via the API. It performs validation, checks for duplicates, and
// inserts new customer records into the database.

// We need access to the database connection, which is created in db_connect.php.
// `dirname(__DIR__)` moves up one directory (from controllers to api) to locate
// the config folder regardless of the current working directory.
require_once dirname(__DIR__) . '/config/db_connect.php';

class CustomerController {
    // store the PDO database connection here so other methods can use it
    private $con;

    public function __construct() {
        // the global $con variable is defined in db_connect.php; capture it
        global $con;
        $this->con = $con;
    }

    /**
     * Create a new customer.
     *
     * This method expects an associative array `$data` containing the customer
     * fields sent by the client. It returns an array describing the result
     * (status and message), which the router will encode as JSON.
     *
     * @param array $data Associative array containing customer fields.
     * @return array Response array with status and message.
     */
    public function createCustomer(array $data) {
        // ------------------------------------------------------------------
        // 1. Extract and sanitise input values.
        $customer_code  = trim($data['customer_code'] ?? '');
        $fullname       = trim($data['fullname'] ?? '');
        $email          = trim($data['email'] ?? '');
        $phone          = trim($data['phone'] ?? '');
        $address        = trim($data['address'] ?? '');

        // ------------------------------------------------------------------
        // 2. Basic validation: make sure none of the required fields are empty.
        if (empty($customer_code) || empty($fullname) || empty($email) || empty($phone) || empty($address)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email format.'];
        }

        // ------------------------------------------------------------------
        // 3. Ensure customer_code and email are unique in the database.
        $stmt = $this->con->prepare("SELECT COUNT(*) FROM tbl_customers WHERE customer_code = ? OR email = ?");
        $stmt->execute([$customer_code, $email]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => 'Customer code or email already exists.'];
        }

        // ------------------------------------------------------------------
        // 4. Insert the new customer record.
        $sql = "INSERT INTO tbl_customers
                   (customer_code, fullname, email, phone, address, date_created)
                VALUES
                   (?, ?, ?, ?, ?, NOW())";
        try {
            $ins = $this->con->prepare($sql);
            $ins->execute([$customer_code, $fullname, $email, $phone, $address]);

            return ['status' => 'success', 'message' => 'Customer added successfully.'];
        } catch (PDOException $e) {
            // In production you would log this error instead of exposing it.
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Retrieve all customers.
     *
     * Fetches all customer records from the database.
     *
     * @return array Response array with status and data.
     */
    public function getCustomers() {
        try {
            $stmt = $this->con->prepare("SELECT objid, customer_code, fullname, email, phone, address, date_created FROM tbl_customers");
            $stmt->execute();
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $customers];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>