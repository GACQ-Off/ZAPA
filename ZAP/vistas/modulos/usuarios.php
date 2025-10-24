<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

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
      
      Administrar usuarios
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar usuarios</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn-lg btn-primary" data-toggle="modal" data-target="#modalAgregarUsuario">
          
          Agregar usuario

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           <th>Usuario</th>
           <th>Nombre y Apellido</th>
           <th>Perfil</th>
           <th>Estado</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>

        <?php

        $item = null;
        $valor = null;

        $usuarios = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

       foreach ($usuarios as $key => $value){
         
          echo ' <tr>
                  <td>'.$value["usuario"].'</td>';
                  echo '<td>'.ucwords($value["nombre"]." ".$value["apellido"]).'</td>';

                  echo '<td>'.$value["perfil"].'</td>';
                  

                  // *** INICIO DE LA LÓGICA DE RESTRICCIÓN PARA EL SUPER USUARIO EN EL ESTADO ***
                  if($value["cedula"] == '1000000'){
                    echo '<td><button class="btn ' . ($value["estado"] != 0 ? 'btn-success' : 'btn-danger') . ' btn-xs" disabled style="cursor: not-allowed;" title="No se puede cambiar el estado del Super Usuario">' . ($value["estado"] != 0 ? 'Activado' : 'Desactivado') . '</button></td>';
                  } else {
                    if($value["estado"] != 0){
                        echo '<td><button class="btn btn-success btn-xs btnActivar" idUsuario="'.$value["cedula"].'" estadoUsuario="0">Activado</button></td>';
                    }else{
                        echo '<td><button class="btn btn-danger btn-xs btnActivar" idUsuario="'.$value["cedula"].'" estadoUsuario="1">Desactivado</button></td>';
                    }
                  }
                  // *** FIN DE LA LÓGICA DE RESTRICCIÓN ***             

                  echo '
                  <td>

                    <div class="btn-group">
                '.
                // *** INICIO DE LA LÓGICA DE RESTRICCIÓN PARA EL SUPER USUARIO ***
                ($value["cedula"] == '1000000' ?
                    '<button class="btn btn-warning" disabled style="cursor: not-allowed;" title="No se puede editar al Super Usuario"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger" disabled style="cursor: not-allowed; margin-left: 5px;" title="No se puede eliminar al Super Usuario"><i class="fa fa-times"></i></button>'
                    :
                    '<button class="btn btn-warning btnEditarUsuario" idUsuario="'.$value["cedula"].'" data-toggle="modal" data-target="#modalEditarUsuario"><i class="fa fa-pencil"></i></button>
                    <button class="btn btn-danger btnEliminarUsuario" idUsuario="'.$value["cedula"].'" fotoUsuario="'.$value["foto"].'" usuario="'.$value["cedula"].'" style="margin-left: 5px;"><i class="fa fa-times"></i></button>'
                )
                .
                // *** FIN DE LA LÓGICA DE RESTRICCIÓN ***
                '
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

<div id="modalAgregarUsuario" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar usuario</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">


            <?php
              $empleados = ModeloEmpleados::MdlMostrarEmpleados("empleado",null,null);
            ?>    
            <!-- ENTRADA PARA EL NOMBRE -->
            <a><b>Cédula</b></a>
            <div class="form-group">
              <select class="form-control input-lg" required name="nuevaCedula">
                  
                  <option disabled selected>Selecciona un empleado</option>
                  <?php foreach($empleados as $empleado): ?>
                    <option value="<?=$empleado["cedula"]?>"><?=ucwords($empleado["nombre"]." ".$empleado["apellido"])." - ".$empleado["cedula"]?></option>
                  <?php endforeach?>

                </select>
            </div>

            <!-- ENTRADA PARA EL USUARIO -->
              <a><b>Nombre de Usuario</b></a>
             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevoUsuario" placeholder="Ingresar usuario" id="nuevoUsuario" required>

              </div>

            </div>

            <!-- ENTRADA PARA LA CONTRASEÑA -->
              <a><b>Clave de Usuario</b></a>
             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-lock"></i></span> 

                <input type="password" class="form-control input-lg" name="nuevoPassword" placeholder="Ingresar clave" required>

              </div>

            </div>

            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->
              <a><b>Seleccionar Perfil</b></a>
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-users"></i></span> 

                <select class="form-control input-lg" name="nuevoPerfil">
                  
                  <option disabled selected>Selecciona un Rol</option>

                  <option value="Administrador">Administrador</option>

                  <option value="Vendedor">Vendedor</option>

                </select>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar usuario</button>

        </div>

        <?php

          $crearUsuario = new ControladorUsuarios();
          $crearUsuario -> ctrCrearUsuario();

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

          <h4 class="modal-title">Editar usuario</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-user"></i></span> 

                <input type="text" class="form-control input-lg" id="editarCedula" name="editarNombre" value="" disabled required>

              </div>

            </div>

            <!-- ENTRADA PARA EL USUARIO -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-key"></i></span> 

                <input type="text" class="form-control input-lg" id="editarUsuario" name="editarUsuario" value="" readonly>

              </div>

            </div>

            <!-- ENTRADA PARA LA CONTRASEÑA -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-lock"></i></span> 

                <input type="password" class="form-control input-lg" name="editarPassword" required placeholder="Escriba la nueva contraseña">

                <input type="hidden" id="passwordActual" name="passwordActual">

              </div>

            </div>

            <!-- ENTRADA PARA SELECCIONAR SU PERFIL -->

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-users"></i></span> 

                <select class="form-control input-lg" id="editarPerfil"name="editarPerfil">
                  
                  <!-- <option value="" id="editarPerfil"></option> -->
                  <option disabled selected>Selecciona un Rol</option>
                  <option value="Administrador">Administrador</option>

                  <option value="Vendedor">Vendedor</option>

                </select>

              </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <!-- <div class="form-group">
              
              <div class="panel">SUBIR FOTO</div>

              <input type="file" class="nuevaFoto" name="editarFoto">

              <p class="help-block">Peso máximo de la foto 2MB</p>

              <img src="vistas/img/usuarios/default/anonymous.png" class="img-thumbnail previsualizarEditar" width="100px">

              <input type="hidden" name="fotoActual" id="fotoActual">

            </div> -->

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Modificar usuario</button>

        </div>

     <?php

          $editarUsuario = new ControladorUsuarios();
          $editarUsuario -> ctrEditarUsuario();

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

  $borrarUsuario = new ControladorUsuarios();
  $borrarUsuario -> ctrBorrarUsuario();

?> 


