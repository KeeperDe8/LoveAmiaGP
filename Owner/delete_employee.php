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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
    // Sanitize the input
    $employeeID = filter_var($_POST['employee_id'], FILTER_SANITIZE_NUMBER_INT);

    if ($employeeID !== false && $employeeID !== null && $employeeID > 0) { 
        $result = $con->deleteEmployee($employeeID);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Employee deleted successfully.']);
        } else {
           
            error_log("Failed to delete employee ID $employeeID from database.");
            echo json_encode(['success' => false, 'message' => 'Failed to delete employee. Please try again.']);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid Employee ID provided.']);
    }
} else {
    
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

ob_end_flush(); // Flush the output buffer
?>