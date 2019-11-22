<?php
include_once '../config/Headers.php';
include_once '../config/Database.php';
include_once '../objects/User.php';
 
// get database connection
$database = new Database();
$db = $database->getConnection();
 
// instantiate user object
$user = new User($db);


// get posted data
$data = json_decode(file_get_contents("php://input"),true);


// set user property values
$user->firstname = $data['firstname'];
$user->username = $data['username'];
$user->password = $data['password'];


// create the user
if(
    !empty($user->firstname) &&
    !empty($user->username) &&
    !empty($user->password) &&
    !$user->userExists()
){
    $user->create();
 
    // set response code
    http_response_code(201);
 
    // display message: user was created
    echo json_encode(array("message" => "User created successfully."));
}elseif ($user->userExists()) {
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "User details already exist."));
}
 // message if unable to create user
else{
 
    // set response code
    http_response_code(400);
 
    // display message: unable to create user
    echo json_encode(array("message" => "Unable to create user."));
}

?>
