 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Crear Respaldo de Base de datos 
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Administrar Apartado</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <?php 
 

  
  {
    include ("modelo/../../conexion.php");
    require ("Exportar_BD.php");
  }
  

 ?>


<head>
  <meta charset="UTF-8">
  
  <title>Exportar</title>
</head>
<body>
  
  <section id="container">
    
    <div class="form_register">
      <h1>Exportar</h1>
      <hr>
      <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

      <form action="" method="post">
        
        <button type="submit" id="Respaldar" name="Respaldar">Respaldar Base de Datos</button> 
      </form>


    </div>


  </section>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->