<?php

if($_SESSION["perfil"] == "Vendedor"){

  echo '<script>

    window.location = "inicio";

  </script>';

  return;

}

?>

<div class="content-wrapper">

  <section class="content-header">
    
    <h1>
      
      Administrar Tipos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Tipos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn-lg btn-primary" data-toggle="modal" data-target="#modalAgregarTipo">
          
          Agregar Tipos

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Tipo</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>

        <?php

          $item = null;
          $valor = null;

          $tipos = ControladorTipos::ctrMostrarTipos($item, $valor);

          foreach ($tipos as $key => $value) {
           
            echo ' <tr>

                    <td>'.($key+1).'</td>

                    <td class="text-uppercase">'.$value["tipo"].'</td>

                    <td>

                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarTipo" idTipo="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarTipo"><i class="fa fa-pencil"></i></button>';

                        if($_SESSION["perfil"] == "Administrador"){

                          echo '<button class="btn btn-danger btnEliminarTipo" idTipo="'.$value["id"].'"><i class="fa fa-times"></i></button>';

                        }

                      echo '</div>  

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
MODAL AGREGAR CATEGORÍA
======================================-->

<div id="modalAgregarTipo" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar Tipo</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

         
                <input type="text" class="form-control input-lg" name="nuevaTipo" id="nuevaTipo" placeholder="Ingresar tipo" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ]+" 
                 oninput="validarNuevaTipo()" oninvalid="this.setCustomValidity('El tipo debe tener como mínimo 3 caracteres y solo puede contener letras  .')">
                <script>
                function validarNuevaTipo() {
                    const nuevaTipoInput = document.getElementById('nuevaTipo');
                    const nuevaTipoRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/;

                    if (!nuevaTipoRegex.test(nuevaTipoInput.value)) {
                        nuevaTipoInput.setCustomValidity('El tipo debe contener solo letras y espacios.');
                    } else {
                        nuevaTipoInput.setCustomValidity('');
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

          <button type="submit" class="btn btn-primary">Guardar Tipo</button>

        </div>

        <?php

          $crearTipo = new ControladorTipos();
          $crearTipo -> ctrCrearTipo();

        ?>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR CATEGORÍA
======================================-->

<div id="modalEditarTipo" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar Tipo</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

            <!-- ENTRADA PARA EL NOMBRE -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <input type="text" class="form-control input-lg" name="editarTipo" id="editarTipo" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ]+"
                 
                 oninput="validarEditarTipo()" oninvalid="this.setCustomValidity('El tipo debe tener como mínimo 3 caracteres y solo puede contener letras  .')">
                <script>
                function validarEditarTipo() {
                    const editarTipoInput = document.getElementById('editarTipo');
                    const editarTipoRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/;

                    if (!editarTipoRegex.test(editarTipoInput.value)) {
                        editarTipoInput.setCustomValidity('El tipo debe contener solo letras y espacios.');
                    } else {
                        editarTipoInput.setCustomValidity('');
                    }
                }
                </script>

                 <input type="hidden"  name="idTipo" id="idTipo" required>

              </div>

            </div>
  
          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar cambios</button>

        </div>

      <?php

          $editarTipo = new ControladorTipos();
          $editarTipo -> ctrEditarTipo();

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
    $borrarTipo = new ControladorTipos();
    $borrarTipo -> ctrBorrarTipo();

   ?> 



