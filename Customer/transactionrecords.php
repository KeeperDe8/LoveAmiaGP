<?php
session_start();
if (!isset($_SESSION['CustomerID'])) {
  header('Location: ../all/login.php');
  exit();
}
require_once('../classes/database.php');
$con = new database();
$customerID = $_SESSION['CustomerID'];
$transactions = $con->getOrdersForCustomer($customerID);
$customer = $_SESSION['CustomerFN'];
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Transaction Records</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen flex bg-cover bg-center bg-no-repeat" style="background-image: url('../images/LAbg.png');">

  <!-- Sidebar -->
  <aside class="w-16 bg-white bg-opacity-90 backdrop-blur-sm flex flex-col items-center py-6 space-y-8 shadow-lg">
    <img src="../images/logo.png" alt="Logo" class="w-14 h-14 rounded-full mb-6" />
    <button title="Home" onclick="window.location='../Customer/advertisement.php'" class="text-xl">
      <i class="fas fa-home <?= $currentPage === 'advertisement.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Cart" onclick="window.location='../Customer/customerpage.php'" class="text-xl">
      <i class="fas fa-shopping-cart <?= $currentPage === 'customerpage.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Order List" onclick="window.location='../Customer/transactionrecords.php'" class="text-xl">
      <i class="fas fa-list <?= $currentPage === 'transactionrecords.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button title="Settings" onclick="window.location='../all/setting.php'" class="text-xl">
      <i class="fas fa-cog <?= $currentPage === 'setting.php' ? 'text-[#C4A07A]' : 'text-[#4B2E0E]' ?>"></i>
    </button>
    <button id="logout-btn" title="Logout" class="text-xl">
      <i class="fas fa-sign-out-alt text-[#4B2E0E]"></i>
    </button>
  </aside>

  <!-- Main Content -->
  <main class="flex-1 p-10 text-white bg-black bg-opacity-40 backdrop-blur-sm">
    <h1 class="text-3xl font-semibold mb-6">Your Transaction Records</h1>
    <div class="overflow-x-auto bg-white bg-opacity-90 rounded-xl shadow-lg">
      <table class="min-w-full text-base text-gray-700">
        <thead>
          <tr class="bg-[#4B2E0E] text-white text-left text-lg">
            <th class="p-4">Date</th>
            <th class="p-4">Item</th>
            <th class="p-4">Order ID</th>
            <th class="p-4">Total</th>
            <th class="p-4">Reference No</th>
            <th class="p-4">Status</th>
          </tr>
        </thead>
        <tbody>
  <?php if (empty($transactions)): ?>
    <tr>
      <td colspan="6" class="text-center p-6 text-gray-500">No transactions yet.</td>
    </tr>
  <?php else: ?>
    <?php foreach ($transactions as $transaction): ?>
      <?php
        $items = explode('; ', $transaction['OrderItems']);
      ?>
      <tr class="border-b">
        <!-- Date -->
        <td class="p-4 align-top"><?= htmlspecialchars($transaction['OrderDate']) ?></td>
        <!-- Items -->
        <td class="p-4 align-top">
          <ul class="list-disc pl-5">
            <?php foreach ($items as $item): ?>
              <li><?= htmlspecialchars($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </td>
        <!-- Order ID -->
        <td class="p-4 align-top"><?= htmlspecialchars($transaction['OrderID']) ?></td>

        <!-- Total -->
        <td class="p-4 align-top">₱<?= number_format($transaction['TotalAmount'], 2) ?></td>

        <!-- Reference No -->
        <td class="p-4 align-top"><?= htmlspecialchars($transaction['ReferenceNo']) ?></td>

        <!-- Status -->
        <td class="p-4 align-top">
          <span id="status-<?= $transaction['OrderID'] ?>" class="text-blue-700 font-medium">Pending</span>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</tbody>

      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-center items-center gap-2">
      <span class="bg-gray-400 text-white px-3 py-1 rounded-md cursor-not-allowed">« Prev</span>
      <span class="bg-[#C4A07A] text-white px-3 py-1 rounded-md font-bold">1</span>
      <span class="bg-gray-400 text-white px-3 py-1 rounded-md cursor-not-allowed">Next »</span>
    </div>
  </main>

  <script>

  document.addEventListener("DOMContentLoaded", () => {
    const orders = document.querySelectorAll("[id^='status-']");
    orders.forEach(order => {
      const orderId = order.id.replace('status-', '');
      const savedStatus = sessionStorage.getItem(`orderStatus-${orderId}`);
      if (savedStatus) {
        order.innerHTML = savedStatus;
      }
    });
  });

    document.getElementById('logout-btn').addEventListener('click', function(e) {
      e.preventDefault();
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
          window.location.href = "../all/logoutcos.php";
        }
      });
    });
  </script>

</body>
</html>