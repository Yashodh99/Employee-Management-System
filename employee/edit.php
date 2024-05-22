<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db.php';
include '../includes/functions.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

$nameErr = $positionErr = $departmentErr = $contactErr = "";
$name = $employee['name'];
$position = $employee['position'];
$department = $employee['department'];
$contact_number = isset($employee['contact_number']) ? $employee['contact_number'] : ''; 
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

  
    if (
        $employee_id !== $employee['employee_id'] ||
        $name !== $employee['name'] ||
        $position !== $employee['position'] ||
        $department !== $employee['department'] ||
        $contact_number !== $employee['contact_number']
    ) {
        if (empty($nameErr) && empty($positionErr) && empty($departmentErr) && empty($contactErr)) {
            $stmt = $conn->prepare("UPDATE employees SET employee_id = ?, name = ?, position = ?, department = ?, contact_number = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $employee_id, $name, $position, $department, $contact_number, $id);

            if ($stmt->execute()) {
                $success = true; 
                $employee = ['employee_id' => $employee_id, 'name' => $name, 'position' => $position, 'department' => $department, 'contact_number' => $contact_number];
            } else {
                echo "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
    <link rel="stylesheet" type="text/css" href="../css/employee_management.css">
    <script type="text/javascript">
        function showSuccessMessage() {
            alert("Employee data updated successfully.");
            window.location.href = '../dashboard.php';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Edit Employee</h2>
        <form method="post" action="edit.php?id=<?php echo $id; ?>">
            <label for="employee_id">Employee ID:</label>
            <input type="text" id="employee_id" name="employee_id" value="<?php echo $employee['employee_id']; ?>" required><br>
            
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
            <input type="text" id="contact_number" name="contact_number" value="<?php echo $contact_number; ?>" required>
            <span class="error"><?php echo $contactErr;?></span><br> 
            
            <div class="buttons">
                <input type="submit" value="Update Employee">
                <a href="../dashboard.php" class="button cancel-button">Cancel</a>
            </div>
        </form>
    </div>
    <?php if ($success) : ?>
        <script type="text/javascript">
            showSuccessMessage();
        </script>
    <?php endif; ?>
</body>
</html>
