<?php
session_start();
$userId = $_SESSION['user_id'] ?? null;
?>

<!DOCTYPE html>
<html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <title>Создать пост</title>
        <link rel="stylesheet" type="text/css" href="css/create_post.css">
        <link rel="stylesheet" type="text/css" href="css/font.css">
        <script src="js/create_post.js" defer></script>
    </head>
    <body>
        <div class="container">
            <div class="menu">
                <header class="menu__header"></header>
                <a href="http://localhost/home.php" class="menu__link">
                    <span class="menu__icon menu__icon_type_home"></span>
                </a>
                <a href="<?php echo $userId ? "profile.php?id=$userId" : "login.html"; ?>" class="menu__link">
                    <span class="menu__icon menu__icon_type_profile"></span>
                </a>
                <a href="<?php echo $userId ? "create_post.php" : "login.html"; ?>" class="menu__link">
                    <span class="menu__icon menu__icon_type_plus"></span>
                </a>
            </div>
            <!-- Создать пост -->
             <form class="form" action='api.php?act=uploader' enctype='multipart/form-data' method='POST'>
                <div class="form__gallery">
                    <div class="form__photo-frame">
                        <div class="frame__track"></div>
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
                <label class="form__multi-file" for="multFileInput">
                    <img src="images/plus-square.svg" alt="+">
                    <span>Добавить фото</span>
                </label>
                <input id="multFileInput" type="file" name="image[]" accept=".png" style="display:none;" multiple/>
                <div class="form__text-area">
                    <label>Добавить подпись</label>
                    <textarea name="content" rows="10"></textarea>
                </div>
                <div>
                    <button class="frame__submit" type='submit'>Поделиться</button>
                </div>
            </form>
        </div>
    </body>
</html>