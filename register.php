<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);

    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Тіркелу</title>
</head>
<body>
    <h1>Тіркелу</h1> <!-- Тақырыпты жоғарыда орналастыру -->
    <form method="POST">
        <label for="username">Қолданушы аты:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Электрондық пошта:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Құпия сөз:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="button">Тіркелу</button>
    </form>
    <a href="index.php" class="button">Артқа қайту</a>
</body>
</html>
