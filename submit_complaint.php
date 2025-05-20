<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$role = $_POST['role'];
$category = $_POST['category'];
$description = $_POST['description'];

// Step 1: Insert into users table (if not already there)
$sql_user = "INSERT INTO users (name, email, role) VALUES ('$name', '$email', '$role')";
$conn->query($sql_user);

// Step 2: Get user_id
$user_id = $conn->insert_id;

// Step 3: Insert into complaints table
$sql_complaint = "INSERT INTO complaints (user_id, category, description) 
                  VALUES ($user_id, '$category', '$description')";

if ($conn->query($sql_complaint) === TRUE) {
  echo "✅ Complaint submitted successfully.";
} else {
  echo "❌ Error: " . $conn->error;
}
require_once 'connect.php'; // or the correct path like '../connect.php'

$conn->close();
?>
