<?php
session_start();
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$ad_id = $_GET['id'];

// Жарнаманы алу
$stmt = $pdo->prepare("SELECT * FROM ads WHERE id = :id");
$stmt->execute(['id' => $ad_id]);
$ad = $stmt->fetch();

if (!$ad) {
    echo "Жарнама табылмады!";
    exit();
}

// Комментарийлерді алу
$stmt = $pdo->prepare("SELECT * FROM comments WHERE ad_id = :ad_id ORDER BY date_posted DESC");
$stmt->execute(['ad_id' => $ad_id]);
$comments = $stmt->fetchAll();

// Комментарий қосу
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $comment_text = htmlspecialchars($_POST['comment_text']);

    $stmt = $pdo->prepare("INSERT INTO comments (ad_id, user_id, comment_text, date_posted) VALUES (:ad_id, :user_id, :comment_text, NOW())");
    $stmt->execute([
        'ad_id' => $ad_id,
        'user_id' => $user_id,
        'comment_text' => $comment_text
    ]);

    header("Location: ad_detail.php?id=" . $ad_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="det.css">
    <title><?= htmlspecialchars($ad['title']) ?> - Жарнама</title>
</head>
<body>
    <div class="header">
        <a href="index.php" class="button">Басты бетке оралу</a>
        <h1><?= htmlspecialchars($ad['title']) ?></h1>
    </div>

    <div class="ad-detail">
        <p><?= htmlspecialchars($ad['description']) ?></p>
        <?php if ($ad['image']): ?>
            <img src="<?= $ad['image'] ?>" alt="Жарнама суреті" width="300">
        <?php endif; ?>
        <p><strong>Бағасы:</strong> <?= htmlspecialchars($ad['price']) ?> тг</p>
        <p><small><?= $ad['date_posted'] ?></small></p>
        
    <!-- Сатып алу батырмасы -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="buy.php?id=<?= $ad['id'] ?>" class="button">Сатып алу</a>
    <?php endif; ?>
    </div>


    <div class="comments">
        <h2>Комментарийлер</h2>
        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <p><?= htmlspecialchars($comment['comment_text']) ?></p>
                <small><?= $comment['date_posted'] ?></small>
            </div>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="" method="POST">
                <textarea name="comment_text" rows="4" placeholder="Комментарий жазыңыз" required></textarea>
                <button type="submit">Жіберу</button>
            </form>
        <?php else: ?>
            <p>Комментарий жазу үшін <a href="login.php">кіріңіз</a>.</p>
        <?php endif; ?>
    </div>
</body>
</html>
