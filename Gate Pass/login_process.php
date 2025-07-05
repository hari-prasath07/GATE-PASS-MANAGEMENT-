<?php
session_start();
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Save user info to session
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Redirect based on role
        if ($user['role'] == 'student') {
            header("Location: student_dashboard.php");
        } elseif ($user['role'] == 'warden') {
            header("Location: warden_dashboard.php");
        } elseif ($user['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: login.php?error=Unknown role");
        }
    } else {
        header("Location: login.php?error=Invalid email or password");
    }
}
?>
