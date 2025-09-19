<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUserId = $_SESSION['user_id'] ?? null;

require_once 'db.php';

$connection = connectToDb();

$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$userId) {
    header('Location: home.php');
    exit();
}

$profile = getProfileFromDb($connection, $userId);

if ($profile === null) {
    header('Location: home.php');
    exit();
}

// Развернутый массив для удобства в шаблоне
$user = $profile['user'];
$postCount = $profile['postCount'];
$images = $profile['images'];
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль <?= htmlspecialchars($user["name"] . " " . $user["surname"]) ?></title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
    <script src="js/menu.js" defer></script>
</head>
<body>
    <div class="container">
        <?php include 'menu.php'; ?>
        <div class="content">
            <!-- Аватар -->
            <div class="content__frame">
                <img class="content__avatar" src="images/<?= htmlspecialchars($user["avatar"] ?? "images/default-avatar.png", ENT_QUOTES) ?>" title="Аватар" alt="Аватар пользователя">
            </div>
            <!-- Имя -->
            <h1 class="content__name">
                <?= htmlspecialchars($user["name"] ?? "") ?>
                <?= htmlspecialchars($user["surname"] ?? "") ?>
            </h1>
            <!-- Обо мне -->
            <?php if (!empty($user["about_me"])): ?>
                <p class="content__aboutme"><?= htmlspecialchars($user["about_me"]) ?></p>
            <?php endif; ?>
            <!-- Количество постов -->
             <button type="button" class="content__button">
                <img class="content__post-icon" src="images/posts.svg" title="Иконка постов" alt="Иконка постов">
                <span class="content__post-count">Постов: <?= htmlspecialchars($postCount) ?></span>
            </button>
            <!-- Галерея -->
            <div class="content__gallery">
                <?php foreach ($images as $image): ?>
                    <div class="content__gallery-frame">
                        <img class="content__gallery-image" src="images/<?= htmlspecialchars($image, ENT_QUOTES) ?>" title="Изображение пользователя" alt="Изображение пользователя">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>   
</body>
</html>