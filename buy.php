<?php
session_start();
require 'db.php';

if (isset($_GET['id'])) {
    $ad_id = $_GET['id'];

    // Жарнаманы сатып алу, статусын өзгерту
    $stmt = $pdo->prepare("UPDATE ads SET status = 'sold' WHERE id = :id");
    $stmt->execute(['id' => $ad_id]);

    $_SESSION['success_message'] = "Тауар сатылды!";
    header("Location: index.php");
    exit();
}
?>
