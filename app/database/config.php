<?php
// config/config.php
$host = 'localhost';
$dbname = 'unity_pgsys_db';
$username = 'pgsys_admin';
$password = 'test';

// $host = "sql213.infinityfree.com";
// $username = "if0_40422080";
// $password = "rrLrWJCO9rp";
// $dbname = "if0_40422080_unity_pgsys_db";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log(date('[Y-m-d H:i:s] ') . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/../log/database.log');
    die("Database connection failed: " . $e->getMessage());
}
?>
