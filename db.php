<?php
$host = 'localhost';
$db = 'ad_board';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Мәліметтер базасына қосыла алмады: " . $e->getMessage());
}
?>
