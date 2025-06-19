
<?php
session_start();


if (!isset($_SESSION['OwnerID'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

require_once('../classes/database.php'); 
$con = new database();

header('Content-Type: application/json'); 

ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $productID = filter_var($_POST['product_id'], FILTER_SANITIZE_NUMBER_INT);

    if ($productID !== false && $productID !== null && $productID > 0) {
        $result = $con->deleteProduct($productID);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully.']);
        } else {
      
            error_log("Failed to delete product ID $productID from database.");
            
           
            echo json_encode(['success' => false, 'message' => 'Failed to delete product. It might be linked to existing orders or other data.']);
        }
    } else {
        http_response_code(400); 
        echo json_encode(['success' => false, 'message' => 'Invalid Product ID provided.']);
    }
} else {
    
    http_response_code(400); 
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

ob_end_flush(); 
?>
