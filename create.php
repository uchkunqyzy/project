<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    $category = htmlspecialchars($_POST['category']);
    $user_id = $_SESSION['user_id'];

    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image_path = 'uploads/' . time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
        $image = $image_path;
    }

    $stmt = $pdo->prepare("INSERT INTO ads (title, description, price, category, image, user_id, date_posted) VALUES (:title, :description, :price, :category, :image, :user_id, NOW())");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'price' => $price,
        'category' => $category,
        'image' => $image,
        'user_id' => $user_id
    ]);

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Жарнама қосу</title>
</head>
<body>
    <div class="header">
        <a href="index.php" class="button">Басты бетке оралу</a>
        <h1>Жарнама қосу</h1>
    </div>

    <form action="create.php" method="POST" enctype="multipart/form-data">
        <label for="title">Атауы:</label>
        <input type="text" name="title" id="title" required>

        <label for="description">Сипаттамасы:</label>
        <textarea name="description" id="description" required></textarea>

        <label for="price">Бағасы:</label>
        <input type="number" name="price" id="price" required>

        <label for="category">Санаты:</label>
        <select name="category" id="category" required>
            <option value="">Санатты таңдаңыз</option>
            <option value="Тұрмыстық техника">Тұрмыстық техника</option>
            <option value="Электроника">Электроника</option>
            <option value="Киім">Киім</option>
            <option value="Авто">Авто</option>
            <option value="Басқалар">Басқалар</option>
        </select>

        <label for="image">Сурет:</label>
        <input type="file" name="image" id="image">

        <button type="submit">Қосу</button>
    </form>
</body>
</html>
