<?php
session_start();
ob_start(); // Start output buffering

if (!isset($_SESSION['CustomerID'])) {
  header('Location: ../all/login.php'); // Path to login.php from Customer/
  ob_end_clean();
  exit();
}
require_once('../classes/database.php'); // Path to database class from Customer/
$con = new database();

$customerID = $_SESSION['CustomerID'];
$customerFN = $_SESSION['CustomerFN'] ?? 'Customer'; // Get customer's first name for display

// Fetch orders specifically for this customer ID
$customerOrders = $con->getOrdersForCustomer($customerID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($customerFN) ?>'s Transaction Records</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Inter', sans-serif; }
    /* Adjust background image path relative to the new location of customer_tranlist.php */
    /* If 'images' is in the LoveAmaiah/ root, path from 'Customer/' would be '../images/LAbg.png' */
    body { background: url('../images/LAbg.png') no-repeat center center/cover; }

    /* Custom scrollbar styles for order list containers */
    .overflow-y-auto::-webkit-scrollbar {
        width: 8px; /* Width of the scrollbar */
    }
    .overflow-y-auto::-webkit-scrollbar-track {
        background: rgba(200, 200, 200, 0.3); /* Color of the tracking area */
        border-radius: 10px;
    }
    .overflow-y-auto::-webkit-scrollbar-thumb {
        background-color: #C4A07A; /* Color of the scroll thumb */
        border-radius: 10px; /* Roundness of the scroll thumb */
        border: 2px solid rgba(255, 255, 255, 0.5); /* Creates padding around thumb */
    }
    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background-color: #a17850; /* Color of the scroll thumb on hover */
    }
  </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4">
  <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg max-w-2xl w-full">
    <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
      <i class="fas fa-history"></i> Your Transaction History
    </h1>
    
    <?php if (empty($customerOrders)): ?>
      <p class="text-gray-700">You have no recorded transactions yet.</p>
    <?php else: ?>
      <div class="space-y-4 max-h-96 overflow-y-auto pr-2"> <!-- Added max-height and overflow for scroll -->
          <?php foreach ($customerOrders as $order): ?>
          <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
              <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($order['OrderID']) ?></p>
              <p class="text-xs text-gray-600 mb-2">
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
  
  <div class="mt-6 flex justify-center w-full max-w-2xl">
      <a href="customerpage.php" class="bg-[#4B2E0E] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#6b3e14] transition shadow-md">Back to Menu</a>
  </div>
</body>
</html>