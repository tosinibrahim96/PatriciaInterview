<?php
// used to get mysql database connection

class Database {

    //database credentials
    private $host = 'localhost';
    private $db_name = 'patricia_db';
    private $username = 'root';
    private $password = '';
    public $conn;

    //database connection

    public function getConnection() {

        $this->conn = null;

        try {
            $this->conn = new PDO( 'mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING) );
        } catch( PDOException $exception ) {
            echo 'Connection error: ' . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
