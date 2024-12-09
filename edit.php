<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $ad_id = $_GET['id'];

    // Жазбаны алу
    $stmt = $pdo->prepare("SELECT * FROM ads WHERE id = :id");
    $stmt->execute(['id' => $ad_id]);
    $ad = $stmt->fetch();

    if ($_SESSION['user_id'] != $ad['user_id']) {
        header("Location: index.php");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}

// Жазбаны жаңарту
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $imagePath = $ad['image'];  // Алдыңғы сурет

    if ($_FILES['image']['error'] == 0) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            // Сурет сәтті жүктелсе
        }
    }

    // Жазбаны мәліметтер базасында жаңарту
    $stmt = $pdo->prepare("UPDATE ads SET title = :title, description = :description, category = :category, image = :image WHERE id = :id");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'category' => $category,
        'image' => $imagePath,
        'id' => $ad_id
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Жазбаны өзгерту</title>
</head>
<body>
    <h1>Жазбаны өзгерту</h1>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Тақырыбы:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($ad['title']) ?>" required>

        <label for="description">Сипаттамасы:</label>
        <textarea id="description" name="description" required><?= htmlspecialchars($ad['description']) ?></textarea>

        <label for="category">Санат:</label>
        <select id="category" name="category" required>
            <option value="Тұрмыстық техника" <?= $ad['category'] == 'Тұрмыстық техника' ? 'selected' : '' ?>>Тұрмыстық техника</option>
            <option value="Электроника" <?= $ad['category'] == 'Электроника' ? 'selected' : '' ?>>Электроника</option>
            <option value="Киім" <?= $ad['category'] == 'Киім' ? 'selected' : '' ?>>Киім</option>
            <option value="Авто" <?= $ad['category'] == 'Авто' ? 'selected' : '' ?>>Авто</option>
            <option value="Басқалар" <?= $ad['category'] == 'Басқалар' ? 'selected' : '' ?>>Басқалар</option>
        </select>

        <label for="image">Сурет қосу:</label>
        <input type="file" id="image" name="image" accept="image/*">
        <?php if ($ad['image']): ?>
            <img src="<?= $ad['image'] ?>" alt="Жазба суреті" width="100">
        <?php endif; ?>

        <button type="submit" class="button">Жаңарту</button>
    </form>

    <a href="index.php" class="button">Артқа қайту</a>
</body>
</html>
