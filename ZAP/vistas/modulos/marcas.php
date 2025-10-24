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
      
      Administrar Marcas
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar Marcas</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn-lg btn-primary" data-toggle="modal" data-target="#modalAgregarCategoria">
          
          Agregar Marca

        </button>

      </div>

      <div class="box-body">
        
       <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th>Marca</th>
           <th>Acciones</th>

         </tr> 

        </thead>

        <tbody>

        <?php

          $item = null;
          $valor = null;

          $marcas = ControladorMarcas::ctrMostrarMarcas($item, $valor);

          foreach ($marcas as $key => $value) {
           
            echo ' <tr>

                    <td>'.($key+1).'</td>

                    <td class="text-uppercase">'.$value["marca"].'</td>

                    <td>

                      <div class="btn-group">
                          
                        <button class="btn btn-warning btnEditarMarca" idMarca="'.$value["id"].'" data-toggle="modal" data-target="#modalEditarCategoria"><i class="fa fa-pencil"></i></button>';

                        if($_SESSION["perfil"] == "Administrador"){

                          echo '<button class="btn btn-danger btnEliminarMarca" idMarca="'.$value["id"].'"><i class="fa fa-times"></i></button>';

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

<div id="modalAgregarCategoria" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar Marca</h4>

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

               
                <input type="text" class="form-control input-lg" name="nuevaMarca" id="nuevaMarca" placeholder="Ingresar marca" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9]+" 
                oninput="validarNuevaMarca()" oninvalid="this.setCustomValidity('La Marca debe tener como mínimo 3 caracteres y solo puede contener letras y números .')">
                <script>
                function validarNuevaMarca() {
                    const nuevaMarcaInput = document.getElementById('nuevaMarca');
                    const nuevaMarcaRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9]+$/;

                    if (!nuevaMarcaRegex.test(nuevaMarcaInput.value)) {
                        nuevaMarcaInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                    } else {
                        nuevaMarcaInput.setCustomValidity('');
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

          <button type="submit" class="btn btn-primary">Guardar Marca</button>

        </div>

        <?php

          $crearMarca = new ControladorMarcas();
          $crearMarca -> ctrCrearMarca();

        ?>

      </form>

    </div>

  </div>

</div>

<!--=====================================
MODAL EDITAR CATEGORÍA
======================================-->

<div id="modalEditarCategoria" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar Marca</h4>

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

                <input type="text" class="form-control input-lg" name="editarMarca" id="editarMarca" required minlength="3" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9]+" 
                oninput="validarEditarMarca()" oninvalid="this.setCustomValidity('La Marca debe tener como mínimo 3 caracteres y solo puede contener letras y números .')">
                <script>
                function validarEditarMarca() {
                    const editarMarcaInput = document.getElementById('editarMarca');
                    const editarMarcaRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ0-9]+$/;

                    if (!editarMarcaRegex.test(editarMarcaInput.value)) {
                        editarMarcaInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                    } else {
                        editarMarcaInput.setCustomValidity('');
                    }
                }
                </script>


                 <input type="hidden"  name="idMarca" id="idMarca" required>

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

          $editarMarca = new ControladorMarcas();
          $editarMarca -> ctrEditarMarca();

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

  $borrarMarca = new ControladorMarcas();
  $borrarMarca -> ctrBorrarMarca();

?>


