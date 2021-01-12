<?php
require_once 'vendor/autoload.php';
 
// init configuration
$clientID = '60908724699-s5s6t9vekp1f6ujb8p2au40adkogt5rl.apps.googleusercontent.com';
$clientSecret = 'hv7YQ8Ch0-p9YwYrNj_QZv3W';
$redirectUri = 'home.php';
  
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
 
// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);
    
    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email =  $google_account_info->email;
    $name =  $google_account_info->name;
 
  // now you can use this profile info to create account in your website and make user logged in.
}else{
    $url = $client->createAuthUrl();
    echo "<a href='".$url."'>Google Login</a>";
}
?>