<?php

namespace Core\Controller;
use Session;
use PDO;

class NotificationsAPI
{
    private $pdo;
    private $currentUser;

    public function __construct($pdo, $currentUser)
    {
        $this->pdo = $pdo;
        $this->currentUser = $currentUser;
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['mark_read'])) {
                $this->markAsRead($_POST['notification_id']);
            }
            exit;
        } else {
            if (isset($_GET['ajax'])) {
                $this->getNotificationsAjax();
                exit;
            } else if (isset($_GET['count'])) {
                $this->getUnreadCount();
                exit;
            }
        }
    }

    private function getNotificationsAjax()
    {
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $notifications = $this->pdo->selectWithLimit(
            "SELECT * FROM notifications
            WHERE user_id = ? OR type='news'
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?",
            [$this->currentUser->id, $limit, $offset]
        )->fetchAll(PDO::FETCH_ASSOC);


        $total = $this->pdo->select(
            "SELECT COUNT(*) as count FROM notifications WHERE user_id = ?",
            [$this->currentUser->id]
        )->fetch(PDO::FETCH_ASSOC)['count'];

        header('Content-Type: application/json');
        echo json_encode([
            'notifications' => $notifications,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => ceil($total / $limit),
            ],
        ]);
    }

    private function getUnreadCount()
    {
        $count = $this->pdo->select(
            "SELECT COUNT(*) as count FROM notifications
            WHERE user_id = ? AND is_read = 0",
            [$this->currentUser->id]
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
            [$notificationId, $this->currentUser->id]
        );

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
}

// Initialize the API
if (isset($_POST['logout'])) {
    Session::unset('loggedin');
    session_destroy();
    redirect('login');
}

if (!empty(Session::get('loggedin'))) {
    $currentUser = toJson($pdo->select(
        "SELECT * FROM users WHERE id=?",
        [Session::get('loggedin')]
    )->fetch(PDO::FETCH_ASSOC));

    if (!empty($currentUser)) {
        $notificationsAPI = new \Core\Controller\NotificationsAPI($pdo, $currentUser);
        $notificationsAPI->handleRequest();
    }
}