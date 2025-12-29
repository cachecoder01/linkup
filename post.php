<?php
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
        header("location: index.html");
        exit();
    }

    include 'config/db_connect.php';

    function feed($feed) {
        global $conn;

        if ($feed == 'Latest') {
            $stmt = $conn->prepare("SELECT * FROM posts ORDER BY id desc");
        }else {
            $stmt = $conn->prepare("SELECT * FROM posts");
        }

        $stmt ->execute();
        $result = $stmt -> get_result();

        $posts = array();
        while ($row = $result -> fetch_assoc()) {
            $posts[] = array(
                'user_id' => $row['user_id'],
                'post_text' => $row['post_text'],
                'post_img' => $row['post_img'],
                'date' => $row['date']
            );
        }
        return $posts;
    }

    function postProfile($poster_id) {
        global $conn;
            
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt ->bind_param("i", $poster_id);
            $stmt ->execute();
            $result = $stmt -> get_result();
            
            if ($result -> num_rows === 1) {
                return $result->fetch_assoc();
            }
            return null;
       
    }
?>