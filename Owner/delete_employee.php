<?php
session_start();

// Ensure only authenticated owners can delete employees
if (!isset($_SESSION['OwnerID'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

require_once('../classes/database.php'); // CORRECTED PATH: Assumes 'classes' is one level up
$con = new database();

header('Content-Type: application/json'); // Set header for JSON response

// Start output buffering immediately to catch any stray output
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
    // Sanitize the input
    $employeeID = filter_var($_POST['employee_id'], FILTER_SANITIZE_NUMBER_INT);

    if ($employeeID !== false && $employeeID !== null && $employeeID > 0) { // Check for valid integer after sanitization
        $result = $con->deleteEmployee($employeeID);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Employee deleted successfully.']);
        } else {
            // Log the error for server-side debugging, but send a generic message to the client
            error_log("Failed to delete employee ID $employeeID from database."); // Check your server error logs for more detail
            echo json_encode(['success' => false, 'message' => 'Failed to delete employee. Please try again.']);
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'Invalid Employee ID provided.']);
    }
} else {
    // Handle cases where employee_id is not set or not a POST request
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

ob_end_flush(); // Flush the output buffer
?>