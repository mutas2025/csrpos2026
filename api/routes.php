<?php
// ============================================================================
// routes.php
// ============================================================================
// Unified RESTful API entry point for all application endpoints.
// This file acts as a single router that directs incoming API requests to
// the appropriate controller based on the resource path being requested.
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
header('Content-Type: application/json');

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

// For GET/POST, input is straightforward. For PUT/DELETE, we read php://input.
 $input = json_decode(file_get_contents('php://input'), true) ?? [];

// ============================================================================
// SECTION 5: EXTRACT THE RESOURCE PATH
// ============================================================================
 $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
 $base = '/csr1/csr_pos_oberes/api';
 $pathInfo = substr($uri, strlen($base));
 $pathInfo = trim($pathInfo, '/');
 $segments = explode('/', $pathInfo);

// Determine resource (e.g., 'products') and optional ID (e.g. '5')
 $resource = '';
 $id = null;

if (!empty($segments[0]) && strpos($segments[0], '.php') === false) {
    $resource = $segments[0];
    if (isset($segments[1])) { $id = $segments[1]; }
} elseif (!empty($segments[1])) {
    $resource = $segments[1];
    if (isset($segments[2])) { $id = $segments[2]; }
}

// ============================================================================
// SECTION 6: DISPATCH TO THE CORRECT CONTROLLER
// ============================================================================

 $response = [];

try {
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
        // CASE: Users (GET List, POST Create, PUT Update, DELETE)
        // ====================================================================
        case 'users':
            $controller = new UsersController();
            switch ($method) {
                case 'GET':
                    $response = $controller->getUsers();
                    break;
                case 'POST':
                    // For registration via API, ensure repassword is set
                    if (!isset($input['repassword'])) { $input['repassword'] = $input['password'] ?? ''; }
                    $response = $controller->register($input);
                    break;
                case 'PUT':
                    // Update user. Assume $input contains objid, or we use $id from URL
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
        // CASE: Products (GET List, POST Create, PUT Update, DELETE)
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
        // CASE: Customers (GET List, POST Create, PUT Update, DELETE)
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
                'message' => 'Resource not found. Available: /register, /login, /users, /products, /customers.'
            ];
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    $response = ['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()];
}

// ============================================================================
// SECTION 8: SEND THE RESPONSE
// ============================================================================
echo json_encode($response);