<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Employee</title>
    <link rel="stylesheet" type="text/css" href="../css/employee_management.css">
</head>
<body>
    <div class="container">
        <h2>View Employee</h2>
        <div class="employee-details">
            <p><strong>Employee ID:</strong> <?php echo $employee['employee_id']; ?></p>
            <p><strong>Name:</strong> <?php echo $employee['name']; ?></p>
            <p><strong>Position:</strong> <?php echo $employee['position']; ?></p>
            <p><strong>Department:</strong> <?php echo $employee['department']; ?></p>
            <p><strong>Contact Number:</strong> <?php echo isset($employee['contact_number']) ? $employee['contact_number'] : 'N/A'; ?></p>
 
        </div>
        <div class="buttons">
            <a href="../dashboard.php" class="button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
