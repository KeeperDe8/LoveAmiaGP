<?php
ob_start();
session_start();
if (!isset($_SESSION['OwnerID'])) {
  header('Location: ../all/login.php');
  exit();
}

require_once('../classes/database.php');
$con = new database();


error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1); // Also ensure logging is enabled
ini_set('error_log', 'C:\xampp\php\logs\php_error_log.txt'); //

$orderID = $_GET['order_id'] ?? null;
$referenceNo = $_GET['ref_no'] ?? null;

$order = false;
if ($orderID && $referenceNo) {
    // Make sure your database has ReferenceNo and PaymentStatus columns in the 'payment' table
    $order = $con->getFullOrderDetails($orderID, $referenceNo);
}

if (!$order) {
    // Handle case where order is not found or parameters are missing
    header('Location: page.php?error=order_not_found'); // Redirect to order page with error
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>Order Receipt</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex items-center justify-center">

<div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg p-8 w-full max-w-md">
    <h2 class="text-3xl font-extrabold text-[#4B2E0E] text-center mb-6">Order Confirmation</h2>

    <div class="mb-6 border-b pb-4">
        <p class="text-sm text-gray-600 mb-1">Order ID: <span class="font-semibold text-[#4B2E0E]"><?= htmlspecialchars($order['OrderID']) ?></span></p>
        <p class="text-sm text-gray-600 mb-1">Reference No: <span class="font-semibold text-[#4B2E0E]"><?= htmlspecialchars($order['ReferenceNo']) ?></span></p>
        <p class="text-sm text-gray-600 mb-1">Date: <span class="font-semibold text-[#4B2E0E]"><?= date('M d, Y H:i', strtotime($order['OrderDate'])) ?></span></p>
        <p class="text-sm text-gray-600">Payment Method: <span class="font-semibold text-[#4B2E0E]"><?= htmlspecialchars($order['PaymentMethod']) ?></span></p>
    </div>

    <div class="mb-6">
        <h3 class="font-semibold text-[#4B2E0E] mb-3">Items Ordered:</h3>
        <ul class="space-y-2">
            <?php if (!empty($order['Details'])): ?>
                <?php foreach ($order['Details'] as $item): ?>
                    <li class="flex justify-between items-center text-sm text-gray-700">
                        <span><?= htmlspecialchars($item['ProductName']) ?> x <?= htmlspecialchars($item['Quantity']) ?></span>
                        <span class="font-semibold">₱<?= htmlspecialchars(number_format($item['Subtotal'], 2)) ?></span>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-sm text-gray-500">No items found for this order.</p>
            <?php endif; ?>
        </ul>
    </div>

    <div class="mt-6 pt-4 border-t border-dashed border-gray-300">
        <div class="flex justify-between items-center text-lg font-bold text-[#4B2E0E]">
            <span>Total:</span>
            <span>₱<?= htmlspecialchars(number_format($order['TotalAmount'], 2)) ?></span>
        </div>
    </div>

    <div class="mt-8 text-center">
        <button onclick="window.location.href='mainpage.php'" class="bg-[#4B2E0E] text-white rounded-full py-2 px-6 font-semibold hover:bg-[#6b3e14] transition">
            Back to Menu
        </button>
    </div>
</div>

</body>
</html>