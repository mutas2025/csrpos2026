<?php
// Include database configuration
require_once dirname(__DIR__) . '/config/db_connect.php';

// Initialize variables
 $success_message = '';
 $error_message = '';

// Handle Registration Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Sanitize and Retrieve Inputs
        $idno = trim($_POST['idNumber']);
        $fullname = trim($_POST['fullName']);
        $department = trim($_POST['department']);
        $user_type = trim($_POST['userType']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $contactno = trim($_POST['contactNumber']);
        $password = $_POST['password'];
        $retype_password = $_POST['retypePassword'];

        // 2. Validations
        if (empty($idno) || empty($fullname) || empty($username) || empty($email) || empty($password)) {
            throw new Exception("All required fields must be filled.");
        }

        if ($password !== $retype_password) {
            throw new Exception("Passwords do not match.");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }

        // 3. Check for duplicates (Username or ID Number)
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ? OR idno = ?");
        $checkStmt->execute([$username, $idno]);
        if ($checkStmt->fetchColumn() > 0) {
            throw new Exception("Username or ID Number already exists.");
        }

        // 4. Hash Password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 5. Insert into Database
        $stmt = $pdo->prepare("INSERT INTO tbl_users (idno, fullname, username, password, email, contactno, department, user_type) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $idno, 
            $fullname, 
            $username, 
            $hashed_password, 
            $email, 
            $contactno, 
            $department, 
            $user_type
        ]);

        $success_message = "Registration successful! You can now login.";

    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}
?>
