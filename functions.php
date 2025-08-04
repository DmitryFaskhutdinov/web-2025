<?php 

const IMAGE_EXT = '.png';
const TITLE_MAX_LENGTH = 255;
const IMAGE_MAX_LENGTH = 50;
const IMAGE_MAX_RANDOM = 5;
const IMAGE_TYPE = 'image/png';
const IMAGE_SIZE = 1024 * 1024;

function validateTitle(string $title): bool {
    $isValidChars = preg_match('/^[A-Za-zА-Яа-я\s]+$/u', $title);
    $isValidLength = mb_strlen($title) <= TITLE_MAX_LENGTH;
    return $isValidChars && $isValidLength;
}

function hasValidImages(): bool {
    if (empty($_FILES['image']) || empty($_FILES['image']['error'])) {
        return false;
    }
    foreach ($_FILES['image']['error'] as $error) {
        if ($error !== UPLOAD_ERR_OK) {
            return false;
        }
    }
    return true;
}

function validateImage(string $type, int $size): bool {
    return $type === IMAGE_TYPE && $size <= IMAGE_SIZE;
}

function generateImageName(string $title): string {
    $safeTitle = preg_replace('/[^A-Za-z0-9_-]/', '', substr($title, offset: 0, length: IMAGE_MAX_LENGTH));
    if ($safeTitle === '') {
        $safeTitle = 'img';
    }
    $randomPart = substr(sha1(string: $title . time()), offset: 0, length: IMAGE_MAX_RANDOM);
    return $safeTitle . '-' . $randomPart . IMAGE_EXT;
}

function isUserLoggedIn(): bool {
    session_start();
    return isset($_SESSION['user_id']);
}

function logoutUser(): void {
    session_start();
    session_unset();
    session_destroy();
}
?>