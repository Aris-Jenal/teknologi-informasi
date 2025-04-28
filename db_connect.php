<?php
$host = 'localhost';
$dbname = 'um_tapsel';
$username = 'root'; // Change to your MySQL username
$password = ''; // Change to your MySQL password

try {
    $pdo = new PDO("mysql:host=localhost;dbname=um_tapsel", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
