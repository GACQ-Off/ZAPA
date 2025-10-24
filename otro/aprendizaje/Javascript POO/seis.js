class Animal {
    constructor(nombre, especie, edad) {
        this.nombre = nombre;
        this.especie = especie;
        this.edad = edad;
        this.salud = 'Buena';
    }
    mostrarInformacion() {
        console.log(this.nombre + ' es un ' + this.especie + ' de ' + this.edad + ' años de edad. Su salud es ' + this.salud);
    }
}
class Leon extends Animal {
    constructor(nombre, especie, edad) {
        super(nombre, especie, edad)
        this.fuerza = 'Fuerte';
    }
    Rugir() {
        console.log('¡Rawr!, hace el ' + this.nombre + ', indicando que es muy ' + this.fuerza);
    }
}
class Zoologico {
    constructor(nombre) {
        this.nombre = nombre;
        this.animales = [];
    }
    agregarAnimal(animal) {
        this.animales.push(animal);
    }
    encontrarAnimalPorNombre(nombre) {
        for (let i = 0; i < this.animales.length; i++) {
            if (nombre == this.animales[i].nombre) {
                return this.animales[i];
            }
        }
        return null;
    }
    listarAnimales() {
        for (let i = 0; i < this.animales.length; i++) {
        console.log('Nombre:' + this.animales[i].nombre + '.' + this.animales[i].especie + '. Edad: ' + this.animales[i].edad + ' años de edad. Salud: ' + this.animales[i].salud);
        }
    }
}

const giratina = new Animal('Giratina', 'Tipo Legendario', 'null');
const incireniboar = new Leon('Incireniboar', 'Tipo Fuego', '8');
const poke = new Zoologico('Sanctuario Pokémon')
poke.agregarAnimal(giratina);
poke.agregarAnimal(incireniboar);
poke.listarAnimales();
incireniboar.Rugir();

const consulta = 'Pikachu';
const Encontrado = poke.encontrarAnimalPorNombre(consulta);

if (Encontrado) {
    console.log('Pokémon encontrado! Es un ' + Encontrado.especie + ' de ' + Encontrado.edad +  ' años.');
} else {
    console.log('El ' + consulta +' no se encuentra en el Sanctuario.');
};