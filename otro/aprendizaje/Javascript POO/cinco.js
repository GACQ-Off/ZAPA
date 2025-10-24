class vehiculo {
    constructor(marca,modelo,anio) {
        this.marca = marca;
        this.modelo = modelo;
        this.anio = anio;
        this.estado = 'nuevo';
    }

    mostrarDetalles() {
        console.log(this.marca);
        console.log(this.modelo);
        console.log(this.anio);
        console.log(this.estado);
    }
}

class coche extends vehiculo {
    constructor(marca,modelo,anio,nro_puertas) {
        super(marca,modelo,anio);
        this.nro_puertas = nro_puertas;
    }
}

class camion extends vehiculo {
    constructor(marca,modelo,anio,capacidad_carga) {
        super(marca,modelo,anio);
        this.capacidad_carga = capacidad_carga;
    }
}

const ford = new camion('Ford', 'F350', '2008', 100);
const toyo = new coche('Toyota', 'Camry', '2008', 4);

toyo.mostrarDetalles();