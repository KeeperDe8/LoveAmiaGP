<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Order List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body style="background: url('../images/LAbg.png') no-repeat center center/cover;" class="min-h-screen flex flex-col items-center justify-center">
  <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg max-w-xl w-full mt-10">
    <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
      <i class="fas fa-list"></i> Transaction Records
    </h1>
    <div class="mb-4">
      <p class="text-gray-700">This is a dummy transaction record for testing purposes.</p>
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

   
    <div class="border-t-2 border-[#4B2E0E] my-4"></div>

    <!-- Total -->
    <div class="flex justify-between font-bold text-lg text-[#4B2E0E] mb-2">
      <span>Total</span>
      <span>₱300.00</span>
    </div>

    <!-- Reference Number -->
    <div class="mt-2">
      <p class="text-sm text-gray-600">Reference No: <span class="font-semibold text-[#4B2E0E]">REF-20240615-XYZ123</span></p>
    </div>

    <div class="mt-6 flex justify-end">
      <a href="customerpage.php" class="bg-[#4B2E0E] text-white px-4 py-2 rounded-full font-semibold hover:bg-[#6b3e14] transition">Back to Order</a>
    </div>
  </div>
</body>
</html>