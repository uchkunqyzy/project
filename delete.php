<?php
require 'db.php';

if (isset($_GET['id'])) {
    $ad_id = $_GET['id'];
    
    // Жарнаманы жою үшін, пайдаланушы растауы керек
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Алдымен комментарийлерді жою
        $stmt = $pdo->prepare("DELETE FROM comments WHERE ad_id = :ad_id");
        $stmt->execute(['ad_id' => $ad_id]);

        // Содан кейін жарнаманы жою
        $stmt = $pdo->prepare("DELETE FROM ads WHERE id = :id");
        $stmt->execute(['id' => $ad_id]);

        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="kk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Жарнаманы жою</title>
    <script>
        function confirmDeletion() {
            // Егер пайдаланушы "ОК" батырмасын басса, форма жіберіледі
            var confirmation = confirm("Жарнаманы жоюға сенімдісіз бе?");
            if (confirmation) {
                document.getElementById("deleteForm").submit(); // Форманы жіберу
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Жарнаманы жою</h1>
        <p>Сіз осы жарнаманы жоюға сенімдісіз бе?</p>
        
        <!-- Жою үшін форма -->
        <form id="deleteForm" method="POST" action="">
            <button type="button" onclick="confirmDeletion()" class="button delete">Жою</button>
            <a href="index.php" class="button cancel">Болдырмау</a>
        </form>
    </div>
</body>
</html>
