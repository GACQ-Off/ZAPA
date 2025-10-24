<?php

if($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>
<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar empleados
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar empleados</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarEmpleado">
          
          Agregar empleado

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           <th>Cédula</th>
           
           <th>Nombre y Apellido</th>
           <th>Teléfono</th>
           <th>Dirección</th>
           <th>Acciones</th>
         </tr> 

        </thead>

        <tbody>

        <?php

        $item = null;
        $valor = null;

        $empleados = ControladorEmpleados::ctrMostrarEmpleados($item, $valor);

       foreach ($empleados as $key => $value){
         
          echo ' <tr>
                  <td>'.$value["cedula"].'</td>';

                  

                  echo '<td>' . ucwords($value["nombre"] . " " . $value["apellido"]) . '</td>';
                  echo '<td>'.$value["telefono"].'</td>';

                  echo '<td>'.ucwords($value["direccion"]).'</td>';

                  echo '
                  <td>

                    <div class="btn-group">
                        
                      <button class="btn btn-warning btnEditarUsuario" idUsuario="'.$value["cedula"].'" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>

                      <button class="btn btn-danger btnEliminarUsuario" idUsuario="'.$value["cedula"].'" fotoUsuario="'.$value["foto"].'" usuario="'.$value["cedula"].'"><i class="fa fa-times"></i></button>

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
MODAL AGREGAR USUARIO
======================================-->

<div id="modalAgregarEmpleado" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar empleado</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="modal-body">

          <div class="box-body">

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
            <a><b>Nombre</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                
                <input type="text" class="form-control input-lg" name="nuevoNombre"  id="txtNuevoNombre" placeholder="Ingresar nombre" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+" 
                oninput="validarNuevoNombre()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 30 caracteres y solo puede contener letras espacios .')">
                <script>
                function validarNuevoNombre() {
                    const nuevoNombreInput = document.getElementById('txtNuevoNombre');
                    const nuevoNombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                    if (!nuevoNombreRegex.test(nuevoNombreInput.value)) {
                        nuevoNombreInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                    } else {
                        nuevoNombreInput.setCustomValidity('');
                    }
                }
                </script>


              </div>

            </div>
            <a><b>Apellido</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoApellido" id="txtNuevoApellido" placeholder="Ingresar apellido" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+" 
                oninput="validarNuevoApellido()" oninvalid="this.setCustomValidity('El apellido debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
                <script>
                function validarNuevoApellido() {
                    const nuevoApellidoInput = document.getElementById('txtNuevoApellido');
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
            <!-- ENTRADA PARA EL USUARIO -->
            <a><b>Número de teléfono</b></a>
<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-phone"></i></span> <select class="form-control input-lg" id="selectPrefijoTelefono" style="width: 100px; margin-right: 5px;" required>
      <option value="">Prefijo</option>
      <option value="0272">0272</option> 
      <option value="0414">0414</option>
      <option value="0424">0424</option>
      
      

      
      
      <option value="0412">0412</option>
      <option value="0416">0416</option>
      <option value="0426">0426</option>
      </select>

    <span class="input-group-addon" >-</span> <input type="text" class="form-control input-lg" id="inputRestoTelefono" placeholder="Ej: 1234567" required minlength="7" maxlength="7" pattern="[0-9]{7}"
      oninput="validarRestoTelefono()"
      oninvalid="this.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.')"
      style="flex-grow: 1; border-left: none;">

    <input type="hidden" name="nuevoTelefono" id="hiddenNuevoTelefono">

    <script>
      function validarRestoTelefono() {
        const inputRestoTelefono = document.getElementById('inputRestoTelefono');
        const restoTelefonoRegex = /^[0-9]{7}$/;

        if (!restoTelefonoRegex.test(inputRestoTelefono.value)) {
          inputRestoTelefono.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.');
        } else {
          inputRestoTelefono.setCustomValidity('');
        }
        combinarTelefono(); // Call combine function whenever the number input changes
      }

      // Function to combine prefix, hyphen, and rest of the number
      function combinarTelefono() {
        const prefijo = document.getElementById('selectPrefijoTelefono').value;
        const resto = document.getElementById('inputRestoTelefono').value;
        const hiddenNuevoTelefono = document.getElementById('hiddenNuevoTelefono');

        // Only combine if both parts are present and valid
        if (prefijo !== "" && resto.match(/^[0-9]{7}$/)) {
            hiddenNuevoTelefono.value = prefijo + '-' + resto;
        } else {
            hiddenNuevoTelefono.value = ''; // Clear if parts are incomplete/invalid
        }
      }

      // Attach event listeners to update the hidden input
      document.getElementById('selectPrefijoTelefono').addEventListener('change', combinarTelefono);
      document.getElementById('inputRestoTelefono').addEventListener('input', combinarTelefono);

      // Ensure the value is set on form submission
      // Assuming your form is within #modalAgregarEmpleado
      document.querySelector('#modalAgregarEmpleado form[role="form"]').addEventListener('submit', function() {
        combinarTelefono();
      });
    </script>
  </div>
</div>

            <!-- ENTRADA PARA EL USUARIO -->
            <a><b>Dirección</b></a>
             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                
                <input type="text" class="form-control input-lg" name="nuevaLocalizacion" id="txtNuevaLocalizacion" placeholder="Ingresar Dirección" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+" 
                oninput="validarNuevaLocalizacion()" oninvalid="this.setCustomValidity('La nueva localización debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales (-º #).')">
                <script>
                  function validarNuevaLocalizacion() {
                      const nuevaLocalizacionInput = document.getElementById('txtNuevaLocalizacion');
                      const nuevaLocalizacionRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/;

                      if (!nuevaLocalizacionRegex.test(nuevaLocalizacionInput.value)) {
                          nuevaLocalizacionInput.setCustomValidity('La nueva localización debe contener solo letras, números, espacios, y estos caracteres especiales (-º #).');
                      } else {
                          nuevaLocalizacionInput.setCustomValidity('');
                      }
                  }
                </script>


              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             
        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar empleado</button>

        </div>

        <?php

          $crearUsuario = new ControladorUsuarios();
          $crearUsuario = new ControladorEmpleados();
          $crearUsuario-> ctrCrearEmpleado();

        ?>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR USUARIO
======================================-->

<div id="modalEditarUsuario" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar empleado</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            <a><b>Cédula</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="number" id="nuevaCedula" class="form-control input-lg" name="editarCedula" placeholder="Ingresar cédula" readonly required>

              </div>

            </div>
            <a><b>Nombre</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarNombre" id="nuevoNombre" placeholder="Ingresar nombre" required required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                
                oninput="validarEditarNombre()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
                <script>
                function validarEditarNombre() {
                    const editarNombreInput = document.getElementById('nuevoNombre');
                    const editarNombreRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                    if (!editarNombreRegex.test(editarNombreInput.value)) {
                        editarNombreInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                    } else {
                        editarNombreInput.setCustomValidity('');
                    }
                }
                </script>


                </div>

            </div>
            <a><b>Apellido</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarApellido" id="nuevoApellido" placeholder="Ingresar apellido" required  required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                oninput="validarEditarApellido()" oninvalid="this.setCustomValidity('El apellido debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
                <script>
                function validarEditarApellido() {
                    const editarApellidoInput = document.getElementById('nuevoApellido');
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
            <!-- ENTRADA PARA EL USUARIO -->
              <a><b>Numero de teléfono</b></a>
             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" name="editarTelefono" placeholder="Ingresar teléfono" id="nuevoTelefono" required  minlength="11" maxlength="11" pattern="[0-9]{11}" 
                oninput="validarEditarTelefono()" oninvalid="this.setCustomValidity('El teléfono solo puede contener números sin caracteres especiales ni espacios ejemplo:(04141074586),(02726521412).')">
                <script>
                function validarEditarTelefono() {
                    const editarTelefonoInput = document.getElementById('nuevoTelefono');
                    const editarTelefonoRegex = /^[0-9]{11}$/;

                    if (!editarTelefonoRegex.test(editarTelefonoInput.value)) {
                        editarTelefonoInput.setCustomValidity('El teléfono solo puede contener números sin caracteres especiales ni espacios ejemplo:(04141074586),(02726521412).');
                    } else {
                        editarTelefonoInput.setCustomValidity('');
                    }
                }
                </script>


              </div>

            </div>

            <a><b>Dirección</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="editarLocalizacion" id="editarLocalizacion" placeholder="Ingresar localización" required  minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+" 
                oninput="validarEditarLocalizacion()" oninvalid="this.setCustomValidity('La nueva dirección debe tener entre 3 y 80 caracteres y solo puede contener letras, números, espacios, y estos caracteres especiales (-º #).')">
                  <script>
                  function validarEditarLocalizacion() {
                      const editarLocalizacionInput = document.getElementById('editarLocalizacion');
                      const editarLocalizacionRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+$/;

                      if (!editarLocalizacionRegex.test(editarLocalizacionInput.value)) {
                          editarLocalizacionInput.setCustomValidity('La nueva dirección debe contener solo letras, números, espacios, y estos caracteres especiales (-º #).');
                      } else {
                          editarLocalizacionInput.setCustomValidity('');
                      }
                  }
                </script>
              

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR FOTO</div>

              <input type="file" class="nuevaFoto" name="editarFoto">

              <p class="help-block">Peso máximo de la foto 2MB</p>

              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Editar empleado</button>

        </div>

        <?php
          $crearUsuario = new ControladorEmpleados();
          $crearUsuario-> ctrEditarEmpleado();

        ?>

      </form>

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

  $borrarUsuario = new ControladorEmpleados();
  $borrarUsuario -> ctrBorrarUsuario();

?> 


