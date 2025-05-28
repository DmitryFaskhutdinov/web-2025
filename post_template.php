<div class="post">
    <!-- Заголовок поста -->
    <div class="post-head">
        <a href="profile.php?id=<?= htmlspecialchars($author["userId"] ?? "", ENT_QUOTES) ?>" class="avatar-link">
            <?php if ($author): ?>
                <!-- Автар автора поста -->
                <div class="avatar">
                    <img class="av-image" src="<?= htmlspecialchars($author["avatar"], ENT_QUOTES) ?>" alt="Аватар" title="Аватар">                        
                </div>
                <!-- Имя автора поста -->
                <div class="name">
                    <?= htmlspecialchars($author["name"]) ?>
                    <?= htmlspecialchars($author["surname"]) ?>
                </div>
            <?php else: ?>
                <div class="avatar">
                    <img src="images/default-avatar.png" alt="Аватар" title="Аватар">
                </div>
                <div class="name">Удаленный пользователь</div>
            <?php endif; ?>
        </a>
        <!-- Кнопка "Написать" -->
        <button class="write-button">
            <img src="images/write.svg" alt="Написать" title="Написать">
        </button>
    </div>
    <!-- Галерея -->
        <?php if (!empty($images)): ?>
        <div class="gallery">
            <!-- Вывод картинки -->
            <?php foreach ($images as $index => $image): ?>
                <img class="gallery-image" src="<?= htmlspecialchars($image, ENT_QUOTES) ?>" alt="Пост-картинка" title="Пост-картинка">
            <?php endforeach; ?>
            <!-- Индикатор количества изобаржений -->
            <?php if (count($images) > 1): ?>
                <div class="indicator">
                    <?= "1/" . count($images) ?>
                </div>
            <?php endif; ?>
            <!-- Кнопка переключения изображения -->
            <?php if (count($images) > 1): ?>
                <button class="next-image">
                    <img src="images/sign.svg" alt="Листать" title="Листать">
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <!-- Лайки -->
    <button class="likes" type="button">
        <img class="heart"  src="images/heart.png" alt="Реакции" title="Реакции">
        <span class="score"><?= htmlspecialchars((string)($post["likes"] ?? 0)) ?></span>
    </button>
    <!-- Контент -->
    <div class="posttext">
        <p class="p-text"><?= htmlspecialchars($post["content"] ?? "") ?></p>
        <button class="more">ещё</button>
    </div>
    <!-- Дата поста -->
    <span class="date"><?= htmlspecialchars(showData($post["createdAt"] ?? time())) ?></span>
</div>