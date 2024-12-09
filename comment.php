<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['ad_id']) && isset($_POST['comment_text'])) {
    $ad_id = $_POST['ad_id'];
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];

    // Пайдаланушының аты
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();

    // Комментарийді деректер базасына сақтау
    $stmt = $pdo->prepare("INSERT INTO comments (ad_id, user_id, comment_text, username) VALUES (:ad_id, :user_id, :comment_text, :username)");
    $stmt->execute([
        'ad_id' => $ad_id,
        'user_id' => $user_id,
        'comment_text' => $comment_text,
        'username' => $user['username']
    ]);

    header("Location: ad_detail.php?id=" . $ad_id);
    exit();
}
