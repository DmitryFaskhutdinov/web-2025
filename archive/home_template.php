<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8"> 
    <title>Главная страница</title>
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/font.css">
    <link rel="stylesheet" type="text/css" href="css/menu.css">
    <script src="js/menu.js" defer></script>
    <script src="js/home_template.js" defer></script>
</head>
<body>
    <div class="container">
        <?php include 'menu.php'; ?>
        <div class="scroll">
            <?php foreach ($posts as $post): 
                $authorName = $post["name"];
                $authorSurname = $post["surname"];
                $authorAvatar = $post["avatar"];
                $images = $post["images"] ?? [];
                include 'post_template.php'; 
            endforeach; ?>
        </div>
    </div>
    <!-- Модальное окно -->
    <div class="modal" id="Modal">
        <div class="modal__window">
            <div class="modal__image-container">
                <button class="modal__close">
                    <img src="images/Cross 30.svg" alt="Закрыть" title="Закрыть">
                </button>
                <div class="modal__viewport">
                    <div class="modal__track"></div>
                </div>
                <button class="modal__button modal__next-image">
                    <img src="images/Arrow-right 10.svg" alt="Листать" title="Листать">
                </button>
                <button class="modal__button modal__prev-image">
                    <img src="images/Arrow-left 10.svg" alt="Листать" title="Листать">
                </button>
                <div class="modal__indicator">
                    <?= "1 из " . count($images) ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>