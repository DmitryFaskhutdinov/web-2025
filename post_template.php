<div class="post">
    <!-- Заголовок поста -->
    <div class="head post__head">
        <a href="profile.php?id=<?= htmlspecialchars($post["userId"] ?? "", ENT_QUOTES) ?>" class="head__avatar-link">
            <?php if (!empty($post["userId"])): ?>
                <!-- Автар автора поста -->
                <div class="head__avatar">
                    <img class="head__avatar-image" src="images/<?= htmlspecialchars($authorAvatar, ENT_QUOTES) ?>" alt="Аватар" title="Аватар">                        
                </div>
                <!-- Имя автора поста -->
                <div class="head__name">
                    <?= htmlspecialchars($authorName) ?>
                    <?= htmlspecialchars($authorSurname) ?>
                </div>
            <?php else: ?>
                <div class="head__avatar">
                    <img src="images/default-avatar.png" alt="Аватар" title="Аватар">
                </div>
                <div class="head__name">Удаленный пользователь</div>
            <?php endif; ?>
        </a>
        <!-- Кнопка "Написать" -->
        <button class="head__write-button">
            <img src="images/write.svg" alt="Написать" title="Написать">
        </button>
    </div>
    <!-- Галерея -->
    <?php if (!empty($images)): ?>
        <div class="post__gallery modal__gallery">
            <div class="gallery__track">
                <!-- Вывод картинки -->
                <?php foreach ($images as $index => $image): ?>
                    <img class="gallery__image" src="images/<?= htmlspecialchars($image, ENT_QUOTES) ?>" alt="Пост-картинка" title="Пост-картинка">
                <?php endforeach; ?>
            </div>
            <!-- Индикатор количества изобаржений -->
            <?php if (count($images) > 1): ?>
                <div class="gallery__indicator">
                    <?= "1/" . count($images) ?>
                </div>
            <?php endif; ?>
            <!-- Кнопка переключения изображения -->
            <?php if (count($images) > 1): ?>
                <button class="gallery__next-image">
                    <img src="images/Arrow-right 10.svg" alt="Листать" title="Листать">
                </button>
                <button class="gallery__last-image">
                    <img src="images/Arrow-left 10.svg" alt="Листать" title="Листать">
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <!-- Лайки -->
    <button class="post__likes" type="button">
        <img class="likes__heart-image"  src="images/heart.png" alt="Реакции" title="Реакции">
        <span class="likes__score"><?= htmlspecialchars((string)($post["likes"] ?? 0)) ?></span>
    </button>
    <!-- Контент -->
    <div class="post__text">
        <p class="post__content"><?= htmlspecialchars($post["content"] ?? "") ?></p>
        <button class="post__more-button">ещё</button>
    </div>
    <!-- Дата поста -->
    <span class="post__date"><?= htmlspecialchars(showData($post["created_at"] ?? time())) ?></span>
</div>