<?php

$servername = "localhost";         
$username = "root";                
$password = "";                    
$dbname = "digital_complaint_box";    

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$role = $_POST['role'];
$password = $_POST['password'];

if (empty($name) || empty($email) || empty($password) || empty($role)) {
    die("Please fill all required fields.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);


$stmt = $conn->prepare("INSERT INTO users2 (name, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("ssss", $name, $email, $hashed_password, $role);


if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    if ($conn->errno == 1062) {
        echo "Email already registered.";
    } else {
        echo "Error: " . $conn->error;
    }
}

$stmt->close();
$conn->close();
?>
