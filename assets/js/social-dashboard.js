// Social Media Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the dashboard
    initializeDashboard();
});

function initializeDashboard() {
    // Load user data
    loadUserData();

    // Load initial posts
    loadPosts('all');

    // Load friends and suggestions
    loadFriends();
    loadSuggestions();

    // Set up event listeners
    setupEventListeners();

    // Set up infinite scroll
    setupInfiniteScroll();
}

function setupEventListeners() {
    // Create post modal
    const createPostBtn = document.getElementById('createPostBtn');
    const postModal = document.getElementById('postModal');
    const closeModal = document.getElementById('closeModal');
    const cancelPost = document.getElementById('cancelPost');
    const submitPost = document.getElementById('submitPost');
    const createPostSidebarBtn = document.querySelector('.create-post-btn');

    // Open modal
    if (createPostBtn) {
        createPostBtn.addEventListener('click', () => openPostModal());
    }
    if (createPostSidebarBtn) {
        createPostSidebarBtn.addEventListener('click', () => openPostModal());
    }

    // Close modal
    if (closeModal) {
        closeModal.addEventListener('click', () => closePostModal());
    }
    if (cancelPost) {
        cancelPost.addEventListener('click', () => closePostModal());
    }

    // Submit post
    if (submitPost) {
        submitPost.addEventListener('click', () => submitNewPost());
    }

    // Close modal when clicking overlay
    if (postModal) {
        postModal.addEventListener('click', (e) => {
            if (e.target === postModal) {
                closePostModal();
            }
        });
    }

    // Feed filters
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const filter = btn.dataset.filter;
            loadPosts(filter);
        });
    });

    // Mobile sidebar toggle
    if (window.innerWidth <= 768) {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.createElement('button');
        menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
        menuToggle.className = 'mobile-menu-toggle';
        menuToggle.style.cssText = `
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1001;
            background: #1877f2;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        `;
        document.body.appendChild(menuToggle);

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }

    // User dropdown toggle
    const userMenu = document.querySelector('.user-menu');
    const userDropdown = document.querySelector('.user-dropdown');

    if (userMenu && userDropdown) {
        userMenu.addEventListener('mouseenter', () => {
            userDropdown.style.opacity = '1';
            userDropdown.style.visibility = 'visible';
            userDropdown.style.transform = 'translateY(0)';
        });

        userMenu.addEventListener('mouseleave', () => {
            userDropdown.style.opacity = '0';
            userDropdown.style.visibility = 'hidden';
            userDropdown.style.transform = 'translateY(-10px)';
        });
    }
}

function openPostModal() {
    const modal = document.getElementById('postModal');
    const textarea = document.getElementById('postContent');

    if (modal && textarea) {
        modal.classList.add('active');
        textarea.focus();
        loadUserDataForModal();
    }
}

function closePostModal() {
    const modal = document.getElementById('postModal');
    const textarea = document.getElementById('postContent');

    if (modal && textarea) {
        modal.classList.remove('active');
        textarea.value = '';
    }
}

function submitNewPost() {
    const content = document.getElementById('postContent').value.trim();

    if (!content) {
        alert('Please write something before posting!');
        return;
    }

    // Show loading state
    const submitBtn = document.getElementById('submitPost');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Posting...';
    submitBtn.disabled = true;

    // Send post to server
    fetch('api/create_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closePostModal();
            loadPosts('all'); // Reload posts
            loadUserData(); // Update user stats
        } else {
            alert('Error creating post: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating post. Please try again.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

function loadUserData() {
    fetch('api/get_user_data.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateUserProfile(data.user);
        }
    })
    .catch(error => {
        console.error('Error loading user data:', error);
    });
}

function loadUserDataForModal() {
    fetch('api/get_user_data.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modalUserName = document.getElementById('modalUserName');
            if (modalUserName) {
                modalUserName.textContent = data.user.username;
            }
        }
    })
    .catch(error => {
        console.error('Error loading user data for modal:', error);
    });
}

function updateUserProfile(user) {
    const profileName = document.getElementById('profileName');
    const postsCount = document.getElementById('postsCount');
    const followersCount = document.getElementById('followersCount');
    const followingCount = document.getElementById('followingCount');

    if (profileName) profileName.textContent = user.username || 'Your Name';
    if (postsCount) postsCount.textContent = user.posts_count || 0;
    if (followersCount) followersCount.textContent = user.followers_count || 0;
    if (followingCount) followingCount.textContent = user.following_count || 0;
}

function loadPosts(filter = 'all') {
    const postsContainer = document.getElementById('postsContainer');
    const loadingIndicator = document.getElementById('loadingIndicator');

    if (loadingIndicator) {
        loadingIndicator.style.display = 'flex';
    }

    fetch(`api/get_posts.php?filter=${filter}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && postsContainer) {
            renderPosts(data.posts);
        } else if (postsContainer) {
            postsContainer.innerHTML = '<p class="text-center">Error loading posts</p>';
        }
    })
    .catch(error => {
        console.error('Error loading posts:', error);
        if (postsContainer) {
            postsContainer.innerHTML = '<p class="text-center">Error loading posts</p>';
        }
    })
    .finally(() => {
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
        }
    });
}

function renderPosts(posts) {
    const postsContainer = document.getElementById('postsContainer');

    if (!postsContainer) return;

    if (posts.length === 0) {
        postsContainer.innerHTML = '<p class="text-center">No posts yet. Be the first to share something!</p>';
        return;
    }

    const postsHTML = posts.map(post => `
        <div class="post-card" data-post-id="${post.id}">
            <div class="post-header">
                <div class="post-user-info">
                    <img src="${post.avatar || 'assets/images/default-avatar.png'}" alt="Avatar" class="post-user-avatar">
                    <div class="post-user-details">
                        <h4>${post.username}</h4>
                        <p>${formatTimeAgo(post.created_at)}</p>
                    </div>
                </div>
                <div class="post-menu">
                    <button class="post-menu-btn">
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                </div>
            </div>
            <div class="post-content">
                ${formatPostContent(post.content)}
            </div>
            <div class="post-actions">
                <div class="post-stats">
                    <span><i class="fas fa-heart"></i> ${post.likes_count || 0}</span>
                    <span><i class="fas fa-comment"></i> ${post.comments_count || 0}</span>
                    <span><i class="fas fa-share"></i> ${post.shares_count || 0}</span>
                </div>
                <div class="action-buttons">
                    <button class="action-btn like-btn ${post.is_liked ? 'liked' : ''}" onclick="toggleLike(${post.id})">
                        <i class="fas fa-heart"></i> Like
                    </button>
                    <button class="action-btn">
                        <i class="fas fa-comment"></i> Comment
                    </button>
                    <button class="action-btn">
                        <i class="fas fa-share"></i> Share
                    </button>
                </div>
            </div>
        </div>
    `).join('');

    postsContainer.innerHTML = postsHTML;

    // Add fade-in animation
    const postCards = postsContainer.querySelectorAll('.post-card');
    postCards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in');
        }, index * 100);
    });
}

function loadFriends() {
    fetch('api/get_friends.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderFriends(data.friends);
        }
    })
    .catch(error => {
        console.error('Error loading friends:', error);
    });
}

function renderFriends(friends) {
    const friendsList = document.getElementById('friendsList');

    if (!friendsList) return;

    if (friends.length === 0) {
        friendsList.innerHTML = '<p class="text-center">No friends yet</p>';
        return;
    }

    const friendsHTML = friends.map(friend => `
        <div class="friend-item">
            <img src="${friend.avatar || 'assets/images/default-avatar.png'}" alt="Avatar" class="friend-avatar">
            <div class="friend-info">
                <h5>${friend.username}</h5>
                <p>Active ${friend.last_active || 'recently'}</p>
            </div>
        </div>
    `).join('');

    friendsList.innerHTML = friendsHTML;
}

function loadSuggestions() {
    fetch('api/get_suggestions.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderSuggestions(data.suggestions);
        }
    })
    .catch(error => {
        console.error('Error loading suggestions:', error);
    });
}

function renderSuggestions(suggestions) {
    const suggestionsList = document.getElementById('suggestionsList');

    if (!suggestionsList) return;

    if (suggestions.length === 0) {
        suggestionsList.innerHTML = '<p class="text-center">No suggestions available</p>';
        return;
    }

    const suggestionsHTML = suggestions.map(suggestion => `
        <div class="suggestion-item">
            <div class="suggestion-info">
                <img src="${suggestion.avatar || 'assets/images/default-avatar.png'}" alt="Avatar" class="suggestion-avatar">
                <div class="suggestion-details">
                    <h5>${suggestion.username}</h5>
                    <p>${suggestion.mutual_friends || 0} mutual friends</p>
                </div>
            </div>
            <button class="follow-btn" onclick="followUser(${suggestion.id})">
                Follow
            </button>
        </div>
    `).join('');

    suggestionsList.innerHTML = suggestionsHTML;
}

function toggleLike(postId) {
    fetch('api/toggle_like.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            post_id: postId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the like count and button state
            const postCard = document.querySelector(`[data-post-id="${postId}"]`);
            if (postCard) {
                const likeBtn = postCard.querySelector('.like-btn');
                const likeCount = postCard.querySelector('.post-stats span:first-child');

                if (likeBtn) {
                    likeBtn.classList.toggle('liked');
                }
                if (likeCount) {
                    likeCount.innerHTML = `<i class="fas fa-heart"></i> ${data.likes_count}`;
                }
            }
        }
    })
    .catch(error => {
        console.error('Error toggling like:', error);
    });
}

function followUser(userId) {
    fetch('api/follow_user.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload suggestions and user data
            loadSuggestions();
            loadUserData();
        }
    })
    .catch(error => {
        console.error('Error following user:', error);
    });
}

function setupInfiniteScroll() {
    const postsContainer = document.getElementById('postsContainer');
    let loading = false;
    let page = 1;

    window.addEventListener('scroll', () => {
        if (loading) return;

        const { scrollTop, scrollHeight, clientHeight } = document.documentElement;

        if (scrollTop + clientHeight >= scrollHeight - 100) {
            loading = true;
            loadMorePosts();
        }
    });
}

function loadMorePosts() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    if (loadingIndicator) {
        loadingIndicator.style.display = 'flex';
    }

    // This would load more posts with pagination
    setTimeout(() => {
        if (loadingIndicator) {
            loadingIndicator.style.display = 'none';
        }
        // In a real implementation, you'd fetch more posts here
    }, 1000);
}

function formatTimeAgo(dateString) {
    const now = new Date();
    const postDate = new Date(dateString);
    const diffInSeconds = Math.floor((now - postDate) / 1000);

    if (diffInSeconds < 60) return 'just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h`;
    if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d`;

    return postDate.toLocaleDateString();
}

function formatPostContent(content) {
    // Basic formatting - in a real app, you'd use a proper markdown parser
    return content
        .replace(/\n/g, '<br>')
        .replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>')
        .replace(/#(\w+)/g, '<a href="#" class="hashtag">#$1</a>')
        .replace(/@(\w+)/g, '<a href="#" class="mention">@$1</a>');
}

function logout() {
    fetch('api/logout.php')
    .then(() => {
        window.location.href = 'login.html';
    })
    .catch(error => {
        console.error('Error logging out:', error);
        window.location.href = 'login.html';
    });
}

// Handle window resize for responsive design
window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) {
            sidebar.classList.remove('active');
        }
    }
});

// Search functionality
const searchInput = document.querySelector('.search-input');
if (searchInput) {
    searchInput.addEventListener('input', debounce(function(e) {
        const query = e.target.value.trim();
        if (query.length > 2) {
            searchUsers(query);
        }
    }, 300));
}

function searchUsers(query) {
    fetch(`api/search_users.php?q=${encodeURIComponent(query)}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displaySearchResults(data.users);
        }
    })
    .catch(error => {
        console.error('Error searching users:', error);
    });
}

function displaySearchResults(users) {
    // This would show search results in a dropdown
    console.log('Search results:', users);
}

// Profile modal functionality
function initializeProfileModals() {
    const editProfileModal = document.getElementById('editProfileModal');
    const viewProfileModal = document.getElementById('viewProfileModal');
    const editProfileBtn = document.querySelector('.profile-actions .btn-outline');
    const profileNavBtn = document.querySelector('.nav-item[data-section="profile"]:nth-child(3)');
    const profileDropdownBtn = document.querySelector('.dropdown-item:first-child');
    const closeEditBtn = document.getElementById('closeEditProfileModal');
    const closeViewBtn = document.getElementById('closeViewProfileModal');
    const cancelEditProfile = document.getElementById('cancelEditProfile');
    const saveProfile = document.getElementById('saveProfile');
    const editProfileFromView = document.getElementById('editProfileFromView');

    // Open edit profile modal
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openEditProfileModal();
        });
    }

    // Open view profile modal from sidebar
    if (profileNavBtn) {
        profileNavBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openViewProfileModal();
        });
    }

    // Open profile modal from dropdown
    if (profileDropdownBtn) {
        profileDropdownBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openViewProfileModal();
        });
    }

    // Close modals
    if (closeEditBtn) {
        closeEditBtn.addEventListener('click', () => {
            console.log('Close edit profile button clicked'); // Debug log
            closeEditProfileModal();
        });
    }
    if (closeViewBtn) {
        closeViewBtn.addEventListener('click', () => {
            console.log('Close view profile button clicked'); // Debug log
            closeViewProfileModal();
        });
    }
    if (cancelEditProfile) {
        cancelEditProfile.addEventListener('click', () => closeEditProfileModal());
    }

    // Switch from view to edit profile
    if (editProfileFromView) {
        editProfileFromView.addEventListener('click', () => {
            closeViewProfileModal();
            openEditProfileModal();
        });
    }

    // Save profile changes
    if (saveProfile) {
        saveProfile.addEventListener('click', () => {
            saveProfileChanges();
        });
    }

    // Close modals when clicking overlay
    if (editProfileModal) {
        editProfileModal.addEventListener('click', (e) => {
            if (e.target === editProfileModal) {
                closeEditProfileModal();
            }
        });
    }

    if (viewProfileModal) {
        viewProfileModal.addEventListener('click', (e) => {
            if (e.target === viewProfileModal) {
                closeViewProfileModal();
            }
        });
    }

    // Avatar change functionality
    const changeAvatarBtn = document.getElementById('changeAvatarBtn');
    const avatarInput = document.getElementById('avatarInput');

    if (changeAvatarBtn && avatarInput) {
        changeAvatarBtn.addEventListener('click', () => {
            avatarInput.click();
        });

        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const currentAvatar = document.getElementById('currentAvatar');
                    if (currentAvatar) {
                        currentAvatar.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

function openEditProfileModal() {
    // Load current user data
    loadUserDataForEdit();
    const editProfileModal = document.getElementById('editProfileModal');
    if (editProfileModal) {
        editProfileModal.classList.add('active');
    }
}

function closeEditProfileModal() {
    const editProfileModal = document.getElementById('editProfileModal');
    if (editProfileModal) {
        editProfileModal.classList.remove('active');
    }
}

function openViewProfileModal() {
    // Load user profile data
    loadUserProfileData();
    const viewProfileModal = document.getElementById('viewProfileModal');
    if (viewProfileModal) {
        viewProfileModal.classList.add('active');
    }
}

function closeViewProfileModal() {
    const viewProfileModal = document.getElementById('viewProfileModal');
    if (viewProfileModal) {
        viewProfileModal.classList.remove('active');
    }
}

function loadUserDataForEdit() {
    // Load user data from existing profile display
    const profileName = document.getElementById('profileName');
    const username = profileName ? profileName.textContent : 'Your Name';
    const email = 'user@example.com'; // This would come from the backend

    const editUsername = document.getElementById('editUsername');
    const editEmail = document.getElementById('editEmail');

    if (editUsername) editUsername.value = username;
    if (editEmail) editEmail.value = email;

    const editBio = document.getElementById('editBio');
    const editLocation = document.getElementById('editLocation');
    const editWebsite = document.getElementById('editWebsite');

    if (editBio) editBio.value = '';
    if (editLocation) editLocation.value = '';
    if (editWebsite) editWebsite.value = '';
}

function loadUserProfileData() {
    // Load profile data from existing elements
    const profileName = document.getElementById('profileName');
    const postsCount = document.getElementById('postsCount');
    const followersCount = document.getElementById('followersCount');
    const followingCount = document.getElementById('followingCount');

    const username = profileName ? profileName.textContent : 'Your Name';
    const posts = postsCount ? postsCount.textContent : '0';
    const followers = followersCount ? followersCount.textContent : '0';
    const following = followingCount ? followingCount.textContent : '0';

    const profileFullName = document.getElementById('profileFullName');
    const profileUsernameDisplay = document.getElementById('profileUsernameDisplay');
    const profilePostsCount = document.getElementById('profilePostsCount');
    const profileFollowersCount = document.getElementById('profileFollowersCount');
    const profileFollowingCount = document.getElementById('profileFollowingCount');

    if (profileFullName) profileFullName.textContent = username;
    if (profileUsernameDisplay) profileUsernameDisplay.textContent = '@' + username.toLowerCase().replace(/\s+/g, '');
    if (profilePostsCount) profilePostsCount.textContent = posts;
    if (profileFollowersCount) profileFollowersCount.textContent = followers;
    if (profileFollowingCount) profileFollowingCount.textContent = following;
}

function saveProfileChanges() {
    const editUsername = document.getElementById('editUsername');
    const editEmail = document.getElementById('editEmail');
    const editBio = document.getElementById('editBio');
    const editLocation = document.getElementById('editLocation');
    const editWebsite = document.getElementById('editWebsite');

    const formData = {
        username: editUsername ? editUsername.value : '',
        email: editEmail ? editEmail.value : '',
        bio: editBio ? editBio.value : '',
        location: editLocation ? editLocation.value : '',
        website: editWebsite ? editWebsite.value : ''
    };

    // Here you would send the data to the server
    console.log('Saving profile data:', formData);

    // Update the UI with new data
    const profileName = document.getElementById('profileName');
    if (profileName) {
        profileName.textContent = formData.username;
    }

    // Show success message
    alert('Profile updated successfully!');

    closeEditProfileModal();
}

// Initialize profile modals when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeProfileModals();
    loadUserProfileData();
    console.log('Profile modals initialized'); // Debug log
});

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}
