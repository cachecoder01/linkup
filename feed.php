<?php
session_start();
$id = $_SESSION['user_id'];

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
    header('location: index.html');
    exit();
}
    include 'config/db_connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $feed = strip_tags(trim($_POST["feed_filter"]));
    }else {
        $feed = 'All';
    }

    function feed($feed) {

        if ($feed == 'All') {
            $stmt = $conn->prepare("SELECT * FROM posts");
            $stmt ->execute();
            $result = $stmt -> get_result();
            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $post = 'all';
                    return $post;
                }
            }
        }elseif ($feed == 'Latest') {
            $stmt = $conn->prepare("SELECT * FROM posts ORDER BY id desc");
            $stmt ->execute();
            $result = $stmt -> get_result();
            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $post = 'latest';
                    return $post;
                }
            }
        }elseif ($feed == 'Following') {
            $stmt = $conn->prepare("SELECT DISTINCT following_user_id FROM following WHERE user_id = '$id' ");
            $stmt ->execute();
            $result = $stmt -> get_result();
            if ($result -> num_rows > 0) {
                while ($row = $result -> fetch_assoc()) {
                    $f_id = $row["following_user_id"];

                    $stmt = $conn->prepare("SELECT * FROM posts where user_id = '$id' ");
                    $stmt ->execute();
                    $result = $stmt -> get_result();
                    if ($result -> num_rows > 0) {
                        while ($row = $result -> fetch_assoc()) {
                            $post = 'Following';
                            return $post;
                        }
                    }
                }
            }else {
                echo 'no following';
            }
        }
    }
    
    $update = feed($feed);
    echo $update;
  
?>