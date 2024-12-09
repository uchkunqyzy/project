<?php
session_start();
require 'db.php';

// Сұрыптау параметрлерін алу
$sortOption = isset($_GET['sort']) ? $_GET['sort'] : 'date_posted'; // Әдепкіде уақыт бойынша
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : ''; // Санат бойынша

// SQL сұрыптау сұрауын құру
switch ($sortOption) {
    case 'date_posted': // Уақыт бойынша
        $orderBy = 'date_posted DESC';
        break;
    case 'price': // Бағасы бойынша
        $orderBy = 'price ASC';
        break;
    case 'category': // Санаты бойынша
        $orderBy = 'category ASC';
        break;
    default:
        $orderBy = 'date_posted DESC';
}

// Барлық жарнамаларды алу
$sql = "SELECT * FROM ads";
$params = [];

// Санат бойынша фильтр
if (!empty($categoryFilter)) {
    $sql .= " WHERE category = :category";
    $params['category'] = $categoryFilter;
}

// Сұрыптау қосу
$sql .= " ORDER BY $orderBy";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$ads = $stmt->fetchAll();

// Пайдаланушы деректері
$user = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();
}

// Сәтті сатып алу туралы хабарлама өңдеу
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Жарнамалық хабарландыру тақтасы</title>
</head>
<body>
<div class="header">
    <h1>Жарнамалар</h1>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-profile">
            <a href="messages.php" class="button">Хабарламалар</a>
            <a href="create.php" class="button">Қосу</a>
            <a href="profile.php" class="button">Профиль</a>
            <a href="logout.php" class="button">Шығу</a>
            <div class="profile-info">
                <img src="<?= $user['profile_image'] ? $user['profile_image'] : 'uploads/default-avatar.png' ?>" alt="Профиль суреті" class="profile-image">
                <span><?= htmlspecialchars($user['username']) ?></span>
            </div>
        </div>
    <?php else: ?>
        <a href="login.php" class="button">Кіру / Тіркелу</a>
    <?php endif; ?>
</div>

<!-- Сұрыптау мәзірі -->
<div class="sort-filter">
    <form method="GET" action="">
        <label for="sort">Сұрыптау:</label>
        <select id="sort" name="sort" onchange="this.form.submit()">
            <option value="date_posted" <?= $sortOption === 'date_posted' ? 'selected' : '' ?>>Уақыты бойынша</option>
            <option value="price" <?= $sortOption === 'price' ? 'selected' : '' ?>>Бағасы бойынша</option>
        </select>

        <label for="category">Санат:</label>
        <select id="category" name="category" onchange="this.form.submit()">
            <option value="" <?= $categoryFilter === '' ? 'selected' : '' ?>>Барлығы</option>
            <option value="Тұрмыстық техника" <?= $categoryFilter === 'Тұрмыстық техника' ? 'selected' : '' ?>>Тұрмыстық техника</option>
            <option value="Электроника" <?= $categoryFilter === 'Электроника' ? 'selected' : '' ?>>Электроника</option>
            <option value="Киім" <?= $categoryFilter === 'Киім' ? 'selected' : '' ?>>Киім</option>
            <option value="Авто" <?= $categoryFilter === 'Авто' ? 'selected' : '' ?>>Авто</option>
            <option value="Басқалар" <?= $categoryFilter === 'Басқалар' ? 'selected' : '' ?>>Басқалар</option>

        </select>
    </form>
</div>

<!-- Сәтті хабарлама -->
<?php if (isset($success_message)): ?>
    <p style="color: green;"><?= htmlspecialchars($success_message) ?></p>
<?php endif; ?>

<!-- Жарнамалар -->
<div class="ads-container">
    <?php if (count($ads) > 0): ?>
        <?php foreach ($ads as $ad): ?>
            <div class="ad">
                <h2><a href="ad_detail.php?id=<?= $ad['id'] ?>"><?= htmlspecialchars($ad['title']) ?></a></h2>
                <p><?= htmlspecialchars($ad['description']) ?></p>
                <?php if ($ad['image']): ?>
                    <img src="<?= $ad['image'] ?>" alt="Жарнама суреті">
                <?php endif; ?>
                <p><strong>Бағасы:</strong> <?= htmlspecialchars($ad['price']) ?> тг</p>
                <p><strong>Санаты:</strong> <?= htmlspecialchars($ad['category']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Жарнамалар табылмады.</p>
    <?php endif; ?>
</div>

</body>
</html>
