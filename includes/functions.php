<?php

function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function validatePassword($password, $confirm_password) {
    return $password === $confirm_password;
}
?>
