// Minimal JavaScript for basic functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize basic functionality
    setupBasicEventListeners();
});

function setupBasicEventListeners() {
    // Create post modal functionality
    const createPostBtn = document.getElementById('createPostBtn');
    const createPostSidebarBtn = document.querySelector('.create-post-btn');
    const postModal = document.getElementById('postModal');
    const closePostModalBtn = document.getElementById('closeModal');

    // Open post modal from main feed
    if (createPostBtn) {
        createPostBtn.addEventListener('click', () => openPostModal());
    }

    // Open post modal from sidebar
    if (createPostSidebarBtn) {
        createPostSidebarBtn.addEventListener('click', () => openPostModal());
    }

    // Close post modal
    if (closePostModalBtn) {
        closePostModalBtn.addEventListener('click', () => closePostModal());
    }

    // Close post modal when clicking overlay
    if (postModal) {
        postModal.addEventListener('click', (e) => {
            if (e.target === postModal) {
                closePostModal();
            }
        });
    }

    // Profile modal functionality
    const profileNavBtn = document.querySelector('.nav-item[data-section="profile"]');
    const profileDropdownBtn = document.querySelector('.dropdown-item:first-child');
    const viewProfileModal = document.getElementById('viewProfileModal');
    const closeViewProfileModalBtn = document.getElementById('closeViewProfileModal');

    // Open profile modal from sidebar
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

    // Close profile modal
    if (closeViewProfileModalBtn) {
        closeViewProfileModalBtn.addEventListener('click', () => closeViewProfileModal());
    }

    // Close profile modal when clicking overlay
    if (viewProfileModal) {
        viewProfileModal.addEventListener('click', (e) => {
            if (e.target === viewProfileModal) {
                closeViewProfileModal();
            }
        });
    }

    // Setup post creation features
    setupPostCreationFeatures();

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
    const textarea = document.querySelector('.post-textarea');

    if (modal && textarea) {
        modal.classList.add('active');
        textarea.focus();

        // Reset form and show placeholder
        resetPostForm();
        loadUserDataForModal();
    }
}

function closePostModal() {
    const modal = document.getElementById('postModal');

    if (modal) {
        modal.classList.remove('active');
        // Reset form after a short delay to allow modal animation to complete
        setTimeout(() => {
            resetPostForm();
        }, 300);
    }
}

function resetPostForm() {
    const textarea = document.querySelector('.post-textarea');
    const charCount = document.querySelector('.char-count');
    const imageUpload = document.getElementById('imageUpload');

    // Reset textarea
    if (textarea) {
        textarea.value = '';
    }

    // Reset character counter
    if (charCount) {
        charCount.textContent = '0';
        charCount.style.color = '#65676b';
    }

    // Reset image upload
    if (imageUpload) {
        imageUpload.value = '';
    }

    // Show image placeholder
    showImagePlaceholder();

    // Reset checkboxes
    const checkboxes = document.querySelectorAll('.option-checkbox input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

function closePostModal() {
    const modal = document.getElementById('postModal');

    if (modal) {
        modal.classList.remove('active');
    }
}

function openViewProfileModal() {
    const modal = document.getElementById('viewProfileModal');
    if (modal) {
        modal.classList.add('active');
    }
}

function closeViewProfileModal() {
    const modal = document.getElementById('viewProfileModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Setup post creation features (character counter, image preview, etc.)
function setupPostCreationFeatures() {
    // Character counter
    const postTextarea = document.querySelector('.post-textarea');
    const charCount = document.querySelector('.char-count');

    if (postTextarea && charCount) {
        postTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            charCount.style.color = count > 450 ? '#e74c3c' : '#65676b';
        });
    }

    // Image upload and preview
    const imageUpload = document.getElementById('imageUpload');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    if (imageUpload && imagePreviewContainer) {
        imageUpload.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            if (files.length > 0) {
                displayImagePreviews(files);
            }
        });
    }
}

function displayImagePreviews(files) {
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    // Clear existing previews
    imagePreviewContainer.innerHTML = '';

    // Create grid container
    const gridContainer = document.createElement('div');
    gridContainer.className = 'image-preview-grid';

    files.forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewItem = document.createElement('div');
                previewItem.className = 'image-preview-item';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = `Preview ${index + 1}`;

                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-image';
                removeBtn.innerHTML = '×';
                removeBtn.onclick = function() {
                    previewItem.remove();
                    // If no images left, show placeholder
                    if (gridContainer.children.length === 1) {
                        showImagePlaceholder();
                    }
                };

                previewItem.appendChild(img);
                previewItem.appendChild(removeBtn);
                gridContainer.appendChild(previewItem);
            };
            reader.readAsDataURL(file);
        }
    });

    imagePreviewContainer.appendChild(gridContainer);
}

function showImagePlaceholder() {
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    imagePreviewContainer.innerHTML = `
        <div class="image-preview-placeholder">
            <i class="fas fa-images"></i>
            <span>Add photos to your post</span>
        </div>
    `;
}

// Initialize first profile edit modal when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeFirstProfileEdit();
});

// Initialize first profile edit modal
function initializeFirstProfileEdit() {
    const firstProfileEdit = document.querySelector('.first-profile-edit');
    const closeButton = document.querySelector('.first-profile-edit .close-modal');

    if (closeButton && firstProfileEdit) {
        closeButton.addEventListener('click', function() {
            firstProfileEdit.style.display = 'none';
        });
    }
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
            reader.onload = (event) => {
                const currentAvatar = document.getElementById('currentAvatar');

                // Check if there's already an img element, if not create one
                let imgElement = currentAvatar.querySelector('img');
                if (!imgElement) {
                    // Remove the placeholder and create img element
                    currentAvatar.innerHTML = '';
                    imgElement = document.createElement('img');
                    imgElement.alt = 'Profile avatar';
                    currentAvatar.appendChild(imgElement);
                }

                // Set the image source
                imgElement.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}