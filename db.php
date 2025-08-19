<?php

const DB_HOST = '127.0.0.1';
const DB_NAME = 'blog';
const DB_USER = 'blog_user';
const DB_PASS = 'bd_pass1234';

function connectToDb(): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
    return new PDO($dsn, username: DB_USER, password: DB_PASS);
}

function getPostFromDb(PDO $connection, $userId, int $limit = 100): array {
    $query = <<<SQL
        SELECT
            p.post_id, p.content, p.created_at,
            u.user_id, u.name, u.surname, u.avatar,
            pi.image_path
        FROM
            post p
        LEFT JOIN
            user u ON p.user_id = u.user_id
        LEFT JOIN
            post_image pi ON p.post_id = pi.post_id
        ORDER BY 
            p.created_at DESC, pi.image_id ASC
        LIMIT {$limit}
    SQL;

    $statement = $connection->query($query);
    $rawPosts = $statement->fetchAll(PDO::FETCH_ASSOC);

    $posts = [];

    foreach ($rawPosts as $row) {
        $postId = $row['post_id'];

        if (!isset($posts[$postId])) {
            $posts[$postId] = [
                'post_id' => $postId,
                'userId' => $row['user_id'],
                'likes' => getLikeCount($connection, $postId),
                'liked' => isLikedByUser($connection, $userId, $postId),
                'content' => $row['content'],
                'created_at' => $row['created_at'],
                'name' => $row['name'],
                'surname' => $row['surname'],
                'avatar' => $row['avatar'],
                'images' => [],
            ];
        }

        if (!empty($row['image_path'])) {
            $posts[$postId]['images'][] = $row['image_path'];
        }
    }

    return array_values($posts);
}

//////////////////////////////////////////////////////////////////////////////

function getProfileFromDb(PDO $connection, int $userId): ?array {
    // Получаем информацию о пользователе
    $userQuery = <<<SQL
        SELECT 
            user_id, name, surname, avatar, about_me
        FROM 
            user
        WHERE 
            user_id = {$userId}
    SQL;

    $statement = $connection->query($userQuery);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        return null;
    }

    // Получаем изображения из всех постов пользователя
    $imagesQuery = <<<SQL
        SELECT 
            pi.image_path
        FROM 
            post p
        JOIN 
            post_image pi ON p.post_id = pi.post_id
        WHERE 
            p.user_id = {$userId}
        ORDER BY 
            pi.image_id ASC
    SQL;

    $statement = $connection->query($imagesQuery);
    $images = $statement->fetchAll(PDO::FETCH_COLUMN);

    // Получаем количество постов пользователя
    $countQuery = <<<SQL
        SELECT COUNT(*) FROM post WHERE user_id = {$userId}
    SQL;

    $statement = $connection->query($countQuery);
    $postCount = (int)$statement->fetchColumn();

    // Формируем итоговую структуру
    return [
        'user' => $user,
        'images' => $images,
        'postCount' => $postCount
    ];
} 

function getUserByEmail(PDO $connection, string $email): ?array {
    $statement = $connection->prepare("SELECT * FROM user WHERE email = ?");
    $statement->execute([$email]);
    return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
}

function saveUser(PDO $connection, string $name, string $surname, string $email, string $passwordHash, ?string $aboutMe, ?string $avatarPath): bool {
    $statement = $connection->prepare("
        INSERT INTO user (name, surname, email, password_hash, about_me, avatar) 
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    return $statement->execute([$name, $surname, $email, $passwordHash, $aboutMe, $avatarPath]);
}

////////////////////////////////////////////////////////////////////////////////

function savePostToDb(PDO $connection, int $userId, string $content, array $filenames): bool {
    $postQuery = "INSERT INTO post (user_id, content) VALUES (:user_id, :content)";
    $postStmt = $connection->prepare($postQuery);
    $success = $postStmt->execute([
        ':user_id' => $userId,
        ':content' => $content
    ]);

    $postId = $connection->lastInsertId();

    $imageQuery = "INSERT INTO post_image (post_id, image_path) VALUES (:post_id, :image_path)";
    $imageStmt = $connection->prepare($imageQuery);

    foreach ($filenames as $filename) {
        $success = $imageStmt->execute([
            ':post_id' => $postId,
            ':image_path' => $filename
        ]);
        if (!$success) {
            return false;
        }
    }

    return true;
}

////////////////////////////////////////////////////////////////////////////////

function switchLike(PDO $connection, int $userId, int $postId): bool {
    $statement = $connection->prepare("SELECT 1 FROM post_like WHERE post_id = ? AND user_id = ?");
    $statement->execute([$postId, $userId]);
    $exists = (bool)$statement->fetchColumn();

    if ($exists) {
        //
        $delete = $connection->prepare("DELETE FROM post_like WHERE post_id = ? AND user_id = ?");
        $delete->execute([$postId, $userId]);
        return false;
    } else {
        //
        $insert = $connection->prepare("INSERT INTO post_like (post_id, user_id) VALUES (?, ?)");
        $insert->execute([$postId, $userId]);
        return true;
    }
}

function getLikeCount(PDO $connection, $postId): int {
    $statement = $connection->prepare("SELECT COUNT(*) FROM post_like WHERE post_id = ?");
    $statement->execute([$postId]);
    return (int)$statement->fetchColumn();
}

function isLikedByUser(PDO $connection, int $userId, int $postId): bool {
    if (!$userId) {
        return false;
    }
    $statement = $connection->prepare("SELECT 1 FROM post_like WHERE post_id = ? AND user_id = ?");
    $statement->execute([$postId, $userId]);
    return (bool)$statement->fetchColumn();
}
?>