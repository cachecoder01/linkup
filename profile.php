<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config/db_connect.php';

    $full_name = trim($_POST["name"]);
    $full_name = htmlspecialchars(strip_tags($full_name), ENT_QUOTES, 'UTF-8');

    $bio = trim($_post["bio"]);
    $bio = filter_var($bio, FILTER_VALIDATE_EMAIL);

    $proff = trim($_POST["proffession"]);
    $proff = htmlspecialchars(strip_tags($proff), ENT_QUOTES, 'UTF-8');

    $location = trim($_POST["location"]);
    $location = htmlspecialchars(strip_tags($location), ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt ->bind_param("s", $email);
    $stmt ->execute();

    $result = $stmt -> get_result();
    if ($result -> num_rows > 0) {
        echo 'email already registered';
        echo '<a href="login.html">Login instead</a>';
    }else {
        if ($pass == $pass_confirm) {
            $pass = password_hash($pass, PASSWORD_DEFAULT);
    
            $stmt = $conn->prepare("INSERT INTO users(username, email, password)VALUE(?, ?, ?)");
            $stmt ->bind_param("sss", $username, $email, $pass);
            $stmt ->execute();
            if ($stmt) {
                echo 'Registed successfully';
            }else {
                echo 'Unable to register';
            }
        }else {
            echo 'password do not match';
        }
    }
}else {
    die("INVALID REQUEST");
}    
?>