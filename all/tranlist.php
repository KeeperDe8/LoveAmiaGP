<?php
session_start();


$loggedInUserType = null;
$loggedInID = null;

if (isset($_SESSION['OwnerID'])) {
    $loggedInUserType = 'owner';
    $loggedInID = $_SESSION['OwnerID'];
    error_log("DEBUG: tranlist.php - Logged in as Owner with ID: " . $loggedInID);
} elseif (isset($_SESSION['EmployeeID'])) {
    $loggedInUserType = 'employee';
    $loggedInID = $_SESSION['EmployeeID'];
    error_log("DEBUG: tranlist.php - Logged in as Employee with ID: " . $loggedInID);
} else {
    error_log("ERROR: tranlist.php - No OwnerID or EmployeeID in session. Redirecting to login.");
    header('Location: login.php');
    exit();
}

require_once('../classes/database.php');
$con = new database();

$allOrders = $con->getOrdersForOwnerOrEmployee($loggedInID, $loggedInUserType);
error_log("DEBUG: tranlist.php - getOrdersForOwnerOrEmployee returned " . count($allOrders) . " orders.");
error_log("DEBUG: tranlist.php - Raw fetched orders array: " . print_r($allOrders, true));


$customerAccountOrders = [];
$walkinStaffOrders = [];

foreach ($allOrders as $transaction) { 

    if ($transaction['UserTypeID'] == 3 && !empty($transaction['CustomerUsername'])) {
        $customerAccountOrders[] = $transaction;
    } else {
        $walkinStaffOrders[] = $transaction;
    }
}

error_log("DEBUG: tranlist.php - Categorized customerAccountOrders count: " . count($customerAccountOrders));
error_log("DEBUG: tranlist.php - Categorized walkinStaffOrders count: " . count($walkinStaffOrders));

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Transaction Records</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Inter', sans-serif; }
    body { background: url('../images/LAbg.png') no-repeat center center/cover; }
    
    .overflow-y-auto::-webkit-scrollbar {
        width: 8px;
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: rgba(200, 200, 200, 0.3);
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background-color: #C4A07A;
        border-radius: 10px;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background-color: #a17850;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
  <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8">

    <div>
      <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
        <i class="fas fa-user-check"></i> Customer Account Orders
      </h1>
      <?php if (empty($customerAccountOrders)): ?>
        <p class="text-gray-700">No registered customer orders found.</p>
      <?php else: ?>
        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
            <?php foreach ($customerAccountOrders as $transaction): ?>
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
                <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($transaction['OrderID']) ?></p>
                <p class="text-xs text-gray-600 mb-2">
                    Customer: <span class="font-medium"><?= htmlspecialchars($transaction['CustomerUsername']) ?></span><br>
                    Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($transaction['OrderDate']))) ?>
                </p>
                <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
                    <?php if (!empty($transaction['OrderItems'])): ?>
                        <li><?= nl2br(htmlspecialchars($transaction['OrderItems'])) ?></li>
                    <?php else: ?>
                        <li>No specific items recorded.</li>
                    <?php endif; ?>
                </ul>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-bold text-lg text-[#4B2E0E]">₱<?= htmlspecialchars(number_format($transaction['TotalAmount'], 2)) ?></span>
                    <span class="text-sm text-gray-600">Ref: <?= htmlspecialchars($transaction['ReferenceNo'] ?? 'N/A') ?></span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Payment: <?= htmlspecialchars($transaction['PaymentMethod'] ?? 'N/A') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div>
      <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
        <i class="fas fa-walking"></i> Walk-in/Staff-Assisted Orders
      </h1>
      <?php if (empty($walkinStaffOrders)): ?>
        <p class="text-gray-700">No walk-in or staff-assisted orders found.</p>
      <?php else: ?>
        <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
            <?php foreach ($walkinStaffOrders as $transaction): ?>
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
                <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($transaction['OrderID']) ?></p>
                <p class="text-xs text-gray-600 mb-2">
                    Placed by: 
                    <?php
                        if ($transaction['UserTypeID'] == 1) {
                            $ownerName = trim((string)($transaction['OwnerFirstName'] ?? '') . ' ' . (string)($transaction['OwnerLastName'] ?? ''));
                            echo htmlspecialchars($ownerName ?: 'Owner') . ' (Owner)'; 
                        } elseif ($transaction['UserTypeID'] == 2) { 
                            $employeeName = trim((string)($transaction['EmployeeFirstName'] ?? '') . ' ' . (string)($transaction['EmployeeLastName'] ?? ''));
                            echo htmlspecialchars($employeeName ?: 'Employee') . ' (Employee)'; 
                        } elseif ($transaction['UserTypeID'] == 3) {
                            echo 'Walk-in (Customer)'; 
                        } else {
                            echo 'Guest/Unknown'; 
                        }
                    ?><br>
                    Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($transaction['OrderDate']))) ?>
                </p>
                <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
                    <?php if (!empty($transaction['OrderItems'])): ?>
                        <li><?= nl2br(htmlspecialchars($transaction['OrderItems'])) ?></li>
                    <?php else: ?>
                        <li>No specific items recorded.</li>
                    <?php endif; ?>
                </ul>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-bold text-lg text-[#4B2E0E]">₱<?= htmlspecialchars(number_format($transaction['TotalAmount'], 2)) ?></span>
                    <span class="text-sm text-gray-600">Ref: <?= htmlspecialchars($transaction['ReferenceNo'] ?? 'N/A') ?></span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Payment: <?= htmlspecialchars($transaction['PaymentMethod'] ?? 'N/A') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
  
  <div class="mt-6 flex justify-center w-full max-w-4xl">
      <?php if ($loggedInUserType === 'owner'): ?>
          <a href="../Owner/mainpage.php" class="bg-[#4B2E0E] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#6b3e14] transition shadow-md">Back to Main Menu</a>
      <?php elseif ($loggedInUserType === 'employee'): ?>
          <a href="../Employee/employesmain.php" class="bg-[#4B2E0E] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#6b3e14] transition shadow-md">Back to Main Menu</a>
      <?php endif; ?>
  </div>

    <script>
        document.getElementById("logout-btn").addEventListener("click", () => {
            Swal.fire({
                title: 'Are you sure you want to log out?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4B2E0E',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, log out',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "logout.php";
                }
            });
        });
    </script>
</body>
</html>