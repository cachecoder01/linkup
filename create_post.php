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
$id = $_SESSION['user_id'];

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
    header('location: index.html');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config/db_connect.php';

    $post_text = strip_tags(trim($_POST["post_text"]));

    $img = "";
    if (isset($_FILES["img"]["name"]) && $_FILES["img"]["error"] == 0) {
        $img = $_FILES["img"];
    }
    
    if (isset($_FILES["img"]["name"]) && $_FILES["img"]["error"] == 0) {

        $targetDir = 'assets/images/posts/';
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

    $stmt = $conn->prepare("INSERT INTO posts(user_id, post_text, post_img)VALUE(?,?,?)");
    $stmt ->bind_param("iss", $id, $post_text, $img);
    $stmt ->execute();

    if ($stmt) {
        echo '<div class="form-container">
                <p>Post Added</p>
                <a href="home.php" class="btn primary">Ok</a>
            </div>
        ';
    }else {
        echo '<div class="form-container">
                <p>Unable To Add Post</p>
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