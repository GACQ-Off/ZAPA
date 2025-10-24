class Videojuego {
    constructor(titulo, plataforma, precio, unidades) {
        this.titulo = titulo;
        this.plataforma = plataforma;
        this.precio = precio;
        this.unidades = unidades;
    }
    vender(cantidad) {
        if (this.unidades >= cantidad) {
            this.unidades -= cantidad;
            console.log(`Se vendieron ${cantidad} unidades de ${this.titulo}.`);
            return true;
        } else {
            console.log(`No hay suficientes unidades de ${this.titulo}. Stock actual: ${this.unidades}`);
            return false;
        }
    }
}

class Tienda {
    constructor(nombre) {
        this.nombre = nombre;
        this.inventario = [];
    }
    agregarVideojuego(videojuego) {
        this.inventario.push(videojuego);
        console.log(`"${videojuego.titulo}" ha sido agregado al inventario.`);
    }
    buscarPorTitulo(tituloConsulta) {
        for (let i = 0; i < this.inventario.length; i++) {
            if (this.inventario[i].titulo === tituloConsulta) {
                return this.inventario[i]; 
            }
        }
        return null; 
    }

    mostrarInventario() {
        console.log(`\n--- Inventario de la tienda "${this.nombre}" ---`);
        for (let i = 0; i < this.inventario.length; i++) {
            const juego = this.inventario[i];
            console.log(`
                Título: ${juego.titulo}
                Plataforma: ${juego.plataforma}
                Precio: $${juego.precio}
                Unidades: ${juego.unidades}
            `);
        }
        console.log('------------------------------------------');
    }
}

const tiendaDeJuegos = new Tienda('Momo-Juegos');

const theWitcher = new Videojuego('Tekken 6', 'PlayStation Portable', 29.99, 10);
const tomb_raider = new Videojuego('Tomb Raider', 'PS1', 59.99, 5);
const zelda = new Videojuego('The Legend of Zelda', 'Nintendo DS', 59.99, 15);

tiendaDeJuegos.agregarVideojuego(tekken);
tiendaDeJuegos.agregarVideojuego(tomb_raider);
tiendaDeJuegos.agregarVideojuego(zelda);

tiendaDeJuegos.mostrarInventario();

const juegoBuscado = tiendaDeJuegos.buscarPorTitulo('Tekken 6');
if (juegoBuscado) {
    console.log('\nJuego encontrado:', juegoBuscado.titulo);
    juegoBuscado.vender(2);
} else {
    console.log('\nEl juego no fue encontrado.');
}

tiendaDeJuegos.mostrarInventario();

juegoBuscado.vender(5);