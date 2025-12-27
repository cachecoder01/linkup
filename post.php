<?php
session_start();
$id = $_SESSION['user_id'];

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
    header('location: index.html');
    exit();
}
    include 'config/db_connect.php';

    function feed($feed) {
        global $conn, $id;

        if ($feed == 'All') {

            $stmt = $conn->prepare("SELECT * FROM posts");

        }elseif ($feed == 'Latest') {

            $stmt = $conn->prepare("SELECT * FROM posts ORDER BY id desc");

        }elseif ($feed == 'Following') {

            $f_stmt = $conn->prepare("SELECT DISTINCT following_user_id FROM following WHERE user_id = ?");
            $f_stmt ->bind_param("i", $id);
            $f_stmt ->execute();
            $f_result = $stmt -> get_result();
            if ($f_result -> num_rows > 0) {
                while ($row = $f_result -> fetch_assoc()) {
                    $f_id = $row["following_user_id"];

                    $stmt = $conn->prepare("SELECT * FROM posts where user_id = ?");
                    $stmt ->bind_param("i", $f_id);
                }
            }else {
                echo 'no following';
            }

        }else {
            echo 'No POst';
        }

        $stmt ->execute();
        $result = $stmt -> get_result();

        $posts = array();
        while ($row = $result -> fetch_assoc()) {
            $posts[] = array(
                'post_text' => $row['post_text'],
                'post_img' => $row['post_img'],
                'date' => $row['date']
            );
        }
        return $posts;
    }
    
    $feed = 'All';
    $posts = feed($feed);

    foreach ($posts as $post) {
        
    }
  
?>