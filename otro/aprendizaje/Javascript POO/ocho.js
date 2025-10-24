class Articulo {
    constructor(nombre, precio, stock) {
        this.nombre = nombre;
        this.precio = precio;
        this.stock = stock;
    }
    vender(cantidad){
        if (this.stock >= cantidad) {
            this.stock -= cantidad;
            return true;
        } else {
            return false;
        }
    }
}
class Carrito {
    constructor() {
        this.items = [];
    }
    agregarItem(producto, cantidad) {
            if (producto) {
                const proceder = producto.vender(cantidad);
                if (proceder == true) {
                    this.items.push({producto: producto, cantidad: cantidad})
                    console.log('Producto añadido')
                    } else {
                        console.log('No puede comprar este Articulo')
                    }
            } else {
                console('Producto no disponible')
            }
    }
    calcularTotal() {
        let valorTotal = 0;
        for (let i = 0; i < this.items.length; i++) {
            valorTotal += this.items[i].producto.precio * this.items[i].cantidad;
        }
        return valorTotal;
    }
}
class Tienda {
    constructor(nombre) {
        this.nombre = nombre;
        this.inventario = [];
    }
    agregarProducto(producto) {
        this.inventario.push(producto)
        return true;
    }
    buscarProducto(nombreProducto) {
        for (let i = 0; i < this.inventario.length; i++) {
            if (this.inventario[i].nombre == nombreProducto) {
                return this.inventario[i];
            }
        }
        return null;
    }
    listarInventario() {
        for (let i = 0; i < this.inventario.length; i++) {
            console.log('Nombre: ' + this.inventario[i].nombre + '. Precio: ' + this.inventario[i].precio + '. Stock: ' + this.inventario[i].stock);
        }
    }
}
const papel = new Articulo('Papel Higienico' , 15, 15);
const jabon = new Articulo('Jabón en Polvo' , 10, 30);
const mopa = new Articulo('Mopa' , 30, 15);
const clean = new Tienda('Supermercado La Económica');
clean.agregarProducto(papel);
clean.agregarProducto(jabon);
clean.agregarProducto(mopa);
const carrote = new Carrito();
carrote.agregarItem(papel, 1);
carrote.agregarItem(mopa, 16);
carrote.calcularTotal();