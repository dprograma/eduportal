<!doctype html>
<html lang="en">
<?php $title = "Notifications | " . SITE_TITLE; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Navbar -->
    <?php include "partials/customer_header.php"; ?>

    <main>
        <!--Account home start-->
        <section class="py-lg-7 py-5 bg-light-subtle">
            <div class="container">
                <div class="row">
                <div class="col-lg-3 col-md-4">
                    <?php include 'partials/customer_menu.php'; ?>
                    </div>
                    <div class="col-lg-9 col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Notifications</h5>
                            </div>
                            <div class="card-body">
                                <div id="notificationsList"></div>
                                <div id="notificationsPagination" class="mt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--Account home end-->
    </main>

    <!-- Notification Detail Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="notificationMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="notificationLink" class="btn btn-primary">View Details</a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>
    <!-- Scroll top -->
    <?php include 'partials/scroll_top.php'; ?>
    <!-- Libs JS -->
    <?php include 'partials/scripts.php'; ?>
    
    <script>
    let currentPage = 1;

    async function loadNotifications(page = 1) {
        try {
            const response = await fetch(`notifications?ajax=true&page=${page}`);
            const data = await response.json();
            
            displayNotifications(data.notifications);
            updatePagination(data.pagination);
            currentPage = page;
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }

    function displayNotifications(notifications) {
        const container = document.getElementById('notificationsList');
        
        if (!notifications.length) {
            container.innerHTML = '<p class="text-center">No notifications</p>';
            return;
        }

        container.innerHTML = notifications.map(notification => `
            <div class="notification-item p-3 border-bottom ${!notification.is_read ? 'bg-light' : ''}"
                 onclick="showNotificationDetail(${JSON.stringify(notification)})">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-1">${notification.title}</h6>
                    <small class="text-muted">${formatDate(notification.created_at)}</small>
                </div>
                <p class="mb-0 text-truncate">${notification.message}</p>
            </div>
        `).join('');
    }

    function showNotificationDetail(notification) {
        document.getElementById('notificationTitle').textContent = notification.title;
        document.getElementById('notificationMessage').textContent = notification.message;
        
        const linkBtn = document.getElementById('notificationLink');
        if (notification.link) {
            linkBtn.href = notification.link;
            linkBtn.style.display = 'block';
        } else {
            linkBtn.style.display = 'none';
        }

        if (!notification.is_read) {
            markAsRead(notification.id);
        }

        new bootstrap.Modal(document.getElementById('notificationModal')).show();
    }

    async function markAsRead(notificationId) {
        try {
            await fetch('notifications', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `mark_read=true&notification_id=${notificationId}`
            });
            updateUnreadCount();
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Load initial notifications
    loadNotifications(1);

    // Set up real-time updates
    setInterval(async () => {
        if (currentPage === 1) {
            await loadNotifications(1);
        }
        updateUnreadCount();
    }, 30000); // Check every 30 seconds
    </script>
</body>

</html>