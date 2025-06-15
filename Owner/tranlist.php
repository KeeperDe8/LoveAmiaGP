<?php
session_start();
if (!isset($_SESSION['OwnerID'])) {
  // Assuming login.php is directly in the parent directory of this file if in Owner/
  header('Location: ../all/login.php'); // Consistent path to login.php
  exit();
}
require_once('../classes/database.php'); // Adjust path to your database class
$con = new database();

// Get the OwnerID from the session
$ownerID = $_SESSION['OwnerID'];

// Fetch all orders associated with this owner's business using the database function
$allOrders = $con->getAllOrdersForOwnerView($ownerID);

$customerAccountOrders = [];
$walkinStaffOrders = [];

// Categorize orders based on UserTypeID and presence of CustomerUsername
foreach ($allOrders as $order) {
    if ($order['UserTypeID'] == 3 && !empty($order['CustomerUsername'])) {
        // Registered customer order (UserTypeID 3 is typically for Customer)
        $customerAccountOrders[] = $order;
    } else {
        // Walk-in/Staff-assisted order (UserTypeID 1 for owner, 2 for employee, or null customer)
        $walkinStaffOrders[] = $order;
    }
}
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
    /* You might want to adjust the background image path */
    /* If 'images' is in the root, and this file is in 'Owner/', path might be '../images/LAbg.png' */
    body { background: url('images/LAbg.png') no-repeat center center/cover; }
  </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
  <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Customer Account Orders Box -->
    <div>
      <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
        <i class="fas fa-user-check"></i> Customer Account Orders
      </h1>
      <?php if (empty($customerAccountOrders)): ?>
        <p class="text-gray-700">No registered customer orders found.</p>
      <?php else: ?>
        <div class="space-y-4 max-h-96 overflow-y-auto pr-2"> <!-- Added max-height and overflow for scroll -->
            <?php foreach ($customerAccountOrders as $order): ?>
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
                <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($order['OrderID']) ?></p>
                <p class="text-xs text-gray-600 mb-2">
                    Customer: <span class="font-medium"><?= htmlspecialchars($order['CustomerUsername']) ?></span><br>
                    Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($order['OrderDate']))) ?>
                </p>
                <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
                    <?php if (!empty($order['OrderItems'])): ?>
                        <li><?= htmlspecialchars($order['OrderItems']) ?></li>
                    <?php else: ?>
                        <li>No specific items recorded.</li>
                    <?php endif; ?>
                </ul>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-bold text-lg text-[#4B2E0E]">₱<?= htmlspecialchars(number_format($order['TotalAmount'], 2)) ?></span>
                    <span class="text-sm text-gray-600">Ref: <?= htmlspecialchars($order['ReferenceNo'] ?? 'N/A') ?></span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Payment: <?= htmlspecialchars($order['PaymentMethod'] ?? 'N/A') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Walk-in/Staff-Assisted Orders Box -->
    <div>
      <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
        <i class="fas fa-walking"></i> Walk-in/Staff-Assisted Orders
      </h1>
      <?php if (empty($walkinStaffOrders)): ?>
        <p class="text-gray-700">No walk-in or staff-assisted orders found.</p>
      <?php else: ?>
        <div class="space-y-4 max-h-96 overflow-y-auto pr-2"> <!-- Added max-height and overflow for scroll -->
            <?php foreach ($walkinStaffOrders as $order): ?>
            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
                <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($order['OrderID']) ?></p>
                <p class="text-xs text-gray-600 mb-2">
                    Placed by: 
                    <?php
                        if ($order['UserTypeID'] == 1 && !empty($order['OwnerFirstName'])) {
                            echo htmlspecialchars($order['OwnerFirstName'] . ' ' . $order['OwnerLastName']) . ' (Owner)';
                        } elseif ($order['UserTypeID'] == 2 && !empty($order['EmployeeFirstName'])) {
                            echo htmlspecialchars($order['EmployeeFirstName'] . ' ' . $order['EmployeeLastName']) . ' (Employee)';
                        } else {
                            echo 'Guest/Unknown';
                        }
                    ?><br>
                    Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($order['OrderDate']))) ?>
                </p>
                <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
                    <?php if (!empty($order['OrderItems'])): ?>
                        <li><?= htmlspecialchars($order['OrderItems']) ?></li>
                    <?php else: ?>
                        <li>No specific items recorded.</li>
                    <?php endif; ?>
                </ul>
                <div class="flex justify-between items-center mt-2">
                    <span class="font-bold text-lg text-[#4B2E0E]">₱<?= htmlspecialchars(number_format($order['TotalAmount'], 2)) ?></span>
                    <span class="text-sm text-gray-600">Ref: <?= htmlspecialchars($order['ReferenceNo'] ?? 'N/A') ?></span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Payment: <?= htmlspecialchars($order['PaymentMethod'] ?? 'N/A') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

  </div>
  
  <div class="mt-6 flex justify-center w-full max-w-4xl">
      <a href="mainpage.php" class="bg-[#4B2E0E] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#6b3e14] transition shadow-md">Back to Main Menu</a>
  </div>
</body>
</html>