<?php
    session_start();
    if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== TRUE) {
        header("location: index.html");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LinkUp | Profile View</title>
    
    <link rel="shortcut icon" href="assets/images/logo.jpeg">
    <link rel="stylesheet" href="assets/css/social-dashboard.css">
    <link rel="stylesheet" href="assets/fonts/css/all.min.css">
    
</head>
<body>

<?php
    include 'config/db_connect.php';
    $email = $_SESSION["email"];
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt ->bind_param("s", $email);
    $stmt ->execute();

    $result = $stmt -> get_result();
    if ($result -> num_rows > 0) {
        while ($row = $result -> fetch_assoc()) {
            $profile = $row["profile"];
            $username = $row["username"];
            $profile_img = $row["profile_img"];
            $name =  $row["full_name"];
            $bio = $row["bio"];
            $prof = $row["profession"];
            $location = $row["location"];
            $datejoined = $row["date"];

            $p_avater = substr($username, 0, 1);
        }
    }
?>
    <!-- HEADER -->
    <header class="header">
        <div class="header-left">
            <img src="assets/images/logo1.png" alt="LinkUp Logo" class="header-logo">
            <h1 class="brand-name">LinkUp</h1>
        </div>

        <div class="header-right">
            <div class="header-icons">
                <button class="icon-btn">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <button class="icon-btn">
                    <i class="fas fa-envelope"></i>
                    <span class="notification-badge">2</span>
                </button>
            </div>
            <div class="user-menu user">
                <button class="icon-btn">
                    <div class="default-avatar-placeholder">
                        <?php
                            if (empty($profile_img)) {
                                echo '<p>'.strtoupper($p_avater).'</p>';
                            }else {
                                echo '<img src="assets/images/profiles/'.$profile_img.'">';
                            }
                        ?>
                    </div>
                </button>
                <div class="user-dropdown" id="userDropdown">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <a href="logout.php" class="nav-item" onclick="return confirm('Are you sure you want to LogOut?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN LAYOUT -->
    <div class="main-container">
        <!-- LEFT SIDEBAR -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="javascript:history.back()" class="nav-item tablink">
                    <i class="fa fa-angle-left"></i>
                    <span>Home</span>
                </a>
            </nav>
        </aside>       

        <!-- MAIN FEED -->
        <main class="main-feed">
            <?php
            include 'post.php';
                $poster_id = $_GET["id"];
                $profile = getProfileInfo($poster_id);
                $full_name = $profile['full_name'];
                $p_username = $profile['username'];
                $p_profile = $profile['profile_img'];
                $p_bio = $profile['bio'];
                $p_prof = $profile['profession'];
                $p_location = $profile['location'];
                $p_datejoined = $profile['date'];

                $pp_avatar = substr($p_username, 0, 1);
            ?>

            <section>
            <!-- VIEW PROFILE -->
                <div class="profile-view">
                    <div class="modal-body">
                        <div class="profile-details">
                            <div class="profile-header-section">
                                <div class="profile-cover">
                                    <div class="cover-placeholder">
                                        <div class="default-avatar-placeholder">
                                            <?php
                                                if (empty($p_profile)) {
                                                    echo '<p>'.strtoupper($pp_avatar).'</p>';
                                                }else {
                                                    echo '<img src="assets/images/profiles/'.$p_profile.'">';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                        $stmt = $conn->prepare("SELECT * FROM friends WHERE request='approved' 
                                            AND (
                                                (user_id = ? AND friend_id = ?)
                                            )
                                        ");
                                        $stmt->bind_param("ii", $user_id, $poster_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if ($result -> num_rows > 0) {
                                            echo '<form method="POST" action="follow.php">
                                                    <input type="hidden" name="id" value="'.$poster_id.'">
                                                    <input type="hidden" name="action" value="unfriend">
                                                    <button type="submit" class="btn-primary connect">
                                                        <i class="fas fa-ban"></i> 
                                                        <span>Unfriend</span>
                                                    </button>
                                                </form>
                                            ';
                                        }elseif($poster_id == $user_id) {
                                            echo null;
                                        }else {
                                            echo '<form method="POST" action="follow.php">
                                                    <input type="hidden" name="id" value="<?= $poster_id ?>">
                                                    <input type="hidden" name="action" value="follow">
                                                    <button type="submit" class="btn-primary connect">
                                                        <i class="fas fa-plus"></i>
                                                        <span>Connect</span>
                                                    </button>
                                                </form>
                                            ';
                                        }
                                    ?>
                                    
                                </div>                                
                                <div class="profile-info-section">
                                    <div class="profile-details-info">                                        
                                        <h2><?= $full_name ?></h2>
                                        <p class="profile-username">@<?= $p_username ?></p>
                                        <p class="profile-bio"><?= ucfirst($p_bio) ?></p>
                                        <div class="profile-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-briefcase"></i> 
                                                <?php
                                                    if (empty($p_prof)) {
                                                        echo 'Not set yet';
                                                    }else {
                                                        echo $p_prof;
                                                    }
                                                ?>
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?php
                                                    if (empty($p_location)) {
                                                        echo 'Not set yet';
                                                    }else {
                                                        echo $p_location;
                                                    }
                                                ?>
                                            </span>
                                            <span class="meta-item" id="profileJoined">
                                                <i class="fas fa-calendar-alt"></i> Joined <?= $p_datejoined ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-stats-detailed">
                                <div class="stat-detail">
                                    <span class="stat-number-large">
                                        <?php
                                            $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ?");
                                            $stmt ->bind_param("i", $poster_id);
                                            $stmt ->execute();
                                       
                                            $result = $stmt -> get_result();
                                            $count = $result -> num_rows;
                                            if ($count > 0) {
                                                echo $count;
                                            }else {
                                                echo '0';
                                            }
                                        ?>
                                    </span>
                                    <span class="stat-label-large">Posts</span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-number-large">
                                        <?php
                                            $stmt = $conn->prepare("SELECT count(*) AS total_friends FROM friends WHERE user_id = ?");
                                            $stmt ->bind_param("i", $poster_id);
                                            $stmt ->execute();
                                       
                                            $result = $stmt->get_result()->fetch_assoc();
                                            $friends = $result['total_friends'];
                                            echo $friends;
                                        ?>
                                    </span>
                                    <span class="stat-label-large">Friends</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </section>
            
        </main>

    <!-- RIGHT SIDEBAR -->
    <aside class="right-sidebar">
        <!-- USER PROFILE CARD -->
        <div class="profile-card">
            <div class="profile-header">
                    <div class="default-avatar-placeholder">
                        <?php
                            if (empty($profile_img)) {
                                echo '<p>'.strtoupper($p_avater).'</p>';
                            }else {
                                echo '<img src="assets/images/profiles/'.$profile_img.'">';
                            }
                        ?>
                    </div>
                <div class="profile-info">
                    <h4 class="profile-name"><?= $name ?></h4>
                    <p class="profile-username">@<?= $username ?></p>
                </div>
            </div>
            <div class="profile-stats">
                <div class="stat">
                    <span class="stat-number" id="postsCount">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM posts WHERE user_id = ?");
                            $stmt ->bind_param("i", $user_id);
                            $stmt ->execute();
                                       
                            $result = $stmt -> get_result();
                            $count = $result -> num_rows;
                            if ($count > 0) {
                                echo $count;
                            }else {
                                echo '0';
                            }
                        ?>
                    </span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat">
                    <span class="stat-number">0</span>
                    <span class="stat-label">Friends</span>
                </div>
            </div>
        </div>

        <!-- SUGGESTIONS -->
        <div class="suggestions-card">
            <div class="card-header">
                <h4>People you may know</h4>
                <p>Add new friends</p>
            </div>
            <div class="suggestions-list">
            <?php
                    $limit = 3;
                    $count = 0;
                    $users = getFindFriends($user_id);
                    if (!empty($users)) {
                        foreach ($users as $user) {
                            if ($count >= $limit) {
                                break;
                            }
                            $f_id = $user["id"];
                            $f_name = $user['full_name'];
                            $f_username = $user["username"];
                            $f_profile_img = $user["profile_img"];

                            $F_p_avatar = substr($f_username, 0, 1);

                        echo '<div class="suggestion-item">
                                <a href="profile_view.php?id='.$f_id.'" class="suggestion-info">
                                    <div class="suggestion-avatar">
                                        <div class="default-avatar-placeholder">';
                                            if (empty($f_profile_img)) {
                                                echo '<p>'.strtoupper($F_p_avatar).'</p>';
                                            }else {
                                                echo '<img src="assets/images/profiles/'.$f_profile_img.'">';
                                            }
                                    echo '</div>
                                    </div>
                                    <div class="suggestion-details">
                                        <h5>'.ucwords($f_name).'</h5>
                                        <p>@'.$f_username.'</p>
                                    </div>
                                </a>
                                <form method="POST" action="follow.php">
                                    <input type="hidden" name="id" value="'.$f_id.'">
                                    <input type="hidden" name="action" value="follow">
                                    <button type="submit" class="follow-btn">Connect</button>
                                </form>
                            </div>';
                            $count++;
                        }
                    }else {
                        echo '<p class="no-suggestions">No suggested friends</p>';
                    }
                ?>
            </div>
            <a href="#" class="see-all">See more</a>
        </div>

        <!-- TRENDING TOPICS -->
        <div class="trending-card">
            <div class="card-header">
                <h4>Trending</h4>
            </div>
            <div class="trending-list">
                <div class="trending-item">
                    <span class="trend-hashtag">#SocialMedia</span>
                    <span class="trend-count">2.1K posts</span>
                </div>
                <div class="trending-item">
                    <span class="trend-hashtag">#Technology</span>
                    <span class="trend-count">1.8K posts</span>
                </div>
                <div class="trending-item">
                    <span class="trend-hashtag">#Programming</span>
                    <span class="trend-count">1.2K posts</span>
                </div>
            </div>
        </div>

    </aside>

</div>

    <script src="assets/js/social-dashboard.js"></script>

</body>
</html>