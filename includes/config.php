<?php

$servername = "localhost:3307";
$username = "root"; // Default for phpMyAdmin
$password = "4bu5!RR]*jufGC1O"; // Leave blank if no password is set
$database = "sulat_project";

$host = 'localhost:3307';       // or 127.0.0.1
$db   = 'sulat_project';   // change to your database name
$user = 'root';            // default user for XAMPP
$pass = '4bu5!RR]*jufGC1O';                // default password for XAMPP is empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                   // disable emulated prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    echo "<script>console.log('Connection failed: '" . $e->getMessage()."')</script>;";
}
?>