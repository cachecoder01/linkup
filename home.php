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
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt ->bind_param("s", $email);
    $stmt ->execute();

    $result = $stmt -> get_result();
    if ($result -> num_rows > 0) {
        while ($row = $result -> fetch_assoc()) {
            $profile = $row["profile"];
            $username = $row["username"];
            
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
                <button class="icon-btn" title="Notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notification-badge">3</span>
                </button>
                <button class="icon-btn" title="Messages">
                    <i class="fas fa-envelope"></i>
                    <span class="notification-badge">2</span>
                </button>
            </div>
            <div class="user-menu user">
                <button class="icon-btn">
                    <div class="current-avatar">
                        <div class="default-avatar-placeholder" id="currentAvatar">
                            <?php
                            if (empty($p_img)) {
                                echo '<p>'.strtoupper($p_avater).'</p>';
                            }else {
                                echo '<img src="assets/images/profile/'.$profile_img.'">';
                            }
                            ?>
                        </div>
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
                /*echo '<div class="first-profile-edit">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3>Edit Profile</h3>
                                <button class="close-modal">&times;</button>
                            </div>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="profile-avatar-section">
                                        <div class="current-avatar">
                                                <div class="default-avatar-placeholder" id="currentAvatar">
                                                <p>'.strtoupper($p_avater).'</p>
                                            </div>
                                        </div>
                                        <div class="avatar-actions">
                                            <button class="btn-secondary" id="changeAvatarBtn">
                                                <i class="fas fa-camera"></i> Change Photo
                                            </button>
                                            <input type="file" name="profile_photo" id="avatarInput" accept="image/*" style="display: none;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" nam="username" class="form-input" placeholder="Enter username">
                                        </div>
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="email" name"full_name" class="form-input" placeholder="Enter email">
                                    </div>
                                    <div class="form-group">
                                        <label>Profession</label>
                                        <input type="text" name"profession" class="form-input" placeholder="Enter profession">
                                    </div>
                                    <div class="form-group">
                                        <label>Bio</label>
                                        <textarea name="bio" class="form-textarea" placeholder="Tell us about yourself..." rows="4"></textarea>
                                    </div>
                                        <div class="form-group">
                                        <label>Location</label>
                                        <input type="text" name="location" class="form-input" placeholder="City, Country">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input class="btn-secondary" type="reset" value="Cancel">
                                    <button class="btn-primary" type="submit">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                ';*/
            }
        ?>

        <!-- LEFT SIDEBAR -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a class="nav-item active tablink" onclick="openPage('Home', this, 'purple')" id="defaultOpen">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="#" class="nav-item tablink" onclick="openPage('Home', this, 'purple')" data-section="profile">
                    <i class="fas fa-users"></i>
                    <span>Followers</span>
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

            <!-- CREATE POST CARD -->
            <div class="create-post-card">
                <div class="create-post-header">
                    <div class="current-avatar">
                        <div class="default-avatar-placeholder" id="currentAvatar">
                            <?php
                            if (empty($p_img)) {
                                echo '<p>'.strtoupper($p_avater).'</p>';
                            }else {
                                echo '<img src="assets/images/profile/'.$profile_img.'">';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="create-post-input" id="createPostBtn">
                        <span class="placeholder-text">What's on your mind?</span>
                    </div>
                </div>
            </div>

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
                            <textarea
                                name="post_content"
                                placeholder="What's on your mind?"
                                class="post-textarea"
                                maxlength="500">
                            </textarea>
                            <!-- Character Counter -->
                            <div class="char-counter">
                                <span class="char-count">0</span>/500
                            </div>
                        </div>
                        <!-- Image Preview Area -->
                        <div class="image-preview-container" id="imagePreviewContainer">
                            <div class="image-preview-placeholder">
                                <i class="fas fa-images"></i>
                                <span>Add photos to your post</span>
                            </div>
                        </div>

                        <!-- Post Actions -->
                        <div class="post-actions">
                            <div class="action-buttons">
                                <label for="imageUpload" class="action-btn">
                                    <i class="fas fa-image"></i> Photo
                                </label>
                                <input type="file" id="imageUpload" name="post_images[]" multiple accept="image/*" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" onclick="closePostModal()">Cancel</button>
                        <button type="submit" class="btn-primary" id="submitPost">
                            <i class="fas fa-paper-plane"></i> Post
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- EDIT PROFILE MODAL -->
        <div class="modal-overlay" id="editProfileModal">
            <div class="modal-content modal-lg">
                <div class="modal-header">
                    <h3>Edit Profile</h3>
                    <button class="close-modal" id="closeEditProfileModal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="profile-edit-form">
                        <div class="profile-avatar-section">
                            <div class="current-avatar">
                                <img src="assets/images/default-avatar.png" alt="Current avatar" id="currentAvatar">
                            </div>
                            <div class="avatar-actions">
                                <button class="btn-secondary" id="changeAvatarBtn">
                                    <i class="fas fa-camera"></i> Change Photo
                                </button>
                                <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="editUsername">Username</label>
                            <input type="text" id="editUsername" class="form-input" placeholder="Enter username">
                        </div>

                        <div class="form-group">
                            <label for="editEmail">Email</label>
                            <input type="email" id="editEmail" class="form-input" placeholder="Enter email">
                        </div>

                        <div class="form-group">
                            <label for="editBio">Bio</label>
                            <textarea id="editBio" class="form-textarea" placeholder="Tell us about yourself..." rows="4"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="editLocation">Location</label>
                            <input type="text" id="editLocation" class="form-input" placeholder="City, Country">
                        </div>

                        <div class="form-group">
                            <label for="editWebsite">Website</label>
                            <input type="url" id="editWebsite" class="form-input" placeholder="https://yourwebsite.com">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn-secondary" id="cancelEditProfile">Cancel</button>
                    <button class="btn-primary" id="saveProfile">Save Changes</button>
                </div>
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
                                    <i class="fas fa-camera"></i>
                                    <span>Add Cover Photo</span>
                                </div>
                            </div>
                            <div class="profile-info-section">
                                <div class="profile-avatar-large">
                                    <img src="assets/images/default-avatar.png" alt="Profile" id="profileAvatarLarge">
                                </div>
                                <div class="profile-details-info">
                                    <h2 id="profileFullName">Your Name</h2>
                                    <p class="profile-username-display" id="profileUsernameDisplay">@username</p>
                                    <p class="profile-bio" id="profileBio">This user hasn't added a bio yet.</p>
                                    <div class="profile-meta">
                                        <span class="meta-item" id="profileLocation">
                                            <i class="fas fa-map-marker-alt"></i> Location not set
                                        </span>
                                        <span class="meta-item" id="profileWebsite">
                                            <i class="fas fa-link"></i> Website not set
                                        </span>
                                        <span class="meta-item" id="profileJoined">
                                            <i class="fas fa-calendar-alt"></i> Joined December 2024
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-stats-detailed">
                            <div class="stat-detail">
                                <span class="stat-number-large" id="profilePostsCount">0</span>
                                <span class="stat-label-large">Posts</span>
                            </div>
                            <div class="stat-detail">
                                <span class="stat-number-large" id="profileFollowersCount">0</span>
                                <span class="stat-label-large">Followers</span>
                            </div>
                            <div class="stat-detail">
                                <span class="stat-number-large" id="profileFollowingCount">0</span>
                                <span class="stat-label-large">Following</span>
                            </div>
                        </div>

                        <div class="profile-actions-section">
                            <button class="btn-primary" id="editProfileFromView">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FEED FILTERS -->
        <div class="feed-filters">
            <button class="filter-btn active" data-filter="all">
                <i class="fas fa-fire"></i> Trending
            </button>
            <button class="filter-btn" data-filter="following">
                <i class="fas fa-users"></i> Following
            </button>
            <button class="filter-btn" data-filter="latest">
                <i class="fas fa-clock"></i> Latest
            </button>
        </div>

        <!-- POSTS CONTAINER -->
        <div class="posts-container" id="postsContainer">
            <!-- Posts will be loaded here dynamically -->
        </div>

        <!-- LOADING INDICATOR -->
        <div class="loading-indicator" id="loadingIndicator">
            <div class="spinner"></div>
            <span>Loading more posts...</span>
        </div>

    </main>

    <!-- RIGHT SIDEBAR -->
    <aside class="right-sidebar">

        <!-- USER PROFILE CARD -->
        <div class="profile-card">
            <div class="profile-header">
                <img src="assets/images/default-avatar.png" alt="Profile" class="profile-avatar">
                <div class="profile-info">
                    <h4 class="profile-name" id="profileName">Your Name</h4>
                    <p class="profile-username" id="profileUsername">@username</p>
                </div>
            </div>
            <div class="profile-stats">
                <div class="stat">
                    <span class="stat-number" id="postsCount">0</span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat">
                    <span class="stat-number" id="followersCount">0</span>
                    <span class="stat-label">Followers</span>
                </div>
                <div class="stat">
                    <span class="stat-number" id="followingCount">0</span>
                    <span class="stat-label">Following</span>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn-outline">Edit Profile</button>
            </div>
        </div>

        <!-- FRIENDS LIST -->
        <div class="friends-card">
            <div class="card-header">
                <h4>Friends</h4>
                <a href="#" class="see-all">See all</a>
            </div>
            <div class="friends-list" id="friendsList">
                <!-- Friends will be loaded here -->
            </div>
        </div>

        <!-- SUGGESTIONS -->
        <div class="suggestions-card">
            <div class="card-header">
                <h4>People you may know</h4>
            </div>
            <div class="suggestions-list" id="suggestionsList">
                <!-- Suggestions will be loaded here -->
            </div>
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
		function openPage(pageName,elmnt,color) {
			var i, tabcontent, tablinks;
		    tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
		        tabcontent[i].style.display = "none";
			}
			tablinks = document.getElementsByClassName("tablink");
			for (i = 0; i < tablinks.length; i++) {
			    tablinks[i].style.backgroundColor = "";
			  }
			  document.getElementById(pageName).style.display = "block";
			  elmnt.style.backgroundColor = color;
		}
			
		// Get the element with id="defaultOpen" and click on it
		document.getElementById("defaultOpen").click();
	</script>

</body>
</html>
