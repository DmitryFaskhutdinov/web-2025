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
include 'home_template.php';
?>

