<?php
session_start();
require_once('../classes/database.php');
$con = new database();

// Allow access if any user is logged in, otherwise redirect to login
if (
    !isset($_SESSION['CustomerID']) &&
    !isset($_SESSION['EmployeeID']) &&
    !isset($_SESSION['OwnerID'])
) {
    header('Location: login.php');
    exit();
}

$userType = '';
$userData = [];

if (isset($_SESSION['CustomerID'])) {
    $userType = 'customer';
    $userID = $_SESSION['CustomerID'];
    $stmt = $con->opencon()->prepare("SELECT * FROM customer WHERE CustomerID = ?");
    $stmt->execute([$userID]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_SESSION['EmployeeID'])) {
    $userType = 'employee';
    $userID = $_SESSION['EmployeeID'];
    $stmt = $con->opencon()->prepare("SELECT * FROM employee WHERE EmployeeID = ?");
    $stmt->execute([$userID]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif (isset($_SESSION['OwnerID'])) {
    $userType = 'owner';
    $userID = $_SESSION['OwnerID'];
    $stmt = $con->opencon()->prepare("SELECT * FROM owner WHERE OwnerID = ?");
    $stmt->execute([$userID]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (!$userData) {
    echo "<div class='text-red-500 font-bold mb-4'>User not found. Please re-login.</div>";
    exit();
}

$saved = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $db = $con->opencon();

    if ($userType === 'customer') {
        $sql = "UPDATE customer SET C_Username=?, CustomerFN=?, C_Email=?, C_PhoneNumber=?" . ($password ? ", C_Password=?" : "") . " WHERE CustomerID=?";
        $params = [$username, $name, $email, $phone];
        if ($password) $params[] = password_hash($password, PASSWORD_DEFAULT);
        $params[] = $userID;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    } elseif ($userType === 'employee') {
        $sql = "UPDATE employee SET E_Username=?, EmployeeFN=?, E_Email=?, E_PhoneNumber=?" . ($password ? ", E_Password=?" : "") . " WHERE EmployeeID=?";
        $params = [$username, $name, $email, $phone];
        if ($password) $params[] = password_hash($password, PASSWORD_DEFAULT);
        $params[] = $userID;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    } elseif ($userType === 'owner') {
        $sql = "UPDATE owner SET Username=?, OwnerFN=?, O_Email=?, O_PhoneNumber=?" . ($password ? ", O_Password=?" : "") . " WHERE OwnerID=?";
        $params = [$username, $name, $email, $phone];
        if ($password) $params[] = password_hash($password, PASSWORD_DEFAULT);
        $params[] = $userID;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }
    $saved = true;
    // Refresh user data after update
    if ($userType === 'customer') {
        $stmt = $con->opencon()->prepare("SELECT * FROM customer WHERE CustomerID = ?");
        $stmt->execute([$userID]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($userType === 'employee') {
        $stmt = $con->opencon()->prepare("SELECT * FROM employee WHERE EmployeeID = ?");
        $stmt->execute([$userID]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    } elseif ($userType === 'owner') {
        $stmt = $con->opencon()->prepare("SELECT * FROM owner WHERE OwnerID = ?");
        $stmt->execute([$userID]);
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    }
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
          <?php if (isset($_SESSION['CustomerID'])): ?>
            <a href="../Customer/customerpage.php" class="inline-flex items-center px-4 py-2 bg-[#c19a6b] text-white rounded-lg hover:bg-[#a17850] transition">
              &larr; Back to Customer Page
            </a>
          <?php elseif (isset($_SESSION['EmployeeID'])): ?>
            <a href="../Employee/employesmain.php" class="inline-flex items-center px-4 py-2 bg-[#c19a6b] text-white rounded-lg hover:bg-[#a17850] transition">
              &larr; Back to Employee Page
            </a>
          <?php elseif (isset($_SESSION['OwnerID'])): ?>
            <a href="../Owner/page.php" class="inline-flex items-center px-4 py-2 bg-[#c19a6b] text-white rounded-lg hover:bg-[#a17850] transition">
              &larr; Back to Owner Page
            </a>
          <?php endif; ?>
        </div>
        <h2 class="text-2xl font-bold text-[#4B2E0E] mb-6 text-center">Account Settings</h2>
        <?php if ($saved): ?>
        <script>
            Swal.fire('Success', 'Profile updated!', 'success');
        </script>
        <?php endif; ?>
        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($userData[$userType === 'customer' ? 'C_Username' : ($userType === 'employee' ? 'E_Username' : 'Username')] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
                <span class="text-xs text-gray-500">Current: <?php echo htmlspecialchars($userData[$userType === 'customer' ? 'C_Username' : ($userType === 'employee' ? 'E_Username' : 'Username')] ?? 'Not set'); ?></span>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Name</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($userData[$userType === 'customer' ? 'CustomerFN' : ($userType === 'employee' ? 'EmployeeFN' : 'OwnerFN')] ?? ''); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
                <span class="text-xs text-gray-500">Current: <?php echo htmlspecialchars($userData[$userType === 'customer' ? 'CustomerFN' : ($userType === 'employee' ? 'EmployeeFN' : 'OwnerFN')] ?? 'Not set'); ?></span>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars(
                    $userData[
                        $userType === 'customer' ? 'C_Email' :
                        ($userType === 'employee' ? 'E_Email' : 'O_Email')
                    ] ?? ''
                ); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
                <span class="text-xs text-gray-500">Current: <?php echo htmlspecialchars(
                    $userData[
                        $userType === 'customer' ? 'C_Email' :
                        ($userType === 'employee' ? 'E_Email' : 'O_Email')
                    ] ?? 'Not set'
                ); ?></span>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">Phone Number</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars(
                    $userData[
                        $userType === 'customer' ? 'C_PhoneNumber' :
                        ($userType === 'employee' ? 'E_PhoneNumber' : 'O_PhoneNumber')
                    ] ?? ''
                ); ?>" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" required>
                <span class="text-xs text-gray-500">Current: <?php echo htmlspecialchars(
                    $userData[
                        $userType === 'customer' ? 'C_PhoneNumber' :
                        ($userType === 'employee' ? 'E_PhoneNumber' : 'O_PhoneNumber')
                    ] ?? 'Not set'
                ); ?></span>
            </div>
            <div>
                <label class="block text-[#4B2E0E] font-semibold mb-1">New Password <span class="text-xs text-gray-400">(leave blank to keep current)</span></label>
                <input type="password" name="password" class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-[#c19a6b] focus:outline-none" placeholder="New password">
            </div>
            <button type="submit" class="w-full bg-[#c19a6b] hover:bg-[#a17850] text-white font-semibold py-2 rounded-lg transition">Save Changes</button>
        </form>
    </div>
</body>
</html>