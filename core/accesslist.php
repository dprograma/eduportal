<?php

$routes = [

    '/' => 'core/controller/Home.php',

    'home' => 'core/controller/Home.php',

    'about' => 'core/controller/About.php',

    'news' => 'core/controller/News.php',

    'signup' => 'core/controller/Register.php',

    'affiliate-signup' => 'core/controller/Register.php',

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

    'facebook-redirect' => 'core/controller/FacebookCallBack.php',

    'facebook-login' => 'core/controller/FacebookLogin.php',

    'redirect-url' => 'core/controller/GoogleCallBack.php',

    'google-login' => 'core/controller/GoogleLogin.php',

    'create-past-question' => 'core/controller/Create-Past-Question.php',

    'edit-question' => 'core/controller/Edit-Question.php',

    'upload-past-question' => 'core/controller/Upload-Past-Question.php',

    'edit-post' => 'core/controller/Edit-Post.php',

    'payment-gateway' => 'core/controller/Admin-Payment.php',

    'checkout' => 'core/controller/Checkout.php',

    'checkout-past-q' => 'core/controller/Checkout-Past-Q.php',

    'purchase-past-question' => 'core/controller/Purchase-Past-Question.php',

    'request-withdrawal' => 'core/controller/RequestWithdrawal.php',

    'past-questions' => 'core/controller/Past-Questions.php',

    'ebooks' => 'core/controller/Ebooks.php',

    'publications' => 'core/controller/Publications.php',

    'downloadfile' => 'core/controller/DownloadFile.php',

    'callback-url' => 'core/controller/CallBackURL.php',

    'q-callback-url' => 'core/controller/Q-CallBack-Url.php',

    'purchases' => 'core/controller/Purchases.php',

    'add-to-cart' => 'core/controller/AddToCart.php',

    'cart' => 'core/controller/Cart.php',

    'agent' => 'core/controller/Agent.php',

    'agent-signup' => 'core/controller/AgentRegister.php',

    'cbt-test' => 'core/controller/CBT-Test.php',

    'save-cbt-test-result' => 'core/controller/SaveCBTScore.php',

    'auth-agent-login' => 'core/controller/AgentLogin.php',

    'agent-dashboard' => 'core/controller/AgentDashboard.php',

    'user-details' => 'core/controller/UserDetails.php',

    'view-agent-documents' => 'core/controller/View-Agent-Documents.php',

    'view-affiliate-documents' => 'core/controller/View-Affiliate-Documents.php',

    'view-current-agent-past-questions' => 'core/controller/View-Current-Agent-Past-Questions.php',

    'view-withdrawal-requests' => 'core/controller/ViewWithdrawalRequests.php',

    'approve-withdrawal' => 'core/controller/ApproveWithdrawal.php',

    'decline-withdrawal' => 'core/controller/DeclineWithdrawal.php',

    'edit-uploaded-past-question' => 'core/controller/Edit-Uploaded-Question.php',

    'customer-ebooks' => 'core/controller/CustomerEbooks.php',

    'customer-publications' => 'core/controller/CustomerPublications.php',

    'customer-past-questions' => 'core/controller/CustomerPastQuestions.php',

    'notifications' => 'core/controller/Notifications.php',

    'notificationsapi' => 'core/controller/NotificationsAPI.php',

    'profile' => 'core/controller/CustomerProfile.php',

    'logout' => 'core/controller/Logout.php',

    'affiliate-earnings' => 'core/controller/AffiliateEarnings.php',

    'affiliate-payment-verify' => 'core/controller/AffiliatePaymentVerify.php',

    'search' => 'core/controller/SearchAPI.php',

    'update-user' => 'core/controller/EditUser.php',

];





$admin_pages = ['admin-dashboard', 'dashboard', 'purchases', '/', 'create-past-question', 'create-post', 'viewpost', 'view-past-questions', 'post-table', 'admin-settings', 'edit-post', 'payment-gateway', 'cart', 'checkout-past-q', 'edit-question', 'upload-past-question', 'view-agent-documents','edit-uploaded-past-question', 'logout', 'agent-dashboard', 'home', '/', 'blogdetails', 'contact', 'about', 'news', 'add-to-cart', 'checkout', 'checkout-past-q', 'q-callback-url', 'cbt-test', 'affiliate-signup', 'facebook-redirect', 'redirect-url', 'facebook-login', 'google-login', 'view-affiliate-documents', 'user-details', 'profile', 'publications', 'ebooks', 'past-questions', 'request-withdrawal', 'view-withdrawal-requests', 'approve-withdrawal', 'decline-withdrawal', 'agent-signup', 'search', 'signup', 'login', 'update-user', 'verify', 'notifications', 'notificationsapi'];



$agent_pages = ['agent-dashboard', '/', 'view-past-questions', 'viewpost', 'upload-past-question', 'logout', 'home', 'contact', 'about', 'news', 'view-agent-past-questions', 'edit-question', 'edit-uploaded-past-question', 'add-to-cart', 'checkout', 'checkout-past-q', 'q-callback-url', 'affiliate-signup', 'facebook-redirect', 'redirect-url', 'facebook-login', 'google-login', 'view-affiliate-documents', 'ebooks', 'publications', 'view-current-agent-past-questions', 'profile', 'request-withdrawal', 'agent-signup', 'search', 'signup', 'login', 'verify', 'notifications', 'notificationsapi'];



$secured_pages = ['dashboard', 'home', '/', 'reset-password', 'blog-detail', 'logout', 'news', 'assessment', 'login', 'auth-agent-login', 'checkout', 'add-to-cart', 'cart', 'auth-register', 'purchases', 'checkout-past-q', 'q-callback-url', 'downloadfile', 'agent', 'agent-dashboard', 'blogdetails', 'contact', 'about', 'purchase-past-question', 'cbt-test', 'first-sub', 'customer-ebooks', 'customer-publications', 'customer-past-questions', 'notifications', 'save-cbt-test-result', 'profile', 'past-questions', 'affiliate-signup', 'facebook-redirect', 'redirect-url', 'facebook-login', 'google-login', 'view-affiliate-documents', 'ebooks', 'publications', 'request-withdrawal', 'agent-signup', 'upload-past-question', 'affiliate-earnings', 'search', 'signup', 'verify', 'notificationsapi'];



$guest_pages = ['home', '/', 'first-sub', 'contact', 'about', 'checkout', 'signup', 'login', 'auth-agent-login', 'add-to-cart', 'cart', 'agent', 'forgot-password', 'auth-two-steps', 'auth-verify-email', 'blogdetails', 'news', 'verify', 'reset', 'reset-password', 'past-questions', 'affiliate-signup', 'facebook-redirect', 'redirect-url', 'facebook-login', 'google-login', 'cbt-test', 'ebooks', 'publications', 'agent-signup', 'dashboard', 'q-callback-url', 'search'];



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








