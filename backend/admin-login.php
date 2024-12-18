<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'stargazer' && $password === 'TheChosenOne8402') {
        $_SESSION['admin'] = $username;
        header('Location: ../html/admin-dashboard.php');  // Redirect to the dashboard
        exit();
    } else {
        header('Location: admin-log.html?error=invalid');
        exit();
    }
}
?>

