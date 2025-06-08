<?php
session_start();

if (!isset($_SESSION['OwnerID'])) {
  header('Location: login.php');
  exit();
}

require_once('classes/database.php');
$con = new database();
$sweetAlertConfig = "";

// Debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form
if (isset($_POST['add_employee'])) {
  $owerID = $_SESSION['OwnerID'];
  $firstF = $_POST['firstF'];
  $firstN = $_POST['firstN'];
  $role = $_POST['role'];
  $date = $_POST['date']; // YYYY-MM-DD
  $timeS = $_POST['timeS']; // HH:MM
  $timeE = $_POST['timeE']; // HH:MM
  $number = $_POST['number'];
  $email = $_POST['email'];

  // Combine date and time for DB timestamp
  $hireDate = $date . ' 00:00:00';
  $shiftStart = $date . ' ' . $timeS . ':00';
  $shiftEnd = $date . ' ' . $timeE . ':00';

  $userID = $con->addEmployee($firstF, $firstN, $role, $hireDate, $shiftStart, $shiftEnd, $number, $email, $owerID);

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
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <title>Employee List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
    #menu-scroll::-webkit-scrollbar {
      width: 6px;
    }
    #menu-scroll::-webkit-scrollbar-thumb {
      background-color: #c4b09a;
      border-radius: 10px;
    }
  </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">

  <!-- Sidebar -->
  <aside class="bg-white bg-opacity-90 backdrop-blur-sm w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
    <button aria-label="Home" class="text-[#4B2E0E] text-xl" title="Home" onclick="window.location='page.php'">
      <i class="fas fa-home"></i>
    </button>
    <button aria-label="Users" class="text-[#4B2E0E] text-xl" title="Users">
      <i class="fas fa-users"></i>
   <button id="logout-btn" aria-label="Logout" class="text-[#4B2E0E] text-xl" title="Logout">
      <i class="fas fa-sign-out-alt"></i>
   </button>
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
            <th class="py-2 px-3">Hire Date</th>
            <th class="py-2 px-3">Shift Start</th>
            <th class="py-2 px-3">Shift End</th>
            <th class="py-2 px-3">Phone</th>
            <th class="py-2 px-3">Email</th>
            <th class="py-2 px-3">Actions</th>
          </tr>
        </thead>
        <tbody>

              <?php
            // Fetch Emplyoee from database
                 $employees =$con->getEmployee();
                foreach($employees as $employee){
                

            
            
                 ?>

          <tr class="border-b hover:bg-gray-50">
            <td class="py-2 px-3"><?php echo $employee['EmployeeID'] ?></td>
            <td class="py-2 px-3"><?php echo $employee['EmployeeFN'] .' '. $employee['EmployeeLN'] ?></td>
            <td class="py-2 px-3"><?php echo $employee['Role'] ?></td>
            <td class="py-2 px-3"><?php echo $employee['HireDate'] ?></td>
            <td class="py-2 px-3"><?php echo $employee['ShiftStart'] ?></td>
            <td class="py-2 px-3"><?php echo $employee['ShiftEnd'] ?></td>
            <td class="py-2 px-3"><?php echo $employee['E_PhoneNumber'] ?></td>
            <td class="py-2 px-3"><?php echo $employee['E_Email'] ?></td>
            <td class="py-2 px-3">
              <a href="#" class="text-blue-600 hover:underline text-xs mr-2" title="Edit"><i class="fas fa-edit"></i></a>
              <a href="#" class="text-red-600 hover:underline text-xs" title="Delete"><i class="fas fa-trash"></i></a>
            </td>
          </tr>

           <?php 
                //Closing foreach uses php to close as well
            }  
            ?>
                  </thead>
        </tbody>
      </table>
    </section>

    <!-- Hidden form -->
<form id="add-employee-form" method="POST" style="display:none;">
  <input type="hidden" name="firstF" id="form-firstF">
  <input type="hidden" name="firstN" id="form-firstN">
  <input type="hidden" name="role" id="form-role">
  <input type="hidden" name="date" id="form-date">
  <input type="hidden" name="timeS" id="form-timeS">
  <input type="hidden" name="timeE" id="form-timeE">
  <input type="hidden" name="number" id="form-number">
  <input type="hidden" name="email" id="form-email">
  <input type="hidden" name="add_employee" value="1">
</form>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('add-employee-btn').addEventListener('click', function (e) {
  e.preventDefault();
  Swal.fire({
    title: 'Add Employee',
    html:
      `<input id="swal-emp-fname" class="swal2-input" placeholder="First Name">
       <input id="swal-emp-lname" class="swal2-input" placeholder="Last Name">
       <input id="swal-emp-role" class="swal2-input" placeholder="Role">
       <input id="swal-emp-hiredate" class="swal2-input" type="date">
       <input id="swal-emp-shiftstart" class="swal2-input" type="time">
       <input id="swal-emp-shiftend" class="swal2-input" type="time">
       <input id="swal-emp-phone" class="swal2-input" placeholder="Phone Number">
       <input id="swal-emp-email" class="swal2-input" type="email" placeholder="Email">`,
    showCancelButton: true,
    confirmButtonText: 'Add',
    preConfirm: () => {
      const firstF = document.getElementById('swal-emp-fname').value.trim();
      const firstN = document.getElementById('swal-emp-lname').value.trim();
      const role = document.getElementById('swal-emp-role').value.trim();
      const date = document.getElementById('swal-emp-hiredate').value;
      const timeS = document.getElementById('swal-emp-shiftstart').value;
      const timeE = document.getElementById('swal-emp-shiftend').value;
      const number = document.getElementById('swal-emp-phone').value.trim();
      const email = document.getElementById('swal-emp-email').value.trim();

      if (!firstF || !firstN || !role || !date || !timeS || !timeE || !number || !email) {
        Swal.showValidationMessage('Please fill out all fields');
        return false;
      }

      // Set hidden form values
      document.getElementById('form-firstF').value = firstF;
      document.getElementById('form-firstN').value = firstN;
      document.getElementById('form-role').value = role;
      document.getElementById('form-date').value = date;
      document.getElementById('form-timeS').value = timeS;
      document.getElementById('form-timeE').value = timeE;
      document.getElementById('form-number').value = number;
      document.getElementById('form-email').value = email;

      return true;
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
      window.location.href = 'logout.php';
    }
  });
});


</script>
  <!-- SweetAlert config output -->
  <?php if (!empty($sweetAlertConfig)) echo $sweetAlertConfig; ?>

</body>
</html>
