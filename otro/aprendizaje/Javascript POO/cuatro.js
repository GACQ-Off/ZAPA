class libro {
    constructor(titulo, autor, isbn) {
        this.titulo = titulo;
        this.autor = autor;
        this.isbn = isbn;
        this.disponible = true;
    };

    prestar() {
        if (this.disponible) {
            this.disponible = false;
        } else {
            console.log("Libro no disponible");
        };
    };

    devolver() {
        if (!this.disponible) {
            this.disponible = true;
        } else {
            console.log('El libro ya fué devuelto');
        };
    };
};

class biblioteca {
    constructor(nombre) {
        this.nombre = nombre;
        this.catalogo = [];
    };

    agregarlibro(libro) {
        this.catalogo.push(libro);
    };

    buscarportitulo(titulo) {
        for (let i = 0; i < this.catalogo.length; i++) {
            if (titulo == this.catalogo[i].titulo) {
                return this.catalogo[i];
            };
        };
        return null;
    };

    mostrarinventario() {
        for (let i = 0; i < this.catalogo.length; i++) {
            console.log(this.catalogo[i].titulo)
        };
    };
};

const cien_gabo = new libro('Cien Años de Soledad', 'Gabriel García Marquez', '111');
const tunel = new libro('El Tunel', 'Ernesto Sabato', '112');
const canaima = new libro('Canaima', 'Romulo Gallegos', '113');
const biblio = new biblioteca('Biblioteca La Redoma');


biblio.agregarlibro(tunel);
biblio.agregarlibro(cien_gabo);
biblio.agregarlibro(canaima);
biblio.mostrarinventario();

tunel.prestar();
tunel.prestar();
cien_gabo.devolver();

biblio.mostrarinventario();

const consulta = 'Necronomicón';

biblio.buscarportitulo(consulta);