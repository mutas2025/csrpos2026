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

            // Get the ID of the newly created customer
            $newId = $this->con->lastInsertId();
            
            return ['status' => 'success', 'message' => 'Customer added successfully.', 'objid' => $newId];
        } catch (PDOException $e) {
            // In production you would log this error instead of exposing it.
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update an existing customer.
     *
     * This method expects an associative array `$data` containing the customer
     * fields sent by the client, including the objid of the customer to update.
     * It returns an array describing the result (status and message).
     *
     * @param array $data Associative array containing customer fields and objid.
     * @return array Response array with status and message.
     */
    public function updateCustomer(array $data) {
        // ------------------------------------------------------------------
        // 1. Extract and sanitise input values.
        $objid          = isset($data['objid']) ? (int)$data['objid'] : 0;
        $customer_code  = trim($data['customer_code'] ?? '');
        $fullname       = trim($data['fullname'] ?? '');
        $email          = trim($data['email'] ?? '');
        $phone          = trim($data['phone'] ?? '');
        $address        = trim($data['address'] ?? '');

        // ------------------------------------------------------------------
        // 2. Basic validation: check if objid is provided and valid
        if ($objid <= 0) {
            return ['status' => 'error', 'message' => 'Invalid customer ID.'];
        }

        // Check if required fields are empty
        if (empty($customer_code) || empty($fullname) || empty($email) || empty($phone) || empty($address)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email format.'];
        }

        // ------------------------------------------------------------------
        // 3. Check if the customer exists
        $checkStmt = $this->con->prepare("SELECT objid FROM tbl_customers WHERE objid = ?");
        $checkStmt->execute([$objid]);
        if (!$checkStmt->fetch()) {
            return ['status' => 'error', 'message' => 'Customer not found.'];
        }

        // ------------------------------------------------------------------
        // 4. Ensure customer_code and email are unique (excluding the current customer)
        $stmt = $this->con->prepare("SELECT COUNT(*) FROM tbl_customers WHERE (customer_code = ? OR email = ?) AND objid != ?");
        $stmt->execute([$customer_code, $email, $objid]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            return ['status' => 'error', 'message' => 'Customer code or email already exists for another customer.'];
        }

        // ------------------------------------------------------------------
        // 5. Update the customer record
        $sql = "UPDATE tbl_customers 
                SET customer_code = ?, 
                    fullname = ?, 
                    email = ?, 
                    phone = ?, 
                    address = ? 
                WHERE objid = ?";
        
        try {
            $update = $this->con->prepare($sql);
            $update->execute([$customer_code, $fullname, $email, $phone, $address, $objid]);

            // Check if any rows were affected
            if ($update->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'Customer updated successfully.'];
            } else {
                return ['status' => 'info', 'message' => 'No changes were made to the customer record.'];
            }
        } catch (PDOException $e) {
            // In production you would log this error instead of exposing it.
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a customer.
     *
     * Deletes a customer record from the database based on the provided objid.
     * This method performs validation and checks if the customer exists before deletion.
     *
     * @param int $objid Customer ID to delete.
     * @return array Response array with status and message.
     */
    public function deleteCustomer($objid) {
        // ------------------------------------------------------------------
        // 1. Validate the objid
        $objid = isset($objid) ? (int)$objid : 0;
        
        if ($objid <= 0) {
            return ['status' => 'error', 'message' => 'Invalid customer ID.'];
        }

        // ------------------------------------------------------------------
        // 2. Check if the customer exists
        try {
            $checkStmt = $this->con->prepare("SELECT objid, fullname FROM tbl_customers WHERE objid = ?");
            $checkStmt->execute([$objid]);
            $customer = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$customer) {
                return ['status' => 'error', 'message' => 'Customer not found.'];
            }

            // ------------------------------------------------------------------
            // 3. Check if the customer has any related records (optional)
            // Uncomment and modify based on your database relationships
            /*
            $checkOrders = $this->con->prepare("SELECT COUNT(*) FROM tbl_orders WHERE customer_id = ?");
            $checkOrders->execute([$objid]);
            $orderCount = $checkOrders->fetchColumn();
            
            if ($orderCount > 0) {
                return ['status' => 'error', 'message' => 'Cannot delete customer with existing orders. Please delete associated orders first.'];
            }
            */

            // ------------------------------------------------------------------
            // 4. Delete the customer record
            $deleteStmt = $this->con->prepare("DELETE FROM tbl_customers WHERE objid = ?");
            $deleteStmt->execute([$objid]);

            // Check if deletion was successful
            if ($deleteStmt->rowCount() > 0) {
                return [
                    'status' => 'success', 
                    'message' => 'Customer deleted successfully.',
                    'data' => [
                        'objid' => $objid,
                        'fullname' => $customer['fullname']
                    ]
                ];
            } else {
                return ['status' => 'error', 'message' => 'Failed to delete customer.'];
            }
            
        } catch (PDOException $e) {
            // Check if it's a foreign key constraint violation
            if ($e->getCode() == '23000') {
                return ['status' => 'error', 'message' => 'Cannot delete customer because they have associated records in other tables.'];
            }
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
            $stmt = $this->con->prepare("SELECT objid, customer_code, fullname, email, phone, address, date_created FROM tbl_customers ORDER BY objid DESC");
            $stmt->execute();
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $customers];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Retrieve a single customer by ID.
     *
     * Fetches a specific customer record from the database.
     *
     * @param int $objid Customer ID to retrieve.
     * @return array Response array with status and data.
     */
    public function getCustomerById($objid) {
        try {
            $stmt = $this->con->prepare("SELECT objid, customer_code, fullname, email, phone, address, date_created FROM tbl_customers WHERE objid = ?");
            $stmt->execute([$objid]);
            $customer = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($customer) {
                return ['status' => 'success', 'data' => $customer];
            } else {
                return ['status' => 'error', 'message' => 'Customer not found.'];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>