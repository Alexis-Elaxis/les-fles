<?php
require('./config/confg.php');

$servername = DB_SERVER_NAME;
$username = DB_USERNAME;
$password = DB_PASSWORD;
$dbname = DB_DATABASE;

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes. In case if your CURL is slow and is loading too much (Can be IPv6 problem)

error_reporting(E_ALL);
define('OAUTH2_CLIENT_ID', DISCORD_CLIENT_ID);
define('OAUTH2_CLIENT_SECRET', DISCORD_CLIENT_SECRET);

$authorizeURL = 'https://discord.com/api/oauth2/authorize';
$tokenURL = 'https://discord.com/api/oauth2/token';
$apiURLBase = 'https://discord.com/api/users/@me';
$revokeURL = 'https://discord.com/api/oauth2/token/revoke';

if(get('action') == 'logout') {
    logout($revokeURL, array(
        'token' => session('access_token'),

        'token_type_hint' => 'access_token',

        'client_id' => OAUTH2_CLIENT_ID,

        'client_secret' => OAUTH2_CLIENT_SECRET,

      ));

    unset($_SESSION['access_token']);

    header('Location: ' . $_SERVER['PHP_SELF']);

    die();

}



session_start();



// Start the login process by sending the user to Discord's authorization page

if(get('action') == 'login') {



  $params = array(

    'client_id' => OAUTH2_CLIENT_ID,

    'redirect_uri' => 'http://les-fles.22web.org/discord.php',

    'response_type' => 'code',

    'scope' => 'identify guilds'

  );



  // Redirect the user to Discord's authorization page

  header('Location: https://discord.com/api/oauth2/authorize' . '?' . http_build_query($params));

  die();

}





// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string

if(get('code')) {



  // Exchange the auth code for a token

  $token = apiRequest($tokenURL, array(

    "grant_type" => "authorization_code",

    'client_id' => OAUTH2_CLIENT_ID,

    'client_secret' => OAUTH2_CLIENT_SECRET,

    'redirect_uri' => 'http://les-fles.22web.org/discord.php',

    'code' => get('code')

  ));

  $logout_token = $token->access_token;

  $_SESSION['access_token'] = $token->access_token;





  header('Location: ' . $_SERVER['PHP_SELF']);

}



if(session('access_token')) {

  $user = apiRequest($apiURLBase);



  $_SESSION["user"] = $user;



  $sql = "INSERT INTO users (user_id, user_name, user_tag)

  VALUES ('".$user->id."', '".$user->username."', '".$user->discriminator."')";



  if ($conn->query($sql) === TRUE) {

    echo "New record created successfully";

  } else {

    echo "Error: " . $sql . "<br>" . $conn->error;

  }

  header('Location: index.php');



} else {

  header('Location: ?action=login');

}





if(get('action') == 'logout') {

  // This should logout you

  logout($revokeURL, array(

    'token' => session('access_token'),

    'token_type_hint' => 'access_token',

    'client_id' => OAUTH2_CLIENT_ID,

    'client_secret' => OAUTH2_CLIENT_SECRET,

  ));

  unset($_SESSION['access_token']);

  header('Location: ' . $_SERVER['PHP_SELF']);

  die();

}



function apiRequest($url, $post=FALSE, $headers=array()) {

  $ch = curl_init($url);

  curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);



  $response = curl_exec($ch);





  if($post)

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));



  $headers[] = 'Accept: application/json';



  if(session('access_token'))

    $headers[] = 'Authorization: Bearer ' . session('access_token');



  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);



  $response = curl_exec($ch);

  return json_decode($response);

}



function logout($url, $data=array()) {

    $ch = curl_init($url);

    curl_setopt_array($ch, array(

        CURLOPT_POST => TRUE,

        CURLOPT_RETURNTRANSFER => TRUE,

        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,

        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded'),

        CURLOPT_POSTFIELDS => http_build_query($data),

    ));

    $response = curl_exec($ch);

    return json_decode($response);

}



function get($key, $default=NULL) {

  return array_key_exists($key, $_GET) ? $_GET[$key] : $default;

}



function session($key, $default=NULL) {

  return array_key_exists($key, $_SESSION) ? $_SESSION[$key] : $default;

}



?>