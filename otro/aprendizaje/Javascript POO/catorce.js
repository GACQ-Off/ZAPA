class medioDigital {
    constructor(titulo, duracion) {
        this.titulo = titulo;
        this.duracion = duracion;
    }
    reproducir() {
        console.log(`Reproduciendo: ${this.titulo}`)
    }
}
class cancion extends medioDigital {
    constructor(titulo, duracion, artista, album) {
        super(titulo, duracion);
        this.artista = artista;
        this.album = album;
    }
    obtenerInfo() {
        console.log(`Título: ${this.titulo}. Duración: ${this.duracion}. Artista: ${this.artista}. Album: ${this.album}`)
    }
    reproducir() {
        console.log(`Reproduciendo: ${this.titulo} - ${this.artista}`)
    }
}
class video extends medioDigital {
    constructor(titulo, duracion, director, plataforma) {
        super(titulo, duracion);
        this.director = director;
        this.plataforma = plataforma;
    }
    obtenerInfo() {
        console.log(`Título: ${this.titulo}. Duración: ${this.duracion}. Director: ${this.director}. Plataforma: ${this.plataforma}`)
    }
    reproducir() {
        console.log(`Reproduciendo: ${this.titulo}, de ${this.director}`)
    }
}
const bohrap = new Cancion('Bohemian Rhapsody', 355, 'Queen', 'A Night at the Opera');
const godfather = new Video('El Padrino', 175, 'Francis Ford Coppola', 'Paramount+');
godfather.reproducir();
bohrap.reproducir();
godfather.obtenerInfo();
bohrap.obtenerInfo();