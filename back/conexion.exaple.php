<?php

date_default_timezone_set('America/Argentina/Buenos_Aires'); // Cambiá a tu zona
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$port = 0000;
$db   = "your_database_name";
$user = "your_username";
$pass = "your_password";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db;charset=utf8",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    die("Error de conexión: " . $e->getMessage());
}
