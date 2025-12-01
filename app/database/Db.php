<?php
include_once __DIR__ .'../../utils/log.php';

class Database { //Database Connection

    // Aiven Credentials
    private $servername = "mysql-f33c54e-fontejoedel1-8150.k.aivencloud.com";
    private $username = "Jho_del";
    private $password = "AVNS_qlduRudNkrNnyj_HUYV";
    private $database = "unity_pgsys_db";
    private $port = "24340";
    private $conn;
    private $ssl_ca_path = __DIR__ .'../../../config/ca.pem';

    function __construct() {
        $dsn = "mysql:host={$this->servername};"
             . "port={$this->port};"
             . "dbname={$this->database};"
             . "charset=utf8mb4";

        $ssl_options = [
            PDO::MYSQL_ATTR_SSL_CA    => $this->ssl_ca_path, // Path to the ca.pem file
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true, // Enforce certificate verification
            PDO::ATTR_ERRMODE         => PDO::ERRMODE_EXCEPTION, // Essential for robust error handling
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Sets default fetch mode
        ];

        try {
            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                $ssl_options
            );

        } catch (PDOException $error) { 
         containlog('Database', 'Database connection failed', __DIR__, 'database.log');
         ob_clean();
         $response = ['success' => false, 'message' => 'Error Connecting Database'];
         echo json_encode($response, JSON_PRETTY_PRINT); 
         die();
     }
    }

    function getConn(){
        return $this->conn;
    }
}




/* 
class Database { //Database Connection
    // private $servername = "localhost";
    // private $username = "pgsys_admin";
    // private $password = "test";
    // private $database = "unity_pgsys_db";

    function __construct() {
            // $dsn = "mysql:host={$this->servername};dbname={$this->database};charset=utf8mb4";
            
            try {
                // Create the PDO instance
                $this->conn = new PDO($dsn, $this->username, $this->password);

                // Set PDO attributes for robust error handling and fetching
                // Throws exceptions on errors, which is critical for debugging
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                // Sets default fetch mode to associative arrays
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $error) { 
                containlog('Database', $error->getMessage(), __DIR__, 'database.log');
                $response = ['success' => false, 'message' => 'Error Connecting Database'];
                echo json_encode($response, JSON_PRETTY_PRINT); 
                die();
            }
        }

        function getConn(){
            return $this->conn;
        }
    } */
?>