<?php

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0");

session_start();
ob_start();

if (!isset($_SESSION['CustomerID'])) {

  if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
      header('Location: ../all/coffee.php');
      ob_end_clean(); 
      exit();
  }
}
require_once('../classes/database.php');
$con = new database();

$customerID = $_SESSION['CustomerID'];
$customerFN = $_SESSION['CustomerFN'] ?? 'Customer'; 

$customerOrders = $con->getOrdersForCustomer($customerID);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title><?= htmlspecialchars($customerFN) ?>'s Transaction Records</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 for logout -->
  <style>
    body { 
        font-family: 'Inter', sans-serif; 
        display: flex; /* Makes the body a flex container for sidebar and main content */
        background: url('../images/LAbg.png') no-repeat center center/cover; 
        min-height: 100vh;
        background-color: rgba(255, 255, 255, 0.7); /* Consistent overlay */
    }

    /* Consistent Sidebar Styling */
    .sidebar {
      width: 90px;
      background-color: #fff;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 30px;
      gap: 35px;
      box-shadow: 2px 0 5px rgba(0,0,0,0.1);
      z-index: 10; /* Ensure sidebar is above main content */
    }
    .sidebar a, .sidebar button {
      color: #4B2E0E;
      font-size: 26px;
      text-decoration: none;
      transition: color 0.3s ease;
      background: none;
      border: none;
      padding: 0;
      cursor: pointer;
    }
    .sidebar a:hover, .sidebar button:hover {
      color: #C4A07A;
    }

    /* Main content area for the transactions list */
    .main-content-area {
        flex-grow: 1; /* Allows it to take remaining width */
        display: flex;
        flex-direction: column; /* Allows content within to stack vertically */
        align-items: center; /* Centers content horizontally */
        justify-content: center; /* Centers content vertically */
        padding: 1.5rem; /* Consistent padding */
        position: relative; /* For the background image */
    }

    /* Background image for main-content-area, placed here for consistent flex layout */
    .main-content-area .bg-image {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.2;
        z-index: -10;
    }

    /* Custom scrollbar for consistency across elements */
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
<body class="min-h-screen flex">
<!-- Sidebar -->
  <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
   <img src="../images/logo.png" alt="Logo" style="width: 56px; height: 56px; border-radius: 9999px; margin-bottom: 25px;" />
   <button aria-label="Home" class="text-[#4B2E0E] text-xl" title="Home" type="button" onclick="window.location='advertisement.php'"><i class="fas fa-home"></i></button>
   <button aria-label="Cart" class="text-[#4B2E0E] text-xl" title="Cart" type="button" onclick="window.location='customerpage.php'"><i class="fas fa-shopping-cart"></i></button>
   <button aria-label="Order List" class="text-[#4B2E0E] text-xl" title="Order List" type="button" onclick="window.location='transactionrecords.php'"><i class="fas fa-list"></i></button> <!-- LINK TO CUSTOMER'S OWN TRANSACTIONS -->
   <button aria-label="Settings" class="text-[#4B2E0E] text-xl" title="Settings" type="button" onclick="window.location='../all/setting.php'"><i class="fas fa-cog"></i></button>
   <button id="logout-btn" aria-label="Logout" name="logout" class="text-[#4B2E0E] text-xl" title="Logout" type="button"><i class="fas fa-sign-out-alt"></i></button>
  </aside>
 
  <!-- Main content area for the transaction records -->
  <div class="main-content-area">
    <img src="../images/Labg.png" alt="Background image of coffee beans" class="bg-image" />

    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-8 shadow-lg max-w-2xl w-full relative z-10">
        <h1 class="text-2xl font-bold text-[#4B2E0E] mb-4 flex items-center gap-2">
            <i class="fas fa-history"></i> Your Transaction History
        </h1>
        
        <?php if (empty($customerOrders)): ?>
            <p class="text-gray-700">You have no recorded transactions yet.</p>
        <?php else: ?>
            <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                <?php foreach ($customerOrders as $order): ?>
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 shadow-sm">
                    <p class="text-sm font-semibold text-[#4B2E0E] mb-1">Order #<?= htmlspecialchars($order['OrderID']) ?></p>
                    <p class="text-xs text-gray-600 mb-2">
                        Date: <?= htmlspecialchars(date('M d, Y H:i', strtotime($order['OrderDate']))) ?>
                    </p>
                    <ul class="text-sm text-gray-700 list-disc list-inside mb-2">
                        <?php if (!empty($order['OrderItems'])): ?>
                            <li><?= nl2br(htmlspecialchars($order['OrderItems'])) ?></li>
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
    
    <div class="mt-6 flex justify-center w-full max-w-2xl relative z-10">
        <a href="customerpage.php" class="bg-[#4B2E0E] text-white px-6 py-3 rounded-full font-semibold hover:bg-[#6b3e14] transition shadow-md">Back to Menu</a>
    </div>

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
         window.location.href = "../all/logoutcos.php";
       }
     });
   });
  </script>
</body>
</html>