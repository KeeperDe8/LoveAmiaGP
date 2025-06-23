<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>LoveAmiah - Advertisement</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    :root {
      --main-color: #a17850;
      --bg-dark: #1e1e1e;
      --white: #fff;
      --spacing: 2rem;
      --card-bg: #2d2d2d;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background: url('../images/LAbg.png') no-repeat center center/cover;
      min-height: 100vh;
      color: var(--white);
      overflow-x: hidden;
    }

    a {
      text-decoration: none;
      color: inherit;
    }

    .top-bar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 5vw;
      background-color: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(8px);
      z-index: 1000;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .logo-container img {
      height: 48px;
      width: 48px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.7);
    }

    .logo-container span {
      font-size: 1.8rem;
      font-weight: bold;
    }

    .auth-buttons {
      display: flex;
      gap: 1rem;
    }

    .auth-buttons a {
      padding: 0.75rem 1.5rem;
      border: 2px solid var(--white);
      background: transparent;
      color: var(--white);
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .auth-buttons a:hover {
      background-color: rgba(255, 255, 255, 0.15);
    }

    .main-content {
      margin-top: 100px;
      padding: var(--spacing) 5vw;
      display: flex;
      flex-direction: column;
      gap: 4rem;
    }

    .hero {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      gap: 4rem;
      padding: 2rem 0;
    }

    .hero img {
      max-width: 800px; 
      width: 100%;
      flex: 1 1 45%;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    }

    .hero-text {
      flex: 1 1 50%;
      padding: 2rem;
      max-width: 700px;
    }

    .hero-text h1 {
      font-size: 4.5rem; 
      line-height: 1.2;
      margin-bottom: 1.5rem;
    }

    .hero-text h1 span {
      color: var(--main-color);
    }

    .hero-text p {
      font-size: 1.7rem; 
      margin-bottom: 2.5rem;
      line-height: 1.6;
    }

    .hero-text button {
      padding: 0.9rem 2rem;
      border: 2px solid var(--white);
      border-radius: 6px;
      background: transparent;
      color: var(--white);
      font-weight: bold;
      font-size: 1.1rem;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .hero-text button:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .coffee-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 2.5rem;
    }

    .card {
      background-color: var(--card-bg);
      border-radius: 16px;
      overflow: hidden;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    }

    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card-body {
      padding: 1.2rem;
    }

    .card h3 {
      font-size: 1.3rem;
      margin-bottom: 0.6rem;
      color: var(--main-color);
    }

    .card p {
      font-size: 1rem;
      line-height: 1.5;
    }

    @media (max-width: 768px) {
      .hero {
        flex-direction: column;
        gap: 3rem;
        padding: 1rem 0;
      }

      .hero-text {
        padding: 1rem;
        text-align: center;
      }

      .hero-text h1 {
        font-size: 2.8rem;
      }

      .hero-text p {
        font-size: 1.2rem;
      }

      .auth-buttons {
        flex-direction: column;
        gap: 0.5rem;
      }
    }

    @media (max-width: 480px) {
      .top-bar {
        flex-direction: column;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
      }

      .logo-container span {
        font-size: 1.3rem;
      }

      .main-content {
        padding: 2rem;
      }

      .coffee-cards {
        grid-template-columns: 1fr;
      }

      .hero-text h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>
<body>
  <header class="top-bar">
    <a href="#" class="logo-container">
      <img src="../images/logo.png" alt="Love Amaiah Logo" />
      <span>Love Amaiah</span>
    </a>
    <div class="auth-buttons">
      <a href="../all/registration.php">Register</a>
      <a href="../all/login.php">Login</a>
    </div>
  </header>
  
  <!-- Main content -->
  <main class="main-content">
    <section class="hero">
      <img src="../images/mainpage_coffee.png" alt="Latte Art">
      <div class="hero-text">
        <h1>Sip Happiness<br><span>One Cup at a Time</span></h1>
        <p>Begin your day with a cup of coffee—boost your energy, sharpen your focus, and set the tone for a productive, positive day ahead.</p>
        <button onclick="window.location.href='login.php'">Order Coffee</button>
      </div>
    </section>
    <!-- COFFEE CARDS -->
    <section class="coffee-cards">
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
        <img src="../images/iced_shaken_brownie.png" alt="Iced Brownie Espresso">
        <div class="card-body">
          <h3>Iced Brownie Espresso</h3>
          <p>Shaken espresso with rich brownie flavor — bold, cold, and energizing.</p>
        </div>
      </div>
    </section>
  </main>
</body>
</html>