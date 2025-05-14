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
        return 'Несуществующая дата'
    }
}

function textData($data) {
    $monthArr = ['january' => 1, 'february' => 1, 'march' => 1, 'april' => 1, 'may' => 1, 'june' => 1, 'july' => 1, 'august' => 1, 'september' => 1, 'october' => 1, 'november' => 1, 'december' => 1, 
    'январь' => 1, 'февраль' => 1, 'март' => 1, 'апрель' => 1, 'май' => 1, 'июнь' => 1, 'июль' => 1, 'август' => 1, 'сентябрь' => 1, 'октябрь' => 1, 'ноябрь' => 1, 'декабрь' => 1];
    $data = explode(' ', $data);
    if (count($data) != 3) {
        return 'Неверный текстовый формат. Введите ''ДД Месяц ГГГГ'''
    }
    $day = $data[0]; 
    $month = mb_strtolower($data[1]);
    $year = $data[2];   
    if ((int)$day > 31 || !(ctype_digit($day))) {
        return 'Неверный текстовый формат. День указан некорректно!'
    } elseif (!(array_key_exists($month, $monthArr, true))) {
        return 'Неверный месяц'
    } elseif (!(ctype_digit($year)) || strlen($year) != 4) {
        return 'Неверный текстовый формат. Год должен быть 4-х значным числом'
    } else {
        //проверки успешны
        $month = $monthArr[$month];
        return zodiacSign($day, $month); // что должна возвращать функция?
    }
}

function diffData($data) {
    return 'возврат функции успешен'
}

function dataForm($data) {
    if ($data == '') {
        return 'Пустой ввод';
    } elseif (strlen($data) > 16) {
        return 'Слишком длинный ввод'
    } elseif (strlen($data) == 16) {
        //Вызов функции на валидацию текстового формата
        $textFormat = textData($data);
        return $textFormat;
    } elseif (strlen($data) == 10) {
        //Вызов функции на проверку Unix, ISO, Европейский, Американский
        $diffFormat = diffData($data);
        return $diffFormat;
    } else {
        return 'Неверный формат';
    }
}

if (isset($_GET['free_zodiac'])) {
    $free_zodiac = ($_GET['free_zodiac']);
    if ($free_zodiac == '' ) {
        echo 'пустой ввод'; exit;
    } else {
        if (strlen($free_zodiac) <= 16) {
            if (is_numeric($free_zodiac) && strlen($free_zodiac) == 10) {
                echo 'Unix Timestamp'; //!
                $flag = 1;
            } elseif (is_numeric($free_zodiac) && strlen($free_zodiac) <> 10) {
                echo 'неверный Unix Timestamp'; exit;
            } elseif (strlen($free_zodiac) == 10) {
                if ($free_zodiac[4] == '-' && $free_zodiac[7] == '-') {
                    echo 'ISO 8601'; //!
                    $flag = 1;
                } elseif ($free_zodiac[2] == '.' && $free_zodiac[5] == '.') {
                    echo 'Европейский (ДД.ММ.ГГГГ)'; //!
                    $flag = 1;
                } elseif ($free_zodiac[2] == '/' && $free_zodiac[5] == '/') {
                    echo 'Американский (ММ/ДД/ГГГГ)'; //!
                    $flag = 1;
                } else {
                    echo 'Неверный формат'; exit;
                }
            } else {
                echo 'Неверный формат'; exit;
            }
        } else {
            echo 'Cлишком длинный ввод'; exit;
        }
    }


    echo zodiacSign($day, $month);
}
?>