class plato {
    constructor(nombre, precio, ingredientes) {
        this.nombre = nombre;
        this.precio = precio;
        this.ingredientes = ingredientes;
    }
    mostrarDetalles(){
        console.log(`El platillo ${this.nombre} cuesta ${this.precio} y se elabora con los siguientes ingredientes: ${this.ingredientes.join(', ')}`);
    }
}

class restaurante {
    constructor(nombre) {
        this.nombre = nombre;
        this.menu = [];
    }
    agregarPlato(plato) {
        this.menu.push(plato);
        console.log('Plato Añadido');
    }
    buscarPlato(consulta) {
        for (let i = 0; i < this.menu.length; i++) {
            if (consulta == this.menu[i].nombre) {
                return this.menu[i];
            }
        }
        return null;
    }
    realizarPedido(nombresplatos) {
        let costoTotal = 0; 
        for (let i = 0; i < nombresplatos.length; i++) {
                let platoEncontrado = this.buscarPlato(nombresplatos[i]);
                if (platoEncontrado === null) {
                    console.log(`El plato "${nombresplatos[i]}" no se encuentra en el menú.`);
                    return;
                } else {
                    costoTotal += platoEncontrado.precio;
                }
            }
        console.log(`El pedido cuesta $${costoTotal}.`);
    }
}

const po = new restaurante('Fideos Panda');
const fideos = new plato('Fideos Picantes', 300, ['Fideos', 'Agua', 'Sal','Pimientos']);
const donplings = new plato('Donplings', 50, ['Harina', 'Agua', 'Sal']);
const salsa_soya = new plato('Salsa de Soya', 30, ['Extracto de Soya', 'Agua', 'Sal']);

po.agregarPlato(fideos);
po.agregarPlato(donplings);
po.agregarPlato(salsa_soya);
fideos.mostrarDetalles();
consulta = 'Fideos Picantes';
Busqueda = po.buscarPlato(consulta);
if (Busqueda) {console.log(`El Platillo ${Busqueda.nombre} está disponible.`);
} else {console.log('El plato no está disponible.') }
pedido = ['Fideos Picantes', 'Donplings', 'Salsa de Soya'];
po.realizarPedido(pedido);