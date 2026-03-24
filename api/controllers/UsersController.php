<?php
// UsersController.php
// This class handles user management logic including registration,
// listing, updating, deleting, and status updates.

require_once dirname(__DIR__) . '/config/db_connect.php';

class UsersController {
    // Store the PDO database connection
    private $con;

    public function __construct() {
        global $con;
        $this->con = $con;
    }

    /**
     * Create a new user (Register).
     */
    public function register(array $data) {
        // Debug logging
        error_log("UsersController::register called with data: " . print_r($data, true));
        
        // 1. Extract and sanitise input
        $idno        = trim($data['idno'] ?? '');
        $fullname    = trim($data['fullname'] ?? '');
        $username    = trim($data['username'] ?? '');
        $password    = $data['password'] ?? '';
        $repassword  = $data['repassword'] ?? '';
        $email       = trim($data['email'] ?? '');
        $contactno   = trim($data['contactno'] ?? '');
        $department  = trim($data['department'] ?? '');
        $user_type   = trim($data['user_type'] ?? '');
        $status      = trim($data['status'] ?? 'APPROVED'); // Default to APPROVED for admin-created users
        $terms_agreed = isset($data['terms_agreed']) ? (int)$data['terms_agreed'] : 1; // Default to 1 for admin-created users

        // 2. Validation
        $missingFields = [];
        if (empty($idno)) $missingFields[] = 'ID No.';
        if (empty($fullname)) $missingFields[] = 'Full Name';
        if (empty($username)) $missingFields[] = 'Username';
        if (empty($password)) $missingFields[] = 'Password';
        if (empty($repassword)) $missingFields[] = 'Confirm Password';
        if (empty($email)) $missingFields[] = 'Email';
        if (empty($contactno)) $missingFields[] = 'Contact No.';
        if (empty($department)) $missingFields[] = 'Department';
        if (empty($user_type)) $missingFields[] = 'User Type';
        
        if (!empty($missingFields)) {
            return [
                'status' => 'error', 
                'message' => 'All fields are required. Missing: ' . implode(', ', $missingFields)
            ];
        }

        if ($password !== $repassword) {
            return ['status' => 'error', 'message' => 'Passwords do not match.'];
        }

        // Validate user_type against enum
        $allowed_types = ['admin', 'manager', 'cashier', 'staff'];
        if (!in_array(strtolower($user_type), $allowed_types)) {
            return ['status' => 'error', 'message' => 'Invalid user type selected. Allowed types: ' . implode(', ', $allowed_types)];
        }

        // 3. Check for duplicates
        try {
            // Check Username
            $stmt = $this->con->prepare("SELECT objid FROM tbl_users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'Username already exists.'];
            }

            // Check Email
            $stmt = $this->con->prepare("SELECT objid FROM tbl_users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'Email address is already in use.'];
            }

            // Check ID No (optional but recommended)
            $stmt = $this->con->prepare("SELECT objid FROM tbl_users WHERE idno = ?");
            $stmt->execute([$idno]);
            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'ID Number already exists.'];
            }

            // 4. Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // 5. Insert
            $sql = "INSERT INTO tbl_users
                       (idno, fullname, username, password_hash, email, contactno, department, user_type, status, terms_agreed)
                    VALUES
                       (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $ins = $this->con->prepare($sql);
            $result = $ins->execute([$idno, $fullname, $username, $hash, $email, $contactno, $department, strtolower($user_type), $status, $terms_agreed]);
            
            if ($result) {
                return ['status' => 'success', 'message' => 'User added successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to insert user into database.'];
            }

        } catch (PDOException $e) {
            error_log("Register Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get all users.
     */
    public function getUsers() {
        try {
            $stmt = $this->con->prepare("SELECT objid, idno, fullname, username, email, contactno, department, user_type, status, created_at FROM tbl_users ORDER BY objid DESC");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $users];
        } catch (PDOException $e) {
            error_log("GetUsers Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update an existing user.
     */
    public function updateUser(array $data) {
        error_log("UsersController::updateUser called with data: " . print_r($data, true));
        
        $objid = $data['objid'] ?? '';
        $idno = trim($data['idno'] ?? '');
        $fullname = trim($data['fullname'] ?? '');
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $contactno = trim($data['contactno'] ?? '');
        $department = trim($data['department'] ?? '');
        $user_type = trim($data['user_type'] ?? '');
        $status = trim($data['status'] ?? 'APPROVED');
        $password = $data['password'] ?? ''; 

        if (empty($objid)) {
            return ['status' => 'error', 'message' => 'User ID is required.'];
        }

        // Validate user_type
        $allowed_types = ['admin', 'manager', 'cashier', 'staff'];
        if (!empty($user_type) && !in_array(strtolower($user_type), $allowed_types)) {
            return ['status' => 'error', 'message' => 'Invalid user type selected.'];
        }

        try {
            // Check duplicates excluding current user
            $stmt = $this->con->prepare("SELECT objid FROM tbl_users WHERE (username = ? OR email = ?) AND objid != ?");
            $stmt->execute([$username, $email, $objid]);
            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'Username or Email already in use by another account.'];
            }

            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE tbl_users SET 
                            idno = ?, fullname = ?, username = ?, email = ?, 
                            contactno = ?, department = ?, user_type = ?, status = ?, password_hash = ?
                        WHERE objid = ?";
                $stmt = $this->con->prepare($sql);
                $result = $stmt->execute([$idno, $fullname, $username, $email, $contactno, $department, strtolower($user_type), $status, $hash, $objid]);
            } else {
                $sql = "UPDATE tbl_users SET 
                            idno = ?, fullname = ?, username = ?, email = ?, 
                            contactno = ?, department = ?, user_type = ?, status = ?
                        WHERE objid = ?";
                $stmt = $this->con->prepare($sql);
                $result = $stmt->execute([$idno, $fullname, $username, $email, $contactno, $department, strtolower($user_type), $status, $objid]);
            }

            if ($result) {
                return ['status' => 'success', 'message' => 'User updated successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to update user.'];
            }
        } catch (PDOException $e) {
            error_log("UpdateUser Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update Status (Approve/Disapprove).
     * Used specifically by the "Approve" button.
     */
    public function updateStatus($id, $status) {
        error_log("UsersController::updateStatus called with id: $id, status: $status");
        
        if (empty($id)) {
            return ['status' => 'error', 'message' => 'User ID is required.'];
        }
        
        $allowed_status = ['APPROVED', 'DISAPPROVED'];
        if (!in_array($status, $allowed_status)) {
            return ['status' => 'error', 'message' => 'Invalid status value. Allowed: ' . implode(', ', $allowed_status)];
        }

        try {
            $sql = "UPDATE tbl_users SET status = ? WHERE objid = ?";
            $stmt = $this->con->prepare($sql);
            $result = $stmt->execute([$status, $id]);

            if ($result && $stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'User status updated successfully.'];
            } elseif ($result && $stmt->rowCount() === 0) {
                return ['status' => 'error', 'message' => 'User not found or status unchanged.'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to update user status.'];
            }
        } catch (PDOException $e) {
            error_log("UpdateStatus Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a user by ID.
     */
    public function deleteUser($id) {
        error_log("UsersController::deleteUser called with id: $id");
        
        if (empty($id)) {
            return ['status' => 'error', 'message' => 'User ID is required.'];
        }

        try {
            $sql = "DELETE FROM tbl_users WHERE objid = ?";
            $stmt = $this->con->prepare($sql);
            $stmt->execute([$id]);

            if ($stmt->rowCount() > 0) {
                return ['status' => 'success', 'message' => 'User deleted successfully.'];
            } else {
                return ['status' => 'error', 'message' => 'User not found.'];
            }
        } catch (PDOException $e) {
            error_log("DeleteUser Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>