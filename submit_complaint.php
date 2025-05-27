<?php
include 'db_config.php';

function show_message($message, $color = 'red') {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Notification</title>
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_POST['user_id'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $file_path = '';

    // User ID existence check
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE id = ?");
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows === 0) {
        show_message("Wrong user ID: The specified user does not exist.", "red");
    }

    // File upload handling
    if (!empty($_FILES['attachment']['name'])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_path = $target_dir . basename($_FILES["attachment"]["name"]);

        if (!move_uploaded_file($_FILES["attachment"]["tmp_name"], $file_path)) {
            show_message("File upload failed.", "red");
        }
    }

    // Set status and created_at
    $status = 'Pending';
    $created_at = date('Y-m-d H:i:s');
 
    $stmt = $conn->prepare("INSERT INTO complaints (user_id, category, description, file_path, status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $category, $description, $file_path, $status, $created_at);

    if ($stmt->execute()) {
        show_message("Submission successful! Your complaint has been recorded.", "green");
    } else {
        show_message("Database error: " . $stmt->error, "red");
    }

} else {
    show_message("Invalid request.", "red");
}
?>
