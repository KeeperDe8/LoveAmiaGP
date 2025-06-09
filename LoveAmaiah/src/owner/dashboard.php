<?php
session_start();
require_once('../../classes/database.php');
require_once('../../classes/user.php');
require_once('../../classes/employee.php');

$con = new database();

if (!isset($_SESSION['OwnerID'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Fetch owner details
$ownerID = $_SESSION['OwnerID'];
$ownerFN = $_SESSION['OwnerFN'];

// Fetch employees
$employees = $con->getAllEmployees(); // Assuming this method exists in the database class

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($ownerFN); ?></h1>
    <h2>Employee Management</h2>
    <a href="add_employee.php" class="btn btn-primary">Add Employee</a>
    
    <h3>Employee List</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?php echo htmlspecialchars($employee['EmployeeID']); ?></td>
                    <td><?php echo htmlspecialchars($employee['EmployeeName']); ?></td>
                    <td>
                        <a href="edit_employee.php?id=<?php echo $employee['EmployeeID']; ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_employee.php?id=<?php echo $employee['EmployeeID']; ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="../../bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>