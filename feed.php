<?php
session_start();
$id = $_SESSION['user_id'];

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
    header('location: index.html');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    include 'config/db_connect.php';

    $_SESSION["feed"] = strip_tags(trim($_POST["feed_filter"]));
    header("location: home.php");
    
}else {
    die("INVALID REQUEST");
}
?>