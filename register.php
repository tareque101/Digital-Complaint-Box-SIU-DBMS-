<?php

function show_message($message, $color = 'red') {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Registration Status</title>
    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .message-box {
            padding: 20px 30px;
            border-radius: 8px;
            font-size: 1.4rem;
            font-weight: bold;
            color: ' . ($color === "green" ? "#155724" : "#721c24") . ';
            background-color: ' . ($color === "green" ? "#d4edda" : "#f8d7da") . ';
            border: 2px solid ' . ($color === "green" ? "#c3e6cb" : "#f5c6cb") . ';
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 90%;
        }
    </style>
    </head>
    <body>
        <div class="message-box">' . htmlspecialchars($message) . '</div>
    </body>
    </html>';
    exit();
}

$servername = "localhost";         
$username = "root";                
$password = "";                    
$dbname = "digital_complaint_box";    

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    show_message("Connection failed: " . $conn->connect_error, "red");
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = $_POST['role'] ?? '';
$password = $_POST['password'] ?? '';

// Form validation
if (empty($name) || empty($email) || empty($password) || empty($role)) {
    show_message("Please fill all required fields.", "red");
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    show_message("Invalid email format.", "red");
}

// Validate disallowed email domains
$disallowed_domains = ['example.com', 'test.com', 'invalid.com'];
$domain = substr(strrchr($email, "@"), 1);
if (in_array(strtolower($domain), $disallowed_domains)) {
    show_message("wrong email format.", "red");
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    show_message("Email already registered.", "red");
}

// Insert the user into the database
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

if ($stmt->execute()) {
    show_message("Registration successful!", "green");
} else {
    show_message("Error: " . $conn->error, "red");
}

$stmt->close();
$conn->close();
?>
