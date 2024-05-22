<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

$nameErr = $positionErr = $departmentErr = $contactErr = "";
$name = $position = $department = $contact_number = $employee_id = ""; 
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = sanitizeInput($_POST['employee_id']);
    $name = sanitizeInput($_POST['name']);
    $position = sanitizeInput($_POST['position']);
    $department = sanitizeInput($_POST['department']);
    $contact_number = sanitizeInput($_POST['contact_number']);

 
    if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $nameErr = "Should Not be a Number";
    }

   
    if (!preg_match("/^[a-zA-Z-' ]*$/", $position)) {
        $positionErr = "Should Not be a Number";
    }

   
    if (!preg_match("/^[a-zA-Z-' ]*$/", $department)) {
        $departmentErr = "Should Not be a Number";
    }

    
    if (!preg_match("/^\d{10}$/", $contact_number)) {
        $contactErr = "Contact number should be a 10-digit phone number";
    }

    if (empty($nameErr) && empty($positionErr) && empty($departmentErr) && empty($contactErr)) {
        $stmt = $conn->prepare("INSERT INTO employees (employee_id, name, position, department, contact_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $employee_id, $name, $position, $department, $contact_number);

        if ($stmt->execute()) {
            $success = true;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
    <link rel="stylesheet" type="text/css" href="../css/employee_management.css">
    <script type="text/javascript">
        window.onload = function() {
            <?php if ($success) : ?>
                alert("Successfully added the employee.");
                window.location.href = '../dashboard.php';
            <?php endif; ?>
        };
    </script>
</head>
<body>
    <div class="container">
        <h2>Add Employee</h2>
        <form id="employeeForm" method="post" action="add.php">
            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id" value="<?php echo $employee_id; ?>" required><br>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            <span class="error"><?php echo $nameErr;?></span><br>
            
            <label for="position">Position:</label>
            <input type="text" id="position" name="position" value="<?php echo $position; ?>" required>
            <span class="error"><?php echo $positionErr;?></span><br>
            
            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="<?php echo $department; ?>" required>
            <span class="error"><?php echo $departmentErr;?></span><br>
            
            <label for="contact_number">Contact Number:</label>
            <input type="tel" id="contact_number" name="contact_number" value="<?php echo $contact_number; ?>" required>
            <span class="error"><?php echo $contactErr;?></span><br> 
            
            <input type="submit" value="Add Employee">
            <a href="../dashboard.php" class="button cancel-button">Cancel</a>
        </form>
    </div>
</body>
</html>
