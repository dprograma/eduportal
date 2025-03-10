<style>
.notification-counter {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #dc3545;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    display: none;
}

.notification-item {
    cursor: pointer;
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #e9ecef;
}
</style>
<header>
        <nav class="navbar navbar-expand-lg navbar-light w-100">
            <div class="container px-3">
            <a class="navbar-brand" href="home"><h3><span class="text-primary">Edu</span><span>Portal</span></h3></a>
                <button class="navbar-toggler offcanvas-nav-btn" type="button">
                    <i class="bi bi-list"></i>
                </button>
                <div class="offcanvas offcanvas-start offcanvas-nav" style="width: 20rem">
                    <div class="offcanvas-header">
                        <a href="home" class="text-inverse"><h3><span class="text-primary">Edu</span><span>Portal</span></h3></a>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body float-end pt-0 align-items-center ms-auto">
                        <ul class="navbar-nav mx-auto align-items-lg-center">
                            <li class="nav-item dropdown">
                            <?php if (!$currentUser->is_affiliate) : ?>
                            <a class="nav-link btn btn-primary" href="affiliate-signup?ref=affiliate" id="navbarDropdown"
                            role="button" aria-haspopup="true" aria-expanded="false">Become an Affiliate</a>
                            <?php endif ?>
                            </li>
                            <li class="nav-item dropdown">
                            <a class="nav-link btn btn-primary" href="agent-signup" id="navbarDropdown" role="button"
                            aria-haspopup="true" aria-expanded="false">Become an Agent</a>
                            </li>
                            <li class="nav-item dropdown dropdown-fullwidth">
                                <!-- <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false"></a> -->
                            </li>
                            <li class="nav-item dropdown">
                            <span class="notification-counter">0</span>
                                <a class="nav-link" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-bell"></i>
                                    <span class="badge bg-danger rounded-pill notification-badge" style="display: none;"></span>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="notificationsDropdown">
                                    <li><a class="dropdown-item" href="notifications">View All Notifications</a></li>
                                </ul>
                            </li>
                        </ul>
                        <div class="mt-3 mt-lg-0 d-flex align-items-center">
                            <!-- User Account Dropdown -->
                            <div class="dropdown">
                                <a class="btn btn-light dropdown-toggle" href="#" role="button" id="userDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo file_exists($currentUser->profileimg) ? $currentUser->profileimg : 'assets/images/avatar/fallback.jpg'?>" alt="avatar"
                                        class="avatar avatar-sm rounded-circle" />
                                    <?php echo $currentUser->fullname?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="profile">Profile</a></li>
                                    <li><a class="dropdown-item" href="settings">Settings</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

<script>
async function updateUnreadCount() {
    try {
        const response = await fetch('notifications?count=true');
        const data = await response.json();

        const badge = document.querySelector('.notification-badge');
        if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    } catch (error) {
        console.error('Error updating notification count:', error);
    }
}

// Update count on page load
updateUnreadCount();

// Update notifications preview when dropdown is opened
document.getElementById('notificationsDropdown').addEventListener('show.bs.dropdown', async () => {
    try {
        const response = await fetch('notifications?ajax=true&page=1&limit=5');
        const data = await response.json();

        const preview = document.getElementById('notificationsPreview');
        if (data.notifications.length === 0) {
            preview.innerHTML = '<p class="text-center mb-0">No notifications</p>';
            return;
        }

        preview.innerHTML = data.notifications.map(notification => `
            <a href="notifications" class="dropdown-item ${!notification.is_read ? 'bg-light' : ''}">
                <h6 class="mb-0">${notification.title}</h6>
                <small class="text-muted">${formatDate(notification.created_at)}</small>
            </a>
        `).join('');
    } catch (error) {
        console.error('Error loading notification preview:', error);
    }
});
</script>