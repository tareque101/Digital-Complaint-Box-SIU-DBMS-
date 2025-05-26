<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];   
    $category = $_POST['category'];
    $description = $_POST['description'];
    $file_path = '';
 
    if (!empty($_FILES['attachment']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);  
        }
        $file_name = basename($_FILES["attachment"]["name"]);
        $target_file = $target_dir . $file_name;
 
        if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
            $file_path = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Database insert query (status and created_at omitted)
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, category, description, file_path) VALUES (?, ?, ?, ?)");

    $stmt->bind_param("isss", $user_id, $category, $description, $file_path);

    if ($stmt->execute()) {
        echo "Complaint submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
