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
      background: url('../images/LAbg.png') no-repeat center center/cover;
      min-height: 100vh;
    }

    .top-bar {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 5vw;
      background-color: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(8px);
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .logo-container {
      display: flex;
      align-items: center;
      gap: 15px;
      text-decoration: none;
    }

    /* --- CSS FOR THE LOGO IMAGE --- */
    .logo-container img {
      height: 40px;
      width: 40px; /* Make width and height equal for a perfect circle */
      border-radius: 50%; /* This is the key property that makes it round */
      object-fit: cover; /* Ensures the image covers the circle area without distortion */
      border: 2px solid rgba(255, 255, 255, 0.7); /* Optional: adds a nice border */
    }

    .logo-container span {
      font-size: 1.5em;
      font-weight: bold;
      color: white;
    }

    .login-button {
      padding: 12px 28px;
      font-size: 1em;
      border: 2px solid white;
      border-radius: 6px;
      background: transparent;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
    }

    .login-button:hover {
      background-color: rgba(255, 255, 255, 0.2);
    }

    .main-content {
      margin-top: 80px; 
      flex-grow: 1;
      padding: 5vw;
      color: white;
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
      .main-content {
        padding: 30px;
      }
      .card {
        width: 100%;
        max-width: 320px;
      }
    }

    @media (max-width: 480px) {
      .top-bar { padding: 10px 20px; }
      .main-content { margin-top: 70px; }
      .logo-container span { font-size: 1.2em; }
      .logo-container img { height: 30px; width: 30px;}
      .login-button { padding: 8px 18px; font-size: 0.9em; }
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

  <!-- TOP BAR HEADER -->
  <header class="top-bar">
    <a href="#" class="logo-container">
      <!-- IMPORTANT: Change this src to the path of your actual logo -->
      <img src="../images/logo.png" alt="Love Amaiah Logo" />
      <span>Love Amaiah</span>
    </a>
    <a href="../all/login.php" class="login-button">Login</a>
  </header>
  
  <!-- Main content -->
  <div class="main-content">
    <div class="hero">
      <img src="../images/mainpage_coffee.png" alt="Latte Art">
      <div class="hero-text">
        <h1>Sip Happiness<br><span>One Cup at a Time</span></h1>
        <p>Begin your day with a cup of coffee—boost your energy, sharpen your focus, and set the tone for a productive, positive day ahead.</p>
        <button onclick="window.location.href='login.php'">Order Coffee</button>
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