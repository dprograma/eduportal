<?php
require 'GoogleAuth.php';

$googleAuth = new GoogleAuth();
$authUrl = $googleAuth->getAuthUrl();
header('Location: ' . $authUrl);
exit;
