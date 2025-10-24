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
      
      Administrar Productos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Administrar productos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-header with-border">
  
        <button class="btn-lg btn-primary" data-toggle="modal" data-target="#modalAgregarProducto">
          
          Agregar Producto

        </button>

      </div>

      <div class="box-body tableOverflow" style="width:calc(100% -15px);">
        
       <table class="table table-bordered table-striped tablaProductos" width="100%">
         
        <thead>
         
         <tr>
           
           <th style="width:10px">#</th>
           <th style="padding-inline:20px;white-space:nowrap">Imagen</th>
           <th style="padding-inline:20px;white-space:nowrap">Código</th>
           <th style="padding-inline:20px;white-space:nowrap">Descripción</th>
           <th style="padding-inline:20px;white-space:nowrap">Categoría</th>
           <th style="padding-inline:20px;white-space:nowrap">Tipo</th>
           <th style="padding-inline:20px;white-space:nowrap">Marca</th>
           
           <th style="padding-inline:20px;white-space:nowrap">Stock</th>
           <th style="padding-inline:20px;white-space:nowrap">P. Compra $ </th>
           <th style="padding-inline:20px;white-space:nowrap">P. Venta $ </th>
           <th style="padding-inline:20px;white-space:nowrap">P. Venta Bs </th>
           <th style="min-width: 130px;">Acciones</th>
           
         </tr> 

        </thead>      

       </table>

       <input type="hidden" value="<?php echo $_SESSION['perfil']; ?>" id="perfilOculto">

      </div>

    </div>

  </section>

</div>

<!--=====================================
MODAL AGREGAR PRODUCTO
======================================-->

<div id="modalAgregarProducto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

          <div class="box-body">

          
              
              <div class="form-group">
                  <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                      <select class="form-control input-lg seleccionarProveedor" id="nuevoProveedorSelect" name="rifCompletoNuevo" required> 
                          <option value="" disabled selected>Selecionar proveedor</option>
                          <?php
                          $item = null;
                          $valor = null;
                          $proveedores = ControladorProveedor::ctrMostrarProveedor($item, $valor);

                          foreach ($proveedores as $key => $value) {
                              // Separamos el tipo_rif y num_rif para pasarlos como data-atributos
                              $rifCompleto = $value["tipo_rif"] . "-" . $value["num_rif"];
                              echo '<option value="'.$rifCompleto.'" data-tipo-rif="'.$value["tipo_rif"].'" data-num-rif="'.$value["num_rif"].'">'.$rifCompleto.' - '.$value["nombre"].'</option>';
                          }
                          ?>
                      </select>
                  </div>
              </div>
              <input type="hidden" name="nuevoTipoRifProveedor" id="nuevoTipoRifProveedor">
              <input type="hidden" name="nuevoNumRifProveedor" id="nuevoNumRifProveedor">

            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <select class="form-control input-lg" id="nuevaCategoria" name="nuevaCategoria" required>
                  
                  <option value="">Selecionar categoría</option>

                  <?php

                  $item = null;
                  $valor = null;

                  $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                  }

                  ?>
  
                </select>

              </div>

            </div>
            <!-- ENTRADA PARA SELECCIONAR Marca -->

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <select class="form-control input-lg" id="nuevaCategoria" name="nuevaMarca" required>
                  
                  <option value="">Selecionar Marca</option>

                  <?php

                  $item = null;
                  $valor = null;

                  $marcas = ControladorMarcas::ctrMostrarMarcas($item, $valor);

                  foreach ($marcas as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["marca"].'</option>';
                  }

                  ?>
  
                </select>

              </div>

            </div>
             <!-- ENTRADA PARA SELECCIONAR TIPO -->

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 

                <select class="form-control input-lg" id="nuevaCategoria" name="nuevoTipo" required>
                  
                  <option value="">Selecionar Tipo</option>

                  <?php

                  $item = null;
                  $valor = null;

                  $tipos = ControladorTipos::ctrMostrarTipos($item, $valor);

                  foreach ($tipos as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["tipo"].'</option>';
                  }

                  ?>
  
                </select>

              </div>

            </div>
            
            

            <!-- ENTRADA PARA SELECCIONAR Proveedor -->

            <div class="form-group">
              
              <div class="input-group">
              
              
               

              </div>

            </div>

            <!-- ENTRADA PARA EL CÓDIGO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg"  name="nuevoCodigo" id="nuevoCodigo" placeholder="Ingresar código" required minlength="4" maxlength="30" pattern="[a-zA-Z0-9-]*" oninput="validarNuevoCodigo()" oninvalid="this.setCustomValidity('El Codigo debe tener entre 4 y 30 caracteres y solo puede contener letras numeros y guiones sin espacios, ejemplo:(asw-2)(2-aweed)(w-3-qw4) .')">
                <script>
                  function validarNuevoCodigo() {
                      const nuevoCodigoInput = document.getElementById('nuevoCodigo');
                      const nuevoCodigoRegex = /^[a-zA-Z0-9-]*$/;

                      if (!nuevoCodigoRegex.test(nuevoCodigoInput.value)) {
                          nuevoCodigoInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                      } else {
                          nuevoCodigoInput.setCustomValidity('');
                      }
                  }
                </script>

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                <input type="text" class="form-control input-lg" name="nuevaDescripcion" id="nuevaDescripcion"placeholder="Ingresar descripción" required minlength="4" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                 
                oninput="validarNuevaDescripcion()" oninvalid="this.setCustomValidity('La Descripcion debe tener entre 4 y 30 caracteres y solo puede contener letras y espacios .')">
                <script>
                  function validarNuevaDescripcion() {
                      const nuevaDescripcionInput = document.getElementById('nuevaDescripcion');
                      const nuevaDescripcionRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                      if (!nuevaDescripcionRegex.test(nuevaDescripcionInput.value)) {
                          nuevaDescripcionInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                      } else {
                          nuevaDescripcionInput.setCustomValidity('');
                      }
                  }
                </script>


              </div>

            </div>

             <!-- ENTRADA PARA STOCK

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                <input type="number" class="form-control input-lg" name="nuevoStock" min="0" placeholder="Stock" required>

              </div>

            </div>-->

             <!-- ENTRADA PARA PRECIO COMPRA -->

             <div class="form-group row">

                <div class="col-xs-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                    <input type="number" class="form-control input-lg" id="nuevoPrecioCompra" name="nuevoPrecioCompra" step="any" min="0" placeholder="Precio de compra" required>

                  </div>

                </div>

                <!-- ENTRADA PARA PRECIO VENTA -->

                <div class="col-xs-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                    <input type="number" class="form-control input-lg" id="nuevoPrecioVenta" name="nuevoPrecioVenta" step="any" min="0" placeholder="Precio de venta" required>

                  </div>
                
                  <br>

                </div>

            </div>
            <div class="col-xs-6">
                    
                    <div class="form-group">
                      
                      <label>
                        
                        <input type="checkbox" class="minimal porcentajeNuevo" checked>
                        Utilizar procentaje
                      </label>

                    </div>

                  </div>

                  <!-- ENTRADA PARA PORCENTAJE -->

                  <div class="col-xs-6" style="padding:0">
                    
                    <div class="input-group">
                      
                      <input type="number" class="form-control input-lg nuevoPorcentajeCompra" min="0" value="30" required>

                      <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                    </div>

                  </div>
            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="nuevaImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Guardar producto</button>

        </div>

      </form>

        <?php

          $crearProducto = new ControladorProductos();
          $crearProducto -> ctrCrearProducto();

        ?>  

    </div>

  </div>

</div>

<div id="modalStockProducto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Agregar stock al producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->
        <style>
          #proveedor-div {
              display: none;
          }
        </style>

        <div class="modal-body">

          <div class="box-body">                        

            <!-- ENTRADA PARA SELECCIONAR Proveedor -->
            
            <!-- ENTRADA PARA EL CÓDIGO -->
            <div class="form-group">
              <a><b>Código del Producto</b></a>
              <div class="input-group">
                
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" id="editarStockCodigo" name="editarStockCodigo" placeholder="Ingresar código" readonly required>

              </div>

            </div>


             <!-- ENTRADA PARA STOCK -->

             <div class="form-group">
              <a><b>Cantidad a agregar al stock</b></a>
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                <input type="number" class="form-control input-lg" name="editarStockNum" id="editarStockNum" min="1" placeholder="Stock" required>

              </div>

            </div>

          </div>

        </div>

        <!--=====================================
        PIE DEL MODAL
        ======================================-->

        <div class="modal-footer">

          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>

          <button type="submit" class="btn btn-primary">Agregar stock</button>

        </div>

      </form>

        <?php

          $crearProducto = new ControladorProductos();
          $crearProducto -> ctrStockProducto();

        ?>  

    </div>

  </div>

</div>

<!--

Modal stock
-->

<!--=====================================
MODAL EDITAR PRODUCTO
======================================-->

<div id="modalEditarProducto" class="modal fade" role="dialog">
  
  <div class="modal-dialog">

    <div class="modal-content">

      <form role="form" method="post" enctype="multipart/form-data">

        <!--=====================================
        CABEZA DEL MODAL
        ======================================-->

        <div class="modal-header" style="background:#3c8dbc; color:white">

          <button type="button" class="close" data-dismiss="modal">&times;</button>

          <h4 class="modal-title">Editar producto</h4>

        </div>

        <!--=====================================
        CUERPO DEL MODAL
        ======================================-->

        <div class="modal-body">

        

          <div class="box-body">

          <div class="form-group">
              <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                  <select class="form-control input-lg seleccionarProveedorEditar" name="rifCompletoEditar" id="editarProveedorSelect" required>
                      <option value="" disabled selected>Selecionar Proveedor</option>
                      <?php
                      $item = null;
                      $valor = null;
                      $proveedores = ControladorProveedor::ctrMostrarProveedor($item, $valor);

                      foreach ($proveedores as $key => $value) {
                          $rifCompleto = $value["tipo_rif"] . "-" . $value["num_rif"];
                          echo '<option value="'.$rifCompleto.'" data-tipo-rif="'.$value["tipo_rif"].'" data-num-rif="'.$value["num_rif"].'">'.$rifCompleto.' - '.$value["nombre"].'</option>';
                      }
                      ?>
                  </select>
              </div>
          </div>
          <input type="hidden" name="editarTipoRifProveedor" id="editarTipoRifProveedor">
          <input type="hidden" name="editarNumRifProveedor" id="editarNumRifProveedor">

            <!-- ENTRADA PARA SELECCIONAR CATEGORÍA -->

            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-th"></i></span> 
                <select class="form-control input-lg" name="editarCategoria" id="editarCategoria" required>
                  
                  <option disabled selected>Selecionar Categoria</option>
                  <?php

                  $item = null;
                  $valor = null;

                  $categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);

                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["categoria"].'</option>';
                  }

                  ?>
                </select>
                
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" name="editarTipo" id="editarTipo" required>
                  
                  <option disabled selected>Selecionar Tipo</option>
                  <?php

                  $item = null;
                  $valor = null;

                  $categorias = ControladorTipos::ctrMostrarTipos($item, $valor);

                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["tipo"].'</option>';
                  }

                  ?>
                </select>
              </div>
            </div>
           
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-th"></i></span>
                <select class="form-control input-lg" name="editarMarca" id="editarMarca" required>
                  
                  <option disabled selected>Selecionar Marca</option>
                  <?php

                  $item = null;
                  $valor = null;

                  $categorias = ControladorMarcas::ctrMostrarMarcas($item, $valor);

                  foreach ($categorias as $key => $value) {
                    
                    echo '<option value="'.$value["id"].'">'.$value["marca"].'</option>';
                  }

                  ?>
                </select>
              </div>
            </div>

            <!-- ENTRADA PARA EL CÓDIGO -->
            
            <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-code"></i></span> 

                <input type="text" class="form-control input-lg" id="editarCodigo" name="editarCodigo" readonly required>

              </div>

            </div>

            <!-- ENTRADA PARA LA DESCRIPCIÓN -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-product-hunt"></i></span> 

                <input type="text" class="form-control input-lg" id="editarDescripcion" name="editarDescripcion" required minlength="4" maxlength="30" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+"
                 
                oninput="validarEditarDescripcion()" oninvalid="this.setCustomValidity('La Descripcion debe tener entre 4 y 30 caracteres y solo puede contener letras y espacios .')">
                <script>
                  function validarEditarDescripcion() {
                      const editarDescripcionInput = document.getElementById('editarDescripcion');
                      const editarDescripcionRegex = /^[a-zA-ZñÑáéíóúÁÉÍÓÚ ]+$/;

                      if (!editarDescripcionRegex.test(editarDescripcionInput.value)) {
                          editarDescripcionInput.setCustomValidity('El nombre debe contener solo letras y espacios.');
                      } else {
                          editarDescripcionInput.setCustomValidity('');
                      }
                  }
                </script>

              </div>

            </div>

             <!-- ENTRADA PARA STOCK -->

             <div class="form-group">
              
              <div class="input-group">
              
                <span class="input-group-addon"><i class="fa fa-check"></i></span> 

                <input type="number" class="form-control input-lg" id="editarStock" name="editarStock" min="0" readonly required>

              </div>

            </div>

             <!-- ENTRADA PARA PRECIO COMPRA -->

             <div class="form-group row">

                <div class="col-xs-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-up"></i></span> 

                    <input type="number" class="form-control input-lg" id="editarPrecioCompra" name="editarPrecioCompra" step="any" min="0" required>

                  </div>

                </div>

                <!-- ENTRADA PARA PRECIO VENTA -->

                <div class="col-xs-6">
                
                  <div class="input-group">
                  
                    <span class="input-group-addon"><i class="fa fa-arrow-down"></i></span> 

                    <input type="number" class="form-control input-lg" id="editarPrecioVenta" name="editarPrecioVenta" step="any" min="0" readonly required>

                  </div>
                
                  <br>

                  <!-- CHECKBOX PARA PORCENTAJE -->

                  <div class="col-xs-6">
                    
                    <div class="form-group">
                      
                      <label>
                        
                        <input type="checkbox" class="minimal porcentaje" checked>
                        Utilizar procentaje
                      </label>

                    </div>

                  </div>

                  <!-- ENTRADA PARA PORCENTAJE -->

                  <div class="col-xs-6" style="padding:0">
                    
                    <div class="input-group">
                      
                      <input type="number" class="form-control input-lg nuevoPorcentaje" min="0" value="30" required>

                      <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                    </div>

                  </div>

                </div>

            </div>

            <!-- ENTRADA PARA SUBIR FOTO -->

             <div class="form-group">
              
              <div class="panel">SUBIR IMAGEN</div>

              <input type="file" class="nuevaImagen" name="editarImagen">

              <p class="help-block">Peso máximo de la imagen 2MB</p>

              <img src="vistas/img/productos/default/anonymous.png" class="img-thumbnail previsualizar" width="100px">

              <input type="hidden" name="imagenActual" id="imagenActual">

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

      </form>

        <?php

          $editarProducto = new ControladorProductos();
          $editarProducto -> ctrEditarProducto();

        ?>      

    </div>

  </div>

</div>
<script>
// Dentro de tu archivo JS o al final del .php
$("#nuevoProveedorSelect").on("change", function(){
    const selectedOption = $(this).find('option:selected');
    const tipoRif = selectedOption.data('tipo-rif');
    const numRif = selectedOption.data('num-rif');
    
    // Llenar los campos ocultos
    $("#nuevoTipoRifProveedor").val(tipoRif);
    $("#nuevoNumRifProveedor").val(numRif);
});
</script>
<script>
// Dentro de tu archivo JS o al final del .php
// Lógica para llenar los campos ocultos al seleccionar en editar
$("#editarProveedorSelect").on("change", function(){
    const selectedOption = $(this).find('option:selected');
    const tipoRif = selectedOption.data('tipo-rif');
    const numRif = selectedOption.data('num-rif');
    
    // Llenar los campos ocultos
    $("#editarTipoRifProveedor").val(tipoRif);
    $("#editarNumRifProveedor").val(numRif);
});

// Además, cuando se carga un producto para editar (generalmente con AJAX, no visible aquí)
// deberías asegurarte de que tu script de "Editar Producto" también:
// 1. Seleccione la opción correcta en #editarProveedorSelect.
// 2. Llene los campos ocultos #editarTipoRifProveedor y #editarNumRifProveedor con los valores del producto.
</script>


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

  $eliminarProducto = new ControladorProductos();
  $eliminarProducto -> ctrEliminarProducto();

?>      



