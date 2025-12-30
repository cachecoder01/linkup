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

    <title>LinkUp | Social Media Platform</title>
    
    <link rel="shortcut icon" href="assets/images/logo.jpeg">
    <link rel="stylesheet" href="assets/css/social-dashboard.css">
    <link rel="stylesheet" href="assets/css/style.css">
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

            <div class="sidebar-footer">
                <div class="create-post-btn">
                    <i class="fas fa-plus"></i>
                    <span>Create Post</span>
                </div>
            </div>
        </aside>       

        <!-- MAIN FEED -->
        <main class="main-feed">
            <!-- CREATE POST MODAL -->
            <div class="modal-overlay" id="postModal">
                <div class="modal-content modal-post">
                    <div class="modal-header">
                        <h3>Create Post</h3>
                        <button class="close-modal" id="closeModal">&times;</button>
                    </div>
                    <form action="create_post.php" method="POST" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="post-content-section">
                                <textarea name="post_text" maxlength="500" class="post-textarea"
                                    placeholder="What's on your mind?" required>
                                </textarea>
                                <!-- Character Counter -->
                                <div class="char-counter">
                                    <span class="char-count">0</span>/500
                                </div>
                            </div>
                            <!-- Image Preview Area -->
                            <div class="image-preview-container" id="imagePreviewContainer">
                                <div class="image-preview-placeholder">
                                    <i class="fas fa-images"></i><span>Add photos to your post</span>
                                </div>
                            </div>
                            <!-- Post Actions -->
                            <div class="post-actions">
                                <div class="action-buttons">
                                    <label for="imageUpload" class="action-btn">
                                        <i class="fas fa-image"></i> Add Photo
                                    </label>
                                    <input type="file" id="imageUpload" name="img" multiple accept="image/*" style="display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="reset" class="btn-secondary" value="Cancel">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-paper-plane"></i> Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            include 'post.php';
                $poster_id = $_GET["id"];
                $profile = postProfile($poster_id);
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
            <!-- VIEW PROFILE MODAL -->
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
                                    <button class="btn-primary connect">
                                        <i class="fas fa-plus"></i>
                                        <span>Connect</span>
                                    </button>
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
                                            $stmt = $conn->prepare("SELECT * FROM friends WHERE user_id = ?");
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
                <!-- User Suggestion 1 -->
                <div class="suggestion-item">
                    <div class="suggestion-info">
                        <div class="suggestion-avatar">
                            <div class="default-avatar-placeholder">
                                <p>A</p>
                            </div>
                        </div>
                        <div class="suggestion-details">
                            <h5>Alex Johnson</h5>
                            <p>5 mutual friends</p>
                        </div>
                    </div>
                    <button class="follow-btn">Follow</button>
                </div>

                <!-- User Suggestion 2 -->
                <div class="suggestion-item">
                    <div class="suggestion-info">
                        <div class="suggestion-avatar">
                            <div class="default-avatar-placeholder">
                                <p>S</p>
                            </div>
                        </div>
                        <div class="suggestion-details">
                            <h5>Sarah Chen</h5>
                            <p>12 mutual friends</p>
                        </div>
                    </div>
                    <button class="follow-btn">Follow</button>
                </div>

                <!-- User Suggestion 3 -->
                <div class="suggestion-item">
                    <div class="suggestion-info">
                        <div class="suggestion-avatar">
                            <div class="default-avatar-placeholder">
                                <p>M</p>
                            </div>
                        </div>
                        <div class="suggestion-details">
                            <h5>Michael Davis</h5>
                            <p>3 mutual friends</p>
                        </div>
                    </div>
                    <button class="follow-btn">Follow</button>
                </div>

                <!-- User Suggestion 4 -->
                <div class="suggestion-item">
                    <div class="suggestion-info">
                        <div class="suggestion-avatar">
                            <div class="default-avatar-placeholder">
                                <p>E</p>
                            </div>
                        </div>
                        <div class="suggestion-details">
                            <h5>Emma Wilson</h5>
                            <p>8 mutual friends</p>
                        </div>
                    </div>
                    <button class="follow-btn">Follow</button>
                </div>

                <!-- User Suggestion 5 -->
                <div class="suggestion-item">
                    <div class="suggestion-info">
                        <div class="suggestion-avatar">
                            <div class="default-avatar-placeholder">
                                <p>J</p>
                            </div>
                        </div>
                        <div class="suggestion-details">
                            <h5>James Rodriguez</h5>
                            <p>2 mutual friends</p>
                        </div>
                    </div>
                    <button class="follow-btn">Connect</button>
                </div>
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