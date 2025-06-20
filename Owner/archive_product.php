<?php
session_start();

// Security check: Ensure an owner is logged in.
if (!isset($_SESSION['OwnerID'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

require_once('../classes/database.php'); 
$con = new database();

// Set the content type to JSON for the response
header('Content-Type: application/json'); 

// Ensure the request is a POST request and contains the product_id
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    
    // Sanitize the input to ensure it's an integer
    $productID = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if the product ID is valid
    if ($productID) {
        // Call the archiveProduct method from the database class
        if ($con->archiveProduct($productID)) {
            echo json_encode(['success' => true, 'message' => 'Product archived successfully.']);
        } else {
            // This would happen if the database query fails
            echo json_encode(['success' => false, 'message' => 'Failed to archive product.']);
        }
    } else {
        // Handle cases where the product ID is invalid
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid Product ID provided.']);
    }
} else {
    // Handle cases where the request is not valid
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>