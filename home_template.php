<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8"> 
    <title>Главная страница</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/font.css">
</head>

<body>
    <div class="container">
        <div class="menu">
            <header class="menu-header"></header>
            <a href="http://localhost/home.php" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-home"></span>
            </a>
            <a href="<?php echo $userId ? "profile.php?id=$userId" : "login.php"; ?>" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-profile"></span>
            </a>
            <a href="http://localhost/create_post.php" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-plus"></span>
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