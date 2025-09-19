<?php 

const ACT_UPLOADER = 'uploader';
const ACT_REGISTER = 'register';
const ACT_LOGIN = 'login';
const ACT_LOGOUT ='logout';
const ACT_LIKE = 'like';
const ACT_EDIT = 'edit';
const ACT_RENDER = 'render';

const STATUS_ERROR = 'error';
const STATUS_OK = 'ok';

const MESSAGE_INVALID_REQUEST_METHOD = 'method не валиден';
const MESSAGE_INVALID_ACT = 'act не валиден';

//функция загрузки данных
const MESSAGE_INVALID_AUTORISATION = 'Вы не авторизованы.';
const MESSAGE_INVALID_CONTENT_REQ = 'Для создания поста необходим текст и хотя бы одна картинка.';
const MESSAGE_INVALID_TITLE = 'Текст содержит недопустимые символы';
const MESSAGE_INVALID_IMAGE = 'Недопустимое изображение';
const MESSAGE_INVALID_SAVE_IMAGE = 'Ошибка сохранения изображения. Попробуйте позже.';
const MESSAGE_INVALID_SAVE_DB_IMAGE = 'Не удалось сохранить пост. Попробуйте позже.';

//функция редактирования поста
const MESSAGE_INVALID_POST = "Пост не найден";
const MESSAGE_INVALID_EDIT_REQUEST = "Вы не можете редактировать этот пост";

//функция регистрации
const MESSAGE_INVALID_PASSWORDS = 'Пароли не совпадают';
const MESSAGE_INVALID_EMAIL = 'Некорректный email';
const MESSAGE_INVALID_EMAIL_EXIST = 'Пользователь с таким email уже существует';
const MESSAGE_INVALID_SAVE_TO_DB = 'Ошибка при сохранении данных пользователя';

//функция логинации
const MESSAGE_INVALID_DB_CONNECTION = 'Произошла ошибка сервера. Попробуйте позже';
const MESSAGE_INVALID_FIELDS = 'Поля не заполнены';
const MESSAGE_INVALID_MAIL_OR_PASS = 'Неверный email или пароль';

// лайки
const MESSAGE_INVALID_POST_ID = 'post_id обязателен';

function getResponse(string $status, string $message): string {
    $response = [
        'status' => $status,
        'message' => $message,
    ];
    return json_encode($response);
}

function authBySession(PDO $connection): array {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        http_response_code(401);
        echo getResponse(STATUS_ERROR, MESSAGE_INVALID_AUTORISATION );
        die();
    }

    $statement = $connection->prepare("SELECT * FROM user WHERE user_id = ?");
    $statement->execute([$userId]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo getResponse(STATUS_ERROR, MESSAGE_INVALID_AUTORISATION ); 
        die();
    }

    return $user;
}

function uploadData(): string {
    $connection = connectToDb();
    if (!$connection) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_DB_CONNECTION);
    }
    $user = authBySession($connection);
    $userId = $user['user_id'];

    $jsonInput = $_POST['json'] ?? '';
    $data = json_decode($jsonInput, true);
    $content = isset($data['content']) ? trim($data['content']) : ''; 

    $hasText = ($content !== '');

    $filenames = [];

    $hasImages = hasValidImages();

    if (!$hasText || !$hasImages) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_CONTENT_REQ );
    }
    
    if (!validateTitle($content)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_TITLE);
    }

    if ($hasImages) {
        foreach ($_FILES['image']['error'] as $key => $error) {

            $type = $_FILES['image']['type'][$key];
            $size = $_FILES['image']['size'][$key];

            if (!validateImage($type, $size)) {
                return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_IMAGE);
            }

            $tmpName = $_FILES['image']['tmp_name'][$key];
            $originalName = $_FILES['image']['name'][$key];

            $filename = generateImageName($originalName);
            $filenames[] = $filename;

            $isSuccess = move_uploaded_file($tmpName, 'images/' . $filename);
            if (!$isSuccess) {
                return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_SAVE_IMAGE);
            }
        }
    }

    $isSuccess = savePostToDb($connection, $userId, $content, $filenames);
    if (!$isSuccess) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_SAVE_DB_IMAGE);
    }

    return getResponse(status: STATUS_OK, message: '');
}

function editAction(): string {
    $connection = connectToDb();
    if (!$connection) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_DB_CONNECTION);
    }
    $user = authBySession($connection);
    $userId = $user['user_id'];

    $jsonInput = $_POST['json'] ?? '';
    $data = json_decode($jsonInput, true);
    $postId = $data['post_id'] ?? null;

    if (!$postId || !is_numeric($postId)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_POST_ID);
    }
    $postId = (int)$postId;

    $existingPost = getPostByPostId($connection, $postId);
    if (!$existingPost) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_POST);
    }
    if ($existingPost['userId'] !== $userId) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_EDIT_REQUEST);
    }


    $content = isset($data['content']) ? trim($data['content']) : ''; 

    $filenames = $existingPost['images'] ?? [];

    if ($content === '' && empty($filenames)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_CONTENT_REQ);
    }
    
    if (!validateTitle($content)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_TITLE);
    }

    $hasImages = hasValidImages();
    if ($hasImages) {
        foreach ($_FILES['image']['error'] as $key => $error) {

            $type = $_FILES['image']['type'][$key];
            $size = $_FILES['image']['size'][$key];

            if (!validateImage($type, $size)) {
                return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_IMAGE);
            }

            $tmpName = $_FILES['image']['tmp_name'][$key];
            $originalName = $_FILES['image']['name'][$key];

            $filename = generateImageName($originalName);
            $filenames[] = $filename;

            $isSuccess = move_uploaded_file($tmpName, 'images/' . $filename);
            if (!$isSuccess) {
                return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_SAVE_IMAGE);
            }
        }
    }

    $isSuccess = updatePostInDb($connection, $postId, $content, $filenames);
    if (!$isSuccess) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_SAVE_DB_IMAGE);
    }

    return getResponse(status: STATUS_OK, message: '');
}

function registerUser(): string {
    $connection = connectToDb();
    if (!$connection) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_DB_CONNECTION);
    }

    // Получаем поля из $_POST
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $about_me = isset($_POST['about_me']) ? trim($_POST['about_me']) : '';

    // Валидация
    if (!$name || !$email || !$password || !$password_confirm) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_FIELDS);
    }
    if ($password !== $password_confirm) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_PASSWORDS);
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_EMAIL);
    }

    // Проверка уникальности почты
    if (getUserByEmail($connection, $email)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_EMAIL_EXIST);
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Обработка аватара
    $avatar_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image'];
        if (!validateImage($image['type'], $image['size'])) {
            return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_IMAGE);
        }
        $avatar_filename = generateImageName($email);
        $target_path = 'images/' . $avatar_filename;
        if (!move_uploaded_file($image['tmp_name'], $target_path)) {
            return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_SAVE_IMAGE);
        }
        $avatar_path = $avatar_filename;
    }

    // Сохраниение в бд
    if (!saveUser($connection, $name, $surname, $email, $password_hash, $about_me, $avatar_path)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_SAVE_TO_DB);
    }

    return getResponse(STATUS_OK, '');
}

function loginUser() {
    $connection = connectToDb();
    if (!$connection) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_DB_CONNECTION);
    }

    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        http_response_code(401);
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_FIELDS);
    }

    $user = getUserByEmail($connection, $email);

    if ($user === null || !password_verify($password, $user['password_hash'])) {
        http_response_code(401);
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_MAIL_OR_PASS);
    }

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $user['user_id'];

    http_response_code(200);
    return json_encode([
        'status' => STATUS_OK,
        'message' => '',
        'user_id' => $user['user_id']
    ]);
}


function logoutAction(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_unset();
    session_destroy();
    return getResponse(status: STATUS_OK, message: '');
}

function likeAction(): string {
    $connection = connectToDb();
    if (!$connection) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_DB_CONNECTION);
    }
    $user = authBySession($connection);
    $userId = $user['user_id'];

    $postId = $_POST['post_id'] ?? null;
    if ($postId === null || !is_numeric($postId)) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_POST_ID); // 'post_id is required'
    }
    $postId = (int)$postId;

    $isLiked = switchLike($connection, $userId, $postId);
    $likeCount = getLikeCount($connection, $postId);

    return json_encode([
        'status' => STATUS_OK,
        'message' => '',
        'liked' => $isLiked,
        'likes' => $likeCount
    ]);
}

function getPosts(): string {
    $connection = connectToDb();
    if (!$connection) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_DB_CONNECTION);
    }  

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $userId = $_SESSION['user_id'] ?? null;
    
    $postsData = getPostFromDb($connection, $userId);

    return json_encode([
        'status' => STATUS_OK,
        'message' => '',
        'posts' => $postsData
    ]);
}
?>