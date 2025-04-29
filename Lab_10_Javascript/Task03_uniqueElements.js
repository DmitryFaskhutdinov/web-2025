function uniqueElements(arr) {
    const counter = {}
    for (element of arr) {
        const key = String(element)
        if (counter[key]) {
            counter[key] += 1
        } else {
            counter[key] = 1
        }
    }
    return counter
}



console.log(uniqueElements(['hello', 'привет', 'hi', 'привет', 1, '1']))