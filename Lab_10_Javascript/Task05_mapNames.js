function returnNames(arr) {
  const names = arr.map(name => name.name)
  return names
}

const users1 = [
  { id: 1, name: "Alice" },
  { id: 2, name: "Bob" },
  { id: 3, name: "Charlie" }
];

const users2 = [
  { id: 1, name: "Gustav" },
  { id: 2, name: "Danny" },
  { id: 3, name: "Michelle" },
  { id: 3, name: "Karl" }
];

console.log(returnNames(users1))
console.log(returnNames(users2))