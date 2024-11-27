<?php
require 'vendor/autoload.php';

require_once 'ENVLoader.php';

use League\OAuth2\Client\Provider\Facebook;

class FacebookAuth {
    private $client;

    public function __construct() {
        $this->client = new Facebook([
            'clientId'     => $_ENV['FACEBOOK_APP_ID'],
            'clientSecret' => $_ENV['FACEBOOK_APP_SECRET'],
            // 'redirectUri'  => APP_URL . '/facebook-redirect/',
            'redirectUri'  => $_ENV['FACEBOOK_REDIRECT_URL'],
            'graphApiVersion' => $_ENV['FACEBOOK_GRAPH_API_VERSION'],
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
