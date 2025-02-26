<?php
require_once 'core/model/DB.php';

class Notifications
{
    private $pdo;
    private $currentUser;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;

        if (Session::get('loggedin')) {
            // Get user data
            $userData = $pdo->select(
                "SELECT * FROM users WHERE id=?",
                [Session::get('loggedin')]
            )->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                $this->currentUser = $userData;
                $this->handleRequest();
            } else {
                // Handle case where user is not found
                Session::unset('loggedin');
                redirect('login');
            }
        } else {
            redirect('login');
        }
    }

    private function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['mark_read'])) {
                $this->markAsRead($_POST['notification_id']);
            }
        } else {
            if (isset($_GET['ajax'])) {
                $this->getNotificationsAjax();
            } else if (isset($_GET['count'])) {
                $this->getUnreadCount();
            } else {
                $this->showNotificationsPage();
            }
        }
    }

    private function getNotificationsAjax()
    {
        $page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit  = 10;
        $offset = ($page - 1) * $limit;

        $notifications = $this->pdo->select(
            "SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?",
            [$this->currentUser['id'], $limit, $offset]
        )->fetchAll(PDO::FETCH_ASSOC);

        $total = $this->pdo->select(
            "SELECT COUNT(*) as count FROM notifications WHERE user_id = ?",
            [$this->currentUser['id']]
        )->fetch(PDO::FETCH_ASSOC)['count'];

        header('Content-Type: application/json');
        echo json_encode([
            'notifications' => $notifications,
            'pagination'    => [
                'currentPage' => $page,
                'totalPages'  => ceil($total / $limit),
            ],
        ]);
    }

    private function getUnreadCount()
    {
        $count = $this->pdo->select(
            "SELECT COUNT(*) as count FROM notifications
            WHERE user_id = ? AND is_read = 0",
            [$this->currentUser['id']]
        )->fetch(PDO::FETCH_ASSOC)['count'];

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
    }

    private function markAsRead($notificationId)
    {
        $this->pdo->update(
            "UPDATE notifications
            SET is_read = 1
            WHERE id = ? AND user_id = ?",
            [$notificationId, $this->currentUser['id']]
        );

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    private function showNotificationsPage()
    {
        // Convert user array to object for view compatibility
        $currentUser = toJson($this->currentUser);
        require_once 'view/loggedin/secured/notifications.php';
    }

    // Static method to create new notifications
    public static function create($userId, $type, $title, $message, $link = null)
    {
        global $pdo;
        $pdo->insert(
            "INSERT INTO notifications (user_id, type, title, message, link)
            VALUES (?, ?, ?, ?, ?)",
            [$userId, $type, $title, $message, $link]
        );
    }
}

// Initialize the controller
new Notifications();
