
<?php
session_start();
require_once('classes/database.php');
$con = new database();

$sweetAlertConfig = "";
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Try employee login
    $user = $con->loginEmployee($username, $password);
    if ($user) {
        $_SESSION['EmployeeID'] = $user['EmployeeID'];
        $_SESSION['role'] = 'employee';

        $sweetAlertConfig = "
        <script>
        Swal.fire({
          icon: 'success',
          title: 'Login Successful',
          text: 'Welcome, " . addslashes(htmlspecialchars($user['E_Username'])) . "!',
          confirmButtonText: 'Continue'
        }).then(() => {
          window.location.href = 'employeepage.php';
        });
        </script>";
    } else {
        // Try owner login
        $admin = $con->loginOwner($username, $password);
        if ($admin) {
            $_SESSION['OwnerID'] = $admin['OwnerID'];
            $_SESSION['OwnerFN'] = $admin['OwnerFN'];
            $_SESSION['role'] = 'owner';

            $sweetAlertConfig = "
            <script>
            Swal.fire({
              icon: 'success',
              title: 'Login Successful',
              text: 'Welcome, " . addslashes(htmlspecialchars($admin['OwnerFN'])) . "!',
              confirmButtonText: 'Continue'
            }).then(() => {
              window.location.href = 'mainpage.php';
            });
            </script>";
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Login - Love Amaiah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&amp;display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="./package/dist/sweetalert2.css">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex items-center justify-center">
  <div class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl shadow-lg p-8 w-full max-w-sm">
    <div class="flex flex-col items-center mb-6">
      <i class="fas fa-user-tie text-4xl text-[#4B2E0E] mb-2"></i>
      <h1 class="text-2xl font-bold text-[#4B2E0E]">Staff Login</h1>
      <p class="text-xs text-gray-400">Employee & Owner Only</p>
    </div>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-sm"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST" autocomplete="off">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-semibold mb-1" for="username">Username</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#4B2E0E]" type="text" id="username" name="username" required>
      </div>
      <div class="mb-6">
        <label class="block text-gray-700 text-sm font-semibold mb-1" for="password">Password</label>
        <input class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-[#4B2E0E]" type="password" id="password" name="password" required>
      </div>
      <button class="w-full bg-[#4B2E0E] text-white py-2 rounded font-semibold hover:bg-[#6b3e14] transition" type="submit">
        Login
      </button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php echo $sweetAlertConfig; ?>
</body>
</html>