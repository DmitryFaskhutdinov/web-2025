<?php
$usersJson = file_get_contents("data/users.json");
$postsJson = file_get_contents("data/posts.json");
if ($usersJson === false || $postsJson === false) {
    header("Location: home.php");
    exit();
}

$users = json_decode($usersJson, true);
$posts = json_decode($postsJson, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    header("Location: home.php");
    exit();
}

$userId = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT, [
    "options" => ["min_range" => 1]
]);

if ($userId === null || $userId === false) {
    header("Location: home.php");
    exit();
}

$usersById = array_column($users, null, 'userId');
$user = $usersById[$userId] ?? null; 

if (!$user) {
    header("Location: home.php");
    exit();
}

$userPosts = array_filter($posts, function ($post) use ($userId) {
    return $post["userId"] == $userId;
});
$postCount = count($userPosts);

include "profile_template.php";
?>