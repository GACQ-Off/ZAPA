<?php
    require_once "controladores/eventolog.controlador.php";
    require_once "modelos/eventolog.modelo.php"; 
    require_once "controladores/empleados.controlador.php"; 
    require_once "modelos/empleados.modelo.php";
// Asegúrate de que estas rutas sean correctas desde la ubicación de este archivo de vista


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
      
      Registro De Eventos
    
    </h1>

    <ol class="breadcrumb">
      
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      
      <li class="active">Registro De Eventos</li>
    
    </ol>

  </section>

  <section class="content">

    <div class="box">

      <div class="box-body">
        
      <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
          
        <thead>
          
          <tr>
            <th style="width:10px">#</th>
            <th>Tipo de Evento</th>
            <th>Descripción</th>
            <th>Empleado</th> <th>Tabla Afectada</th>
            <th>ID Fila Afectada</th>
            <th>Fecha y Hora</th> 
          </tr> 

        </thead>

        <tbody>

          <?php
          require_once "controladores/eventolog.controlador.php";
          require_once "modelos/eventolog.modelo.php"; 
          require_once "controladores/empleados.controlador.php"; 
          require_once "modelos/empleados.modelo.php";
              
                   $eventoLog = ControladorEventoLog::ctrMostrarEventoLog(null, null);

                  if($eventosLog){ 
                $i = 1; 
                foreach ($eventoLog as $key => $value) {
                  // Construir el nombre completo del empleado
                  $nombreEmpleado = "N/A"; // Valor por defecto si no se encuentra el empleado
                  if (!empty($value["nombre"]) && !empty($value["apellido"])) {
                      $nombreEmpleado = $value["nombre"] . " " . $value["apellido"];
                  } else if (!empty($value["employee_cedula"])) {
                      // Si no se encuentra el nombre/apellido pero sí la cédula, mostrar la cédula
                      $nombreEmpleado = "Cédula: " . $value["employee_cedula"] . " (No encontrado)";
                  }

                  echo '<tr>
                          <td>'.($i++).'</td>
                          <td>'.$value["event_type"].'</td>
                          <td>'.$value["description"].'</td>
                          <td>'.$nombreEmpleado.'</td> 
                          <td>'.$value["affected_table"].'</td>
                          <td>'.$value["affected_row_id"].'</td>
                          <td>'.$value["timestamp"].'</td> 
                        </tr>';
                }
              }
              
          ?>
        
        </tbody>

      </table>
        
      </div>
      </div>
    </section>
  </div>
<script>
    $("#logout").click(function(e) {
        e.preventDefault();

        swal({
            type: "warning", 
            title: "¿Estás seguro de salir?",
            text: "Esta acción cerrará tu sesión.",
            showCancelButton: true,
            confirmButtonColor: "#3498db", 
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