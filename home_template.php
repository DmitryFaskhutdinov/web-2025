<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8"> 
    <title>Главная страница</title>
    <link rel="stylesheet" type="text/css" href="css/home.css">
    <link rel="stylesheet" type="text/css" href="css/font.css">
</head>

<body>
    <div class="container">
        <div class="menu">
            <header class="menu__header"></header>
            <a href="http://localhost/home.php" class="menu__link">
                <span class="menu__icon menu__icon_type_home"></span>
            </a>
            <a href="<?php echo $userId ? "profile.php?id=$userId" : "login.php"; ?>" class="menu__link">
                <span class="menu__icon menu__icon_type_profile"></span>
            </a>
            <a href="http://localhost/create_post.php" class="menu__link">
                <span class="menu__icon menu__icon_type_plus"></span>
            </a>
        </div>
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
</body>
</html>