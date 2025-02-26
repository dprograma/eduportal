<?php
session_start();

define('APP_ROOT', dirname(__FILE__));
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', 'eduportal/');
define('SITE_TITLE', 'EduPortal');
define('APP_SERVER_NAME', $_SERVER['SERVER_NAME']);
define('APP_URL', 'http://' . APP_SERVER_NAME . DS . ROOT);
define('DESCRIPTION', 'JAMB, NECO and WAEC past questions and answers are available here for download!');
define('FAVICON', 'assets/img/icons/logos/prepr_favicon.png');
define('LOGO', 'assets/img/icons/logos/prepr_logo.png');
define('CART', 'assets/images/payments/cart.png');
define('FACEBOOK', 'https://web.facebook.com/people/Prepr/61554297521186/');

define('KEYWORDS', 'JAMB, NECO, WAEC, past questions , Blog, News, Schools, POST UTME');
$url = @explode('/', trim(parse_url($_SERVER['REQUEST_URI'])['path'], '/'))[1];
$url = empty($url) ? '/' : $url;
$url = @explode('.', $url)[0];
// $url = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
require_once 'core' . DS . 'helper.php';
require_once 'core' . DS . 'model' . DS . 'DB.php';
require_once 'core' . DS . 'Session.php';
require_once 'core' . DS . 'Mailer.php';

// require_once 'core' . DS . 'Router.php';

require_once 'core' . DS . 'accesslist.php';
