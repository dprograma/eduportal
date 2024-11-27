<?php
require 'vendor/autoload.php';

require_once 'ENVLoader.php';

use League\OAuth2\Client\Provider\Google;

class GoogleAuth {
    private $client;

    public function __construct() {
        $this->client = new Google([
            'clientId'     => $_ENV['GOOGLE_CLIENT_ID'],
            'clientSecret' => $_ENV['GOOGLE_CLIENT_SECRET'],
            // 'redirectUri'  => APP_URL . '/redirect-url/',
            'redirectUri'  => $_ENV['GOOGLE_REDIRECT_URL'],
        ]);
    }

    public function getAuthUrl() {
        return $this->client->getAuthorizationUrl();
    }

    public function getAccessToken($code) {
        return $this->client->getAccessToken('authorization_code', ['code' => $code]);
    }

    public function getUserData($token) {
        return $this->client->getResourceOwner($token)->toArray();
    }
}
