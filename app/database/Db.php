<?php
// Note: Assuming 'containlog' function is defined in utils/log.php and available here.
ob_start();
include_once __DIR__ .'../../utils/log.php';

class Database { // Database Connection

    // --- Primary (Aiven) Credentials ---
    private $servername = "mysql-f33c54e-fontejoedel1-8150.k.aivencloud.com";
    private $username = "Jho_del";
    private $password = "AVNS_qlduRudNkrNnyj_HUYV";
    private $database = "unity_pgsys_db";
    private $port = "24340";
    private $ssl_ca_path = __DIR__ .'../../../config/ca.pem';

    // --- Secondary (Localhost) Credentials (Fallback) ---
    private $backup_servername = "localhost";
    private $backup_username = "root";
    private $backup_password = "";
    private $backup_database = "unity_pgsys_db";
    
    private $conn;

    function __construct() {
        // --- Attempt 1: Primary (Aiven) Connection ---
        try {
            $dsn_primary = "mysql:host={$this->servername};"
                         . "port={$this->port};"
                         . "dbname={$this->database};"
                         . "charset=utf8mb4";

            $ssl_options = [
                PDO::MYSQL_ATTR_SSL_CA              => $this->ssl_ca_path,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
                PDO::ATTR_ERRMODE                   => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE        => PDO::FETCH_ASSOC
            ];

            $this->conn = new PDO(
                $dsn_primary,
                $this->username,
                $this->password,
                $ssl_options
            );
            
            containlog('Database', 'Successfully connected to Primary (Aiven) Database.', __DIR__, 'database.log');
            
        } catch (PDOException $primary_error) {
            containlog('Database', 'Primary (Aiven) connection failed. Attempting backup: ' . $primary_error->getMessage(), __DIR__, 'database.log');
            echo "<script>Primary (Aiven) connection failed. Attempting backup: " .$primary_error->getMessage() ."</script>";
            // --- Attempt 2: Backup (Localhost) Connection ---
            try {
                $dsn_backup = "mysql:host={$this->backup_servername};"
                            . "dbname={$this->backup_database};"
                            . "charset=utf8mb4";
                
                $this->conn = new PDO(
                    $dsn_backup,
                    $this->backup_username,
                    $this->backup_password,
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
                
                containlog('Database', 'Successfully connected to Backup (Localhost) Database.', __DIR__, 'database.log');

            } catch (PDOException $backup_error) {
                // Log the failure of the backup connection
                containlog('Database', 'Backup (Localhost) connection also failed: ' . $backup_error->getMessage(), __DIR__, 'database.log');
                containlog('Database', 'All database connection attempts failed.', __DIR__, 'database.log');
                
                ob_clean();
                $response = ['success' => false, 'message' => 'Error Connecting Database: All connection attempts failed.'];
                echo json_encode($response, JSON_PRETTY_PRINT);
                die();
            }
        }
    }

    function getConn(){
        return $this->conn;
    }
}