<?php
include 'db_config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT password, role, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_password, $role, $name);
        $stmt->fetch();

        if ($password === $db_password) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['name'] = $name;

            
            header("Location: home.html");
            exit();
        } else {
            echo "Password is wrong";
        }
    } else {
        echo "Email is not correct";
    }
}