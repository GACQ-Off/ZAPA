/*=============================================
EDITAR CLIENTE
=============================================*/
$(".tablas").on("click", ".btnEditarCliente", function () {
  // Leer atributos tipoCedula y numCedula del botón
  var tipoCedula = $(this).attr("tipoCedula");
  var numCedula = $(this).attr("numCedula");

  if (!tipoCedula || !numCedula) {
    console.error("Faltan datos de cédula.");
    return;
  }

  var datos = new FormData();
  datos.append("tipo_ced", tipoCedula);
  datos.append("num_ced", numCedula);

  $.ajax({
    url: "ajax/clientes.ajax.php",
    method: "POST",
    data: datos,
    cache: false,
    contentType: false,
    processData: false,
    dataType: "json",
    success: function (respuesta) {
      if (respuesta.error) {
        console.error("Error del servidor:", respuesta.error);
        return;
      }

      // Mostrar cédula combinada en campo readonly
      $("#editarCedulaDisplay").val(respuesta["tipo_ced"] + "-" + respuesta["num_ced"]);

      // Guardar valores reales en campos ocultos
      $("#originalTipoCedula").val(respuesta["tipo_ced"]);
      $("#originalNumCedula").val(respuesta["num_ced"]);

      // Rellenar los demás campos
      $("#nombre").val(respuesta["nombre"]);
      $("#apellido").val(respuesta["apellido"]);
      $("#email").val(respuesta["email"]);
      $("#direccion").val(respuesta["direccion"]);
      $("#editarPrefijoTelefono").val(respuesta["prefijo_telefono"]);
      $("#editarNumeroTelefono").val(respuesta["numero_telefono"]);
    },
    error: function () {
      console.error("Error en la solicitud AJAX.");
    }
  });
});

$(".tablas").on("click", ".btnEliminarUsuario", function() {
  var tipoCed = $(this).attr("tipoCed");
  var numCed = $(this).attr("numCed");

  // ✅ Confirmamos en consola
  console.log("Cedula cliente a eliminar: " + tipoCed + "-" + numCed);

  swal({
    title: '¿Está seguro de borrar el cliente?',
    text: "¡Si no lo está puede cancelar la acción!",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'Cancelar',
    confirmButtonText: 'Sí, borrar cliente!'
  }).then(function(result) {
    if (result.value) {
      window.location = "index.php?ruta=clientes&tipoCed=" + tipoCed + "&numCed=" + numCed;
    }
  });
});

