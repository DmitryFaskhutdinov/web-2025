<?php
// Задание 4
function zodiacSign($day, $month) {
    if (($day >= 21 && $month == 3) || ($day <= 19 && $month == 4)) {
        return 'овен';
    } elseif (($day >= 20 && $month == 4) || ($day <= 20 && $month == 5)) {
        return 'телец';
    } elseif (($day >= 21 && $month == 5) || ($day <= 21 && $month == 6)) {
        return 'близнецы';
    } elseif (($day >= 22 && $month == 6) || ($day <= 22 && $month == 7)) {
        return 'рак';
    } elseif (($day >= 23 && $month == 7) || ($day <= 22 && $month == 8)) {
        return 'лев';
    } elseif (($day >= 23 && $month == 8) || ($day <= 22 && $month == 9)) {
        return 'дева';
    } elseif (($day >= 23 && $month == 9) || ($day <= 23 && $month == 10)) {
        return 'весы';
    } elseif (($day >= 24 && $month == 10) || ($day <= 22 && $month == 11)) {
        return 'скорпион';
    } elseif (($day >= 23 && $month == 11) || ($day <= 21 && $month == 12)) {
        return 'стрелец';
    } elseif (($day >= 22 && $month == 12) || ($day <= 20 && $month == 1)) {
        return 'козерог';
    } elseif (($day >= 21 && $month == 1) || ($day <= 18 && $month == 2)) {
        return 'водолей';
    } elseif (($day >= 19 && $month == 2) || ($day <= 20 && $month == 3)) {
        return 'рыбы';
    } else {
        return 'Несуществующая дата';
    }
}

function textData($data) {
    $monthArr = ['january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12, 
    'января' => 1, 'февраля' => 2, 'марта' => 3, 'апреля' => 4, 'мая' => 5, 'июня' => 6, 'июля' => 7, 'августа' => 8, 'сентября' => 9, 'октября' => 10, 'ноября' => 11, 'декабря' => 12];
    $data = explode(' ', $data);
    if (count($data) != 3) {
        return 'Неверный текстовый формат. Введите ДД Месяц ГГГГ';
    }
    $day = $data[0]; 
    $month = mb_strtolower($data[1]);
    $year = $data[2];   
    if ((int)$day > 31 || !(ctype_digit($day))) {
        return 'Неверный текстовый формат. День указан некорректно!';
    } elseif (!(array_key_exists($month, $monthArr))) {
        return 'Неверный месяц';
    } elseif (!(ctype_digit($year)) || mb_strlen($year) != 4) {
        return 'Неверный текстовый формат. Год должен быть 4-х значным числом';
    } else {
        //проверки успешны
        $month = $monthArr[$month];
        return zodiacSign($day, $month); // что должна возвращать функция?
    }
}

function diffData($data) {
    if (ctype_digit($data)) {
        return 'Unix';
    } else {
        
    } 
}
// 2015-07-17
// 17:04:43
// 17.07.2015
// american


if (isset($_GET['free_zodiac'])) {
    $free_zodiac = ($_GET['free_zodiac']);
    if ($free_zodiac == '') {
        echo 'Пустой ввод';
    } elseif (mb_strlen($free_zodiac) > 17) {
        echo 'Слишком длинный ввод';
    } elseif (mb_strlen($free_zodiac) <= 17 && mb_strlen($free_zodiac) != 10 ) {
        //Вызов функции на валидацию текстового формата
        $textFormat = textData($free_zodiac);
        echo $textFormat;
    } elseif (mb_strlen($free_zodiac) == 10) {
        //Вызов функции на проверку Unix, ISO, Европейский, Американский
        $diffFormat = diffData($free_zodiac);
        echo $diffFormat;
    } else {
        echo 'Неверный формат';
    }
}
?>

