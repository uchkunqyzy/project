<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Пайдаланушының мәліметтерін алу
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

// Пайдаланушының жарнамаларын алу
$stmt = $pdo->prepare("SELECT * FROM ads WHERE user_id = :user_id ORDER BY date_posted DESC");
$stmt->execute(['user_id' => $user_id]);
$user_ads = $stmt->fetchAll();

// Профиль суретін өзгерту
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_image'])) {
    $profileImage = $user['profile_image'];
    if ($_FILES['profile_image']['error'] == 0) {
        $profileImage = 'uploads/profile_' . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $profileImage)) {
            // Сурет жүктелген жағдайда
            $_SESSION['success_message'] = "Сурет сақталды!";
        } else {
            $_SESSION['error_message'] = "Сурет жүктеу кезінде қате болды.";
        }
    }

    // Пайдаланушының мәліметтерін жаңарту
    $stmt = $pdo->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :id");
    $stmt->execute(['profile_image' => $profileImage, 'id' => $user_id]);

    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="prof.css">
    <title>Профиль</title>
</head>
<body>
    <h1>Профиль</h1>

    <!-- Сурет сақталды немесе қате туралы хабарлама -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p style="color: green;"><?= $_SESSION['success_message'] ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php elseif (isset($_SESSION['error_message'])): ?>
        <p style="color: red;"><?= $_SESSION['error_message'] ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Профиль суретін өзгерту формасы -->
    <form method="POST" enctype="multipart/form-data">
        <label for="profile_image">Профиль суретін өзгерту:</label>
        <input type="file" id="profile_image" name="profile_image" accept="image/*">
        <button type="submit" class="button">Сақтау</button>
    </form>

    <!-- Жарнамалар -->
    <h2>Сіздің жарнамаларыңыз</h2>
    <div class="ads-container">
        <?php foreach ($user_ads as $ad): ?>
            <div class="ad">
                <h3><?= htmlspecialchars($ad['title']) ?></h3>
                <p><?= htmlspecialchars($ad['description']) ?></p>
                <?php if ($ad['image']): ?>
                    <img src="<?= $ad['image'] ?>" alt="Жарнама суреті" width="200">
                <?php endif; ?>
                <p><strong>Бағасы:</strong> <?= htmlspecialchars($ad['price']) ?> тг</p>
                <p><strong>Санаты:</strong> <?= htmlspecialchars($ad['category']) ?></p>
                <a href="edit.php?id=<?= $ad['id'] ?>" class="button">Өзгерту</a>
                <a href="delete.php?id=<?= $ad['id'] ?>" class="button">Жою</a>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Артқа қайту батырмасы -->
    <a href="index.php" class="button back-button">Артқа қайту</a>
</body>
</html>
