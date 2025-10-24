 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Mantenimientos
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Mantenimiento</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

    <div class="row">
      
    <div class="col-lg-3 col-xs-6">

  <form class="small-box bg-red" method="post">
  
    <div class="inner">
      <input type="hidden" name="exportar">
    
      <h1>Exportar</h1>
      <h1>Base de Datos</h1>
    
    </div>
    
    <div class="icon">
      
      <i class="fa fa-database"></i>
      <i class="fa fa-arrow-down"></i>
    </div>
    
    <a href="productos" class="small-box-footer">
      
    
    
    </a>

</a>

</div>

    </div> 

     <div class="row">
       
        <div class="col-lg-12">

         

        </div>

        <div class="col-lg-6">

         

        </div>

         <div class="col-lg-6">

       

        </div>

         <div class="col-lg-12">
           
          <?php

          if($_SESSION["perfil"] =="Especial" || $_SESSION["perfil"] =="Vendedor"){

             echo '<div class="box box-success">

             <div class="box-header">

             <h1>Bienvenid@ ' .$_SESSION["nombre"].'</h1>

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
    $("form").on("click",(e)=>{
      e.currentTarget.submit()
    })
  </script>
  <!-- /.content-wrapper -->