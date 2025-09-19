<?php
//to do: объединить  с другими файлами, сделать js для построения поста и модального окна
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Europe/Moscow');
require_once 'db.php';

$connection = connectToDb();
$currentUserId = $_SESSION['user_id'] ?? 0;
$posts = getPostFromDb($connection, $currentUserId);

function showData($date) {
    $currentTime = time();
    $timestamp = strtotime($date); 
    $diff = $currentTime - $timestamp;

    if ($diff < 60) {
        return 'только что'; // в пределах первой минуты
    } elseif ($diff < 3600) {
        $minutes = floor($diff / 60);
        return "$minutes минут назад";
    } elseif ($diff < 86400) {
        $hours = floor($diff / 3600);
        return "$hours часов назад";
    } else {
        return date("d.m.Y H:i", $timestamp);
    }
}

include 'home_template.php';
?>

