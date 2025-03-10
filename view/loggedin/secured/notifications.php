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
                        <?= $currentUser->access == 'admin' ? include 'partials/admin_menu.php' : ($currentUser->is_agent == '1' ? include 'partials/agent_menu.php' : ($currentUser->is_affiliate ? include 'partials/affiliate_menu.php' : include 'partials/customer_menu.php')); ?>
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
                const response = await fetch(`notificationsapi?ajax=true&page=${page}`);
                const data = await response.json();
                displayNotifications(data.notifications);
                updatePagination(data.pagination);
                currentPage = page;
                return;
            } catch (error) {
                console.error('Error loading notifications:', error);
            }
        }

        async function updateUnreadCount() {
            try {
                const response = await fetch('notificationsapi?count=true');
                const text = await response.text(); // First get the raw text

                // Log the raw response for debugging
                console.log('Raw response:', text);

                // Try to parse the JSON
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', e);
                    return;
                }

                // Update the notification counter in the header
                const notificationCounter = document.querySelector('.notification-counter');
                if (notificationCounter && typeof data.count !== 'undefined') {
                    notificationCounter.textContent = data.count;
                    notificationCounter.style.display = data.count > 0 ? 'block' : 'none';
                }
            } catch (error) {
                console.error('Error updating notification count:', error);
            }
        }

        function updatePagination(pagination) {
            const container = document.getElementById('notificationsPagination');

            if (!pagination || pagination.totalPages <= 1) {
                container.innerHTML = '';
                return;
            }

            let html = '';
            // Previous button
            html += `
                <li class="page-item ${pagination.currentPage === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadNotifications(${pagination.currentPage - 1}); return false;">Previous</a>
                </li>`;

            // Page numbers
            for (let i = 1; i <= pagination.totalPages; i++) {
                html += `
                    <li class="page-item ${pagination.currentPage === i ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="loadNotifications(${i}); return false;">${i}</a>
                    </li>`;
            }

            // Next button
            html += `
                <li class="page-item ${pagination.currentPage === pagination.totalPages ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="loadNotifications(${pagination.currentPage + 1}); return false;">Next</a>
                </li>`;

            container.innerHTML = html;
        }

        function displayNotifications(notifications) {
            const container = document.getElementById('notificationsList');

            if (!notifications.length) {
                container.innerHTML = '<p class="text-center">No notifications</p>';
                return;
            }

            container.innerHTML = notifications.map(notification => `
        <div class="notification-item p-3 border-bottom ${!notification.is_read ? 'bg-light' : ''}"
             onclick='showNotificationDetail(${JSON.stringify(notification).replace(/'/g, "&#39;")})'>
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-1">${notification.title}</h6>
                <small class="text-muted">${formatDate(notification.created_at)}</small>
            </div>
            <p class="mb-0 text-truncate">${notification.message}</p>
        </div>
    `).join('');
        }

        function showNotificationDetail(notification) {
            // Parse the notification if it's a string
            const notificationData = typeof notification === 'string'
                ? JSON.parse(notification)
                : notification;

            document.getElementById('notificationTitle').textContent = notificationData.title;
            document.getElementById('notificationMessage').textContent = notificationData.message;

            const linkBtn = document.getElementById('notificationLink');
            if (notificationData.link) {
                linkBtn.href = notificationData.link;
                linkBtn.style.display = 'block';
            } else {
                linkBtn.style.display = 'none';
            }

            if (!notificationData.is_read) {
                markAsRead(notificationData.id);
            }

            new bootstrap.Modal(document.getElementById('notificationModal')).show();
        }



        async function markAsRead(notificationId) {
            try {
                await fetch('notificationsapi', {
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

        document.addEventListener('DOMContentLoaded', () => {
            // Initial load
            loadNotifications(1);
            updateUnreadCount();

            // Set up real-time updates
            setInterval(async () => {
                if (currentPage === 1) {
                    await loadNotifications(1);
                }
                updateUnreadCount();
            }, 30000);
        });
    </script>
</body>

</html>