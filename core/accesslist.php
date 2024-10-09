<?php
$routes = [
    '/' => 'core/controller/Home.php',

    'home' => 'core/controller/Home.php',
    'news' => 'core/controller/News.php',

    'signup' => 'core/controller/Register.php',
    'forgot-password' => 'core/controller/Forgot-Password.php',
    'reset-password' => 'core/controller/Reset-Password.php',
    'auth-two-steps' => 'core/controller/Two-Steps.php',
    'auth-verify-email' => 'core/controller/Verify-Email.php',
    'login' => 'core/controller/Login.php',
    'verify' => 'core/controller/Verify.php',
    'reset' => 'core/controller/Reset.php',
    'dashboard' => 'core/controller/Dashboard.php',
    'create-post' => 'core/controller/Post.php',
    '404' => 'core/controller/404.php',
    'blogdetails' => 'core/controller/Blogdetail.php',
    'viewpost' => 'core/controller/Viewpost.php',
    'view-past-questions' => 'core/controller/View-Past-Question.php',
    'assessment' => 'core/controller/Assessment.php',
    'first-sub' => 'core/controller/First-Sub.php',
    'admin-dashboard' => 'core/controller/Admin-Dashboard.php',
    'admin-settings' => 'core/controller/Admin-Settings.php',

    'create-past-question' => 'core/controller/Create-Past-Question.php',
    'edit-question' => 'core/controller/Edit-Question.php',
    'upload-past-question' => 'core/controller/Upload-Past-Question.php',
    'edit-post' => 'core/controller/Edit-Post.php',
    'payment-gateway' => 'core/controller/Admin-Payment.php',
    'checkout' => 'core/controller/Checkout.php',
    'checkout-past-q' => 'core/controller/Checkout-Past-Q.php',
    'purchase-past-question' => 'core/controller/Purchase-Past-Question.php',
    'downloadfile' => 'core/controller/DownloadFile.php',
    'callback-url' => 'core/controller/CallBackURL.php',
    'q-callback-url' => 'core/controller/Q-CallBack-Url.php',
    'purchases' => 'core/controller/Purchases.php',
    'add-to-cart' => 'core/controller/AddToCart.php',
    'cart' => 'core/controller/Cart.php',
    'agent' => 'core/controller/Agent.php',
    'cbt-test' => 'core/controller/CBT-Test.php',
    'auth-agent-login' => 'core/controller/AgentLogin.php',
    'agent-dashboard' => 'core/controller/AgentDashboard.php',
    'view-agent-past-questions' => 'core/controller/View-Agent-Past-Questions.php',
    'edit-uploaded-past-question' => 'core/controller/Edit-Uploaded-Question.php',
    'customer-ebooks' => 'core/controller/CustomerEbooks.php',
    'customer-publications' => 'core/controller/CustomerPublications.php',
    'customer-past-questions' => 'core/controller/CustomerPastQuestions.php',
    'logout' => 'core/controller/Logout.php',
];


$admin_pages = ['admin-dashboard', 'dashboard', 'purchases', '/', 'create-past-question', 'create-post', 'viewpost', 'view-past-questions', 'post-table', 'admin-settings', 'edit-post', 'payment-gateway', 'cart', 'checkout-past-q', 'edit-question', 'upload-past-question', 'view-agent-past-questions','edit-uploaded-past-question', 'logout', 'agent-dashboard', 'home', '/', 'blogdetails', 'contact', 'about', 'news', 'add-to-cart', 'checkout', 'checkout-past-q', 'q-callback-url', 'cbt-test'];

$agent_pages = ['agent-dashboard', '/', 'view-past-questions', 'viewpost', 'upload-past-question', 'logout', 'home', 'contact', 'about', 'news', 'view-agent-past-questions', 'edit-question', 'edit-uploaded-past-question', 'add-to-cart', 'checkout', 'checkout-past-q', 'q-callback-url'];

$secured_pages = ['dashboard', 'home', '/', 'reset-password', 'blog-detail', 'logout', 'news', 'assessment', 'login', 'auth-agent-login', 'checkout', 'add-to-cart', 'cart', 'auth-register', 'purchases', 'checkout-past-q', 'q-callback-url', 'downloadfile', 'agent', 'agent-dashboard', 'blogdetails', 'contact', 'about', 'purchase-past-question', 'cbt-test', 'first-sub', 'customer-ebooks', 'customer-publications', 'customer-past-questions'];

$guest_pages = ['home', '/', 'first-sub', 'contact', 'about', 'checkout', 'signup', 'login', 'auth-agent-login', 'add-to-cart', 'cart', 'agent', 'forgot-password', 'auth-two-steps', 'auth-verify-email', 'blogdetails', 'news', 'verify', 'reset', 'reset-password'];

if (Session::exists('loggedin')) {
    $access_level = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC))->access;

    $is_agent = toJson($pdo->select("SELECT * FROM users WHERE id=?", [Session::get('loggedin')])->fetch(PDO::FETCH_ASSOC))->is_agent;

    switch ($access_level) {
        case 'guest':
            if (in_array($url, $guest_pages)) {
                require $routes[$url];
            } else {
                abort(403);
            }

            break;
        case 'secured':
            switch ($is_agent) {
                case true:
                    if (in_array($url, $agent_pages)) {
                        require $routes[$url];
                    } else {
                        abort(403);
                    }
                    break;
                default:
                    if (in_array($url, $secured_pages)) {
                        require $routes[$url];
                    } else {
                        abort(403);

                    }
                    break;
            }

        case 'admin':
            if (in_array($url, $admin_pages)) {
                require $routes[$url];
            } else {
                abort(403);

            }
            break;
    }

} else {
    if (in_array($url, $guest_pages)) {

        require $routes[$url];

    } else {
        abort(404);

    }

}



