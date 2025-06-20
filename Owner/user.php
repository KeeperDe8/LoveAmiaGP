<?php
session_start();
 
if (!isset($_SESSION['OwnerID'])) {
  header('Location: ../all/login.php');
  exit();
}
 
require_once('../classes/database.php'); 
$con = new database();
$sweetAlertConfig = "";


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
    $sweetAlertConfig = "<script>document.addEventListener('DOMContentLoaded', () => Swal.fire('Success', 'Employee added.', 'success').then(() => window.location.href = 'user.php'));</script>";
  } else {
    $sweetAlertConfig = "<script>document.addEventListener('DOMContentLoaded', () => Swal.fire('Error', 'Failed to add employee.', 'error'));</script>";
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-[rgba(255,255,255,0.7)] min-h-screen flex">
 
<!-- Sidebar -->
<aside class="bg-white w-16 flex flex-col items-center py-6 space-y-8 shadow-lg">
  <button title="Home" onclick="window.location='page.php'" class="text-[#4B2E0E] text-xl"><i class="fas fa-home"></i></button>
  <button title="Users" class="text-[#4B2E0E] text-xl"><i class="fas fa-users"></i></button>
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
 
  <section class="bg-white rounded-xl p-4 max-w-6xl shadow-lg flex-1 overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead>
        <tr class="text-left text-[#4B2E0E] border-b">
          <th class="py-2 px-3">#</th>
          <th class="py-2 px-3">Name</th>
          <th class="py-2 px-3">Role</th>
          <th class="py-2 px-3">Status</th> <!-- NEW COLUMN -->
          <th class="py-2 px-3">Phone</th>
          <th class="py-2 px-3">Email</th>
          <th class="py-2 px-3">Username</th>
          <th class="py-2 px-3 text-center">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $employees = $con->getEmployee();
        foreach ($employees as $employee) {
        ?>
        <tr class="border-b hover:bg-gray-50 <?= $employee['is_active'] == 0 ? 'bg-red-50 text-gray-500' : '' ?>">
          <td class="py-2 px-3"><?= htmlspecialchars($employee['EmployeeID']) ?></td>
          <td class="py-2 px-3 font-semibold <?= $employee['is_active'] == 0 ? 'line-through' : '' ?>"><?= htmlspecialchars($employee['EmployeeFN'] . ' ' . $employee['EmployeeLN']) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars($employee['Role']) ?></td>
          <!-- STATUS DISPLAY -->
          <td class="py-2 px-3">
            <?php if ($employee['is_active'] == 1): ?>
              <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200">Active</span>
            <?php else: ?>
              <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200">Archived</span>
            <?php endif; ?>
          </td>
          <td class="py-2 px-3"><?= htmlspecialchars($employee['E_PhoneNumber']) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars($employee['E_Email']) ?></td>
          <td class="py-2 px-3"><?= htmlspecialchars($employee['E_Username']) ?></td>
          <td class="py-2 px-3 text-center">
            <?php if ($employee['is_active'] == 1): ?>
              <!-- Show Archive button for active employees -->
              <button class="text-red-600 hover:underline text-lg archive-employee-btn" title="Archive"
                 data-employee-id="<?= htmlspecialchars($employee['EmployeeID']) ?>"
                 data-employee-name="<?= htmlspecialchars($employee['EmployeeFN'] . ' ' . $employee['EmployeeLN']) ?>">
                <i class="fas fa-archive"></i>
              </button>
            <?php else: ?>
              <!-- Show Restore button for archived employees -->
              <button class="text-green-600 hover:underline text-lg restore-employee-btn" title="Restore"
                 data-employee-id="<?= htmlspecialchars($employee['EmployeeID']) ?>"
                 data-employee-name="<?= htmlspecialchars($employee['EmployeeFN'] . ' ' . $employee['EmployeeLN']) ?>">
                <i class="fas fa-undo-alt"></i>
              </button>
            <?php endif; ?>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </section>
 
  <!-- Hidden form for add employee (no changes) -->
  <form id="add-employee-form" method="POST" style="display:none;">
    <input type="hidden" name="firstF" id="form-firstF"><input type="hidden" name="firstN" id="form-firstN">
    <input type="hidden" name="role" id="form-role"><input type="hidden" name="number" id="form-number">
    <input type="hidden" name="email" id="form-email"><input type="hidden" name="username" id="form-username">
    <input type="hidden" name="password" id="form-password"><input type="hidden" name="add_employee" value="1">
  </form>
 
  <?= $sweetAlertConfig ?>
</main>
 
<script>
// Add Employee popup script (no changes needed here)
document.getElementById('add-employee-btn').addEventListener('click', function(e) { /* ... same as before ... */ });

// --- JAVASCRIPT FOR ARCHIVE/RESTORE ---
document.addEventListener('DOMContentLoaded', function() {
  
  // Logic for ARCHIVE button
  document.querySelectorAll('.archive-employee-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const employeeId = this.dataset.employeeId;
      const employeeName = this.dataset.employeeName;
      Swal.fire({
        title: 'Are you sure?',
        text: `You are about to archive "${employeeName}". They will not be able to log in.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, archive them!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('employee_id', employeeId);
          fetch('archive_employee.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                Swal.fire('Archived!', `${employeeName} has been archived.`, 'success').then(() => window.location.reload());
              } else {
                Swal.fire('Error!', data.message || 'Failed to archive.', 'error');
              }
            })
            .catch(() => Swal.fire('Error!', 'An error occurred.', 'error'));
        }
      });
    });
  });

  // Logic for RESTORE button
  document.querySelectorAll('.restore-employee-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const employeeId = this.dataset.employeeId;
      const employeeName = this.dataset.employeeName;
      Swal.fire({
        title: 'Are you sure?',
        text: `You are about to restore "${employeeName}". They will be able to log in again.`,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, restore them!',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          const formData = new FormData();
          formData.append('employee_id', employeeId);
          fetch('restore_employee.php', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                Swal.fire('Restored!', `${employeeName} has been restored.`, 'success').then(() => window.location.reload());
              } else {
                Swal.fire('Error!', data.message || 'Failed to restore.', 'error');
              }
            })
            .catch(() => Swal.fire('Error!', 'An error occurred.', 'error'));
        }
      });
    });
  });
});
</script>
</body>
</html>