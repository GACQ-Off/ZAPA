// El Ejercicio: Clase Producto
// Imagina que quieres modelar un producto de una tienda. Un producto tiene un nombre y un precio.
// Tu tarea es:
//     Crear una clase llamada Producto.
//     Definir su constructor para que reciba nombre y precio como parámetros.
//     Dentro del constructor, asigna esos parámetros a las propiedades del objeto (this.nombre y this.precio).
//     Luego, crea un método dentro de la clase llamado obtenerInformacion que devuelva una cadena de texto como "Nombre del producto: Laptop, Precio: $1200".
//     Finalmente, crea una nueva instancia de la clase Producto y llama a su método obtenerInformacion para ver el resultado.
var nombre = document.getElementById("nombre");
var precio = document.getElementById("precio");

class Producto {
    constructor(nombre, precio) {
        this.nombre = nombre;
        this.precio = precio;
    }

    obtenerInformacion() {
        return `Nombre del producto: ${this.nombre}, Precio: $${this.precio}`;
    }
}

const miLaptop = new Producto("Laptop", 1200);
const miCelular = new Producto("Celular", 800);

console.log(miLaptop.obtenerInformacion()); 
console.log(miCelular.obtenerInformacion());