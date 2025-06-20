<?php
session_start();
require_once('../classes/database.php');
$con = new database();


$userType = '';
$userID = null;

if (isset($_SESSION['CustomerID'])) {
    $userType = 'customer';
    $userID = $_SESSION['CustomerID'];
} elseif (isset($_SESSION['EmployeeID'])) {
    $userType = 'employee';
    $userID = $_SESSION['EmployeeID'];
} elseif (isset($_SESSION['OwnerID'])) {
    $userType = 'owner';
    $userID = $_SESSION['OwnerID'];
} else {
   
    header('Location: login.php');
    exit();
}

$saved = false;
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pass the user details and all POST data to the update method
    if ($con->updateUserData($userID, $userType, $_POST)) {
        $saved = true;
    }
}

// Fetch user data using the new standardized method
$userData = $con->getUserData($userID, $userType);

// If user data could not be fetched, show an error
if (empty($userData)) {
    echo "Error: User could not be found. Please try logging in again.";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: url('../images/LAbg.png') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="bg-white/90 rounded-2xl shadow-xl p-8 w-full max-w-md">
        <!-- Back Button -->
        <div class="mb-4">
          <?php if ($userType === 'customer'): ?>
            <a href="../Customer/customerpage.php" class="inline-flex items-center px-4 py-2 bg-[#c19a6b] text-white rounded-lg hover:bg-[#a17850] transition">
              ← Back to Customer Page
            </a>
          <?php elseif ($userType === 'employee'): ?>
            <a href="../Employee/employesmain.php" class="inline-flex items-center px-4 py-2 bg-[#c19a6b] text-white rounded-lg hover:bg-[#a17850] transition">
              ← Back to Employee Page
            </a>
          <?php elseif ($userType === 'owner'): ?>
            <a href="../Owner/page.php" class="inline-flex items-center px-4 py-2 bg-[#c19a6b] text-white rounded-lg hover:bg-[#a17850] transition">
              ← Back to Owner Page
            </a>
          <?php endif; ?>
        </div>
        <h2 class="text-2xl font-bold text-[#4B2E0E] mb-6 text-center">Account Settings</h2>
        
        <?php if ($saved): ?>
        <script>
            // Use DOMContentLoaded to ensure the script runs after the page is loaded
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire('Success', 'Profile updated successfully!', 'success');
            });
        </script>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($userData['username'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($userData['name'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($userData['phone'] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">New Password <span class="text-xs text-gray-400">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" placeholder="Enter new password">
            </div>
            <button type="submit" class="w-full bg-[#c19a6b] hover:bg-[#a17850] text-white font-semibold py-2 rounded-lg transition">Save Changes</button>
        </form>
    </div>
</body>
</html>