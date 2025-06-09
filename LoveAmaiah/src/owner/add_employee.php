<?php
session_start();
require_once('../../classes/database.php');
require_once('../../classes/employee.php');

$con = new database();

if (!isset($_SESSION['OwnerID'])) {
    header('Location: ../auth/login.php');
    exit();
}

$sweetAlertConfig = "";

if (isset($_POST['add_employee'])) {
    $employeeName = $_POST['employee_name'];
    $employeeUsername = $_POST['employee_username'];
    $employeePassword = $_POST['employee_password'];

    $employee = new Employee();
    $result = $employee->addEmployee($employeeName, $employeeUsername, $employeePassword);

    if ($result) {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
          icon: 'success',
          title: 'Employee Added',
          text: 'The employee has been successfully added.',
          confirmButtonText: 'Continue'
        }).then(() => {
          window.location.href = 'dashboard.php';
        });
        </script>";
    } else {
        $sweetAlertConfig = "
        <script>
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'There was an error adding the employee. Please try again.',
          confirmButtonText: 'Try Again'
        });
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Add Employee - Amaiah</title>
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="../../package/dist/sweetalert2.css">
</head>
<body>
<div class="container mt-5">
    <h2>Add New Employee</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <input type="text" name="employee_name" class="form-control" placeholder="Enter employee name" required>
        </div>
        <div class="mb-3">
            <input type="text" name="employee_username" class="form-control" placeholder="Enter employee username" required>
        </div>
        <div class="mb-3">
            <input type="password" name="employee_password" class="form-control" placeholder="Enter employee password" required>
        </div>
        <button type="submit" name="add_employee" class="btn btn-primary">Add Employee</button>
    </form>
</div>

<script src="../../bootstrap-5.3.3-dist/js/bootstrap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php echo $sweetAlertConfig; ?>
</body>
</html>