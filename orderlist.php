<?php
session_start();
$lastPayment = isset($_SESSION['last_payment_method']) ? ucfirst($_SESSION['last_payment_method']) : 'N/A';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Order List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex flex-col items-center justify-center">
  <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg max-w-xl w-full mt-10">
    <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
      <i class="fas fa-list"></i> Order List
    </h1>
    <div class="mb-4">
      <p class="text-gray-700">This is a dummy order list for testing.</p>
      <p class="text-sm text-gray-500">Last payment method: <span class="font-semibold"><?php echo $lastPayment; ?></span></p>
    </div>
    <ul class="divide-y divide-gray-200">
      <li class="py-2 flex justify-between">
        <span>Hot Americano x2</span>
        <span class="font-semibold text-[#4B2E0E]">₱180.00</span>
      </li>
      <li class="py-2 flex justify-between">
        <span>Matcha x1</span>
        <span class="font-semibold text-[#4B2E0E]">₱120.00</span>
      </li>
    </ul>
    <div class="mt-6 flex justify-end">
      <a href="page.php" class="bg-[#4B2E0E] text-white px-4 py-2 rounded-full font-semibold hover:bg-[#6b3e14] transition">Back to Order</a>
    </div>
  </div>
</body>
</html>