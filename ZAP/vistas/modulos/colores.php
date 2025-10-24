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
      
      Administrar Colores
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Colores</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarColor">
          
          Agregar Colores

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Colores</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>

        <?php

          $item = null;
          $valor = null;

          $colores = ControladorColores::ctrMostrarColor($item, $valor);

          foreach ($colores as $key => $value) {
           
            echo ' <tr>

                    <td>'.($key+1).'</td>

                    <td class="text-uppercase">'.$value["color"].'</td>

                    <td>

                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarColor" idcolor="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarColor"><i class="fa fa-pencil"></i></button>';

                        if($_SESSION["perfil"] == "Administrador"){

                          echo '<button class="btn btn-danger btnEliminarColor" idColor="'.$value["id"].'"><i class="fa fa-times"></i></button>';

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
MODAL AGREGAR colores
======================================-->

<div id="modalAgregarColor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar Color</h4>

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

                
                <input type="text" class="form-control input-lg" name="nuevaColor" id="nuevaColor" placeholder="Ingresar color" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ]+" 
                oninput="validarNuevaColor()" oninvalid="this.setCustomValidity('El color debe tener como mínimo 3 caracteres y solo puede contener letras  .')">
                <script>
                function validarNuevaColor() {
                    const nuevaColorInput = document.getElementById('nuevaColor');
                    const nuevaColorRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/;

                    if (!nuevaColorRegex.test(nuevaColorInput.value)) {
                        nuevaColorInput.setCustomValidity('El color debe contener solo letras y espacios.');
                    } else {
                        nuevaColorInput.setCustomValidity('');
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

          <button type="submit" class="btn btn-primary">Guardar Color</button>

        </div>

        <?php

          $crearColor = new ControladorColores();
          $crearColor -> ctrCrearColor();

        ?>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR colores
======================================-->

<div id="modalEditarColor" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar Color</h4>

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

                <input type="text" class="form-control input-lg" name="editarColor" id="editarColor" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ]+"
                 oninput="validarEditarColor()" oninvalid="this.setCustomValidity('El color debe tener como mínimo 3 caracteres y solo puede contener letras  .')">
                <script>
                  function validarEditarColor() {
                      const editarColorInput = document.getElementById('editarColor');
                      const editarColorRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ]+$/;

                      if (!editarColorRegex.test(editarColorInput.value)) {
                          editarColorInput.setCustomValidity('El color debe contener solo letras .');
                      } else {
                          editarColorInput.setCustomValidity('');
                      }
                  }
                </script>


                 <input type="hidden"  name="idColor" id="idColor" required>

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

          $editarColor = new ControladorColores();
          $editarColor -> ctrEditarColor();

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
    $borrarColor = new ControladorColores();
    $borrarColor -> ctrBorrarColor();

   ?> 



