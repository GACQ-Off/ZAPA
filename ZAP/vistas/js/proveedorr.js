
/*=============================================
EDITAR CLIENTE
=============================================*/
$(".tablas").on("click", ".btnEditarProveedor", function(){
	var idCliente = $(this).attr("idProveedor");

	var datos = new FormData();
    datos.append("idProveedor", idCliente);

    $.ajax({

      url:"ajax/proveedores.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",
      success:function(respuesta){
      
      	 $("#rif").val(respuesta["rif"]);
	       $("#empresa").val(respuesta["nombre"]);
	       $("#nombreRepresentante").val(respuesta["nombre_representante"]);
	       $("#apellidoRepresentante").val(respuesta["apellido_representante"]);
	       $("#cedulaRepresentante").val(respuesta["cedula_representante"]);
	       $("#correo").val(respuesta["correo"]);
         $("#telefono").val(respuesta["telefono"]);
           $("#direccion").val(respuesta["direccion"]);
	  }

  	})

})

 

})