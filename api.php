<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'functions.php';
require_once 'db.php';
require_once 'act.php';

const METHOD_POST = 'POST';

if ($_SERVER['REQUEST_METHOD'] !== METHOD_POST) {
    echo getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_REQUEST_METHOD);
    die();
}

$act = isset($_GET['act']) ? $_GET['act'] : null;

switch ($act) {
    case ACT_UPLOADER:
        echo uploadData();
        break;
    case ACT_REGISTER:
        echo registerUser();
        break;
    case ACT_LOGIN:
        echo loginUser();
        break;
    case ACT_LOGOUT:
        echo logoutAction();
        break;
    case ACT_LIKE:
        echo likeAction();
        break;
    default:
        echo getResponse(status: STATUS_ERROR, message: MESSAGE_INVALID_ACT);
        die();
}
?>
