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
                                    <span class="stat-number-large">
                                        <?php
                                            /*$user_id = $_SESSION['user_id'];
                                            echo friendsCount($user_id);*/
                                        
                                            $stmt = $conn->prepare("SELECT * FROM friends WHERE user_id = ?");
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
                                    <span class="stat-label-large">Friends</span>
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
                                            <a href="profile_view.php?id='.$poster_id.'" class="avatar-container">
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

            <section class="tabcontent" id="Friends">
                <!-- FRIENDS HEADER -->
                <div class="friends-header">
                    <div class="friends-title">
                        <h2>Friends</h2>                        
                        <span class="friends-count">
                            <i class="fa fa-users"></i>
                            0 friends</span>
                    </div>
                    <div class="friends-search">
                        <div class="search-container">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search friends..." id="friendSearch">
                        </div>
                    </div>
                </div>

                <!-- FRIENDS TABS -->
                <div class="friends-tabs">
                    <button class="friends-tab active" onclick="openFriendsTab('allFriends', this)">
                        <i class="fas fa-users"></i>
                        <span>All Friends</span>
                    </button>
                    <button class="friends-tab" onclick="openFriendsTab('friendRequests', this)">
                        <i class="fas fa-user-plus"></i>
                        <span>Friend Requests</span>
                        <span class="tab-badge">0</span>
                    </button>
                    <button class="friends-tab" onclick="openFriendsTab('findFriends', this)">
                        <i class="fas fa-search"></i>
                        <span>Find Friends</span>
                    </button>
                </div>

                <!-- FRIENDS CONTENT -->
                <div class="friends-content">
                    <!-- ALL FRIENDS TAB -->
                    <div id="allFriends" class="friends-tab-content active">
                        <div class="friends-toolbar">
                            <div class="friends-sort">
                                <select class="sort-select">
                                    <option value="recent">Recently Added</option>
                                    <option value="name">Name (A-Z)</option>
                                    <option value="online">Online First</option>
                                </select>
                            </div>
                            <div class="friends-view-toggle">
                                <button class="view-btn active" data-view="grid">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button class="view-btn" data-view="list">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>

                        <div class="friends-list" id="friendsList">
                            <!-- Friend Item -->
                            <div class="friend-item">
                                <div class="friend-info">
                                    <div class="friend-avatar">
                                        <div class="default-avatar-placeholder">
                                            <p>J</p>
                                        </div>
                                        <div class="online-status online"></div>
                                        <div class="friend-badge premium">
                                            <i class="fas fa-crown"></i>
                                        </div>
                                    </div>
                                    <div class="friend-details">
                                        <div class="friend-name-row">
                                            <h4>John Doe</h4>
                                            <div class="friend-menu">
                                                <button class="friend-menu-btn">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div class="friend-menu-dropdown">
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-user-check"></i> View Profile
                                                    </a>
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-envelope"></i> Send Message
                                                    </a>
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-user-times"></i> Unfriend
                                                    </a>
                                                    <a href="#" class="menu-item danger">
                                                        <i class="fas fa-ban"></i> Block
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <p>@johndoe</p>
                                        <div class="friend-meta">
                                            <span class="friend-status">Online</span>
                                            <span class="friend-location">New York, NY</span>
                                        </div>
                                        <div class="friend-mutual">
                                            <i class="fas fa-users"></i> 5 mutual friends
                                        </div>
                                    </div>
                                </div>
                                <div class="friend-actions">
                                    <button class="friend-action-btn message-btn">
                                        <i class="fas fa-envelope"></i>
                                        <span>Message</span>
                                    </button>
                                    <button class="friend-action-btn call-btn">
                                        <i class="fas fa-phone"></i>
                                        <span>Call</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Friend Item -->
                            <div class="friend-item">
                                <div class="friend-info">
                                    <div class="friend-avatar">
                                        <div class="default-avatar-placeholder">
                                            <p>S</p>
                                        </div>
                                        <div class="online-status offline"></div>
                                    </div>
                                    <div class="friend-details">
                                        <div class="friend-name-row">
                                            <h4>Sarah Wilson</h4>
                                            <div class="friend-menu">
                                                <button class="friend-menu-btn">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div class="friend-menu-dropdown">
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-user-check"></i> View Profile
                                                    </a>
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-envelope"></i> Send Message
                                                    </a>
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-user-times"></i> Unfriend
                                                    </a>
                                                    <a href="#" class="menu-item danger">
                                                        <i class="fas fa-ban"></i> Block
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <p>@sarahw</p>
                                        <div class="friend-meta">
                                            <span class="friend-status">Last seen 2 hours ago</span>
                                            <span class="friend-location">Los Angeles, CA</span>
                                        </div>
                                        <div class="friend-mutual">
                                            <i class="fas fa-users"></i> 12 mutual friends
                                        </div>
                                    </div>
                                </div>
                                <div class="friend-actions">
                                    <button class="friend-action-btn message-btn">
                                        <i class="fas fa-envelope"></i>
                                        <span>Message</span>
                                    </button>
                                    <button class="friend-action-btn call-btn">
                                        <i class="fas fa-phone"></i>
                                        <span>Call</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Friend Item -->
                            <div class="friend-item">
                                <div class="friend-info">
                                    <div class="friend-avatar">
                                        <div class="default-avatar-placeholder">
                                            <p>M</p>
                                        </div>
                                        <div class="online-status online"></div>
                                    </div>
                                    <div class="friend-details">
                                        <div class="friend-name-row">
                                            <h4>Mike Johnson</h4>
                                            <div class="friend-menu">
                                                <button class="friend-menu-btn">
                                                    <i class="fas fa-ellipsis-h"></i>
                                                </button>
                                                <div class="friend-menu-dropdown">
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-user-check"></i> View Profile
                                                    </a>
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-envelope"></i> Send Message
                                                    </a>
                                                    <a href="#" class="menu-item">
                                                        <i class="fas fa-user-times"></i> Unfriend
                                                    </a>
                                                    <a href="#" class="menu-item danger">
                                                        <i class="fas fa-ban"></i> Block
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <p>@mikej</p>
                                        <div class="friend-meta">
                                            <span class="friend-status">Online</span>
                                            <span class="friend-location">Chicago, IL</span>
                                        </div>
                                        <div class="friend-mutual">
                                            <i class="fas fa-users"></i> 8 mutual friends
                                        </div>
                                    </div>
                                </div>
                                <div class="friend-actions">
                                    <button class="friend-action-btn message-btn">
                                        <i class="fas fa-envelope"></i>
                                        <span>Message</span>
                                    </button>
                                    <button class="friend-action-btn call-btn">
                                        <i class="fas fa-phone"></i>
                                        <span>Call</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Loading State -->
                            <div class="friends-loading" style="display: none;">
                                <div class="loading-spinner"></div>
                                <p>Loading friends...</p>
                            </div>

                            <!-- No Friends Message -->
                            <div class="no-friends" style="display: none;">
                                <div class="no-friends-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3>No friends yet</h3>
                                <p>Start connecting with people by sending friend requests or searching for friends.</p>
                                <button class="btn-primary find-friends-btn" onclick="openFriendsTab('findFriends', document.querySelector('.friends-tab[onclick*=\"findFriends\"]'))">
                                    Find Friends
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- FRIEND REQUESTS TAB -->
                    <div id="friendRequests" class="friends-tab-content">
                        <div class="requests-toolbar">
                            <div class="requests-filter">
                                <button class="filter-btn active" data-filter="all">All</button>
                                <button class="filter-btn" data-filter="received">Received</button>
                                <button class="filter-btn" data-filter="sent">Sent</button>
                            </div>
                        </div>

                        <div class="requests-container">
                            <!-- Received Requests -->
                            <div class="requests-section received-requests">
                                <div class="section-header">
                                    <h3>Friend Requests</h3>
                                    <span class="request-count">2</span>
                                </div>
                                <div class="requests-list">
                                    <!-- Request Item -->
                                    <div class="request-item">
                                        <div class="request-info">
                                            <div class="request-avatar">
                                                <div class="default-avatar-placeholder">
                                                    <p>M</p>
                                                </div>
                                            </div>
                                            <div class="request-details">
                                                <h4>Mike Johnson</h4>
                                                <p>@mikej</p>
                                                <div class="request-meta">
                                                    <span class="mutual-count">2 mutual friends</span>
                                                    <span class="request-time">2 days ago</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="request-actions">
                                            <button class="btn-primary accept-btn" onclick="acceptFriendRequest(this)">
                                                <i class="fas fa-check"></i>
                                                Accept
                                            </button>
                                            <button class="btn-secondary decline-btn" onclick="declineFriendRequest(this)">
                                                <i class="fas fa-times"></i>
                                                Decline
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Request Item -->
                                    <div class="request-item">
                                        <div class="request-info">
                                            <div class="request-avatar">
                                                <div class="default-avatar-placeholder">
                                                    <p>E</p>
                                                </div>
                                            </div>
                                            <div class="request-details">
                                                <h4>Emma Davis</h4>
                                                <p>@emmad</p>
                                                <div class="request-meta">
                                                    <span class="mutual-count">5 mutual friends</span>
                                                    <span class="request-time">1 week ago</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="request-actions">
                                            <button class="btn-primary accept-btn" onclick="acceptFriendRequest(this)">
                                                <i class="fas fa-check"></i>
                                                Accept
                                            </button>
                                            <button class="btn-secondary decline-btn" onclick="declineFriendRequest(this)">
                                                <i class="fas fa-times"></i>
                                                Decline
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sent Requests -->
                            <div class="requests-section sent-requests">
                                <div class="section-header">
                                    <h3>Sent Requests</h3>
                                    <span class="request-count">1</span>
                                </div>
                                <div class="requests-list">
                                    <!-- Sent Request Item -->
                                    <div class="request-item sent">
                                        <div class="request-info">
                                            <div class="request-avatar">
                                                <div class="default-avatar-placeholder">
                                                    <p>A</p>
                                                </div>
                                            </div>
                                            <div class="request-details">
                                                <h4>Alex Chen</h4>
                                                <p>@alexchen</p>
                                                <div class="request-meta">
                                                    <span class="request-status">Pending</span>
                                                    <span class="request-time">3 days ago</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="request-actions">
                                            <button class="btn-secondary cancel-btn" onclick="cancelFriendRequest(this)">
                                                <i class="fas fa-times"></i>
                                                Cancel Request
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- No Requests Message -->
                            <div class="no-requests" style="display: none;">
                                <div class="no-requests-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h3>No friend requests</h3>
                                <p>When someone sends you a friend request, it will appear here.</p>
                            </div>
                        </div>
                    </div>

                    <!-- FIND FRIENDS TAB -->
                    <div id="findFriends" class="friends-tab-content">
                        <div class="find-friends-toolbar">
                            <div class="search-filters">
                                <select class="filter-select">
                                    <option value="all">All People</option>
                                    <option value="mutual">With Mutual Friends</option>
                                    <option value="location">Nearby</option>
                                    <option value="interests">Similar Interests</option>
                                </select>
                            </div>
                        </div>

                        <div class="find-friends-container">
                            <!-- Search Suggestions -->
                            <div class="search-suggestions">
                                <div class="suggestions-header">
                                    <h3>People you may know</h3>
                                    <p>Based on your mutual friends and activity</p>
                                </div>
                                <div class="suggestions-grid">
                                    <!-- Suggestion Card -->
                                    <div class="suggestion-card">
                                        <div class="suggestion-header">
                                            <div class="suggestion-avatar">
                                                <div class="default-avatar-placeholder">
                                                    <p>R</p>
                                                </div>
                                                <div class="online-indicator online"></div>
                                            </div>
                                            <div class="suggestion-actions">
                                                <button class="btn-primary add-friend-btn" onclick="sendFriendRequest(this)">
                                                    <i class="fas fa-user-plus"></i>
                                                    Add Friend
                                                </button>
                                            </div>
                                        </div>
                                        <div class="suggestion-details">
                                            <h4>Robert Brown</h4>
                                            <p>@robertb</p>
                                            <div class="suggestion-meta">
                                                <span class="mutual-friends">8 mutual friends</span>
                                                <span class="common-interest">Likes photography</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Suggestion Card -->
                                    <div class="suggestion-card">
                                        <div class="suggestion-header">
                                            <div class="suggestion-avatar">
                                                <div class="default-avatar-placeholder">
                                                    <p>D</p>
                                                </div>
                                                <div class="online-indicator online"></div>
                                            </div>
                                            <div class="suggestion-actions">
                                                <button class="btn-primary add-friend-btn" onclick="sendFriendRequest(this)">
                                                    <i class="fas fa-user-plus"></i>
                                                    Add Friend
                                                </button>
                                            </div>
                                        </div>
                                        <div class="suggestion-details">
                                            <h4>David Lee</h4>
                                            <p>@davidl</p>
                                            <div class="suggestion-meta">
                                                <span class="mutual-friends">3 mutual friends</span>
                                                <span class="common-interest">From San Francisco</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Suggestion Card -->
                                    <div class="suggestion-card">
                                        <div class="suggestion-header">
                                            <div class="suggestion-avatar">
                                                <div class="default-avatar-placeholder">
                                                    <p>C</p>
                                                </div>
                                                <div class="online-indicator offline"></div>
                                            </div>
                                            <div class="suggestion-actions">
                                                <button class="btn-primary add-friend-btn" onclick="sendFriendRequest(this)">
                                                    <i class="fas fa-user-plus"></i>
                                                    Add Friend
                                                </button>
                                            </div>
                                        </div>
                                        <div class="suggestion-details">
                                            <h4>Carol Martinez</h4>
                                            <p>@carolm</p>
                                            <div class="suggestion-meta">
                                                <span class="mutual-friends">15 mutual friends</span>
                                                <span class="common-interest">Loves hiking</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="load-more-container">
                                    <button class="btn-secondary load-more-btn">
                                        <i class="fas fa-plus"></i>
                                        Load More Suggestions
                                    </button>
                                </div>
                            </div>

                            <!-- Search Results (shown when searching) -->
                            <div class="search-results" style="display: none;">
                                <div class="results-header">
                                    <h3>Search Results</h3>
                                    <span class="results-count">0 results</span>
                                </div>
                                <div class="results-list">
                                    <!-- Results will be populated by JavaScript -->
                                </div>
                                <div class="no-results" style="display: none;">
                                    <div class="no-results-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h4>No results found</h4>
                                    <p>Try searching with a different name or username.</p>
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

        // Friends tabs functionality
        function openFriendsTab(tabName, elmnt) {
            var i, tabcontent, tablinks;

            // Hide all friends tab contents
            tabcontent = document.getElementsByClassName("friends-tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].classList.remove("active");
            }

            // Reset all friends tab buttons
            tablinks = document.getElementsByClassName("friends-tab");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].classList.remove("active");
            }

            // Show current tab
            document.getElementById(tabName).classList.add("active");

            // Apply active class to clicked tab
            elmnt.classList.add("active");
        }

        // Enhanced friend menu functionality
        document.addEventListener('click', function(e) {
            // Close all friend menus when clicking outside
            if (!e.target.closest('.friend-menu')) {
                document.querySelectorAll('.friend-menu-dropdown').forEach(dropdown => {
                    dropdown.classList.remove('active');
                });
            }

            // Toggle friend menu
            if (e.target.closest('.friend-menu-btn')) {
                const menuBtn = e.target.closest('.friend-menu-btn');
                const dropdown = menuBtn.parentElement.querySelector('.friend-menu-dropdown');

                // Close other menus
                document.querySelectorAll('.friend-menu-dropdown').forEach(d => {
                    if (d !== dropdown) d.classList.remove('active');
                });

                // Toggle current menu
                dropdown.classList.toggle('active');
            }
        });

        // Friend sorting functionality
        document.querySelector('.sort-select')?.addEventListener('change', function(e) {
            const sortBy = e.target.value;
            const friendsList = document.getElementById('friendsList');
            const friendItems = Array.from(document.querySelectorAll('.friend-item'));

            friendItems.sort((a, b) => {
                const nameA = a.querySelector('.friend-details h4').textContent.toLowerCase();
                const nameB = b.querySelector('.friend-details h4').textContent.toLowerCase();

                switch(sortBy) {
                    case 'name':
                        return nameA.localeCompare(nameB);
                    case 'online':
                        const statusA = a.querySelector('.friend-status').textContent.includes('Online') ? 1 : 0;
                        const statusB = b.querySelector('.friend-status').textContent.includes('Online') ? 1 : 0;
                        return statusB - statusA; // Online first
                    case 'recent':
                    default:
                        return 0; // Keep original order
                }
            });

            // Re-append sorted items
            friendItems.forEach(item => friendsList.appendChild(item));
        });

        // View toggle functionality
        document.querySelectorAll('.view-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // Could implement grid/list view switching here
            });
        });

        // Friend request filter functionality
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Update active filter button
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                // Show/hide request sections
                const receivedSection = document.querySelector('.received-requests');
                const sentSection = document.querySelector('.sent-requests');

                if (filter === 'received') {
                    receivedSection.style.display = 'block';
                    sentSection.style.display = 'none';
                } else if (filter === 'sent') {
                    receivedSection.style.display = 'none';
                    sentSection.style.display = 'block';
                } else {
                    receivedSection.style.display = 'block';
                    sentSection.style.display = 'block';
                }
            });
        });

        // Friend search functionality
        document.getElementById('friendSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const friendItems = document.querySelectorAll('.friend-item');
            const searchResults = document.querySelector('.search-results');
            const searchSuggestions = document.querySelector('.search-suggestions');

            if (searchTerm.length > 0) {
                // Show search results
                searchResults.style.display = 'block';
                searchSuggestions.style.display = 'none';

                // Filter friends
                let hasResults = false;
                friendItems.forEach(item => {
                    const name = item.querySelector('.friend-details h4').textContent.toLowerCase();
                    const username = item.querySelector('.friend-details p').textContent.toLowerCase();

                    if (name.includes(searchTerm) || username.includes(searchTerm)) {
                        item.style.display = 'flex';
                        hasResults = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Show no results message if no matches
                const noResults = document.querySelector('.no-results');
                noResults.style.display = hasResults ? 'none' : 'block';
            } else {
                // Show suggestions when search is empty
                searchResults.style.display = 'none';
                searchSuggestions.style.display = 'block';

                // Show all friends
                friendItems.forEach(item => {
                    item.style.display = 'flex';
                });
            }
        });

        // Friend action functions (placeholders for backend integration)
        function sendFriendRequest(button) {
            button.innerHTML = '<i class="fas fa-check"></i> Request Sent';
            button.style.background = '#42b883';
            button.disabled = true;
            // Add backend call here
        }

        function acceptFriendRequest(button) {
            const requestItem = button.closest('.request-item');
            requestItem.style.opacity = '0.5';
            button.innerHTML = '<i class="fas fa-check"></i> Accepted';
            button.disabled = true;
            // Add backend call here
        }

        function declineFriendRequest(button) {
            const requestItem = button.closest('.request-item');
            requestItem.remove();
            // Add backend call here
        }

        function cancelFriendRequest(button) {
            const requestItem = button.closest('.request-item');
            requestItem.remove();
            // Add backend call here
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
