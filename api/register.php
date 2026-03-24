<?php
// api/register.php
// Handle registration requests

require_once dirname(__DIR__) . '/config/db_connect.php';

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit();
}

try {
    // Get input data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // If not JSON, get from POST
    if (!$input) {
        $input = $_POST;
    }
    
    // Sanitize and validate inputs
    $idno = trim($input['idNumber'] ?? '');
    $fullname = trim($input['fullName'] ?? '');
    $department = trim($input['department'] ?? '');
    $user_type = trim($input['userType'] ?? '');
    $username = trim($input['username'] ?? '');
    $email = trim($input['email'] ?? '');
    $contactno = trim($input['contactNumber'] ?? '');
    $password = $input['password'] ?? '';
    $retype_password = $input['retypePassword'] ?? '';
    $terms_agreed = isset($input['terms']) ? 1 : 0;
    
    // Validations
    if (empty($idno) || empty($fullname) || empty($username) || empty($email) || empty($password)) {
        throw new Exception("All required fields must be filled.");
    }
    
    if ($password !== $retype_password) {
        throw new Exception("Passwords do not match.");
    }
    
    if (strlen($password) < 6) {
        throw new Exception("Password must be at least 6 characters long.");
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format.");
    }
    
    if (!$terms_agreed) {
        throw new Exception("You must agree to the terms and conditions.");
    }
    
    // Validate user_type
    $allowed_types = ['admin', 'manager', 'cashier', 'staff'];
    if (!in_array($user_type, $allowed_types)) {
        throw new Exception("Invalid user type.");
    }
    
    // Check for duplicates
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE username = ? OR idno = ? OR email = ?");
    $checkStmt->execute([$username, $idno, $email]);
    if ($checkStmt->fetchColumn() > 0) {
        throw new Exception("Username, ID Number, or Email already exists.");
    }
    
    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert into Database with status PENDING
    $stmt = $pdo->prepare("INSERT INTO tbl_users (idno, fullname, username, password_hash, email, contactno, department, user_type, status, terms_agreed) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'PENDING', ?)");
    
    $stmt->execute([
        $idno, 
        $fullname, 
        $username, 
        $hashed_password, 
        $email, 
        $contactno, 
        $department, 
        $user_type,
        $terms_agreed
    ]);
    
    echo json_encode([
        'status' => 'success',
        'message' => 'Registration successful! Your account is pending approval. You will be notified once approved.'
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} catch (PDOException $e) {
    error_log("Registration error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred. Please try again.'
    ]);
}
?>