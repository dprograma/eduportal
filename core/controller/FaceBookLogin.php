<?php
require 'FaceBookAuth.php';

$facebookAuth = new FacebookAuth();
$authUrl = $facebookAuth->getAuthUrl();
header('Location: ' . $authUrl);
exit;
