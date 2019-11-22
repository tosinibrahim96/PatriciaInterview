<?php

class User {

    private $conn;
    private $table_name = 'users';

    public $id;
    public $firstname;
    public $username;
    public $password;

    // constructor

    public function __construct( $db ) {
        $this->conn = $db;
    }

    // create new user record

    function create() {

        // insert query
        $query = 'INSERT INTO ' . $this->table_name . '(firstname, username,password) VALUES(:firstname, :username, :password)';

        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->firstname = htmlspecialchars( strip_tags( $this->firstname ) );
        $this->username = htmlspecialchars( strip_tags( $this->username ) );
        $this->password = htmlspecialchars( strip_tags( $this->password ) );

        // bind the values
        $stmt->bindParam( ':firstname', $this->firstname );
        $stmt->bindParam( ':username', $this->username );

        // hash the password before saving to database
        $password_hash = password_hash( $this->password, PASSWORD_BCRYPT );
        $stmt->bindParam( ':password', $password_hash );

        // execute the query, also check if query was successful
        if ( $stmt->execute() ) {
            return true;
        }

        return false;
    }

    // check if given email exist in the database

    function userExists() {

        // query to check if user exists
        $query = "SELECT id, firstname, username, password
            FROM " . $this->table_name . "
            WHERE username = ?
            LIMIT 0,1";

        // prepare the query
        $stmt = $this->conn->prepare( $query );

        // sanitize
        $this->username = htmlspecialchars( strip_tags( $this->username ) );

        // bind given username value
        $stmt->bindParam( 1, $this->username);

        // execute the query
        $stmt->execute();

        // get number of rows
        $num = $stmt->rowCount();

        // if email exists, assign values to object properties for easy access and use for php sessions
        if ( $num>0 ) {

            // get record details / values
            $row = $stmt->fetch( PDO::FETCH_ASSOC );

            // assign values to object properties
            $this->id = $row['id'];
            $this->firstname = $row['firstname'];
            $this->username = $row['username'];
            $this->password = $row['password'];

            // return true because email exists in the database
            return true;
        }

        // return false if email does not exist in the database
        return false;
    }

}

?>