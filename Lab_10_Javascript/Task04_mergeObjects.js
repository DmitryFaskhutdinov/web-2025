function mergeObjects(obj1, obj2) {
    const keys1 = Object.keys(obj1)
    const keys2 = Object.keys(obj2)
    if (keys1.some(key => keys2.includes(key))) {
        return Object.assign(obj1, obj2)
    }
    return 'Can`t merge'
}

console.log(mergeObjects({ a: 1, b: 2 }, { b: 3, c: 4 }))
console.log(mergeObjects({ a: 1, b: 2 }, { c: 3, d: 4 }))

// .some позволяет узнать есть ли в keys1 хотябы 1 элемент удовлетворяющий условию keys2.includes(key)
// Условие keys2.includes(key) проверяет есть ли элемент массива keys1 в keys2 