<?php
session_start();
if (!isset($_SESSION['OwnerID'])) {
  header('Location: login.php');
  exit();
}
$ownerName = $_SESSION['OwnerFN'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Main Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-image: url('images/LAbg.png');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
    }
    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-thumb {
      background-color: #c4b09a;
      border-radius: 10px;
    }
  </style>
</head>
<body class="min-h-screen flex text-[#4B2E0E]">

  <!-- Sidebar -->
  <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
    <img src="images/logo.png" alt="Logo" class="w-10 h-10 rounded-full mb-4" />
    <button title="Home" onclick="window.location='mainpage.php'"><i class="fas fa-home text-xl"></i></button>
    <button title="Orders" onclick="window.location='page.php'"><i class="fas fa-shopping-cart text-xl"></i></button>
    <button title="Inventory" onclick="window.location='inventory.php'"><i class="fas fa-box text-xl"></i></button>
    <button title="Reports" onclick="window.location='reports.php'"><i class="fas fa-chart-bar text-xl"></i></button>
    <button title="Users" onclick="window.location='user.php'"><i class="fas fa-users text-xl"></i></button>
    <button title="Menu" onclick="window.location='menu.php'"><i class="fas fa-bars text-xl"></i></button>
    <button title="Settings" onclick="window.location='settings.php'"><i class="fas fa-cog text-xl"></i></button>
    <button id="logout-btn" title="Logout"><i class="fas fa-sign-out-alt text-xl"></i></button>
  </aside>

  <!-- Main content -->
  <main class="flex-1 p-10 relative flex flex-col justify-center items-center text-center">
    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg p-10 max-w-3xl w-full">
      <h1 class="text-3xl font-extrabold mb-2">Welcome, <?php echo htmlspecialchars($ownerName); ?> 👋</h1>
      <p class="text-gray-700">This is your café dashboard. Use the sidebar to manage employees, view reports, check orders, and more.</p>
      <div class="mt-6 text-sm text-gray-400">Group 49 © 2025</div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
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
          window.location.href = 'logout.php';
        }
      });
    });
  </script>
</body>
</html>
