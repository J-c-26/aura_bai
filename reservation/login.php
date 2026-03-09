<?php
// ============================================
// FILE: login.php 
// ============================================
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    
    if ($email === 'admin@gmail.com' && $password === 'admin123') {
        $_SESSION['user_email'] = $email;
        echo json_encode(["status" => "LoggedIn", "email" => $email]);
    } else {
        echo json_encode(["status" => "Error", "message" => "Invalid credentials"]);
    }
}
?> 