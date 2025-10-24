 
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header">
     <h1>
       Tableros

     </h1>
     <ol class="breadcrumb">
       <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
       <li class="active">Tablero</li>
     </ol>
   </section>

   <section class="content">

     <div class="row">

       <?php
         

        if ($_SESSION["perfil"] == "Administrador") {

          include "inicio/cajas-superiores.php";
        }

        ?>

     </div>

     <div class="row">

       <div class="col-lg-6">

          <?php

          if($_SESSION["perfil"] =="Administrador"){
          
           include "vendedores.php";

          }

          ?>

       </div>

       <div class="col-lg-6">

                   <?php

          if($_SESSION["perfil"] =="Administrador"){
          
           include "compradores.php";

          }

          ?>

       </div>

       <div class="col-lg-6">



       </div>

       <div class="col-lg-12">

         <?php

          if ($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor") {

            echo '<div class="box box-success">

             <div class="box-header">

             <h1>Bienvenid@ ' . $_SESSION["nombre"] . '</h1>

             </div>

             </div>';
          }

          ?>

       </div>

     </div>

   </section>
   <!-- /.content -->
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
 <!-- /.content-wrapper -->