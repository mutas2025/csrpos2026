<?php
// RegisterController.php
// Handles self-registration logic for new users.

// Get the absolute path to config file
 $configPath = dirname(__DIR__) . '/config/db_connect.php';
if (!file_exists($configPath)) {
    error_log("Database config not found at: " . $configPath);
    die(json_encode(['status' => 'error', 'message' => 'Database configuration not found']));
}

require_once $configPath;

class RegisterController {
    private $con;

    public function __construct() {
        global $con;
        if (!isset($con)) {
            error_log("Database connection not established in RegisterController");
            throw new Exception("Database connection not available");
        }
        $this->con = $con;
    }

    /**
     * Register a new user (Public-facing).
     * Defaults status to 'PENDING' for admin approval.
     */
    public function registerUser(array $data) {
        error_log("RegisterController::registerUser called with data: " . print_r($data, true));
        
        // 1. Extract and sanitise input
        $idno        = trim($data['idno'] ?? '');
        $fullname    = trim($data['fullname'] ?? '');
        $username    = trim($data['username'] ?? '');
        $password    = $data['password'] ?? '';
        $repassword  = $data['repassword'] ?? '';
        $email       = trim($data['email'] ?? '');
        $contactno   = trim($data['contactno'] ?? '');
        $department  = trim($data['department'] ?? '');
        $user_type   = trim($data['user_type'] ?? 'staff'); // Default to staff
        
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
        
        if (!empty($missingFields)) {
            return [
                'status' => 'error', 
                'message' => 'Please fill in all required fields. Missing: ' . implode(', ', $missingFields)
            ];
        }

        if ($password !== $repassword) {
            return ['status' => 'error', 'message' => 'Passwords do not match.'];
        }

        // Validate password strength (Min 6 chars, 1 number, 1 special char)
        if (strlen($password) < 6) {
            return ['status' => 'error', 'message' => 'Password must be at least 6 characters long.'];
        }
        if (!preg_match('/[0-9]/', $password)) {
            return ['status' => 'error', 'message' => 'Password must contain at least one number.'];
        }
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            return ['status' => 'error', 'message' => 'Password must contain at least one special character.'];
        }

        // Validate user_type
        $allowed_types = ['admin', 'manager', 'cashier', 'staff'];
        if (!in_array(strtolower($user_type), $allowed_types)) {
            return ['status' => 'error', 'message' => 'Invalid user type selected.'];
        }

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'error', 'message' => 'Invalid email format.'];
        }

        // 3. Check for duplicates
        try {
            if (!$this->con) {
                return ['status' => 'error', 'message' => 'Database connection error'];
            }
            
            // Check Username
            $stmt = $this->con->prepare("SELECT objid FROM tbl_users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'Username is already taken.'];
            }

            // Check Email
            $stmt = $this->con->prepare("SELECT objid FROM tbl_users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'Email address is already registered.'];
            }

            // Check ID No
            $stmt = $this->con->prepare("SELECT objid FROM tbl_users WHERE idno = ?");
            $stmt->execute([$idno]);
            if ($stmt->fetch()) {
                return ['status' => 'error', 'message' => 'ID Number already exists in our records.'];
            }

            // 4. Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // 5. Insert
            // Note: Status defaults to 'PENDING' for self-registration
            $status = 'PENDING'; 
            $terms_agreed = 1; // Assuming checkbox sent it

            $sql = "INSERT INTO tbl_users
                       (idno, fullname, username, password_hash, email, contactno, department, user_type, status, terms_agreed)
                    VALUES
                       (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $ins = $this->con->prepare($sql);
            $result = $ins->execute([$idno, $fullname, $username, $hash, $email, $contactno, $department, strtolower($user_type), $status, $terms_agreed]);
            
            if ($result) {
                error_log("User registered successfully: " . $username);
                return ['status' => 'success', 'message' => 'Registration successful! Your account is pending approval.'];
            } else {
                $errorInfo = $ins->errorInfo();
                error_log("Failed to register user: " . print_r($errorInfo, true));
                return ['status' => 'error', 'message' => 'Failed to register user. Please try again.'];
            }

        } catch (PDOException $e) {
            error_log("RegisterUser Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        } catch (Exception $e) {
            error_log("RegisterUser General Error: " . $e->getMessage());
            return ['status' => 'error', 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
?>