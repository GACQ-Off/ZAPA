<?php

$item = null;
$valor = null;
$orden = "id";

$ventas = ControladorVentas::ctrSumaTotalVentas();

$categorias = ControladorCategorias::ctrMostrarCategorias($item, $valor);
$totalCategorias = count($categorias);

$clientes = ControladorClientes::ctrMostrarClientes($item, $valor);
$totalClientes = count($clientes);

$productos = ControladorProductos::ctrMostrarProductos($item, $valor, $orden);
$totalProductos = count($productos);

?>





<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-blue">
    
    <div class="inner">
    
      

      <h1>Exportar</h1>
      <h1>Base de Datos</h1>
    
    </div>
    
    <div class="icon">
    
      <i class="fa fa-database"></i>
      <i class="fa fa-arrow-up"></i>
    </div>
    
    <a href="categorias" class="small-box-footer">
      
      
    
    </a>

  </div>

</div>


<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-red">
  
    <div class="inner">
    
      <h1>Importar</h1>
      <h1>Base de Datos</h1>
    
    </div>
    
    <div class="icon">
      
      <i class="fa fa-database"></i>
      <i class="fa fa-arrow-down"></i>
    </div>
    
    <a href="productos" class="small-box-footer">
      
    
    
    </a>

  </div>

</div>