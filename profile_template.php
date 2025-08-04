<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Профиль <?= htmlspecialchars($user["name"] . " " . $user["surname"]) ?></title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body>
    <div class="container">
        <div class="menu">
            <header class="menu-header"></header>
            <a href="home.php" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-home"></span>
            </a>
            <a href="profile.php" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-profile"></span>
            </a>
            <a href="+" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-plus"></span>
            </a>
        </div>

        <div class="content">
            <!-- Аватар -->
            <div class="frame">
                <img class="image" src="images/<?= htmlspecialchars($user["avatar"] ?? "images/default-avatar.png", ENT_QUOTES) ?>" title="Аватар" alt="Аватар пользователя">
            </div>

            <!-- Имя -->
            <h1 class="name">
                <?= htmlspecialchars($user["name"] ?? "") ?>
                <?= htmlspecialchars($user["surname"] ?? "") ?>
            </h1>

            <!-- Обо мне -->
            <?php if (!empty($user["about_me"])): ?>
                <p class="aboutme"><?= htmlspecialchars($user["about_me"]) ?></p>
            <?php endif; ?>

            <!-- Количество постов -->
             <button type="button" class="button">
                <img class="post-icon" src="images/posts.svg" title="Иконка постов" alt="Иконка постов">
                <span class="post-count">Постов: <?= htmlspecialchars($postCount) ?></span>
            </button>

            <!-- Галерея -->
            <div class="gallery">
                <?php foreach ($images as $image): ?>
                    <div class="gallery-frame">
                        <img class="images" src="images/<?= htmlspecialchars($image, ENT_QUOTES) ?>" title="Изображение пользователя" alt="Изображение пользователя">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
</body>

</html>