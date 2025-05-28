<?php
$usersJson = file_get_contents("data/users.json");
$postsJson = file_get_contents("data/posts.json");
if ($usersJson === false || $postsJson === false) {
    echo "Ошибка загрузки JSON-файлов";
}

$users = json_decode($usersJson, true);
$posts = json_decode($postsJson, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Ошибка JSON: " . json_last_error_msg();
}

$usersById = array_column($users, null, 'userId');

function showData($date) {
    $currentTime = time();
    $diff = $currentTime - $date;
    if ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "$hours часов назад";
    } else {
        return date("d.m.Y H:i", $date);
    }
}
?>

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
            <a href="http://localhost/profile.php" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-profile"></span>
            </a>
            <a href="+" class="menu-icon-wrapper">
                <span class="menu-icon menu-icon-plus"></span>
            </a>
        </div>
        <div class="scroll">
            <?php foreach ($posts as $post): 
                $author = $usersById[$post["userId"]] ?? null;
                $images = $post["images"] ?? [];
                include 'post_template.php'; 
            endforeach; ?>
        </div>
    </div>
</body>

</html>