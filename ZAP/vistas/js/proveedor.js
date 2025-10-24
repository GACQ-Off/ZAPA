/*=============================================
EDITAR PROVEEDOR
=============================================*/
$(".tablas").on("click", ".btnEditarProveedor", function(){
  var idProveedor = $(this).attr("idProveedor"); // Asegúrate que este atributo contenga el RIF combinado (ej. "J-123456789")

  var datos = new FormData();
    datos.append("idProveedor", idProveedor); // El nombre aquí debe coincidir con cómo tu AJAX espera el RIF para buscar

    $.ajax({
        url:"ajax/proveedores.ajax.php",
        method: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(respuesta){

            // *************************************************************************
            // **** INICIO DE LOS CAMBIOS PARA EL RIF ****
            // *************************************************************************
            // 1. Rellenar el campo visible de solo lectura con el RIF combinado
            //    Asegúrate que 'respuesta' tenga 'tipo_rif' y 'num_rif' separados.
            $("#editarRifDisplay").val(respuesta["tipo_rif"] + '-' + respuesta["num_rif"]);

            // 2. Rellenar los campos ocultos con los valores ORIGINALES del RIF.
            //    Estos se enviarán al controlador para la cláusula WHERE en la edición.
            $("#originalTipoRif").val(respuesta["tipo_rif"]);
            $("#originalNumRif").val(respuesta["num_rif"]);
            // *************************************************************************
            // **** FIN DE LOS CAMBIOS PARA EL RIF ****
            // *************************************************************************


            // El resto de tu código JS se mantiene EXACTAMENTE igual
            $("#empresa").val(respuesta["nombre"]);
            $("#nombreRepresentante").val(respuesta["nombre_representante"]);
            $("#apellidoRepresentante").val(respuesta["apellido_representante"]);
            // --- INICIO: CORRECCIÓN PARA CÉDULA ---
            // Usamos los IDs correctos del HTML del modal de edición
            $("#editarCedulaRepresentanteTipo").val(respuesta["tipo_ced"]);
            $("#editarCedulaRepresentanteNumero").val(respuesta["num_ced"]);
            $("#correo").val(respuesta["correo"]);
            // *************************************************************************
            // **** ESTA ES LA PARTE CRUCIAL: RELLENAR LOS CAMPOS DE TELÉFONO SEPARADOS ****
            // **** DEBES USAR LOS IDs de tus SELECT e INPUT para el prefijo y número ****
            // *************************************************************************
            $("#editarTelefonoProveedorPrefijo").val(respuesta["prefijo_telefono"]); // ID del SELECT del prefijo
            $("#editarTelefonoProveedorNumero").val(respuesta["numero_telefono"]);    // ID del INPUT del número
            // *************************************************************************
            
            $("#direccion").val(respuesta["direccion"]);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error al obtener datos del proveedor:", textStatus, errorThrown);
            // Puedes mostrar un mensaje de error al usuario aquí
        }
    })
})

/*=============================================
ELIMINAR PROVEEDOR
=============================================*/
$(".tablas").on("click", ".btnEliminarProveedor", function(){

  var idProveedor = $(this).attr("idProveedor"); // Asegúrate que este atributo contenga el RIF combinado (ej. "J-123456789")

    console.log(idProveedor)
  
  swal({
        title: '¿Está seguro de borrar el proveedor?',
        text: "¡Si no lo está puede cancelar la acción!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        cancelButtonText: 'Cancelar',
        confirmButtonText: 'Si, borrar proveedor!'
    }).then(function(result){
        if (result.value) {
            window.location = "index.php?ruta=proveedor&idCliente="+idProveedor; // Asegúrate que 'idCliente' aquí realmente corresponda al RIF combinado
        }
    })
})