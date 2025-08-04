//Напишите код, который будет генерировать пароль заданного размера (размер передается параметром к функции). Пароль обязательно должен будет содержать маленькую и большую буквы, цифру и какой-то специальный символ

function genPass(size) {
    const smallChars = 'abcdefghijklmnopqrstuvwxyz';
    const bigChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const nums = '0123456789';
    const specialChars = '!@#$%^&*()';

    const MAX_CONDITIONS = 4;
    if (size < MAX_CONDITIONS) {
        return 'Длина пароля должна быть минимум ' + MAX_CONDITIONS;
    } else {
        let passChars = [
            smallChars[randomIndex(smallChars)],
            bigChars[randomIndex(bigChars)],
            nums[randomIndex(nums)],
            specialChars[randomIndex(specialChars)]
        ];
        const allChars = smallChars + bigChars + nums + specialChars;

        while (passChars.length < size) {
            passChars.push(allChars[randomIndex(allChars)]);
        }

        shuffle(passChars);
        return passChars.join('');

    }
}

function randomIndex(str) {
    return Math.floor(Math.random() * str.length);
}

function shuffle(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [array[i], array[j]] = [array[j], array[i]];
    }
}

console.log(genPass(3));
console.log(genPass(4));
console.log(genPass(5));
console.log(genPass(10));