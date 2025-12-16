<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LinkUp | Sign Up</title>

    <link rel="shortcut icon" href="assets/images/logo.jpeg">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body class="landing">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config/db_connect.php';

    $username = trim($_POST["username"]);
    $username = htmlspecialchars(strip_tags($username), ENT_QUOTES, 'UTF-8');

    $email = trim($_POST["email"]);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    $pass = trim($_POST["password"]);
    $pass = htmlspecialchars(strip_tags($pass), ENT_QUOTES, 'UTF-8');

    $pass_confirm = trim($_POST["pass_confirm"]);
    $pass_confirm = htmlspecialchars(strip_tags($pass_confirm), ENT_QUOTES, 'UTF-8');

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt ->bind_param("s", $email);
    $stmt ->execute();

    $result = $stmt -> get_result();
    if ($result -> num_rows > 0) {
        echo '<div class="form-container">
                <p>Email already registered</p>
                <a href="login.html" class="btn primary">Login instead</a>
            </div>
            ';
    }else {
        if ($pass == $pass_confirm) {
            $pass = password_hash($pass, PASSWORD_DEFAULT);
    
            $stmt = $conn->prepare("INSERT INTO users(username, email, password)VALUE(?, ?, ?)");
            $stmt ->bind_param("sss", $username, $email, $pass);
            $stmt ->execute();
            if ($stmt) {
                echo '<div class="form-container">
                        <p>Registed successfully</p>
                        <a href="login.html" class="btn primary">Login</a>
                    </div>
                    ';
            }else {
                echo '<div class="form-container">
                        <p>Unable to register</p>
                        <a href="register.html" class="btn primary">Try Again</a>
                    </div>
                    ';
            }
        }else {
            echo '<div class="form-container">
                    <p>password do not match</p>
                    <a href="register.html" class="btn primary">Try Again</a>
                </div>
                ';
        }
    }
}else {
    die("INVALID REQUEST");
}    
?>
</body>
</html>