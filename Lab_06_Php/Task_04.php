<?php
// Задание 4
function getZodiacSign($day, $month) {
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

function isLeapYear($year) {
    return (($year % 4 == 0) && ($year % 100 != 0)) || ($year % 400 == 0);
}

//  проверка реальности даты
function isRealDate($year, $month, $day) {
    if ($month < 1 || $month > 12) {
        return false;
    }
    if (isLeapYear($year)) {
        $feb = 29;
    } else {
        $feb = 28;
    }
    $daysInMonth = [0, 31, $feb, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    if ($day < 1 || $day > $daysInMonth[$month]) {
        return false;
    }
    return true;
}

function monthToNum($monthName) {
    $monthArr = ['january' => 1, 'february' => 2, 'march' => 3, 'april' => 4, 'may' => 5, 'june' => 6, 'july' => 7, 'august' => 8, 'september' => 9, 'october' => 10, 'november' => 11, 'december' => 12, 
    'января' => 1, 'февраля' => 2, 'марта' => 3, 'апреля' => 4, 'мая' => 5, 'июня' => 6, 'июля' => 7, 'августа' => 8, 'сентября' => 9, 'октября' => 10, 'ноября' => 11, 'декабря' => 12];
    $monthName = trim(mb_strtolower($monthName, 'UTF-8'));
    return $monthArr[$monthName] ?? 0;
}

function parseDate($date, $divider) {
    if ($divider === '') {
        $year = (int)substr($date, 0, 4);
        $month = (int)substr($date, 4, 2);
        $day = (int)substr($date, 6, 2);
    } else {
        $parts = explode($divider, $date);
        if (count($parts) != 3) {
            return 'Недопустимый формат даты';
        }
    }   
    switch($divider) {
        case '':
            $parts = [$year, $month, $day];
            break;
        case '.':
            [$day, $month, $year] = $parts;
            break;
        case '/':
            [$month, $day, $year] = $parts;
            break;
        case '-':
            [$year, $month, $day] = $parts;
            break;
        case ' ':
            [$day, $monthName, $year] = $parts;
            $month = monthToNum($monthName);
            break;
        default:
            return 'Недопустимый формат даты. Используйте ".", "-", "/" или пробел для разделения частей даты.';
    }
    if (!ctype_digit((string)$year) || !ctype_digit((string)$month) || !ctype_digit((string)$day)) {
        return 'Части даты должны быть числами';
    }
    $year = (int)$year;
    $month = (int)$month;
    $day = (int)$day;
    if (!isRealDate($year, $month, $day)) {
        return 'Такой даты не существует';
    }
    return ['year' => $year, 'month' => $month, 'day' => $day];
}


if (isset($_GET['free_zodiac'])) {
    $input = trim($_GET['free_zodiac']);

    if ($input === '') {
        echo 'Пустой ввод';
    } elseif (ctype_digit($input) && strlen($input) === 8) {
        // YYYYMMDD
        $divider = '';
    } elseif (strpos($input, '.') !== false) {
        // DD.MM.YYYY
        $divider = '.';
    } elseif (strpos($input, '/') !== false) {
        // MM/DD/YYYY
        $divider = '/';
    } elseif (strpos($input, '-') !== false) {
        // YYYY-MM-DD
        $divider = '-';
    } else {
        // Текстовый формат: "2 июля 2004", "02 Март 2023", etc.
        $divider = ' ';
    }
    $result = parseDate($input, $divider);
    if (is_array($result)) {
        echo getZodiacSign($result['day'], $result['month']);
    } else {
        echo "Ошибка: " . $result;
    }
}
?>