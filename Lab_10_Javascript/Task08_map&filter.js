//Дан массив чисел. Используйте цепочку mapи filter, чтобы:
//Умножить каждый элемент на 3.
//Оставить только те, которые больше 10. 

//const numbers = [2, 5, 8, 10, 3];
// Результат после map: [6, 15, 24, 30, 9]
// Результат после filter: [15, 24, 30]

function map_filter(nums) {
    mapRes = nums.map(num => num * 3);
    filterRes = mapRes.filter(num => num > 10);
    return {
        map: mapRes,
        filter: filterRes
    }
}

const numbers = [2, 5, 8, 10, 3];
console.log(map_filter(numbers));