<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LinkUp | Sign In</title>

    <link rel="shortcut icon" href="assets/images/logo.jpeg">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body class="landing">
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config/db_connect.php';

    $email = trim($_POST["email"]);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $pass = strip_tags(trim($_POST["password"]));

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt ->bind_param("s", $email);
    $stmt ->execute();

    $result = $stmt -> get_result();
    if ($result -> num_rows > 0) {
        while ($row = $result -> fetch_assoc()) {
            $saved_pass = $row["password"];

            if (password_verify($pass, $saved_pass)) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['user_logged_in'] = TRUE;
                header("location: home.php");
            }else {
                echo '<div class="form-container">
                        <p>password do not match</p>
                        <a href="login.html" class="btn primary">Try Again</a>
                    </div>';
            }
        }
    }else {
        echo '<div class="form-container">
                <p>Account Not Found</p>
                <a href="register.html" class="btn primary">Register Instead</a>
            </div>';
    }
}else {
    die("INVALID REQUEST");
}    
?>
</body>
</html>