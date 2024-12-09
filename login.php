<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Қате электрондық пошта немесе құпия сөз.";
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Кіру</title>
</head>
<body>
    <h1>Кіру</h1>
    <?php if (isset($error)): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <label for="email">Электрондық пошта:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Құпия сөз:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit" class="button">Кіру</button>
    </form>
    <p>Тіркелмегенсізбе?</p>
    <a href="register.php" class="button">Тіркелу</a>
    <!-- Артқа қайту батырмасы -->
    <a href="index.php" class="button back-button">Артқа қайту</a>
</body>
</html>

