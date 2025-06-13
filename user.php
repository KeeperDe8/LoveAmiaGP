
<?php 
session_start();

if (!isset($_SESSION['OwnerID'])) {
  header('Location: login.php');
  exit();
}

require_once('../classes/database.php');
$con = new database();
$sweetAlertConfig = "";

// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Add employee
if (isset($_POST['add_employee'])) {
  $owerID = $_SESSION['OwnerID'];
  $firstF = $_POST['firstF'];
  $firstN = $_POST['firstN'];
  $role = $_POST['role'];
  $number = $_POST['number'];
  $emailN = $_POST['email'];
  $Euser = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

  $userID = $con->addEmployee($firstF, $firstN, $Euser, $password, $role, $emailN,  $number, $owerID);

  if ($userID) {
    $sweetAlertConfig = "
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'success',
        title: 'Success',
        text: 'Employee added.',
        confirmButtonText: 'OK'
      }).then(() => {
        window.location.href = 'user.php';
      });
    });
    </script>";
  } else {
    $sweetAlertConfig = "
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Failed to add employee.',
        confirmButtonText: 'OK'
      });
    });
    </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Employee List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { font-family: 'Inter', sans-serif; }
    #menu-scroll::-webkit-scrollbar { width: 6px; }
    #menu-scroll::-webkit-scrollbar-thumb { background-color: #c4b09a; border-radius: 10px; }
    .swal-feedback { color: #dc3545; font-size: 13px; text-align: left; display: block; margin-bottom: 5px; }
    .swal2-input.is-valid { border: 2px solid #198754 !important; }
    .swal2-input.is-invalid { border: 2px solid #dc3545 !important; }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">

<!-- Sidebar -->
<aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <button title="Home" onclick="window.location='page.php'" class="text-[#4B2E0E] text-xl"><i class="fas fa-home"></i></button>
  <button title="Users" class="text-[#4B2E0E] text-xl"><i class="fas fa-users"></i></button>
  <button id="logout-btn" title="Logout" class="text-[#4B2E0E] text-xl"><i class="fas fa-sign-out-alt"></i></button>
</aside>

<!-- Main content -->
<main class="flex-1 p-6 relative flex flex-col">
  <header class="mb-4 flex items-center justify-between">
    <div>
      <h1 class="text-[#4B2E0E] font-semibold text-xl mb-1">Employee List</h1>
      <p class="text-xs text-gray-400">Manage your employees here</p>
    </div>
    <a href="#" id="add-employee-btn" class="bg-[#4B2E0E] text-white rounded-full px-5 py-2 text-sm font-semibold shadow-md hover:bg-[#6b3e14] transition flex items-center">
      <i class="fas fa-user-plus mr-2"></i>Add Employee
    </a>
  </header>

  <section class="bg-white bg-opacity-90 backdrop-blur-sm rounded-xl p-4 max-w-6xl shadow-lg flex-1 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-[#4B2E0E] border-b">
          <th class="py-2 px-3">#</th>
          <th class="py-2 px-3">Name</th>
          <th class="py-2 px-3">Role</th>
          <th class="py-2 px-3">Phone</th>
          <th class="py-2 px-3">Email</th>
          <th class="py-2 px-3">Username</th>
          <th class="py-2 px-3">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $employees = $con->getEmployee();
        foreach ($employees as $employee) {
        ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="py-2 px-3"><?= $employee['EmployeeID'] ?></td>
          <td class="py-2 px-3"><?= $employee['EmployeeFN'] . ' ' . $employee['EmployeeLN'] ?></td>
          <td class="py-2 px-3"><?= $employee['Role'] ?></td>
          <td class="py-2 px-3"><?= $employee['E_PhoneNumber'] ?></td>
          <td class="py-2 px-3"><?= $employee['E_Email'] ?></td>
          <td class="py-2 px-3"><?= $employee['E_Username'] ?></td>
          <td class="py-2 px-3">
            <a href="#" class="text-blue-600 hover:underline text-xs mr-2"><i class="fas fa-edit"></i></a>
            <a href="#" class="text-red-600 hover:underline text-xs"><i class="fas fa-trash"></i></a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>

  <!-- Hidden form -->
  <form id="add-employee-form" method="POST" style="display:none;">
    <input type="hidden" name="firstF" id="form-firstF">
    <input type="hidden" name="firstN" id="form-firstN">
    <input type="hidden" name="role" id="form-role">
    <input type="hidden" name="number" id="form-number">
    <input type="hidden" name="email" id="form-email">
    <input type="hidden" name="username" id="form-username">
    <input type="hidden" name="password" id="form-password">
    <input type="hidden" name="add_employee" value="1">
  </form>

  <?= $sweetAlertConfig ?>
</main>

<script>
// Validation functions
const isNotEmpty = (value) => value.trim() !== '';
const isPasswordValid = (value) => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/.test(value);
const isPhoneValid = (value) => /^09\d{9}$/.test(value);

// Helper for field state
function setSwalFieldState(field, isValid, message) {
  if (isValid) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    field.style.borderColor = '#198754';
    field.nextElementSibling.textContent = '';
  } else {
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    field.style.borderColor = '#dc3545';
    field.nextElementSibling.textContent = message;
  }
}

// Real-time email check
function checkEmployeeEmailAvailability(emailField, confirmBtn) {
  emailField.addEventListener('input', () => {
    const email = emailField.value.trim();
    if (email === '') {
      setSwalFieldState(emailField, false, 'Email is required.');
      confirmBtn.disabled = true;
      return;
    }
    fetch('ajax/check_employeemail.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `email=${encodeURIComponent(email)}`
    })
      .then(res => res.json())
      .then(data => {
        if (data.exists) {
          setSwalFieldState(emailField, false, 'Email is already taken.');
          confirmBtn.disabled = true;
        } else {
          setSwalFieldState(emailField, true, '');
          confirmBtn.disabled = false;
        }
      })
      .catch(() => {
        setSwalFieldState(emailField, false, 'Error checking email.');
        confirmBtn.disabled = true;
      });
  });
}

// Real-time username check
function checkEmployeeUsernameAvailability(usernameField, confirmBtn) {
  usernameField.addEventListener('input', () => {
    const username = usernameField.value.trim();
    if (username ===''){
      usernameField.classList.remove('is-valid');
      usernameField.classList.add('is-invalid');
      usernameField.nextElementSibling.textContent = 'Username is required.';
      confirmBtn.disabled = true;
      return;
    }
    fetch('ajax/check_employename.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `username=${encodeURIComponent(username)}`
    })
      .then((response)=>response.json())
      .then((data)=>{
        if (data.exists){
          usernameField.classList.remove('is-valid');
          usernameField.classList.add('is-invalid');
          usernameField.nextElementSibling.textContent = 'Username is already taken.';
          confirmBtn.disabled = true;
        }else {
          usernameField.classList.remove('is-invalid');
          usernameField.classList.add('is-valid');
          usernameField.nextElementSibling.textContent = '';
          confirmBtn.disabled = false;
        }
      })
      .catch((error)=>{
        console.error('Error:', error);
        confirmBtn.disabled = true;
      });
  });
}

document.getElementById('add-employee-btn').addEventListener('click', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Add Employee',
    html:
      `<input id="swal-emp-fname" class="swal2-input" placeholder="First Name">
       <input id="swal-emp-lname" class="swal2-input" placeholder="Last Name">
       <select id="swal-emp-role" class="swal2-input">
          <option value="" disabled selected>Select Role</option>
          <option value="Barista">Barista</option>
          <option value="Cashier">Cashier</option>
        </select>
       <input id="swal-emp-phone" class="swal2-input" placeholder="Phone Number">
       <input id="swal-emp-email" class="swal2-input" type="email" placeholder="Email">
       <span class="swal-feedback"></span>
       <input id="swal-emp-username" class="swal2-input" placeholder="Username">
       <span class="swal-feedback"></span>
       <input id="swal-emp-password" class="swal2-input" type="password" placeholder="Password">`,
    showCancelButton: true,
    confirmButtonText: 'Add',
    preConfirm: () => {
      const firstF = document.getElementById('swal-emp-fname').value.trim();
      const firstN = document.getElementById('swal-emp-lname').value.trim();
      const role = document.getElementById('swal-emp-role').value;
      const number = document.getElementById('swal-emp-phone').value.trim();
      const email = document.getElementById('swal-emp-email').value.trim();
      const username = document.getElementById('swal-emp-username').value.trim();
      const password = document.getElementById('swal-emp-password').value;

      if (!firstF || !firstN || !role || !number || !email || !username || !password) {
        Swal.showValidationMessage('All fields are required');
        return false;
      }
      if (!isPhoneValid(number)) {
        Swal.showValidationMessage('Invalid Philippine phone number');
        return false;
      }
      if (!isPasswordValid(password)) {
        Swal.showValidationMessage('Password must have at least 6 characters, 1 uppercase, 1 number, and 1 special character');
        return false;
      }
      // Check if username/email fields are marked invalid
      if (
        document.getElementById('swal-emp-email').classList.contains('is-invalid') ||
        document.getElementById('swal-emp-username').classList.contains('is-invalid')
      ) {
        Swal.showValidationMessage('Please fix the errors in the form');
        return false;
      }

      document.getElementById('form-firstF').value = firstF;
      document.getElementById('form-firstN').value = firstN;
      document.getElementById('form-role').value = role;
      document.getElementById('form-number').value = number;
      document.getElementById('form-email').value = email;
      document.getElementById('form-username').value = username;
      document.getElementById('form-password').value = password;

      return true;
    },
    didOpen: () => {
      const emailField = document.getElementById('swal-emp-email');
      const usernameField = document.getElementById('swal-emp-username');
      const phoneField = document.getElementById('swal-emp-phone');
      const passwordField = document.getElementById('swal-emp-password');
      const confirmBtn = document.querySelector('.swal2-confirm');

      // Add feedback spans if not present
      if (!emailField.nextElementSibling || !emailField.nextElementSibling.classList.contains('swal-feedback')) {
        const span = document.createElement('span');
        span.className = 'swal-feedback';
        emailField.parentNode.insertBefore(span, emailField.nextSibling);
      }
      if (!usernameField.nextElementSibling || !usernameField.nextElementSibling.classList.contains('swal-feedback')) {
        const span = document.createElement('span');
        span.className = 'swal-feedback';
        usernameField.parentNode.insertBefore(span, usernameField.nextSibling);
      }

      checkEmployeeEmailAvailability(emailField, confirmBtn);
      checkEmployeeUsernameAvailability(usernameField, confirmBtn);

      // Real-time phone validation
      phoneField.addEventListener('input', () => {
        const value = phoneField.value.trim();
        if (value === '') {
          phoneField.classList.remove('is-valid', 'is-invalid');
          phoneField.style.borderColor = '';
        } else if (isPhoneValid(value)) {
          phoneField.classList.remove('is-invalid');
          phoneField.classList.add('is-valid');
          phoneField.style.borderColor = '#198754';
        } else {
          phoneField.classList.remove('is-valid');
          phoneField.classList.add('is-invalid');
          phoneField.style.borderColor = '#dc3545';
        }
      });

      // Real-time password validation
      passwordField.addEventListener('input', () => {
        const value = passwordField.value.trim();
        if (value === '') {
          passwordField.classList.remove('is-valid', 'is-invalid');
          passwordField.style.borderColor = '';
        } else if (isPasswordValid(value)) {
          passwordField.classList.remove('is-invalid');
          passwordField.classList.add('is-valid');
          passwordField.style.borderColor = '#198754';
        } else {
          passwordField.classList.remove('is-valid');
          passwordField.classList.add('is-invalid');
          passwordField.style.borderColor = '#dc3545';
        }
      });
    }
  }).then((result) => {
    if (result.isConfirmed) {
      document.getElementById('add-employee-form').submit();
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
      window.location.href = 'logoutOE.php';
    }
  });
});
</script>
</body>
</html>