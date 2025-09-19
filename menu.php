<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
    $userName = null;
    $userEmail = null;
    $currentUserId = $_SESSION['user_id'] ?? null;
    if ($currentUserId) {
        $userData = getUserDataByUserId($connection, $currentUserId);
        $userName = $userData['name'] ?? null;
        $userEmail = $userData['email'] ?? null;
    }

    function generateUserColor($mail): string {
        $colors = [
            "#F44336", "#E91E63", "#9C27B0", "#673AB7",
            "#3F51B5", "#2196F3", "#03A9F4", "#009688",
            "#4CAF50", "#FF9800", "#FF5722", "#795548",
        ];
        $mailChar = mb_substr($mail, 0, 1);
        $hash = crc32(strtolower($mailChar));
        return $colors[$hash % count($colors)];
    }
?>
<div class="menu">
    <header class="menu__header"></header>
    <?php if ($userName): ?>
        <div class="menu__user-icon menu__icon_type_user" 
            style="background-color: <?= htmlspecialchars(generateUserColor($userEmail)) ?>;">
            <?= htmlspecialchars(mb_substr($userName, 0, 1)) ?>
        </div>
    <?php endif; ?>
    <a href="home.php" class="menu__link">
        <span class="menu__icon menu__icon_type_home <?php if($currentPage === 'home.php') echo 'active'; ?>"></span>
    </a>
    <?php if ($currentUserId !== null): ?>
        <a href="profile.php?id=<?= $currentUserId ?>" class="menu__link">
            <span class="menu__icon menu__icon_type_profile <?php if($currentPage === 'profile.php') echo 'active'; ?>"></span>
        </a>
        <a href="create_post.php" class="menu__link">
            <span class="menu__icon menu__icon_type_plus <?php if($currentPage === 'create_post.php') echo 'active'; ?>"></span>
        </a>
        <a class="menu__link">
            <span class="menu__icon menu__icon_type_logout"></span>
        </a>
    <?php else: ?>
        <a href="login.html" class="menu__link">
            <span class="menu__icon menu__icon_type_login"></span>
        </a>
        <a href="register.php" class="menu__link">
            <span class="menu__icon menu__icon_type_register"></span>
        </a>
    <?php endif; ?>
</div>