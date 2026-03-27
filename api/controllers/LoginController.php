<?php
// LoginController.php
// This class is responsible for handling authentication requests that
// arrive via the API. It validates credentials against the database
// and manages session creation for approved users.

// We need access to the database connection, which is created in db_connect.php.
// `dirname(__DIR__)` moves up one directory (from controllers to api) to locate
// the config folder regardless of the current working directory.
require_once dirname(__DIR__) . '/config/db_connect.php';

class LoginController {
    // store the PDO database connection here so other methods can use it
    private $con;

    public function __construct() {
        // the global $con variable is defined in db_connect.php; capture it
        global $con;
        $this->con = $con;
    }

    /**
     * Authenticate a user.
     *
     * This method expects an associative array `$data` containing the login
     * credentials (username/email and password) sent by the client. 
     * It returns an array describing the result (status and message), 
     * which the router will encode as JSON.
     *
     * @param array $data Associative array containing 'username' and 'password'.
     * @return array Response array with status and message.
     */
    public function login(array $data) {
        // ------------------------------------------------------------------
        // 1. Extract and sanitise input values.
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? ''; // Password itself is not trimmed to allow spaces

        // ------------------------------------------------------------------
        // 2. Basic validation: make sure credentials are not empty.
        if (empty($username) || empty($password)) {
            return ['status' => 'error', 'message' => 'Please enter username/email and password.'];
        }

        // ------------------------------------------------------------------
        // 3. Attempt to find the user in the database.
        // We check if the input matches the username OR email, 
        // and ensure the account status is 'APPROVED'.
        try {
            $stmt = $this->con->prepare("SELECT * FROM tbl_users WHERE (username = ? OR email = ?) AND status = 'APPROVED' LIMIT 1");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // ------------------------------------------------------------------
            // 4. Verify user existence and password.
            if ($user && password_verify($password, $user['password_hash'])) {
                
                // --------------------------------------------------------------
                // 5. Start session and store user data.
                // Ensuring session is started only if not already active.
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Set session variables for use across the application
                $_SESSION['user_id'] = $user['objid'];
                $_SESSION['idno'] = $user['idno'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['department'] = $user['department'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['contactno'] = $user['contactno'];
                $_SESSION['status'] = $user['status'];
                $_SESSION['logged_in'] = true;

                return ['status' => 'success', 'message' => 'Login successful!'];

            } else {
                // Generic error message to prevent user enumeration
                return ['status' => 'error', 'message' => 'Invalid username/email or password, or account not approved.'];
            }

        } catch (PDOException $e) {
            // In production you would log this error instead of exposing it.
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>