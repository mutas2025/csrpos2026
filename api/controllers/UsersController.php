<?php
// UsersController.php
// This class handles user management logic including registration,
// listing, updating, and deleting users.

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
        // 1. Extract and sanitise input
        $idno       = trim($data['idno'] ?? '');
        $fullname   = trim($data['fullname'] ?? '');
        $username   = trim($data['username'] ?? '');
        $password   = $data['password'] ?? '';
        $repassword = $data['repassword'] ?? '';
        $email      = trim($data['email'] ?? '');
        $contactno  = trim($data['contactno'] ?? '');
        $department = trim($data['department'] ?? '');
        $user_type  = trim($data['user_type'] ?? '');

        // 2. Validation
        if (empty($idno) || empty($fullname) || empty($username) || empty($password) || empty($repassword)
            || empty($email) || empty($contactno) || empty($department) || empty($user_type)) {
            return ['status' => 'error', 'message' => 'All fields are required.'];
        }

        if ($password !== $repassword) {
            return ['status' => 'error', 'message' => 'Passwords do not match.'];
        }

        // 3. Check for duplicates
        $stmt = $this->con->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetchColumn() > 0) {
            return ['status' => 'error', 'message' => 'Username or email already in use.'];
        }

        // 4. Hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // 5. Insert
        $sql = "INSERT INTO tbl_users
                   (idno, fullname, username, password, email, contactno, department, user_type, created_at)
                VALUES
                   (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        try {
            $ins = $this->con->prepare($sql);
            $ins->execute([$idno, $fullname, $username, $hash, $email, $contactno, $department, $user_type]);
            return ['status' => 'success', 'message' => 'Registration successful.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Get all users.
     * Updated to include 'objid' so the frontend can use it for edits/deletes.
     */
    public function getUsers() {
        try {
            // Added 'objid' to the select list
            $stmt = $this->con->prepare("SELECT objid, idno, fullname, username, email, contactno, department, user_type FROM tbl_users");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ['status' => 'success', 'data' => $users];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Update an existing user.
     */
    public function updateUser(array $data) {
        // 1. Extract ID and data
        $objid = $data['objid'] ?? '';
        $idno = trim($data['idno'] ?? '');
        $fullname = trim($data['fullname'] ?? '');
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $contactno = trim($data['contactno'] ?? '');
        $department = trim($data['department'] ?? '');
        $user_type = trim($data['user_type'] ?? '');
        $password = $data['password'] ?? ''; // Optional

        if (empty($objid)) {
            return ['status' => 'error', 'message' => 'User ID is required.'];
        }

        // 2. Build SQL dynamically based on whether password is being updated
        try {
            if (!empty($password)) {
                // Update with password
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "UPDATE tbl_users SET 
                            idno = ?, fullname = ?, username = ?, email = ?, 
                            contactno = ?, department = ?, user_type = ?, password = ?
                        WHERE objid = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->execute([$idno, $fullname, $username, $email, $contactno, $department, $user_type, $hash, $objid]);
            } else {
                // Update without password
                $sql = "UPDATE tbl_users SET 
                            idno = ?, fullname = ?, username = ?, email = ?, 
                            contactno = ?, department = ?, user_type = ?
                        WHERE objid = ?";
                $stmt = $this->con->prepare($sql);
                $stmt->execute([$idno, $fullname, $username, $email, $contactno, $department, $user_type, $objid]);
            }

            return ['status' => 'success', 'message' => 'User updated successfully.'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Delete a user by ID.
     * This fixes the "Call to undefined method" error.
     */
    public function deleteUser($id) {
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
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>