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

        if (password_verify($password, $db_password)) {
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
            $_SESSION['name'] = $name;
 
            echo '
            <div style="
                display: flex;
                justify-content: center;
                margin-top: 50px;
            ">
                <button style="
                    background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 15px 30px;
                    font-size: 18px;
                    font-weight: bold;
                    border-radius: 5px;
                    cursor: default;
                ">
                    ✔ Login Successful
                </button>
            </div>';
            
            exit();
        } else {
      
            echo '
            <div style="
                display: flex;
                justify-content: center;
                margin-top: 50px;
            ">
                <button style="
                    background-color: #dc3545;
                    color: white;
                    border: none;
                    padding: 15px 30px;
                    font-size: 18px;
                    font-weight: bold;
                    border-radius: 5px;
                    cursor: default;
                ">
                    ✖ Password is wrong
                </button>
            </div>';
        }
    } else {
     
        echo '
        <div style="
            display: flex;
            justify-content: center;
            margin-top: 50px;
        ">
            <button style="
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 15px 30px;
                font-size: 18px;
                font-weight: bold;
                border-radius: 5px;
                cursor: default;
            ">
                ✖ Email is not correct
            </button>
        </div>';
    }
}
?>
