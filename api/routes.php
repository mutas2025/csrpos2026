<?php
// ============================================================================
// routes.php
// ============================================================================
// Unified RESTful API entry point for all application endpoints.
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
// SECTION 3: LOAD DEPENDENT CLASSES
// ============================================================================
// Get the absolute path to the controllers directory
$basePath = dirname(__DIR__);
$controllersPath = $basePath . '/api/controllers/';

// Check if files exist before requiring
$usersControllerFile = $controllersPath . 'UsersController.php';
$loginControllerFile = $controllersPath . 'LoginController.php';
$productControllerFile = $controllersPath . 'ProductController.php';
$customerControllerFile = $controllersPath . 'CustomerController.php';

if (file_exists($usersControllerFile)) {
    require_once $usersControllerFile;
} else {
    error_log("UsersController.php not found at: " . $usersControllerFile);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server configuration error: UsersController not found']);
    exit;
}

if (file_exists($loginControllerFile)) {
    require_once $loginControllerFile;
}

if (file_exists($productControllerFile)) {
    require_once $productControllerFile;
}

if (file_exists($customerControllerFile)) {
    require_once $customerControllerFile;
}

// ============================================================================
// SECTION 4: PARSE THE INCOMING REQUEST
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
// SECTION 5: EXTRACT THE RESOURCE PATH (DYNAMIC)
// ============================================================================

// Get the directory name of this script relative to the web root
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

// Determine resource (e.g., 'users') and optional ID/sub-action (e.g. '5' or 'status/5')
$resource = $segments[0] ?? '';
$action   = $segments[1] ?? null; // Can be an ID or a sub-resource like 'status'
$id       = $segments[2] ?? null; // Used if action is a sub-resource (e.g. /users/status/5)

error_log("Resource: " . $resource);
error_log("Action: " . $action);
error_log("ID: " . $id);

// ============================================================================
// SECTION 6: DISPATCH TO THE CORRECT CONTROLLER
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
        // CASE: User Registration (standalone endpoint - ADD)
        // ====================================================================
        case 'add':
            if ($method === 'POST') {
                $controller = new UsersController();
                $response = $controller->addUser($input);
            } else {
                http_response_code(405);
                $response = ['status' => 'error', 'message' => 'Invalid method. Use POST.'];
            }
            break;

        // ====================================================================
        // CASE: User Login
        // ====================================================================
        case 'login':
            if ($method === 'POST') {
                if (class_exists('LoginController')) {
                    $controller = new LoginController();
                    $response = $controller->login($input['username'] ?? '', $input['password'] ?? '');
                } else {
                    $response = ['status' => 'error', 'message' => 'LoginController not found'];
                }
            } else {
                http_response_code(405);
                $response = ['status' => 'error', 'message' => 'Invalid method. Use POST.'];
            }
            break;

        // ====================================================================
        // CASE: Users
        // ====================================================================
        case 'users':
            if (!class_exists('UsersController')) {
                $response = ['status' => 'error', 'message' => 'UsersController not found'];
                break;
            }
            
            $controller = new UsersController();
            
            // CASE: Sub-resource routing (e.g., /users/status/5)
            if ($action === 'status' && !empty($id)) {
                if ($method === 'PUT' || $method === 'POST') {
                    // Route to updateStatus method
                    // Input is expected to be JSON: { "status": "APPROVED" }
                    $status = $input['status'] ?? 'APPROVED';
                    $response = $controller->updateStatus($id, $status);
                } else {
                    http_response_code(405);
                    $response = ['status' => 'error', 'message' => 'Use PUT or POST for status updates.'];
                }
                break; // Exit switch after handling sub-resource
            }

            // CASE: Standard REST routing (e.g., /users or /users/5)
            switch ($method) {
                case 'GET':
                    $response = $controller->getUsers();
                    break;
                case 'POST':
                    // For adding new users, ensure repassword exists for validation
                    if (!isset($input['repassword'])) { 
                        $input['repassword'] = $input['password'] ?? ''; 
                    }
                    $response = $controller->addUser($input);
                    break;
                case 'PUT':
                    // If action is numeric, it's an ID: /users/5
                    if (is_numeric($action)) {
                        $input['objid'] = $action;
                        $response = $controller->updateUser($input);
                    } elseif (!empty($action)) {
                        // If action is not numeric and not 'status', it's an invalid endpoint
                        http_response_code(404);
                        $response = ['status' => 'error', 'message' => 'Unknown user action.'];
                    } else {
                        // Bulk update without ID (not typically allowed, catch error)
                        http_response_code(400);
                        $response = ['status' => 'error', 'message' => 'User ID required for update.'];
                    }
                    break;
                case 'DELETE':
                    // ID is expected in the URL: /users/5
                    $deleteId = $action; 
                    if ($deleteId) {
                        $response = $controller->deleteUser($deleteId);
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
            $prodId = $action; // $action holds the ID in this context (e.g., /products/5)

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
            $custId = $action; // $action holds the ID

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
                    'input' => $input
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
                'hint' => 'Available endpoints: /add, /login, /users, /products, /customers, /test'
            ];
            break;
    }
} catch (Exception $e) {
    // Log error to file if possible, don't echo details in production
    error_log("API Error: " . $e->getMessage());
    error_log("API Error Trace: " . $e->getTraceAsString());
    http_response_code(500);
    $response = ['status' => 'error', 'message' => 'Server error occurred: ' . $e->getMessage()];
}

// ============================================================================
// SECTION 8: SEND THE RESPONSE
// ============================================================================
echo json_encode($response);
?>