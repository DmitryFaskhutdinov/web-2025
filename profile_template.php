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
            <header class="menu__header"></header>
            <a href="home.php" class="menu__link">
                <span class="menu__icon menu__icon_type_home"></span>
            </a>
            <a href="profile.php" class="menu__link">
                <span class="menu__icon menu__icon_type_profile"></span>
            </a>
            <a href="+" class="menu__link">
                <span class="menu__icon menu__icon_type_plus"></span>
            </a>
        </div>
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