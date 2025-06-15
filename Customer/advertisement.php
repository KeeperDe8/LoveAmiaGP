<?php
session_start();
if (!isset($_SESSION['CustomerID'])) {
  header('Location: login.php');
  exit();
}
$customer = $_SESSION['CustomerFN'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LoveAmiah - Advertisement</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      background: url('../images/LAbg.png') no-repeat center center/cover;
      min-height: 100vh;
    }

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
    }

    .sidebar a {
      color: #4B2E0E;
      font-size: 26px;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .sidebar a:hover {
      color: #C4A07A;
    }

    .main-content {
      flex-grow: 1;
      padding: 5vw;
      color: white;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .hero {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      margin-bottom: 50px;
      gap: 60px;
    }

    .hero img {
      width: 100%;
      max-width: 600px;
      border-radius: 12px;
    }

    .hero-text {
      max-width: 650px;
      text-align: left;
    }

    .hero-text h1 {
      font-size: 3.5em;
      font-weight: bold;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .hero-text h1 span {
      color: #a17850;
    }

    .hero-text p {
      font-size: 1.4em;
      margin-bottom: 30px;
    }

    .hero-text button {
      padding: 14px 32px;
      font-size: 1.1em;
      border: 2px solid white;
      border-radius: 6px;
      background: transparent;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .hero-text button:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .coffee-cards {
      display: flex;
      gap: 35px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .card {
      width: 260px;
      background-color: #333;
      border-radius: 12px;
      overflow: hidden;
      color: white;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-6px);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card-body {
      padding: 16px;
    }

    .card h3 {
      font-size: 1.4em;
      margin-bottom: 10px;
    }

    @media (max-width: 1024px) {
      .hero {
        flex-direction: column;
        text-align: center;
      }

      .hero-text {
        max-width: 100%;
      }

      .hero-text h1 {
        font-size: 2.5em;
      }
    }

    @media (max-width: 768px) {
      .sidebar {
        display: none;
      }

      .main-content {
        padding: 30px;
      }

      .card {
        width: 100%;
        max-width: 320px;
      }
    }

    @media (max-width: 480px) {
      .hero-text h1 {
        font-size: 1.8em;
      }

      .hero-text button {
        width: 100%;
        margin-top: 10px;
      }

      .coffee-cards {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <a href="advertisement.php" title="Home"><i class="fas fa-house"></i></a>
    <a href="../Customer/customerpage.php" title="Cart"><i class="fas fa-cart-shopping"></i></a>
    <a href="../all/setting.php" title="Settings"><i class="fas fa-gear"></i></a>
    <a href="../all/logout.php" title="Logout"><i class="fas fa-right-from-bracket"></i></a>
  </div>

  <!-- Main content -->
  <div class="main-content">
    <div class="hero">
      <img src="../images/mainpage_coffee.png" alt="Latte Art">
      <div class="hero-text">
        <h1>Sip Happiness<br><span>One Cup at a Time</span></h1>
        <p>Begin your day with a cup of coffee—boost your energy, sharpen your focus, and set the tone for a productive, positive day ahead.</p>
        <button onclick="window.location.href='customerpage.php'">Order Coffee</button>
      </div>
    </div>

    <div class="coffee-cards">
      <div class="card">
        <img src="../images/affogato.png" alt="Affogato">
        <div class="card-body">
          <h3>Affogato</h3>
          <p>Espresso poured over vanilla ice cream — bold, creamy, and decadent.</p>
        </div>
      </div>

      <div class="card">
        <img src="../images/caramel_cloud_latte.png" alt="Caramel Cloud Latte">
        <div class="card-body">
          <h3>Caramel Cloud Latte</h3>
          <p>Fluffy foam, bold espresso, and silky caramel — heavenly in every sip.</p>
        </div>
      </div>

      <div class="card">
        <img src="../images/cinnamon_macchiato.png" alt="Cinnamon Macchiato">
        <div class="card-body">
          <h3>Cinnamon Macchiato</h3>
          <p>Warm cinnamon meets espresso and milk — sweet, spicy, and smooth.</p>
        </div>
      </div>

      <div class="card">
        <img src="../images/iced_shaken_brownie.png" alt="Iced Shaken Brownie">
        <div class="card-body">
          <h3>Iced Brownie Espresso</h3>
          <p>Shaken espresso with rich brownie flavor — bold, cold, and energizing.</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>