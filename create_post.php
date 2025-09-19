<?php
//to do: добавить возможность удолять изображения в сохранение и редактирование постов

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUserId = $_SESSION['user_id'] ?? null;
$postId = $_GET['id'] ?? null;
$editingPost = null;

require_once 'db.php';
$connection = connectToDb();

if($postId && is_numeric($postId)) {
    $editingPost = getPostByPostId($connection, (int)$postId);

    if (!$editingPost || $editingPost['userId'] !== $currentUserId) {
        $editingPost = null;
    }
}
?>

<!DOCTYPE html>
<html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <title><?= $editingPost ? 'Редактирование поста' : 'Создать пост' ?></title>
        <link rel="stylesheet" type="text/css" href="css/create_post.css">
        <link rel="stylesheet" type="text/css" href="css/font.css">
        <link rel="stylesheet" type="text/css" href="css/menu.css">
        <script src="js/menu.js" defer></script>
        <script src="js/create_post.js" defer></script>
    </head>
    <body>
        <div class="container">
            <?php include 'menu.php'; ?>
            <!-- Создать пост -->
             <form class="form" 
                action='api.php?act=<?= $editingPost ? 'edit' : 'uploader' ?>' 
                data-post-id='<?= $editingPost['post_id'] ?? "" ?>'
                enctype='multipart/form-data' 
                method='POST'>

                <?php if ($editingPost): ?>
                    <input type="hidden" name="id" value="<?= $editingPost['post_id'] ?>">
                <?php endif; ?>
                <div class="form__gallery">
                    <div class="form__photo-frame">
                        <div class="frame__track">
                            <!-- добавлено -->
                            <?php if (!empty($editingPost['images'])): ?>
                                <?php foreach ($editingPost['images'] as $image): ?>
                                    <img class="frame__image" src="images/<?= htmlspecialchars($image) ?>" alt="Пост-картинка">
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <!-- добавлено -->
                        </div>
                        <div class="frame__container">
                            <img class="frame__placeholder" src="images/add-photo.png" alt="Плейсхолдер" class="placeholder">
                            <label class="frame__button" for="oneFileInput">Добавить фото</label>
                            <input id="oneFileInput" type="file" name="image[]" accept=".png" style="display:none;"/>
                        </div>
                    </div>
                    <!-- Кнопки переключения изображения -->
                    <button class="form__next-image" type="button">
                        <img src="images/Arrow-right 10.svg" alt="Листать" title="Листать">
                    </button>
                    <button class="form__last-image" type="button">
                        <img src="images/Arrow-left 10.svg" alt="Листать" title="Листать">
                    </button>
                </div>
                <!-- Кнопки добавления изображений -->
                <label class="form__multi-file" for="multFileInput">
                    <img src="images/plus-square.svg" alt="+">
                    <span>Добавить фото</span>
                </label>
                <input id="multFileInput" type="file" name="image[]" accept=".png" style="display:none;" multiple/>
                <div class="form__text-area">
                    <label>Добавить подпись</label>
                    <textarea name="content" rows="10"><?= htmlspecialchars($editingPost['content'] ?? '') ?></textarea>
                </div>
                <!-- Кнопка отправки -->
                <div class="frame__submit-container">
                    <button class="frame__submit" type='submit'>
                        <?= $editingPost ? 'Сохранить' : 'Поделиться' ?>
                    </button>
                    <div class="frame__error-box"></div>
                </div>
            </form>
            <!-- Окно при успешном сохранении/редактировании поста -->
            <div class="post-saved">
                <span class="post-saved__message">
                    <?= $editingPost ? 'Пост успешно изменен!' : 'Пост успешно создан!'  ?>
                </span>
                <div class="post-saved__button-container">
                    <button class="post-saved__button post-saved__home" type="button" onclick="location.href='home.php'">На главную</button>
                    <button class="post-saved__button post-saved__create" type="button" onclick="location.href='create_post.php'">Создать еще</button>
                </div>
            </div>
        </div>
    </body>
</html>

