// Напишите функцию mapObject(obj, callback), которая применяет
// callback к каждому значению объекта и возвращает новый объект с результатами. Пример:
// const nums = { a: 1, b: 2, c: 3 };
// mapObject(nums, x => x * 2) // { a: 2, b: 4, c: 6 }

const nums = { a: 1, b: 2, c: 3 };

function mapObject(obj, callback) {
    const result = {};
    for (let key in obj) {
        result[key] = callback(obj[key], key)
    }
    return result;
}

console.log(mapObject(nums, x => x * 2));
console.log(mapObject(nums, x => x + 1)); 
console.log(mapObject(nums, x => x * x)); 
console.log(mapObject(nums, (value, key) => key + value)); 


//const names = arr.map(name => name.name)