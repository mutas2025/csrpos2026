<?php
// ============================================================================
// routes.php - Updated with complete login handling
// ============================================================================

// ============================================================================
// SECTION 1: DEVELOPMENT CONFIGURATION
// ============================================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ============================================================================
// SECTION 2: HTTP HEADERS
// ============================================================================
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle OPTIONS preflight request for CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ============================================================================
// SECTION 3: START SESSION
// ============================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ============================================================================
// SECTION 4: LOAD DEPENDENT CLASSES
// ============================================================================
$basePath = dirname(__DIR__);
$controllersPath = $basePath . '/api/controllers/';

// Define controller files
$controllers = [
    'UsersController.php',
    'LoginController.php',
    'ProductController.php',
    'CustomerController.php'
];

// Load controllers if they exist
foreach ($controllers as $controller) {
    $filePath = $controllersPath . $controller;
    if (file_exists($filePath)) {
        require_once $filePath;
    } else {
        error_log("Controller not found: " . $filePath);
    }
}

// ============================================================================
// SECTION 5: PARSE THE INCOMING REQUEST
// ============================================================================
$method = $_SERVER['REQUEST_METHOD'];

// Log the request for debugging
error_log("Request Method: " . $method);
error_log("Request URI: " . $_SERVER['REQUEST_URI']);

// Parse input. Prioritize JSON body for PUT/POST, fallback to $_REQUEST
$input = [];
if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
    $rawInput = file_get_contents('php://input');
    error_log("Raw Input: " . $rawInput);
    
    $decoded = json_decode($rawInput, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $input = $decoded;
        error_log("Decoded JSON: " . print_r($input, true));
    } else {
        // Fallback for standard form-data if JSON decode fails
        $input = $_POST;
        error_log("Using POST data: " . print_r($input, true));
    }
} else {
    $input = $_REQUEST;
    error_log("GET/REQUEST data: " . print_r($input, true));
}

// ============================================================================
// SECTION 6: EXTRACT THE RESOURCE PATH
// ============================================================================
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

error_log("Script Name: " . $scriptName);
error_log("URI: " . $uri);

// Remove the base path from the URI to get the endpoint
$pathInfo = substr($uri, strlen($scriptName));
$pathInfo = trim($pathInfo, '/');

// Remove 'routes.php' if it appears in the path
$pathInfo = str_replace('routes.php', '', $pathInfo);
$pathInfo = trim($pathInfo, '/');

$segments = explode('/', $pathInfo);

// Determine resource and parameters
$resource = $segments[0] ?? '';
$action   = $segments[1] ?? null;
$id       = $segments[2] ?? null;

error_log("Resource: " . $resource);
error_log("Action: " . $action);
error_log("ID: " . $id);

// ============================================================================
// SECTION 7: DISPATCH TO THE CORRECT CONTROLLER
// ============================================================================

$response = [];

try {
    // Simple validation to ensure a resource was requested
    if (empty($resource)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'No API endpoint specified.']);
        exit;
    }

    switch ($resource) {
        
        // ====================================================================
        // CASE: User Login
        // ====================================================================
        case 'login':
            if ($method === 'POST') {
                if (class_exists('LoginController')) {
                    $controller = new LoginController();
                    $username = $input['username'] ?? '';
                    $password = $input['password'] ?? '';
                    $response = $controller->login($username, $password);
                } else {
                    http_response_code(500);
                    $response = [
                        'status' => 'error', 
                        'message' => 'LoginController not found. Please check the installation.'
                    ];
                }
            } else {
                http_response_code(405);
                $response = [
                    'status' => 'error', 
                    'message' => 'Invalid method. Use POST for login.'
                ];
            }
            break;
        
        // ====================================================================
        // CASE: Check Login Status
        // ====================================================================
        case 'check-login':
            if ($method === 'GET') {
                if (class_exists('LoginController')) {
                    $controller = new LoginController();
                    $response = $controller->checkLoginStatus();
                } else {
                    $response = ['status' => 'error', 'message' => 'LoginController not found'];
                }
            } else {
                http_response_code(405);
                $response = ['status' => 'error', 'message' => 'Use GET method'];
            }
            break;
        
        // ====================================================================
        // CASE: Logout
        // ====================================================================
        case 'logout':
            if ($method === 'POST' || $method === 'GET') {
                if (class_exists('LoginController')) {
                    $controller = new LoginController();
                    $response = $controller->logout();
                } else {
                    $response = ['status' => 'error', 'message' => 'LoginController not found'];
                }
            } else {
                http_response_code(405);
                $response = ['status' => 'error', 'message' => 'Invalid method'];
            }
            break;
        
        // ====================================================================
        // CASE: Change Password
        // ====================================================================
        case 'change-password':
            if ($method === 'POST') {
                if (class_exists('LoginController')) {
                    // Check if user is logged in
                    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                        http_response_code(401);
                        $response = ['status' => 'error', 'message' => 'User not logged in'];
                        break;
                    }
                    
                    $controller = new LoginController();
                    $userId = $_SESSION['user']['objid'];
                    $oldPassword = $input['old_password'] ?? '';
                    $newPassword = $input['new_password'] ?? '';
                    $response = $controller->changePassword($userId, $oldPassword, $newPassword);
                } else {
                    $response = ['status' => 'error', 'message' => 'LoginController not found'];
                }
            } else {
                http_response_code(405);
                $response = ['status' => 'error', 'message' => 'Use POST method'];
            }
            break;
        
        // ====================================================================
        // CASE: User Registration
        // ====================================================================
        case 'add':
            if ($method === 'POST') {
                if (class_exists('UsersController')) {
                    $controller = new UsersController();
                    $response = $controller->addUser($input);
                } else {
                    $response = ['status' => 'error', 'message' => 'UsersController not found'];
                }
            } else {
                http_response_code(405);
                $response = ['status' => 'error', 'message' => 'Invalid method. Use POST.'];
            }
            break;

        // ====================================================================
        // CASE: Users Management
        // ====================================================================
        case 'users':
            if (!class_exists('UsersController')) {
                $response = ['status' => 'error', 'message' => 'UsersController not found'];
                break;
            }
            
            $controller = new UsersController();
            
            // Sub-resource routing (e.g., /users/status/5)
            if ($action === 'status' && !empty($id)) {
                if ($method === 'PUT' || $method === 'POST') {
                    $status = $input['status'] ?? 'APPROVED';
                    $response = $controller->updateStatus($id, $status);
                } else {
                    http_response_code(405);
                    $response = ['status' => 'error', 'message' => 'Use PUT or POST for status updates.'];
                }
                break;
            }

            // Standard REST routing
            switch ($method) {
                case 'GET':
                    $response = $controller->getUsers();
                    break;
                case 'POST':
                    if (!isset($input['repassword'])) { 
                        $input['repassword'] = $input['password'] ?? ''; 
                    }
                    $response = $controller->addUser($input);
                    break;
                case 'PUT':
                    if (is_numeric($action)) {
                        $input['objid'] = $action;
                        $response = $controller->updateUser($input);
                    } else {
                        http_response_code(400);
                        $response = ['status' => 'error', 'message' => 'User ID required for update.'];
                    }
                    break;
                case 'DELETE':
                    if ($action) {
                        $response = $controller->deleteUser($action);
                    } else {
                        http_response_code(400);
                        $response = ['status' => 'error', 'message' => 'ID required for delete.'];
                    }
                    break;
                default:
                    http_response_code(405);
                    $response = ['status' => 'error', 'message' => 'Method not allowed.'];
            }
            break;

        // ====================================================================
        // CASE: Products
        // ====================================================================
        case 'products':
            if (!class_exists('ProductController')) {
                $response = ['status' => 'error', 'message' => 'ProductController not found'];
                break;
            }
            
            $controller = new ProductController();
            $prodId = $action;

            switch ($method) {
                case 'GET':
                    $response = $controller->getProducts();
                    break;
                case 'POST':
                    $response = $controller->createProduct($input);
                    break;
                case 'PUT':
                    if ($prodId) { 
                        $input['objid'] = $prodId; 
                    }
                    $response = $controller->updateProduct($input);
                    break;
                case 'DELETE':
                    if ($prodId) {
                        $response = $controller->deleteProduct($prodId);
                    } else {
                        http_response_code(400);
                        $response = ['status' => 'error', 'message' => 'ID required.'];
                    }
                    break;
                default:
                    http_response_code(405);
                    $response = ['status' => 'error', 'message' => 'Method not allowed.'];
            }
            break;

        // ====================================================================
        // CASE: Customers
        // ====================================================================
        case 'customers':
            if (!class_exists('CustomerController')) {
                $response = ['status' => 'error', 'message' => 'CustomerController not found'];
                break;
            }
            
            $controller = new CustomerController();
            $custId = $action;

            switch ($method) {
                case 'GET':
                    $response = $controller->getCustomers();
                    break;
                case 'POST':
                    $response = $controller->createCustomer($input);
                    break;
                case 'PUT':
                    if ($custId) { 
                        $input['objid'] = $custId; 
                    }
                    $response = $controller->updateCustomer($input);
                    break;
                case 'DELETE':
                    if ($custId) {
                        $response = $controller->deleteCustomer($custId);
                    } else {
                        http_response_code(400);
                        $response = ['status' => 'error', 'message' => 'ID required.'];
                    }
                    break;
                default:
                    http_response_code(405);
                    $response = ['status' => 'error', 'message' => 'Method not allowed.'];
            }
            break;

        // ====================================================================
        // CASE: Test endpoint for debugging
        // ====================================================================
        case 'test':
            $response = [
                'status' => 'success',
                'message' => 'API is working',
                'data' => [
                    'method' => $method,
                    'resource' => $resource,
                    'action' => $action,
                    'id' => $id,
                    'input' => $input,
                    'session' => isset($_SESSION['user']) ? 'User logged in' : 'No active session'
                ]
            ];
            break;

        // ====================================================================
        // CASE: Invalid or Unknown Resource
        // ====================================================================
        default:
            http_response_code(404);
            $response = [
                'status' => 'error',
                'message' => "Resource '{$resource}' not found.",
                'hint' => 'Available endpoints: /login, /logout, /check-login, /change-password, /add, /users, /products, /customers, /test'
            ];
            break;
    }
} catch (Exception $e) {
    error_log("API Error: " . $e->getMessage());
    error_log("API Error Trace: " . $e->getTraceAsString());
    http_response_code(500);
    $response = [
        'status' => 'error', 
        'message' => 'Server error occurred: ' . $e->getMessage()
    ];
}

// ============================================================================
// SECTION 8: SEND THE RESPONSE
// ============================================================================
echo json_encode($response);
?>