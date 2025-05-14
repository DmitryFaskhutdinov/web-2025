<?php
// Задание 1
function leapYear($year) {
    if (($year % 4 == 0) && ($year % 100 != 0)) {
        return 'да';
    } elseif ($year % 400 == 0) {
        return 'да';
    } else {
        return 'нет';
    }
}

if (isset($_GET['year'])) {
    if (ctype_digit($_GET['year']))  {
        if (($_GET['year']) > 0) {
            echo leapYear($_GET['year']);
        } else {
            echo 'Число должно быть положительным';
        }
    } else {
        echo 'неверный ввод';
    }
}

// Задание 2
function translation($n) {
        switch ($n) {
        case 0:
            return 'ноль';
        case 1:
            return 'один';
        case 2:
            return 'два';
        case 3:
            return 'три';
        case 4:
            return 'четыре';
        case 5:
            return 'пять';
        case 6:
            return 'шесть';
        case 7:
            return 'семь';
        case 8:
            return 'восемь';
        case 9:
            return 'девять';
        default:
            return 'неверный ввод';
    }   
}

if (isset($_GET['translator'])) {
    $tr = $_GET['translator'];
    echo translation($tr);
}

// Задание 3
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
    }
}

if (isset($_GET['zodiac'])) {
    if (($_GET['zodiac']) <> '') {
        $zodiac = ($_GET['zodiac']);
        $data = explode('-', $zodiac);
        $year = $data[0];
        $month = $data[1];
        $day = $data[2]; 
        echo $_GET['zodiac']; // test 1234-01-23 водолей
        echo ' '; // test
        echo zodiacSign($day, $month);
    } else {
        echo 'неверный ввод';
    }
}

// Задание 5
function luckyTiket($n1, $n2) {
    for ($i = $n1; $i <= $n2; $i++) {
        $istr = (string)$i;
        for ($ilength = strlen($istr); $ilength < 6; $ilength++) {
            $istr = '0' . $istr;
        }
        $num0 = (int)$istr[0];
        $num1 = (int)$istr[1];
        $num2 = (int)$istr[2];
        $num3 = (int)$istr[3];
        $num4 = (int)$istr[4];
        $num5 = (int)$istr[5];
        $luck1 = $num0 + $num1 + $num2;
        $luck2 = $num3 + $num4 + $num5;
        if ($luck1 == $luck2) {
            $answer[] = $istr;
        }
    }
    return implode('<br>', $answer);
}

if (isset($_GET['tiket1']) && isset($_GET['tiket2'])) {
    $tiket1 = $_GET['tiket1'];
    $tiket2 = $_GET['tiket2'];
    if ($tiket1 == '' || $tiket2 == '') {
        echo 'Поля не должны быть пустыми';
    } else {
        if (strlen($tiket1) == 6 && strlen($tiket2) == 6) {
            if ((int)$tiket1 <= (int)$tiket2) {
                echo luckyTiket($tiket1, $tiket2);
            } else {
                echo 'Некорректный диапазон';
            }
        } else {
            echo 'Введитие 6-значные билеты';
       }
    }
}

// Задание 6
function findFactorial($n) {
    if ($n <= 1) {
        return 1;
    } else {
        return $n * findFactorial($n - 1);
    }
}

if (isset($_GET['factorial'])) {
    if (is_numeric($_GET['factorial'])) {
        $fct = $_GET['factorial'];
        if ((int)$fct >= 0) {
            echo findFactorial($fct);
        } else {
            echo 'введите положитьельное число';
        }
    } else {
        echo 'неверный ввод';
    }
}
?>