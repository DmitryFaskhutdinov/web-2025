<?php

function popTwoOperands(&$stack) {
    if (count($stack) < 2) {
        return null;
    }
    $b = array_pop($stack);
    $a = array_pop($stack);
    return [$a, $b];
}

function calculateRPN($parts, $count) {
    $stack = [];
    for ($index = 0;  $index < $count; $index++) {
        if ($parts[$index] !== '+' && $parts[$index] !== '-' && $parts[$index] !== '*') {
            if (!is_numeric($parts[$index])) {
                return 'Ошибка, в нотации присутвует нечисловое значение: ' . $parts[$index];
            }
            array_push($stack, (int)$parts[$index]);
        } else {
            $operands = popTwoOperands($stack);
            if ($operands === null) {
                return 'Ошибка, нужно два операнда';
            }
            [$a, $b] = $operands;

            if ($parts[$index] === '+') {
                array_push($stack, $a + $b);
            } elseif ($parts[$index] === '-') {
                array_push($stack, $a - $b);
            } elseif ($parts[$index] === '*') {
                array_push($stack, $a * $b);
            }
        }
    }
    if (count($stack) !== 1) {
        return 'Ошибка, нотация вычисленна некорректно';
    }
    return array_pop($stack);
}

const MAX_PARTS = 100;
const MIN_PARTS = 3;

if (isset($_GET['polish'])) {
    $input = trim($_GET['polish']);
    if ($input === '') {
        echo 'Строка не должна быть пустой';
        return;
    }

    $parts = explode(' ', $input);
    $count = count($parts);

    if ($count < MIN_PARTS) {
        echo 'Запись должна содержать не менее ', MIN_PARTS, ' частей';
    } elseif ($count > MAX_PARTS) {
        echo 'Запись должна содержать не более ', MAX_PARTS, ' частей';
    } else {
        echo 'Принято: ', htmlspecialchars($input), '<br>';
        echo 'Количество элементов: ', $count, '<br>';
        echo calculateRPN($parts, $count);
    }
}

?>