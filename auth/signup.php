<?php

include '../includes/db.php';
include '../includes/functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = sanitizeInput($_POST['firstname']);
    $lastname = sanitizeInput($_POST['lastname']);
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    // Validation for firstname and lastname
    if (!preg_match("/^[a-zA-Z]*$/", $firstname)) {
        echo "First name should contain only letters.";
        exit();
    }

    if (!preg_match("/^[a-zA-Z]*$/", $lastname)) {
        echo "Last name should contain only letters.";
        exit();
    }

    // Validation for password
    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long.";
        exit();
    }

    if (!preg_match("/[0-9]/", $password)) {
        echo "Password must contain at least one number.";
        exit();
    }

    if (!preg_match("/[A-Z]/", $password)) {
        echo "Password must contain at least one uppercase letter.";
        exit();
    }

    if (!preg_match("/[a-z]/", $password)) {
        echo "Password must contain at least one lowercase letter.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    // If all validations pass, proceed with inserting the user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $hashed_password);
    
    if ($stmt->execute()) {
        header("Location: login.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="../css/signup.css">
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
      
        <form method="post" action="signup.php">
            First Name: <input type="text" name="firstname" required><br>
            Last Name: <input type="text" name="lastname" required><br>
            Email: <input type="email" name="email" required><br>
            Password: <input type="password" name="password" id="password" required>
            <input type="checkbox" onclick="togglePasswordVisibility()"> Show Password<br> <br>
            Confirm Password: <input type="password" name="confirm_password" required><br>
            <input type="submit" value="Signup">
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
    
    <script>
        function togglePasswordVisibility() {
            var passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
