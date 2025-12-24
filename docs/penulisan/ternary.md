
## ternary

operator kondisional yang merupakan bentuk singkat dari if-else dalam satu baris, 
digunakan untuk mengevaluasi ekspresi berdasarkan suatu kondisi dan mengembalikan salah satu dari dua nilai, 
disebut "ternari" karena menggunakan tiga bagian (operan): kondisi, nilai jika benar (true), dan nilai jika salah (false).

---

### jelek: karena susah dibaca

```js
return id ? route(
	'very.long.name.route', id
) : '#'
```

### bagus: karena pendek dan bisa dibaca
```js
// pakai function untuk taruh result
var href = id => route('very.long.name.route', id)
return id ? href(id) : '#'
```

### bagus: tidak pakai ternary
```js
// pakai var untuk taruh value
var href = '#'
if (id) {
	href = route('very.long.name.route', id)
}
return href
```

# nested ternary

tidak masalah, selama pengkondision konsisten dan sudah divariabelkan.
atau menggunakan if-else (dengan early return atau tanpa early return).


```js
var a = true
var b = true
var c = false

return a && b && !c ? 'ya' : 'tidak'
```

```js
var a = true
var b = true
var c = false

if (!c) return 'tidak'
if (!a || !b) return 'tidak'
return 'ya'
```
