<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Revenue & Stock</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">

<!-- Sidebar -->
<aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <button title="Home" onclick="window.location='page.php'" class="text-[#4B2E0E] text-xl"><i class="fas fa-home"></i></button>
  <button title="Users" onclick="window.location='user.php'" class="text-[#4B2E0E] text-xl"><i class="fas fa-users"></i></button>
  <button title="Dashboard" onclick="window.location='dashboard.php'" class="text-[#4B2E0E] text-xl"><i class="fas fa-chart-bar"></i></button> 
</aside>

<!-- Main Content -->
<main class="flex-1 p-6">
  <header class="mb-6">
    <h1 class="text-[#4B2E0E] font-semibold text-xl">Dashboard Overview</h1>
    <p class="text-xs text-gray-500">Summary of revenue and stock count</p>
  </header>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Revenue Chart -->
    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-4 shadow-lg">
      <h2 class="text-md font-semibold text-[#4B2E0E] mb-3">Monthly Revenue</h2>
      <canvas id="revenueChart" height="200"></canvas>
    </div>

    <!-- Stock Chart -->
    <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-lg p-4 shadow-lg">
      <h2 class="text-md font-semibold text-[#4B2E0E] mb-3">Available Stock</h2>
      <canvas id="stockChart" height="200"></canvas>
    </div>
  </div>
</main>

<script>
// Dummy Data for Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
  type: 'line',
  data: {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
    datasets: [{
      label: 'Revenue (PHP)',
      data: [5000, 7000, 4000, 8000, 12000, 9000],
      backgroundColor: 'rgba(75, 46, 14, 0.2)',
      borderColor: '#4B2E0E',
      borderWidth: 2,
      tension: 0.4,
      fill: true
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: { beginAtZero: true }
    }
  }
});

const stockCtx = document.getElementById('stockChart').getContext('2d');
new Chart(stockCtx, {
  type: 'bar',
  data: {
    labels: ['Coffee Beans', 'Milk', 'Cups', 'Syrup', 'Sugar'],
    datasets: [{
      label: 'Stock Count',
      data: [120, 80, 200, 60, 90],
      backgroundColor: '#4B2E0E'
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false }
    },
    scales: {
      y: { beginAtZero: true }
    }
  }
});
</script>

</body>
</html>