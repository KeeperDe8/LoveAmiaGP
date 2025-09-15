<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['OwnerID']) && !isset($_SESSION['EmployeeID'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require_once __DIR__ . '/../classes/database.php';
$con = new database();

$loggedInUserType = isset($_SESSION['OwnerID']) ? 'owner' : 'employee';
$loggedInID = isset($_SESSION['OwnerID']) ? (int)$_SESSION['OwnerID'] : (int)$_SESSION['EmployeeID'];

$since = isset($_GET['since']) ? (int)$_GET['since'] : 0;

try {
    $allOrders = $con->getOrdersForOwnerOrEmployee($loggedInID, $loggedInUserType);
    $new = [];
    $maxId = $since;
    foreach ($allOrders as $row) {
        $oid = isset($row['OrderID']) ? (int)$row['OrderID'] : 0;
        if ($oid > $since) { $new[] = $row; }
        if ($oid > $maxId) { $maxId = $oid; }
    }

    echo json_encode([
        'success' => true,
        'new' => $new,
        'max_id' => $maxId,
        'count' => count($new)
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}
