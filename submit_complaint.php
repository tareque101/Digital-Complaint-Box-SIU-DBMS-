<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $user_id = $_POST['user_id'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $file_path = '';

 
    if (!empty($_FILES['attachment']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_path = $target_dir . basename($_FILES["attachment"]["name"]);

        if (!move_uploaded_file($_FILES["attachment"]["tmp_name"], $file_path)) {
            die("File upload failed.");
        }
    }

     
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, category, description, file_path) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $category, $description, $file_path);

    if ($stmt->execute()) {
       
        header("Location: complaint_form.html?success=1");
        exit();
    } else {
        echo "Database error: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}
?>
