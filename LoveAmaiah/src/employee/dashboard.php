<?php
session_start();
require_once('../../classes/database.php');
require_once('../../classes/employee.php');

$con = new database();
$employee = new Employee();

if (!isset($_SESSION['EmployeeID'])) {
    header('Location: ../auth/login.php');
    exit();
}

$employeeDetails = $employee->getEmployeeDetails($_SESSION['EmployeeID']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Employee Dashboard</title>
    <link rel="stylesheet" href="../../bootstrap-5.3.3-dist/css/bootstrap.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }
        .dashboard-container {
            padding: 20px;
            max-width: 800px;
            margin: auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($employeeDetails['EmployeeName']); ?></h2>
    <p>Your Employee ID: <?php echo htmlspecialchars($employeeDetails['EmployeeID']); ?></p>
    <p>Role: <?php echo htmlspecialchars($employeeDetails['Role']); ?></p>
    <hr>
    <h4>Available Actions</h4>
    <ul>
        <li><a href="view_tasks.php">View Tasks</a></li>
        <li><a href="submit_report.php">Submit Report</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</div>
<script src="../../bootstrap-5.3.3-dist/js/bootstrap.js"></script>
</body>
</html>