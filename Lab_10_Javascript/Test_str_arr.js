function countVowels(str) {
    const vowels = [
        'а', 'е', 'ё', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я', 
        'А', 'Е', 'Ё', 'И', 'О', 'У', 'Ы', 'Э', 'Ю', 'Я',
        'a', 'e', 'i', 'o', 'u', 'y', 'A', 'E', 'I', 'O', 'U', 'Y'
    ]
    let count = 0
    for (const char of str) {
        if (vowels.includes(char)) count++;
    }
    return count + ' - number of vowels'
}

const str = 'Hello world! Привет Мир!'
console.log(countVowels(str))
