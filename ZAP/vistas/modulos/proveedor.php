<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

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
      
      Administrar proveedores
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Proveedor</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
        <button class="btn-lg btn-primary" onclick="window.location.href = 'extensiones/fpdf/reporteProveedoresFPDF.php';">
          <i class="fa fa-file-pdf-o"></i> Reporte de Proveedores
        </button>
        <button class="btn-lg btn-primary" data-toggle="modal" data-target="#modalAgregarCliente">
          
          Agregar proveedor

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Empresa</th>
           <th>RIF</th>
           <th>Representante</th>
           <th>Cedula</th>
           <th>Teléfono</th>
           <th>Correo</th>
           <th>Dirección</th>

           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>
        <?php

$item = null;
$valor = null;

        $clientes = ControladorProveedor::ctrMostrarProveedor($item, $valor);

        foreach ($clientes as $key => $value){
         
         echo ' <tr>
          <td>'.($key+1).'</td>
          <td>'.ucwords($value["nombre"]).'</td>
          <td>'.$value["tipo_rif"].'-'.$value["num_rif"].'</td>  <td>'.ucwords($value["nombre_representante"]." ".$value["apellido_representante"]).'</td>
          <td>'.$value["tipo_ced"].'-'.$value["num_ced"].'</td>
          <td>'.$value["prefijo_telefono"].'-'.$value["numero_telefono"].'</td>
          
          <td>'.$value["correo"].'</td>

          <td>'.ucwords($value["direccion"]).'</td>
          <td>
            <div class="btn-group">
              <button class="btn btn-warning btnEditarProveedor" idProveedor="'.$value["tipo_rif"].'-'.$value["num_rif"].'" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>
              <button class="btn btn-danger btnEliminarProveedor" idProveedor="'.$value["tipo_rif"].'-'.$value["num_rif"].'"><i class="fa fa-times"></i></button>
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

          <h4 class="modal-title">Agregar proveedor</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL EMAIL -->
          <a><b>RIF del Proveedor</b></a>
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
        <select class="form-control input-lg" id="nuevoRifLetra" name="tipo_rif" style="width: 100px; margin-right: 5px;" required>
            <option value="">Tipo</option>
            <option value="J">J</option>
            <option value="G">G</option>
            <option value="V">V</option>
            <option value="P">P</option>
            <option value="C">C</option>
        </select>

        <span class="input-group-addon">-</span>
        <input type="text" class="form-control input-lg" id="nuevoRifNumero" name="num_rif" placeholder="Ej: 123456789" required minlength="9" maxlength="9" pattern="[0-9]{9}"
            oninput="validarNuevoRifNumero()"
            oninvalid="this.setCustomValidity('El RIF solo puede contener 9 números.')"
            style="flex-grow: 1; border-left: none;">

        <script>
            function validarNuevoRifNumero() {
                const nuevoRifNumeroInput = document.getElementById('nuevoRifNumero');
                const rifNumeroRegex = /^[0-9]{9}$/;

                if (!rifNumeroRegex.test(nuevoRifNumeroInput.value)) {
                    nuevoRifNumeroInput.setCustomValidity('Los 9 dígitos del RIF solo pueden contener números.');
                } else {
                    nuevoRifNumeroInput.setCustomValidity('');
                }
                // Ya no necesitamos llamar a combinarNuevoRif() aquí si no hay hidden input
                // pero si quieres, puedes mantener una función para validación o feedback visual si es necesario.
            }

            // CAMBIO: Eliminar o simplificar la función combinarNuevoRif
            // Si el objetivo era solo para el hidden input, ya no es necesaria.
            // Si la usabas para alguna otra lógica, como validación combinada,
            // entonces puedes adaptarla para ese propósito específico.
            // En este caso, ya no la necesitamos para el envío a la DB.

            // CAMBIO: Eliminar o adaptar los event listeners si ya no son necesarios para el hidden input
            // document.getElementById('nuevoRifLetra').addEventListener('change', combinarNuevoRif);
            // document.getElementById('nuevoRifNumero').addEventListener('input', combinarNuevoRif);

            // CAMBIO: Eliminar o adaptar el listener del submit si la combinación era solo para el hidden input
            // document.querySelector('form[name="formAgregarProveedor"]').addEventListener('submit', function() {
            //   combinarNuevoRif(); // Si combinarNuevoRif ya no existe o es irrelevante, esto se puede quitar
            // });
        </script>
    </div>
</div>
            <!-- ENTRADA PARA EL NOMBRE -->
            <a><b>Nombre de la Empresa</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaEmpresa"  id= "nuevaEmpresa" placeholder="Ingresar nombre de la Empresa" required minlength="4" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+"
              
                 
                oninput="validarNuevaEmpresa()" oninvalid="this.setCustomValidity('La Descripcion debe tener entre 4 y 30 caracteres y solo puede contener letras y espacios .')">
                <script>
                  function validarNuevaEmpresa() {
                      const nuevaEmpresaInput = document.getElementById('nuevaEmpresa');
                      const nuevaEmpresaRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+$/;

                      if (!nuevaEmpresaRegex.test(nuevaEmpresaInput.value)) {
                          nuevaEmpresaInput.setCustomValidity('El nombre debe contener solo letras guiones  y espacios.');
                      } else {
                          nuevaEmpresaInput.setCustomValidity('');
                      }
                  }
                </script>

              </div>

            </div>
           <a><b>Cédula del Representante</b></a>
<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span> 
    <select class="form-control input-lg" 
            id="nuevaCedulaRepresentanteTipo" 
            name="tipo_ced" 
            style="width: 80px; margin-right: 5px;" 
            required>
      <option value="V">V</option>
      <option value="E">E</option>
    </select>

    <span class="input-group-addon" >-</span> 
    <input type="text" 
           class="form-control input-lg" 
           id="nuevaCedulaRepresentanteNumero" 
           name="num_ced"
           placeholder="Ingresar cédula" 
           required minlength="7" maxlength="8" 
           pattern="[0-9]{7,8}"
           oninput="validarNuevaCedulaRepresentanteNumero()"
           oninvalid="this.setCustomValidity('La cédula solo puede contener entre 7 y 8 números.')"
           style="flex-grow: 1; border-left: none;">

    <script>
      // Función para validar el número de cédula
      function validarNuevaCedulaRepresentanteNumero() {
        const cedulaNumeroInput = document.getElementById('nuevaCedulaRepresentanteNumero');
        const cedulaNumeroRegex = /^[0-9]{7,8}$/;

        if (!cedulaNumeroRegex.test(cedulaNumeroInput.value)) {
          cedulaNumeroInput.setCustomValidity('La cédula solo puede contener entre 7 y 8 números.');
        } else {
          cedulaNumeroInput.setCustomValidity('');
        }
      }
    </script>
  </div>
</div>
            <!-- ENTRADA PARA EL NOMBRE -->
            <a><b>Nombre del Representante</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoNombre" id="nuevoNombre" placeholder="Ingresar nombre" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                 
                oninput="validarNuevoNombre()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios .')">
                <script>
                  function validarNuevoNombre() {
                      const nuevoNombreInput = document.getElementById('nuevoNombre');
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

            <!-- ENTRADA PARA EL DOCUMENTO ID -->
               <a><b> Apellido del Representante</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoApellido" id="nuevoApellido" placeholder="Ingresar Apellido" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+" 
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
            <a><b> Correo del Proveedor</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="email" class="form-control input-lg" name="nuevoEmail"  id="nuevoEmail" placeholder="Ingresar Email" required 
                oninput="validarNuevoEmail()" oninvalid="this.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida por ejemplo (bocono12@gmail.com).')">
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

            <a><b>Telefono del Proveedor</b></a>
<div class="form-group">
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-phone"></i></span>

        <select class="form-control input-lg" id="nuevoTelefonoProveedorPrefijo" name="prefijo_telefono" style="width: 100px; margin-right: 5px;" required>
            <option value="">Prefijo</option>
            <option value="0272">0272</option>
            <option value="0412">0412</option>
            <option value="0414">0414</option>
            <option value="0416">0416</option>
            <option value="0424">0424</option>
            <option value="0426">0426</option>
        </select>

        <span class="input-group-addon" >-</span>
        <input type="text" class="form-control input-lg" id="nuevoTelefonoProveedorNumero" name="numero_telefono" placeholder="Ej: 1234567" required minlength="7" maxlength="7" pattern="[0-9]{7}"
            oninput="validarNuevoTelefonoProveedorNumero()"
            oninvalid="this.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.')"
            style="flex-grow: 1; border-left: none;">

        <script>
            // La función de validación del número sigue siendo útil.
            function validarNuevoTelefonoProveedorNumero() {
                const telefonoNumeroInput = document.getElementById('nuevoTelefonoProveedorNumero');
                const telefonoNumeroRegex = /^[0-9]{7}$/;

                if (!telefonoNumeroRegex.test(telefonoNumeroInput.value)) {
                    telefonoNumeroInput.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.');
                } else {
                    telefonoNumeroInput.setCustomValidity('');
                }
            }

            // Las siguientes funciones JavaScript y sus event listeners
            // que combinaban el prefijo y el número en el campo hidden
            // YA NO SON NECESARIAS PARA EL ENVÍO AL SERVIDOR CON TUS NUEVAS COLUMNAS.
            // Puedes eliminarlas de tu código HTML si solo las usabas para el envío.

            // function combinarNuevoTelefonoProveedor() { ... }
            // document.getElementById('nuevoTelefonoProveedorPrefijo').addEventListener('change', combinarNuevoTelefonoProveedor);
            // document.getElementById('nuevoTelefonoProveedorNumero').addEventListener('input', combinarNuevoTelefonoProveedor);
            // document.querySelector('form[role="form"]').addEventListener('submit', function() { ... });
        </script>
    </div>
</div>

            <!-- ENTRADA PARA LA DIRECCIÓN -->
            <a><b>Direccion del Proveedor</b></a>
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

            </div>
  
          </div>

          <!--=====================================
          PIE DEL MODAL
          ======================================-->
  
          <div class="modal-footer">
  
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
  
            <button type="submit" class="btn btn-primary">Guardar proveedor</button>
  
          </div>
        </div>


      </form>

      <?php

        $crearCliente = new ControladorProveedor();
        $crearCliente -> ctrCrearProveedor();

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

  <h4 class="modal-title">Editar proveedor</h4>

</div>

<!--=====================================
CUERPO DEL MODAL
======================================-->

<div class="modal-body">

  <div class="box-body">

    <!-- ENTRADA PARA el Rif-->
    

<input type="hidden" id="originalTipoRif" name="original_tipo_rif">
<input type="hidden" id="originalNumRif" name="original_num_rif">
<input type="hidden" id="rifOriginalParaEdicion" name="rifOriginalParaEdicion">
<!-- ENTRADA PARA EL RIF -->
<a><b>RIF del Proveedor</b></a>
<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
    <input type="text" id="editarRifDisplay" class="form-control input-lg" name="editarRifDisplay" readonly>
  </div>
</div>
<!-- Opcional si lo usas en backend -->

    <!-- ENTRADA PARA EL NOMBRE -->
    <a><b>Nombre de la Empresa</b></a>
    <div class="form-group">
      
      <div class="input-group">
      
        <span class="input-group-addon"><i class="fa fa-user"></i></span> 

        <input type="text" id="empresa" class="form-control input-lg" name="editarEmpresa" placeholder="Ingresar nombre de la Empresa" required minlength="4" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+"
        oninput="validarEditarEmpresa()" oninvalid="this.setCustomValidity('La Descripcion debe tener entre 4 y 30 caracteres y solo puede contener letras y espacios .')">
        <script>
                  function validarEditarEmpresa() {
                      const editarEmpresaInput = document.getElementById('empresa');
                      const editarEmpresaRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ 0-9-]+$/;

                      if (!editarEmpresaRegex.test(editarEmpresaInput.value)) {
                          editarEmpresaInput.setCustomValidity('El nombre debe contener solo letras guiones  y espacios.');
                      } else {
                          editarEmpresaInput.setCustomValidity('');
                      }
                  }
        </script>

      </div>

    </div>
   <a><b>Cédula del Representante</b></a>
<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-id-card-o"></i></span> 
    
    <select class="form-control input-lg" 
            id="editarCedulaRepresentanteTipo" 
            name="tipo_ced" 
            style="width: 80px; margin-right: 5px;" 
            required>
      <option value="V" <?= (isset($proveedores['tipo_ced']) && $proveedores['tipo_ced'] == 'V') ? 'selected' : '' ?>>V</option>
      <option value="E" <?= (isset($proveedores['tipo_ced']) && $proveedores['tipo_ced'] == 'E') ? 'selected' : '' ?>>E</option>
    </select>

    <span class="input-group-addon" >-</span> 
    
    <input type="text" 
           class="form-control input-lg" 
           id="editarCedulaRepresentanteNumero" 
           name="num_ced"
           placeholder="Ingresar cédula" 
           required minlength="7" maxlength="8" 
           pattern="[0-9]{7,8}"
           oninput="validarEditarCedulaRepresentanteNumero()"
           oninvalid="this.setCustomValidity('La cédula solo puede contener entre 7 y 8 números.')"
           style="flex-grow: 1; border-left: none;"
           value="<?= isset($proveedor['num_ced']) ? $proveedor['num_ced'] : '' ?>">

    <script>
      // Se mantiene la función de validación del número
      function validarEditarCedulaRepresentanteNumero() {
        const cedulaNumeroInput = document.getElementById('editarCedulaRepresentanteNumero');
        const cedulaNumeroRegex = /^[0-9]{7,8}$/;

        if (!cedulaNumeroRegex.test(cedulaNumeroInput.value)) {
          cedulaNumeroInput.setCustomValidity('La cédula solo puede contener entre 7 y 8 números.');
        } else {
          cedulaNumeroInput.setCustomValidity('');
        }
      }
    </script>
  </div>
</div>
    <!-- ENTRADA PARA EL NOMBRE -->
    <a><b>Nombre del Representante</b></a>
    <div class="form-group">
      
      <div class="input-group">
      
        <span class="input-group-addon"><i class="fa fa-user"></i></span> 

        <input type="text" id="nombreRepresentante" class="form-control input-lg" name="editarNombre" placeholder="Ingresar nombre" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                 
                oninput="validarEditarNombre()" oninvalid="this.setCustomValidity('El nombre debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios .')">
        <script>
                  function validarEditarNombre() {
                      const editarNombreInput = document.getElementById('nombreRepresentante');
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

    <!-- ENTRADA PARA EL DOCUMENTO ID -->
       <a><b> Apellido del Representante</b></a>
    <div class="form-group">
      
      <div class="input-group">
      
        <span class="input-group-addon"><i class="fa fa-key"></i></span> 

        <input type="text" id="apellidoRepresentante" class="form-control input-lg" name="editarApellido" placeholder="Ingresar Apellido" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"oninput="validarEditarApellido()" oninvalid="this.setCustomValidity('El apellido debe tener entre 3 y 30 caracteres y solo puede contener letras y espacios.')">
        <script>
                function validarEditarApellido() {
                    const editarApellidoInput = document.getElementById('apellidoRepresentante');
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
    <a><b> Correo del Proveedor</b></a>
    <div class="form-group">
      
      <div class="input-group">
      
        <span class="input-group-addon"><i class="fa fa-key"></i></span> 

        <input type="email" id="correo" class="form-control input-lg" name="editarEmail" placeholder="Ingresar Email" required oninput="validarEditarEmail()" oninvalid="this.setCustomValidity('Por favor, ingresa una dirección de correo electrónico válida por ejemplo (bocono12@gmail.com).')">
        <script>
                 function validarEditarEmail() {
                      const editarEmailInput = document.getElementById('correo');
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

  <!-- ENTRADA PARA EL TELÉFONO DEL PROVEEDOR -->
<a><b>Teléfono del Proveedor</b></a>
<div class="form-group">
  <div class="input-group">
    <span class="input-group-addon"><i class="fa fa-phone"></i></span>

    <!-- PREFIJO con name -->
    <select name="eprefijo_telefono" class="form-control input-lg" id="editarTelefonoProveedorPrefijo" style="width: 100px; margin-right: 5px;" required>
      <option value="">Prefijo</option>
      <option value="0212">0212</option>
      <option value="0251">0251</option>
      <option value="0261">0261</option>
      <option value="0272">0272</option>
      <option value="0281">0281</option>
      <option value="0295">0295</option>
      <option value="0412">0412</option>
      <option value="0414">0414</option>
      <option value="0416">0416</option>
      <option value="0424">0424</option>
      <option value="0426">0426</option>
    </select>

    <span class="input-group-addon">-</span>

    <!-- NÚMERO con name -->
    <input
      type="text"
      name="enumero_telefono"
      class="form-control input-lg"
      id="editarTelefonoProveedorNumero"
      placeholder="Ej: 1234567"
      required
      minlength="7"
      maxlength="7"
      pattern="[0-9]{7}"
      oninput="validarEditarTelefonoProveedorNumero()"
      oninvalid="this.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.')"
      style="flex-grow: 1; border-left: none;"
    >

    <!-- CAMPO OCULTO CON TELEFONO COMPLETO -->
    <input type="hidden" name="editarTelefono" id="hiddenEditarTelefonoProveedor">
  </div>
</div>

<!-- SCRIPT DE VALIDACIÓN Y COMBINACIÓN -->
<script>
  function validarEditarTelefonoProveedorNumero() {
    const telefonoNumeroInput = document.getElementById('editarTelefonoProveedorNumero');
    const telefonoNumeroRegex = /^[0-9]{7}$/;

    if (!telefonoNumeroRegex.test(telefonoNumeroInput.value)) {
      telefonoNumeroInput.setCustomValidity('Los 7 dígitos del teléfono solo pueden contener números.');
    } else {
      telefonoNumeroInput.setCustomValidity('');
    }

    combinarEditarTelefonoProveedor(); // Actualizar el campo oculto
  }

;
  });
</script>
<!-- ENTRADA PARA LA DIRECCIÓN -->
    <a><b>Direccion del Proveedor</b></a>
    <div class="form-group">
      
      <div class="input-group">
      
        <span class="input-group-addon"><i class="fa fa-map-marker"></i></span> 

        <input type="text" id="direccion" class="form-control input-lg" name="editarDireccion" placeholder="Ingresar dirección" required minlength="3" maxlength="80" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9 º#-]+" 
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

    </div>

  </div>


  <!--=====================================
  PIE DEL MODAL
  ======================================-->

  <div class="modal-footer">

    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

    <button type="submit" class="btn btn-primary">Editar proveedor</button>

  </div>
</div>


</form>

      <?php

        $editarCliente = new ControladorProveedor();
        $editarCliente -> ctrEditarProveedor();

      ?>

    

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

  $eliminarCliente = new ControladorProveedor();
  $eliminarCliente -> ctrEliminarProveedor();

?>


