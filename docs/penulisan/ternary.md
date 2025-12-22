
## ternary

operator kondisional yang merupakan bentuk singkat dari if-else dalam satu baris, 
digunakan untuk mengevaluasi ekspresi berdasarkan suatu kondisi dan mengembalikan salah satu dari dua nilai, 
disebut "ternari" karena menggunakan tiga bagian (operan): kondisi, nilai jika benar (true), dan nilai jika salah (false).

---

jelek
```js
return id ? route(
	'very.long.name.route', id
) : '#'
```

bagus
```js
var href = id => route('very.long.name.route', id)
return id ? href(id) : '#'
```

bagus
```js
var href = '#'
if (id) {
	href = route('very.long.name.route', id)
}
return href
```
