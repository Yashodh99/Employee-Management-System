<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include 'includes/db.php';

$result = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <h2>Employee Management System</h2>
        <h3>Employee List</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Contact Number</th> 
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['employee_id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['position']; ?></td>
                <td><?php echo $row['department']; ?></td>
                <td><?php echo $row['contact_number']; ?></td> 
                <td>
                    <a href="employee/edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                    <a href="employee/delete.php?id=<?php echo $row['id']; ?>" onclick="return confirmDelete();">Delete</a> |
                    <a href="employee/view.php?id=<?php echo $row['id']; ?>">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <div class="buttons">
            <a href="employee/add.php" class="button">Add Employee</a>
            <a href="auth/logout.php" class="button" onclick="return confirmLogout();">Logout</a>
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this employee?");
        }

        function confirmLogout() {
            return confirm("Are you sure you want to logout?");
        }
    </script>
</body>
</html>
