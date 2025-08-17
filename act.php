<?php 

const ACT_UPLOADER = 'uploader';
const ACT_REGISTER = 'register';
const ACT_LOGIN = 'login';
const ACT_LOGOUT ='logout';

const STATUS_ERROR = 'error';
const STATUS_OK = 'ok';

const MESSAGE_INVALID_REQUEST_METHOD = 'invalid method';
const MESSAGE_INVALID_ACT = 'invalid act';

//функция загрузки данных
const MESSAGE_INVALID_AUTORISATION = 'Вы не авторизованы.';
const MESSAGE_INVALID_CONTENT_REQ = 'Для создания поста необходим текст и хотя бы одна картинка.';
const MESSAGE_INVALID_TITLE = 'invalid title';
const MESSAGE_INVALID_IMAGE = 'invalid image';
const MESSAGE_INVALID_SAVE_IMAGE = 'invalid save image';
const MESSAGE_INVALID_SAVE_DB_IMAGE = 'invalid save db image';

//функция регистрации
const MESSAGE_INVALID_PASSWORDS = 'Пароли не совпадают';
const MESSAGE_INVALID_EMAIL = 'Некорректный email';
const MESSAGE_INVALID_EMAIL_EXIST = 'Пользователь с таким email уже существует';
const MESSAGE_INVALID_SAVE_TO_DB = 'Ошибка при сохранении данных пользователя';

//функция логинации
const MESSAGE_INVALID_DB_CONNECTION = 'Произошла ошибка сервера. Попробуйте позже';
const MESSAGE_INVALID_FIELDS = 'Поля не заполнены';
const MESSAGE_INVALID_MAIL_OR_PASS = 'Неверный email или пароль';

function getResponse(string $status, string $message): string {
    $response = [
        'status' => $status,
        'message' => $message,
    ];
    return json_encode($response);
}

function uploadData(): string {

    session_start();
    $userId = $_SESSION['user_id'] ?? null;

    if (!$userId) {
        return getResponse(STATUS_ERROR, MESSAGE_INVALID_AUTORISATION );
    }

    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
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

    $connection = connectToDb();
    $isSuccess = savePostToDb($connection, $userId, $content, $filenames);
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
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_FIELDS);
    }

    $user = getUserByEmail($connection, $email);

    if ($user === null) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_MAIL_OR_PASS);
    }

    if (!password_verify($password, $user['password_hash'])) {
        return getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_MAIL_OR_PASS);
    }

    session_start();
    $_SESSION['user_id'] = $user['user_id'];

    return getResponse(status: STATUS_OK, message: '');
}


function logoutAction(): string {
    logoutUser(); // Функция уже есть в functions.php, нужно подумать над структурой проекта
    return getResponse(STATUS_OK, '');
}
?>