<?php


if($_SESSION["perfil"] == "Especial"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar clientes
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar clientes</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" onclick="window.location.href = 'extensiones/fpdf/reporteClientesFPDF.php';">
          Reporte de Clientes
        </button>
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarCliente">
          
          Agregar cliente

        </button>


      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Nombre y Apellido</th>
           <th>Cédula</th>
           <th>Teléfono</th>
           <th>Correo Electrónico</th>
           <th>Dirección</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>
        <?php

$item = null;
$valor = null;

        $clientes = ControladorClientes::ctrMostrarClientes($item, $valor);

        foreach ($clientes as $key => $value){
         
          echo ' <tr>
                  <td>'.($key+1).'</td>
                  <td>'.ucwords($value["nombre"]." ".$value["apellido"]).'</td>
                  <td>'.$value["cedula"].'</td>';


                  $buttons = ($_SESSION["perfil"] == "Administrador") ? '<button class="btn btn-danger btnEliminarUsuario" idCliente="'.$value["cedula"].'"><i class="fa fa-times"></i></button>' : '';
                  echo '<td>'.$value["telefono"].'</td>';
                  echo '<td>'.$value["email"].'</td>';            

                  echo '<td>'.ucwords($value["direccion"]).'</td>
                  <td>

                    <div class="btn-group">
                        
                      <button class="btn btn-warning btnEditarCliente" idCliente="'.$value["cedula"].'" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>
                      '.$buttons.'

                    </div>  

                  </td>

                </tr>';
        }
  ?>

        
   
        </tbody>

       </table>

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR CLIENTE
======================================-->

<div id="modalAgregarCliente" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL EMAIL -->
            <a><b>Cédula</b></a>
<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>

    <select class="form-control input-lg" id="selectTipoCedula" style="width: 80px; margin-right: 5px;" required>
      <option value="V">V</option>
      <option value="E">E</option>
    </select>

    <span class="input-group-addon" >-</span>

    <input type="text" class="form-control input-lg" id="inputNumeroCedula" placeholder="Ingresar cédula" required minlength="7" maxlength="8" pattern="[0-9]{7,8}"
      oninput="validarNumeroCedula()"
      oninvalid="this.setCustomValidity('La cédula solo puede contener entre 7 y 8 números sin caracteres especiales ni espacios. Ejemplo: (1234567) o (87654321).')"
      style="flex-grow: 1; border-left: none;">

    <input type="hidden" name="nuevaCedula" id="hiddenNuevaCedula">

    <script>
      function validarNumeroCedula() {
        const inputNumeroCedula = document.getElementById('inputNumeroCedula');
        const numeroCedulaRegex = /^[0-9]{7,8}$/;

        if (!numeroCedulaRegex.test(inputNumeroCedula.value)) {
          inputNumeroCedula.setCustomValidity('La cédula solo puede contener entre 7 y 8 números sin caracteres especiales ni espacios. Ejemplo: (1234567) o (87654321).');
        } else {
          inputNumeroCedula.setCustomValidity('');
        }
        combinarCedula(); // Call combine function whenever the number input changes
      }

      // Function to combine Cédula type and number
      function combinarCedula() {
        const tipoCedula = document.getElementById('selectTipoCedula').value;
        const numeroCedula = document.getElementById('inputNumeroCedula').value;
        const hiddenNuevaCedula = document.getElementById('hiddenNuevaCedula');

        // Only combine if the numeric part is valid
        if (numeroCedula.match(/^[0-9]{7,8}$/)) {
            hiddenNuevaCedula.value = tipoCedula + '-' + numeroCedula;
        } else {
            hiddenNuevaCedula.value = ''; // Clear if the numeric part is invalid
        }
      }

      // Attach event listeners to update the hidden input
      document.getElementById('selectTipoCedula').addEventListener('change', combinarCedula);
      document.getElementById('inputNumeroCedula').addEventListener('input', combinarCedula);

      // Ensure the value is set on form submission, especially if fields are pre-filled or not interacted with
      document.querySelector('form[role="form"]').addEventListener('submit', function() {
        combinarCedula();
      });
    </script>
  </div>
</div>

            <!-- ENTRADA PARA EL NOMBRE -->
            <a><b>Nombre del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoCliente" id= "nuevoCliente"placeholder="Ingresar nombre" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                 
                oninput="validarNuevoCliente()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios .')">
                <script>
                  function validarNuevoCliente() {
                      const nuevoClienteInput = document.getElementById('nuevoCliente');
                      const nuevoClienteRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                      if (!nuevoClienteRegex.test(nuevoClienteInput.value)) {
                          nuevoClienteInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                      } else {
                          nuevoClienteInput.setCustomValidity('');
                      }
                  }
                </script>

              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
               <a><b> Apellido del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoApellido" id="nuevoApellido" placeholder="Ingresar Apellido" required
                minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+" 
                oninput="validarNuevoApellido()" oninvalid="this.setCustomValidity('El apellido debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
                <script>
                function validarNuevoApellido() {
                    const nuevoApellidoInput = document.getElementById('nuevoApellido');
                    const nuevoApellidoRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                    if (!nuevoApellidoRegex.test(nuevoApellidoInput.value)) {
                        nuevoApellidoInput.setCustomValidity('El apellido debe contener solo letras y espacios.');
                    } else {
                        nuevoApellidoInput.setCustomValidity('');
                    }
                }
                </script>

              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            <a><b> Correo del Cliente </b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail" id="nuevoEmail" placeholder="Ingresar Email" required oninput="validarNuevoEmail()" oninvalid="this.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida por ejemplo (noposee@gmail.com),(bocono12@gmail.com).')">
                 <script>
                 function validarNuevoEmail() {
                      const nuevoEmailInput = document.getElementById('nuevoEmail');
                      const nuevoEmailRegex = /^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/; // Expresión regular para correo electrónico

                      if (!nuevoEmailRegex.test(nuevoEmailInput.value)) {
                          nuevoEmailInput.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida.');
                      } else {
                          nuevoEmailInput.setCustomValidity('');
                      }
                  }


                 </script>


              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
           <a><b>Teléfono del Cliente</b></a>
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-phone"></i></span>

        <select class="form-control input-lg" id="nuevoClienteTelefonoPrefijo" name="nuevoPrefijoTelefono" style="width: 100px; margin-right: 5px;" required>
            <option value="">Prefijo</option>
            <option value="0272">0272</option>
            <option value="0412">0412</option>
            <option value="0414">0414</option>
            <option value="0416">0416</option>
            <option value="0424">0424</option>
            <option value="0426">0426</option>
        </select>

        <span class="input-group-addon">-</span>
        <input type="text" class="form-control input-lg" id="nuevoClienteTelefonoNumero" name="nuevoNumeroTelefono" placeholder="Ej: 1234567" required minlength="7" maxlength="7" pattern="[0-9]{7}"
            oninput="validarNuevoClienteTelefonoNumero()"
            oninvalid="this.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.')"
            style="flex-grow: 1; border-left: none;">

        <script>
            // Mantén la función de validación del número, ajustando el ID si lo cambiaste
            function validarNuevoClienteTelefonoNumero() {
                const telefonoNumeroInput = document.getElementById('nuevoClienteTelefonoNumero');
                const telefonoNumeroRegex = /^[0-9]{7}$/;

                if (!telefonoNumeroRegex.test(telefonoNumeroInput.value)) {
                    telefonoNumeroInput.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.');
                } else {
                    telefonoNumeroInput.setCustomValidity('');
                }
            }
            // Elimina aquí las funciones 'combinarTelefono' y sus event listeners, ya no son necesarios
        </script>
    </div>
</div>
            <!-- ENTRADA PARA LA DIRECCIÓN -->
            <a><b>Dirección del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDireccion" id="nuevaDireccion" placeholder="Ingresar dirección" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+" 
                oninput="validarNuevaDireccion()" oninvalid="this.setCustomValidity('La nueva localización debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales (-º #).')">
                <script>
                  function validarNuevaDireccion() {
                      const nuevaDireccionInput = document.getElementById('nuevaDireccion');
                      const nuevaDireccionRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/;

                      if (!nuevaDireccionRegex.test(nuevaDireccionInput.value)) {
                          nuevaDireccionInput.setCustomValidity('La nueva localización debe contener solo letras, números, espacios, y estos caracteres especiales (-º #).');
                      } else {
                          nuevaDireccionInput.setCustomValidity('');
                      }
                  }
                </script>

              </div>

            </div>

             <!-- ENTRADA PARA LA FECHA DE NACIMIENTO -->
            
            <div class="form-group">
              
              
              
                

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cliente</button>

        </div>

      </form>

      <?php

        $crearCliente = new ControladorClientes();
        $crearCliente -> ctrCrearCliente();

      ?>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR CLIENTE
======================================-->

<div id="modalEditarUsuario" class="modal fade" role="dialog">
  
<div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar cliente</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL EMAIL -->
            <a><b>Cédula del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span> 

                <input type="text" class="form-control input-lg" name="editarCedula" readonly id="cedula" placeholder="Ingresar Cedula" required>

              </div>
            </div>

            <!-- ENTRADA PARA EL NOMBRE -->
            <a><b>Nombre del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text"  class="form-control input-lg" name="editarCliente"  id="nombre" placeholder="Ingresar nombre" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                oninput="validarEditarCliente()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios .')">
                 <script>
                    function validarEditarCliente() {
                        const editarClienteInput = document.getElementById('nombre');
                        const editarClienteRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                        if (!editarClienteRegex.test(editarClienteInput.value)) {
                            editarClienteInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                        } else {
                            editarClienteInput.setCustomValidity('');
                        }
                    }
                </script>

                
                


              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
               <a><b> Apellido del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text"id="apellido" class="form-control input-lg" name="editarApellido" placeholder="Ingresar Apellido" required 
                minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+" 
                oninput="validarEditarApellido()" oninvalid="this.setCustomValidity('El apellido debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
                
                <script>
                  function validarEditarApellido() {
                      const editarApellidoInput = document.getElementById('apellido');
                      const editarApellidoRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                      if (!editarApellidoRegex.test(editarApellidoInput.value)) {
                          editarApellidoInput.setCustomValidity('El apellido debe contener solo letras y espacios.');
                      } else {
                          editarApellidoInput.setCustomValidity('');
                      }
                  }
                </script>

              </div>

            </div>

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
            <a><b> Correo del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="email" id="email" class="form-control input-lg" name="editarEmail" placeholder="Ingresar Email" required oninput="validarEditarEmail()" oninvalid="this.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida por ejemplo noposee@gmail.com),(bocono12@gmail.com).')" >
                
                 <script>
                   function validarEditarEmail() {
                        const editarEmailInput = document.getElementById('email');
                        const editarEmailRegex = /^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/; // Expresión regular para correo electrónico

                        if (!editarEmailRegex.test(editarEmailInput.value)) {
                            editarEmailInput.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida.');
                        } else {
                            editarEmailInput.setCustomValidity('');
                        }
                    }


                 </script>


              </div>

            </div>

            <!-- ENTRADA PARA EL TELÉFONO -->
             <a><b>Teléfono del Cliente</b></a>
<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-phone"></i></span>

    <select class="form-control input-lg" id="editarPrefijoTelefono" style="width: 100px; margin-right: 5px;" required>
      <option value="">Prefijo</option>
      <option value="0272">0272</option>
      <option value="0414">0414</option>
      <option value="0424">0424</option>
      <option value="0412">0412</option>
      <option value="0416">0416</option>
      <option value="0426">0426</option>
      </select>

    <span class="input-group-addon" style="border-left: none; padding-left: 5px;">-</span> <input type="text" class="form-control input-lg" id="editarRestoTelefono" placeholder="Ej: 1234567" required minlength="7" maxlength="7" pattern="[0-9]{7}"
      oninput="validarEditarRestoTelefono()"
      oninvalid="this.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.')"
      style="flex-grow: 1; border-left: none;">

    <input type="hidden" name="editarTelefono" id="hiddenEditarTelefono">

    <script>
      function validarEditarRestoTelefono() {
        const editarRestoTelefonoInput = document.getElementById('editarRestoTelefono');
        const restoTelefonoRegex = /^[0-9]{7}$/;

        if (!restoTelefonoRegex.test(editarRestoTelefonoInput.value)) {
          editarRestoTelefonoInput.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.');
        } else {
          editarRestoTelefonoInput.setCustomValidity('');
        }
        combinarEditarTelefono(); // Call combine function whenever the input changes
      }

      // Function to combine prefix, hyphen, and rest of the phone number
      function combinarEditarTelefono() {
        const prefijo = document.getElementById('editarPrefijoTelefono').value;
        const resto = document.getElementById('editarRestoTelefono').value;
        const hiddenEditarTelefono = document.getElementById('hiddenEditarTelefono');

        // Only combine if both parts are present and valid
        if (prefijo !== "" && resto.match(/^[0-9]{7}$/)) {
            hiddenEditarTelefono.value = prefijo + '-' + resto;
        } else {
            hiddenEditarTelefono.value = ''; // Clear if parts are incomplete/invalid
        }
      }

      // Attach event listeners to update the hidden input
      document.getElementById('editarPrefijoTelefono').addEventListener('change', combinarEditarTelefono);
      document.getElementById('editarRestoTelefono').addEventListener('input', combinarEditarTelefono);

      // Ensure the value is set on form submission for the edit modal
      // IMPORTANT: Adjust the selector below to match your specific edit form's ID or name
      // For example, if your edit modal has the ID 'modalEditarCliente' and its form has the class 'cliente-form':
      // document.querySelector('#modalEditarCliente .cliente-form').addEventListener('submit', function() {
      //     combinarEditarTelefono();
      // });
      document.querySelector('form[name="formEditarCliente"]').addEventListener('submit', function() { // Adjust form name or ID here
        combinarEditarTelefono();
      });
    </script>
  </div>
</div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            <a><b>Dirección del Cliente</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

                <input type="text" id="direccion" class="form-control input-lg" name="editarDireccion" placeholder="Ingresar dirección" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+" 
                oninput="validarEditarDireccion()" oninvalid="this.setCustomValidity('La nueva localización debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales (-º #).')">
                
                <script>
                  function validarEditarDireccion() {
                      const editarDireccionInput = document.getElementById('direccion');
                      const editarDireccionRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/;

                      if (!editarDireccionRegex.test(editarDireccionInput.value)) {
                          editarDireccionInput.setCustomValidity('La nueva localización debe contener solo letras, números, espacios, y estos caracteres especiales (-º #).');
                      } else {
                          editarDireccionInput.setCustomValidity('');
                      }
                  }
                </script>

              </div>

            </div>

            </div>
  
          </div>

          <div class="modal-footer">
  
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
  
            <button type="submit" class="btn btn-primary">Guardar cliente</button>
  
          </div>
        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->


      </form>

    </div>

  </div>

</div>

</div>
<script>
    $("#logout").click(function(e) {
        e.preventDefault();

        swal({
            type: "warning", // Changed to warning for a more cautious tone
            title: "¿Estás seguro de salir?",
            text: "Esta acción cerrará tu sesión.",
            showCancelButton: true,
           confirmButtonColor: "#3498db", // Color azul
            confirmButtonText: "Sí, salir",
            cancelButtonText: "Cancelar",
            closeOnConfirm: false
        }).then(function(result) {
            if (result.value) {
                window.location = "salir";
            }
        });
    });
</script>

<?php

  $eliminarCliente = new ControladorClientes();
  $eliminarCliente -> ctrEliminarCliente();

  $editarCliente = new ControladorClientes();
  $editarCliente -> ctrEditarCliente();

?>


