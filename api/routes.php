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
require_once __DIR__ . '/controllers/UsersController.php';
require_once __DIR__ . '/controllers/LoginController.php';
require_once __DIR__ . '/controllers/ProductController.php';
require_once __DIR__ . '/controllers/CustomerController.php';

// ============================================================================
// SECTION 4: PARSE THE INCOMING REQUEST
// ============================================================================
 $method = $_SERVER['REQUEST_METHOD'];

// Parse input. Prioritize JSON body for PUT/POST, fallback to $_REQUEST
 $input = [];
if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
    $rawInput = file_get_contents('php://input');
    $decoded = json_decode($rawInput, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $input = $decoded;
    } else {
        // Fallback for standard form-data if JSON decode fails
        $input = $_POST; 
    }
} else {
    $input = $_REQUEST;
}

// ============================================================================
// SECTION 5: EXTRACT THE RESOURCE PATH (DYNAMIC)
// ============================================================================

// Get the directory name of this script relative to the web root
 $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
 $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove the base path from the URI to get the endpoint
 $pathInfo = substr($uri, strlen($scriptName));
 $pathInfo = trim($pathInfo, '/');

// Remove 'routes.php' if it appears in the path
 $pathInfo = str_replace('routes.php', '', $pathInfo);
 $pathInfo = trim($pathInfo, '/');

 $segments = explode('/', $pathInfo);

// Determine resource (e.g., 'products') and optional ID (e.g. '5')
 $resource = $segments[0] ?? '';
 $id = $segments[1] ?? null;

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
        // CASE: User Registration
        // ====================================================================
        case 'register':
            if ($method === 'POST') {
                $controller = new UsersController();
                $response = $controller->register($input);
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
                $controller = new LoginController();
                $response = $controller->login($input['username'] ?? '', $input['password'] ?? '');
            } else {
                http_response_code(405);
                $response = ['status' => 'error', 'message' => 'Invalid method. Use POST.'];
            }
            break;

        // ====================================================================
        // CASE: Users
        // ====================================================================
        case 'users':
            $controller = new UsersController();
            switch ($method) {
                case 'GET':
                    $response = $controller->getUsers();
                    break;
                case 'POST':
                    if (!isset($input['repassword'])) { $input['repassword'] = $input['password'] ?? ''; }
                    $response = $controller->register($input);
                    break;
                case 'PUT':
                    if ($id) { $input['objid'] = $id; }
                    $response = $controller->updateUser($input);
                    break;
                case 'DELETE':
                    if ($id) {
                        $response = $controller->deleteUser($id);
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
            $controller = new ProductController();
            switch ($method) {
                case 'GET':
                    $response = $controller->getProducts();
                    break;
                case 'POST':
                    $response = $controller->createProduct($input);
                    break;
                case 'PUT':
                    if ($id) { $input['objid'] = $id; }
                    $response = $controller->updateProduct($input);
                    break;
                case 'DELETE':
                    if ($id) {
                        $response = $controller->deleteProduct($id);
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
            $controller = new CustomerController();
            switch ($method) {
                case 'GET':
                    $response = $controller->getCustomers();
                    break;
                case 'POST':
                    $response = $controller->createCustomer($input);
                    break;
                case 'PUT':
                    if ($id) { $input['objid'] = $id; }
                    $response = $controller->updateCustomer($input);
                    break;
                case 'DELETE':
                    if ($id) {
                        $response = $controller->deleteCustomer($id);
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
        // CASE: Invalid or Unknown Resource
        // ====================================================================
        default:
            http_response_code(404);
            $response = [
                'status' => 'error',
                'message' => "Resource '{$resource}' not found.",
                'hint' => 'Available endpoints: /register, /login, /users, /products, /customers'
            ];
            break;
    }
} catch (Exception $e) {
    // Log error to file if possible, don't echo details in production
    http_response_code(500);
    $response = ['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()];
}

// ============================================================================
// SECTION 8: SEND THE RESPONSE
// ============================================================================
echo json_encode($response);