<?php
if ($free_zodiac == '' ) {
        echo 'пустой ввод'; exit;
    } else {
        if (strlen($free_zodiac) <= 17) {
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
    !>