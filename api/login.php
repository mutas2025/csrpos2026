<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust in production
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Require controllers
require_once __DIR__ . '/controllers/LoginController.php';

// Route handling
 $response = ['status' => 'error', 'message' => 'Invalid request method'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    $controller = new LoginController();
    $response = $controller->login($input);
}

// Output response
echo json_encode($response);