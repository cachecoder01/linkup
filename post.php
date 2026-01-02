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

    function friendsCount($user_id) {
        global $conn;
    
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM friends WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    
        $result = $stmt->get_result()->fetch_assoc();
        return (int) $result['total'];
    }

    function users($user_id) {
        global $conn;
            
        $stmt = $conn->prepare("SELECT * FROM users WHERE id != ?");
        $stmt ->bind_param("i", $user_id);
        $stmt ->execute();
        $result = $stmt -> get_result();
            
        if ($result -> num_rows > 0) {
            $users = array();
            while ($row = $result->fetch_assoc()) {
                $users[] = array(
                    'id' => $row["id"],
                    'username' => $row["username"],
                    'profile_img' => $row["profile_img"],
                    'full_name' =>  $row["full_name"],
                    'bio' => $row["bio"],
                    'profession' => $row["profession"],
                    'location' => $row["location"],
                    'date' => $row["date"]
                );
            }
        }
    
        return $users;       
    }
    
?>