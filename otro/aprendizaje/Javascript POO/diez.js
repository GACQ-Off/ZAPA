class nodo {
    constructor(valor) {
        this.valor = valor;
        this.siguiente = null;
    }
}
class listaenlazada {
    constructor(){
        this.cabeza = null;
        this.cola = null;
        this.longitud = 0;
    }
    agregarAlFinal(valor) {
        const nuevo_nodo = new nodo(valor);
        if (this.cabeza === null) {
            this.cabeza = nuevo_nodo;
            this.cola = nuevo_nodo;
        } else {
            this.cola.siguiente = nuevo_nodo;
            this.cola = nuevo_nodo;
        }
        this.longitud++;
    }
    eliminarDeLaCabeza() {
        if (!this.cabeza) {
            return null;
        } 
            const old_cabeza = this.cabeza;
            this.cabeza = old_cabeza.siguiente;
            this.longitud--;
            if (this.longitud == 0) {
            this.cola = null;
            }
                return old_cabeza.valor;
    }
    imprimirLista() {
        let pointer = this.cabeza;
        while (pointer != null) {
            console.log(`${pointer.valor}`);
            pointer = pointer.siguiente;
        }
    }
}
const Lista = new listaenlazada();
Lista.agregarAlFinal('A');
Lista.agregarAlFinal('B');
Lista.agregarAlFinal('C');
Lista.agregarAlFinal('D');
Lista.agregarAlFinal('E');
Lista.imprimirLista();
Lista.eliminarDeLaCabeza();
Lista.eliminarDeLaCabeza();
Lista.imprimirLista();