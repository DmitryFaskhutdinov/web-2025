<?php
require_once 'db.php'; // Подключение к БД и функции

$connection = connectToDb();

$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if (!$userId) {
    header('Location: home.php');
    exit();
}

// Получаем профиль пользователя из базы
$profile = getProfileFromDb($connection, $userId);

if ($profile === null) {
    header('Location: home.php');
    exit();
}

// Разворачиваем массив для удобства в шаблоне
$user = $profile['user'];
$postCount = $profile['postCount'];
$images = $profile['images'];

include "profile_template.php";
?>