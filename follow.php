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
    session_start();
    if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== TRUE) {
        header('location: indesx.html');
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == 'POST') {

        include 'config/db_connect.php';

        $user_id = $_SESSION["user_id"];
        $f_id = $_POST["id"];
        $action = $_POST["action"];
        $request = 'pending';

        if ($action == 'follow') {
            $stmt = $conn ->prepare("INSERT INTO friends(user_id, friend_id, request)VALUE(?,?,?)");
            $stmt ->bind_param("iis", $user_id, $f_id, $request);
            $result = $stmt ->execute();

            if ($result) {
                echo '<div class="form-container">
                        <p>Request Sent</p>
                        <a href="javascript:history.back()" class="btn primary">Ok</a>
                    </div>
                ';
            }

        }
    }else {
        die('INVALID REQUEST');
    }
?>
</body>
</html>