// Este código se ejecutará una vez que todo el contenido HTML de la página esté cargado.
document.addEventListener('DOMContentLoaded', function() {

    // --- Parte 1: Obtener referencias a los elementos HTML ---
    // Seleccionamos los elementos del DOM por su ID. Estos IDs deben coincidir
    // con los que usamos en el HTML del modal.
    const btnAgregarPago = document.getElementById('btn-agregar-otro-pago-modal');
    const contenedorNuevosPagos = document.getElementById('contenedor-nuevos-pagos');
    const formPagosVenta = document.getElementById('form-pagos-venta');

    // Un contador para dar IDs únicos a cada nuevo campo de pago.
    // Empezamos en 2 porque el primer campo de pago en el HTML ya tiene el ID 'monto-pago-1'.
    let contadorPagos = 2;


    // --- Parte 2: Lógica para el botón "Agregar otro pago" ---
    // Verificamos que los elementos existan antes de añadir el 'event listener'.
    if (btnAgregarPago && contenedorNuevosPagos) {
        // Cuando se hace clic en el botón 'btnAgregarPagoModal', se ejecuta la función `agregarNuevoCampoDePago`.
        btnAgregarPago.addEventListener('click', function() {
            agregarNuevoCampoDePago();
        });
    }

    // --- Parte 3: Función para crear y añadir un nuevo campo de pago ---
    // Esta función genera el HTML para un nuevo set de campos (monto, tipo, botón eliminar)
    // y lo inserta en el contenedor de nuevos pagos.
    function agregarNuevoCampoDePago() {
        // Plantilla de cadena con el HTML para el nuevo campo de pago.
        // `contadorPagos` se usa para asegurar que cada nuevo campo tenga un ID único.
        const nuevoCampoHTML = `
            <div class="form-group row mb-3 align-items-center nuevo-pago-item" id="pago-item-${contadorPagos}">
                <label for="monto-pago-${contadorPagos}" class="col-sm-3 col-form-label">Monto (${contadorPagos}):</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="montos_pagos[]" id="monto-pago-${contadorPagos}" placeholder="Ej: 50.00" step="0.01" required>
                </div>
                <div class="col-sm-3">
                    <select class="form-select" name="tipos_pagos[]" id="tipo-pago-${contadorPagos}">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                        <option value="credito">Crédito</option>
                    </select>
                </div>
                <div class="col-sm-12 text-end mt-2">
                    <button type="button" class="btn btn-danger btn-sm eliminar-pago" data-id="pago-item-${contadorPagos}">
                        <i class="fas fa-times"></i> Eliminar
                    </button>
                </div>
            </div>
        `;

        // Inserta el nuevo HTML al final del contenedor de nuevos pagos.
        contenedorNuevosPagos.insertAdjacentHTML('beforeend', nuevoCampoHTML);

        // Incrementa el contador para que el siguiente campo generado tenga un ID distinto.
        contadorPagos++;
    }


    // --- Parte 4: Lógica para los botones "Eliminar" (Delegación de Eventos) ---
    // Como los botones de "Eliminar" se añaden dinámicamente (después de que la página carga),
    // no podemos adjuntarles un 'event listener' directamente al inicio.
    // En su lugar, usamos "delegación de eventos": escuchamos los clics en un elemento padre
    // que siempre existe (como `document.body`), y luego verificamos si el clic ocurrió
    // en uno de nuestros botones de "eliminar" (usando la clase `eliminar-pago`).
    document.body.addEventListener('click', function(event) {
        // `event.target.closest('.eliminar-pago')` busca el botón con la clase
        // 'eliminar-pago' más cercano al elemento que se hizo clic.
        const btnEliminar = event.target.closest('.eliminar-pago');

        if (btnEliminar) {
            // Obtenemos el ID del elemento de pago completo a eliminar desde el atributo `data-id` del botón.
            const idItemAEliminar = btnEliminar.dataset.id;
            // Obtenemos la referencia al elemento div completo.
            const itemAEliminar = document.getElementById(idItemAEliminar);

            // Si el elemento existe, lo removemos del DOM.
            if (itemAEliminar) {
                itemAEliminar.remove();
            }
        }
    });


    // --- Parte 5: Manejar el envío del formulario de pagos ---
    // Esta parte intercepta el envío del formulario para que podamos procesar los datos
    // con JavaScript antes de enviarlos a un servidor.
    if (formPagosVenta) {
        formPagosVenta.addEventListener('submit', function(event) {
            event.preventDefault(); // Evita el envío tradicional del formulario (recarga de página).

            // Crea un objeto `FormData` que recolecta todos los datos del formulario.
            const formData = new FormData(formPagosVenta);

            // `getAll()` es útil para obtener todos los valores de campos con el mismo `name` (ej. `montos_pagos[]`),
            // devolviéndolos como un array.
            const montos = formData.getAll('montos_pagos[]');
            const tipos = formData.getAll('tipos_pagos[]');

            // Mostramos los datos recolectados en la consola del navegador para depuración.
            console.log("Montos de pagos:", montos);
            console.log("Tipos de pagos:", tipos);

            // --- ESTE ES EL PUNTO CLAVE PARA TU BACKEND ---
            // Aquí es donde normalmente harías una solicitud HTTP (por ejemplo, con `fetch()`)
            // a una URL en tu servidor para guardar estos datos.
            // Ejemplo de cómo harías una llamada `fetch` (comentado):
            /*
            fetch('/tu-api-para-guardar-pagos', {
                method: 'POST', // O 'PUT', dependiendo de tu API
                body: formData // Envía los datos del formulario directamente
            })
            .then(response => response.json()) // Asume que tu servidor responde con JSON
            .then(data => {
                console.log('Respuesta del servidor al guardar pagos:', data);
                // Si la operación fue exitosa, podrías cerrar el modal:
                const modalElement = document.getElementById('modalPagoMultiple');
                const bsModal = bootstrap.Modal.getInstance(modalElement); // Obtiene la instancia de Bootstrap del modal
                if (bsModal) {
                    bsModal.hide(); // Oculta el modal
                    // Opcional: mostrar un mensaje de éxito al usuario
                }
            })
            .catch(error => {
                console.error('Error al enviar los pagos al servidor:', error);
                // Opcional: mostrar un mensaje de error al usuario
            });
            */
        });
    }

    // --- Parte 6: Lógica Opcional: Actualizar el "Total de la Venta" en el Modal ---
    // Esta sección se activa cuando el modal se va a mostrar, y actualiza el texto
    // del total de la venta dentro del modal. Deberás adaptar `obtenerTotalDeLaVentaActual()`
    // a la forma en que tu aplicación maneja el total de la venta actual.
    const totalVentaMostrar = document.getElementById('total-venta-mostrar');
    const modalPagoMultiple = document.getElementById('modalPagoMultiple');

    if (modalPagoMultiple && totalVentaMostrar) {
        // Añade un listener al evento 'show.bs.modal' de Bootstrap.
        // Este evento se dispara justo antes de que el modal se haga visible.
        modalPagoMultiple.addEventListener('show.bs.modal', function () {
            // Ejemplo de cómo obtener el total. ¡ADAPTA ESTA LÍNEA A TU LÓGICA REAL!
            // Podrías obtenerlo de un campo oculto, de una variable JS global,
            // o de una función que calcule el total de la venta.
            let totalDeLaVentaActual = 250.75; // <-- ¡REEMPLAZA ESTO CON EL VALOR REAL DE TU VENTA!

            // Actualiza el texto del span con el total formateado.
            totalVentaMostrar.textContent = `$${totalDeLaVentaActual.toFixed(2)}`;
        });
    }

}); // Fin de DOMContentLoaded