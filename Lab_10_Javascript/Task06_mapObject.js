// Напишите функцию mapObject(obj, callback), которая применяет
// callback к каждому значению объекта и возвращает новый объект с результатами. Пример:
// const nums = { a: 1, b: 2, c: 3 };
// mapObject(nums, x => x * 2) // { a: 2, b: 4, c: 6 }

const nums = { a: 1, b: 2, c: 3 }

function mapObjects(obj, callback) {
    const result = obj.map(callback)
    return result
}

console.log(mapObjects(nums, x => x * 2))


//const names = arr.map(name => name.name)