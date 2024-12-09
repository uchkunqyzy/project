<?php
session_start();
require 'db.php';

// Егер пайдаланушы кірген болса
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Пайдаланушының жарнамаларына жазылған комментарийлерді алу
    $stmt = $pdo->prepare("SELECT c.comment_text, c.date_posted, u.username, a.title
                           FROM comments c
                           JOIN users u ON c.user_id = u.id
                           JOIN ads a ON c.ad_id = a.id
                           WHERE a.user_id = :user_id  -- Пайдаланушының жарнамалары
                           ORDER BY c.date_posted DESC");
    $stmt->execute(['user_id' => $user_id]);
    $messages = $stmt->fetchAll();
} else {
    // Егер пайдаланушы жүйеге кірмеген болса
    $_SESSION['error_message'] = "Сіз жүйеге кірмегенсіз.";
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="mes.css">
    <title>Хабарламалар</title>
</head>
<body>
    <div class="header">
        <h1>Хабарламалар</h1>
        <a href="index.php" class="button">Басты бетке қайту</a>
    </div>

    <div class="messages-container">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <h3>Жарнама: <?= htmlspecialchars($message['title']) ?></h3>
                    <p><strong>Пайдаланушы:</strong> <?= htmlspecialchars($message['username']) ?></p>
                    <p><?= htmlspecialchars($message['comment_text']) ?></p>
                    <small>Уақыты: <?= $message['date_posted'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Қазіргі уақытта ешқандай хабарлама жоқ.</p>
        <?php endif; ?>
    </div>
</body>
</html>
