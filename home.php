<?php
session_start();
date_default_timezone_set('Europe/Moscow');
require_once 'db.php';

$connection = connectToDb();
$posts = getPostFromDb($connection);

$userId = $_SESSION['user_id'] ?? null;

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

