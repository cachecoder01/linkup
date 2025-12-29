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
        <?php
            if (empty($profile)) {
                echo '<div class="first-profile-edit">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Create Profile</h3>
                                <button class="close-modal">&times;</button>
                            </div>
                            <form method="POST" action="profile.php" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="profile-avatar-section">
                                        <div class="default-avatar-placeholder" id="currentAvatar">
                                            <p>'.strtoupper($p_avater).'</p>
                                        </div>
                                        <div class="avatar-actions">
                                            <button class="btn-secondary" id="changeAvatarBtn">
                                                <i class="fas fa-camera"></i> Add Photo
                                            </button>
                                            <input type="file" name="img" id="avatarInput" accept="image/*" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" name="name" class="form-input" placeholder="Enter Full Name" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Profession</label>
                                        <input type="text" name="profession" class="form-input" placeholder="Enter profession" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Bio</label>
                                        <textarea name="bio" class="form-textarea" placeholder="Tell us about yourself..." rows="4" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" name="location" class="form-input" placeholder="City, Country" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input class="btn-secondary" type="reset" value="Cancel">
                                    <button class="btn-primary" type="submit">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                ';
            }
        ?>

        <!-- LEFT SIDEBAR -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a class="nav-item tablink" onclick="openPage('Home', this, '#e7f3ff', '#1877f2')" id="defaultOpen">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="#" class="nav-item tablink" onclick="openPage('Friends', this, '#e7f3ff', '#1877f2')" data-section="profile">
                    <i class="fas fa-users"></i>
                    <span>Friends</span>
                </a>
                <a href="#" class="nav-item" data-section="profile">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>                
                <a href="logout.php" class="nav-item" onclick="return confirm('Are you sure you want to LogOut?')">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
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

            <!-- VIEW PROFILE MODAL -->
            <div class="modal-overlay" id="viewProfileModal">
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h3>My Profile</h3>
                        <button class="close-modal" id="closeViewProfileModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="profile-details">
                            <div class="profile-header-section">
                                <div class="profile-cover">
                                    <div class="cover-placeholder">
                                        <div class="default-avatar-placeholder">
                                            <?php
                                                if (empty($profile_img)) {
                                                    echo '<p>'.strtoupper($p_avater).'</p>';
                                                }else {
                                                    echo '<img src="assets/images/profiles/'.$profile_img.'">';
                                                }
                                            ?>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="profile-info-section">
                                    <div class="profile-details-info">
                                        <h2><?= $name ?></h2>
                                        <p class="profile-username">@<?= $username ?></p>
                                        <p class="profile-bio"><?= ucfirst($bio) ?></p>
                                        <div class="profile-meta">
                                            <span class="meta-item">
                                                <i class="fas fa-briefcase"></i> 
                                                <?php
                                                    if (empty($prof)) {
                                                        echo 'Not set yet';
                                                    }else {
                                                        echo $prof;
                                                    }
                                                ?>
                                            </span>
                                            <span class="meta-item">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?php
                                                    if (empty($location)) {
                                                        echo 'Not set yet';
                                                    }else {
                                                        echo $location;
                                                    }
                                                ?>
                                            </span>
                                            <span class="meta-item" id="profileJoined">
                                                <i class="fas fa-calendar-alt"></i> Joined <?= $datejoined ?>
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
                                    <span class="stat-label-large">Posts</span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-number-large">0</span>
                                    <span class="stat-label-large">Followers</span>
                                </div>
                                <div class="stat-detail">
                                    <span class="stat-number-large">0</span>
                                    <span class="stat-label-large">Following</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            

            <section class="tabcontent" id="Home">
                <div class="create-post-card">
                    <div class="create-post-header">
                            <div class="default-avatar-placeholder">
                                <?php
                                    if (empty($profile_img)) {
                                        echo '<p>'.strtoupper($p_avater).'</p>';
                                    }else {
                                        echo '<img src="assets/images/profiles/'.$profile_img.'">';
                                    }
                                ?>
                            </div>
                        <div class="create-post-input" id="createPostBtn">
                            <span class="placeholder-text">What's on your mind?</span>
                        </div>
                    </div>
                </div>

                <!-- FEED FILTERS -->
                <div>
                    <?php
                        // Get current feed filter (default = All)
                        $feed = $_SESSION['feed'] ?? 'All';

                        // Define available filters
                        $filters = [
                            'All' => [
                                'icon' => 'fa-list',
                                'label' => 'All'
                            ],
                            'Latest' => [
                                'icon' => 'fa-fire',
                                'label' => 'Latest'
                            ]
                        ];
                    ?>

                    <div class="feed-filters">
                        <?php foreach ($filters as $key => $filter): ?>
                        <form method="POST" action="feed.php">
                            <input type="hidden" name="feed_filter" value="<?= htmlspecialchars($key) ?>">
                            <button type="submit" class="filter-btn <?= ($feed === $key) ? 'active' : '' ?>">
                                <i class="fas <?= $filter['icon'] ?>"></i> <?= htmlspecialchars($filter['label']) ?>
                            </button>
                        </form>
                        <?php endforeach; ?>
                    </div>

                    <?php
                        include 'post.php';
                        $posts = feed($feed);
                        if (!empty($posts)) {
                            foreach ($posts as $post) {
                                $poster_id = $post["user_id"];
                                $post_text = $post["post_text"];
                                $post_img = $post["post_img"];
                                $post_date = $post["date"];

                                $profile = postProfile($poster_id);
                                $full_name = $profile['full_name'];
                                $p_username = $profile['username'];
                                $p_profile = $profile['profile_img'];

                                $pp_avatar = substr($p_username, 0, 1);

                                echo '<div class="dashboard-feed">
                                    <div class="posts-card">
                                        <div class="post-header">
                                            <a href="profile_view" class="avatar-container">
                                                <div class="default-avatar-placeholder">';
                                                    if (empty($p_profile)) {
                                                        echo '<p>'.strtoupper($pp_avatar).'</p>';
                                                    }else {
                                                        echo '<img src="assets/images/profiles/'.$p_profile.'">';
                                                    }
                                                   echo '</div>
                                                <div>
                                                    <div class="name">' .$full_name. '</div>
                                                    <p class="profile-username">@' .$p_username. '</p>
                                                </div>
                                            </a>                                            
                                            <div>                          
                                                <div class="post-date">' .$post_date. '</div>
                                            </div>
                                        </div>
                                        <div class="post-body">
                                            <p>' .$post_text. '</p>';
                                            if (!empty($post_img)) {
                                                echo '<img src="assets/images/posts/'.$post_img.'" alt="Post image">';
                                            }
                                    echo '</div>

                                        <div class="posts-actions">
                                            <button>❤️ Like</button>
                                            <button>💬 Comment</button>
                                            <button>🔁 Share</button>
                                        </div>
                                    </div>
                                </div>
                            ';
                            }                            
                        }
                    ?>
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
                    <span class="stat-label">Followers</span>
                </div>
                <div class="stat">
                    <span class="stat-number">0</span>
                    <span class="stat-label">Following</span>
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
                    <button class="follow-btn">Follow</button>
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
    <script>
        function openPage(pageName, elmnt, backgroundColor, textColor) {
            var i, tabcontent, tablinks;
        
            // Hide all tab contents
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
        
            // Reset all tab buttons
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.backgroundColor = "";
                tablinks[i].style.color = "";  // reset text color
            }
        
            // Show current tab
            document.getElementById(pageName).style.display = "block";
        
            // Apply active background + text color
            elmnt.style.backgroundColor = backgroundColor;
            elmnt.style.color = textColor;
        }
        
        // Check URL hash and open corresponding tab, or default to "All"
        const urlHash = window.location.hash.substring(1); // Remove the '#'
        if (urlHash) {
            const tabButton = document.querySelector(`button[onclick*="${urlHash}"]`);
            if (tabButton) {
                tabButton.click();
            } else {
                // If hash doesn't match any tab, default to "All"
                document.getElementById("defaultOpen").click();
            }
        } else {
            // No hash in URL, default to "All"
            document.getElementById("defaultOpen").click();
        }
    </script>

</body>
</html>
