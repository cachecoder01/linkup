<?php

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
        $posts = feed($feed);
        foreach ($posts as $post) {
            $poster_id = $post["user_id"];
        
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
            $stmt ->bind_param("i", $poster_id);
            $stmt ->execute();

            $result = $stmt -> get_result();
            $posters = array();
            while ($row = $result -> fetch_assoc()) {
                $posters[] = array(
                    'username' => $row['username'],
                    'profile_img' => $row["profile_img"],
                    'full_name' => $row["full_name"],
                    'bio' => $row["bio"],
                    'profession' => $row["profession"],
                    'location' => $row["location"],
                    'date' => $row["date"]
                );
            }
        }
    }
?>