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
$email = $_SESSION['email'];

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
    header('location: index.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config/db_connect.php';

    $full_name = strip_tags(trim($_POST["name"]));

    $bio = strip_tags(trim($_POST["bio"]));

    $prof = strip_tags(trim($_POST["profession"]));

    $location = strip_tags(trim($_POST["location"]));

    $img = "";
    if (isset($_FILES["img"]["name"]) && $_FILES["img"]["error"] == 0) {
        $img = $_FILES["img"];
    }
    
    if (isset($_FILES["img"]["name"]) && $_FILES["img"]["error"] == 0) {

        $targetDir = 'assets/images/profiles/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, TRUE);
        }

        $imgName = time() ."_". basename((string)$_FILES["img"]["name"]);

        $targetFile = $targetDir . $imgName;

        $check = getimagesize($_FILES["img"]["tmp_name"]);
        $allowedType = ['image/png', 'image/jpg', 'image/jpeg', 'image/webp', 'image/svg', 'image/jfif'];
        if (!in_array($check['mime'], $allowedType)) {
            die('File Format Not Allowed');
            exit();
        }

        if ($check) {
            if (move_uploaded_file($_FILES["img"]["tmp_name"], $targetFile)) {
                $img = $imgName;
            }else {
                die('unable to upload image:' . $_FILES["img"]["error"]);
                exit();
            }
        }elseif($_FILES["img"]["error"] !== 4) {
            die('unable to upload: ' . $_FILES["img"]["error"]);
            exit();
        }
    }

    $profile = 1;

    $stmt = $conn->prepare("UPDATE users SET profile=?, full_name=?, profile_img=?, bio=?, profession=?, location=? WHERE email=?");
    $stmt ->bind_param("issssss", $profile, $full_name, $img, $bio, $prof, $location, $email);
    $stmt ->execute();

    if ($stmt) {
        echo '<div class="form-container">
                <p>Profile Saved</p>
                <a href="home.php" class="btn primary">Ok</a>
            </div>
        ';
    }else {
        echo '<div class="form-container">
                <p>Unable To Saved Profile</p>
                <a href="home.php" class="btn primary">Try Again</a>
            </div>
        ';
    }

}else {
    die("INVALID REQUEST");
}    
?>
</body>
</html>