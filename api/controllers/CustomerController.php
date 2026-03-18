<?php
// CustomerController.php
require_once dirname(__DIR__) . '/config/db_connect.php';

class CustomerController {
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    public function createCustomer(array $data) {
        $customer_code  = trim($data['customer_code'] ?? '');
        $fullname       = trim($data['fullname'] ?? '');
        $email          = trim($data['email'] ?? '');
        $phone          = trim($data['phone'] ?? '');
        $address        = trim($data['address'] ?? '');

        if (empty($customer_code) || empty($fullname) || empty($email) || empty($phone) || empty($address)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email format.'];
        }

        $stmt = $this->con->prepare("SELECT COUNT(*) FROM tbl_customers WHERE customer_code = ? OR email = ?");
        $stmt->execute([$customer_code, $email]);
        if ($stmt->fetchColumn() > 0) {
            return ['status' => 'error', 'message' => 'Customer code or email already exists.'];
        }

        $sql = "INSERT INTO tbl_customers (customer_code, fullname, email, phone, address, date_created) VALUES (?, ?, ?, ?, ?, NOW())";
        try {
            $ins = $this->con->prepare($sql);
            $ins->execute([$customer_code, $fullname, $email, $phone, $address]);
            return ['status' => 'success', 'message' => 'Customer added successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

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

    public function updateCustomer(array $data) {
        try {
            $sql = "UPDATE tbl_customers SET customer_code=?, fullname=?, email=?, phone=?, address=? WHERE objid=?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$data['customer_code'], $data['fullname'], $data['email'], $data['phone'], $data['address'], $data['objid']]);
            return ['status' => 'success', 'message' => 'Customer updated successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function deleteCustomer($id) {
        try {
            $stmt = $this->con->prepare("DELETE FROM tbl_customers WHERE objid = ?");
            $stmt->execute([$id]);
            return ['status' => 'success', 'message' => 'Customer deleted successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}