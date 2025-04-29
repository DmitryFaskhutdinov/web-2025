function isPrimeNumber(num) {
    if (num > 1) {
      for (let i = 2; i < num; i++) {
        if (num % i == 0) {
          return num + ' not prime'
        }
      }
      return num + ' prime'
    } else {
      return "Num must be bigger than " + num
    }
}

console.log(isPrimeNumber(1))
console.log(isPrimeNumber(2))
console.log(isPrimeNumber(3))
console.log(isPrimeNumber(4))
console.log(isPrimeNumber(5))
console.log(isPrimeNumber(6))
console.log(isPrimeNumber(7))
console.log(isPrimeNumber(8))
console.log(isPrimeNumber(9))
console.log(isPrimeNumber(10))
console.log(isPrimeNumber(11))
console.log(isPrimeNumber(12))



const arr = [3, 4, 5]
arr.forEach(num => {
    console.log(isPrimeNumber(num))
});