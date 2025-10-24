class publicacion {
    constructor( titulo, autor, fechaPublicacion) {
        this.titulo = titulo;
        this.autor = autor;
        this.fechaPublicacion = fechaPublicacion;
    }
    mostrarInformacion() {
        console.log(`Título: ${this.titulo}. Autoría: ${this.autor}. Fecha de Publicación: ${this.fechaPublicacion}`);
    }
}
class libro extends publicacion {
    constructor(titulo, autor, fechaPublicacion, numPaginas) {
        super(titulo, autor, fechaPublicacion);
        this.numPaginas = numPaginas;
    }
    mostrarInformacion() {
        console.log(`Título: ${this.titulo}. Autoría: ${this.autor}. Fecha de Publicación: ${this.fechaPublicacion}. Número de Páginas: ${this.numPaginas}`);
    }
}
class revista extends publicacion {
    constructor(titulo, autor, fechaPublicacion, numero) {
        super(titulo, autor, fechaPublicacion);
        this.numero = numero;
    }
    mostrarInformacion() {
        console.log(`Título: ${this.titulo}. Autoría: ${this.autor}. Fecha de Publicación: ${this.fechaPublicacion}. Número: ${this.numero}`);
    }
}
class biblioteca {
    constructor() {
        this.coleccion = [];
    }
    agregarPublicacion(publicacion) {
        this.coleccion.push(publicacion);
        console.log(`Publicación Añadida.`)
    }
    buscarPorTitulo(titulo) {
        for (let i = 0; i < this.coleccion.length; i++) {
            if (titulo == this.coleccion[i].titulo) {
                return this.coleccion[i];
            }
        }
        return null;
    }
    listarColeccion() {
        for (let i = 0; i < this.coleccion.length; i++) {
            this.coleccion[i].mostrarInformacion();
        }
    }
}
const sabato = new libro(`El Tunel`, `Ernesto Sabato`, `1948`, 300);
const rolling = new revista(`RollingStone`, `Jann Wenner`, `1949`, 32);
const marco = new biblioteca();

marco.agregarPublicacion(rolling);
marco.agregarPublicacion(sabato);
marco.buscarPorTitulo('RollingStone');
rolling.mostrarInformacion();