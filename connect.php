<?php
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "digital_complaint_box"; // ✅ Your database name

// ✅ Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// ✅ Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "✅ Connected successfully";
?>
