<?php
session_start();

// Ensure only authenticated owners can delete products
if (!isset($_SESSION['OwnerID'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

require_once('../classes/database.php'); // Adjust path if your classes folder is elsewhere
$con = new database();

header('Content-Type: application/json'); // Set header for JSON response

// Start output buffering to catch any stray output before JSON
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productID = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    if ($productID !== false && $productID !== null && $productID > 0) {
        $result = $con->deleteProduct($productID);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
        } else {
            // Log the error for server-side debugging
            error_log("Failed to delete product ID $productID from database.");
            
            // Provide a more informative error message if it's a foreign key constraint issue
            // This relies on catching the specific error in database.php and propagating it,
            // or by checking the database logs. For now, a generic message.
            echo json_encode(['success' => false, 'message' => 'Failed to delete product. It might be linked to existing orders or other data.']);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid Product ID provided.']);
    }
} else {
    // Handle cases where product_id is not set or not a POST request
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

ob_end_flush(); // Flush the output buffer
?>