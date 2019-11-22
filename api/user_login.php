<?php
include_once '../config/Headers.php';
include_once '../config/Database.php';
include_once '../objects/User.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User( $db );

// get posted data
$data = json_decode( file_get_contents( 'php://input' ) );

// set user property values
$user->username = $data->username;
$user_exists = $user->userExists();

// generate json web token
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

// set your default time-zone
date_default_timezone_set('Africa/Lagos');

// check if user exists and if password is correct
if ( $user_exists && password_verify( $data->password, $user->password ) ) {

    $key = 'secret_key';
    $issuedAt = time();

    
    $payload = array(
        'iat' => $issuedAt,
        'exp'=> $issuedAt +60,
        'data' => array(
            'id' => $user->id,
            'firstname' => $user->firstname,
            'username' => $user->username,
        )
    );

    // set response code
    http_response_code( 200 );

    // generate jwt
    $jwt = JWT::encode( $payload, $key );
    echo json_encode(
        array(
            'message' => 'Successful login.',
            'token' => $jwt
        )
    );

}
// login failed
else {

    // set response code
    http_response_code( 401 );

    // tell the user login failed
    echo json_encode( array( 'message' => 'Login failed.' ) );
}

?>