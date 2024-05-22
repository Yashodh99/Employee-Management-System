<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM employees WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ../dashboard.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
?>
