<?php
// INICIO: CONFIGURACIÓN DE DEPURACIÓN (DESACTIVAR EN PRODUCCIÓN)
ini_set("display_errors", 1);
error_reporting(E_ALL);
// FIN: CONFIGURACIÓN DE DEPURACIÓN

// **MUY IMPORTANTE:** Ajusta estas rutas según la ubicación de este archivo (ej. si está en 'vistas/modulos/')
// OPCIÓN 1: Si 'modulos' está en la misma carpeta que 'controladores' y 'modelos' (la raíz de tu proyecto):
require_once "controladores/eventolog.controlador.php";
require_once "modelos/eventolog.modelo.php"; 
require_once "controladores/empleados.controlador.php"; // Necesario para buscar el nombre del empleado
require_once "modelos/empleados.modelo.php";

// OPCIÓN 2: Si 'modulos' está dentro de 'vistas/' y 'controladores'/'modelos' están en la raíz (un nivel arriba):
// require_once "../controladores/controlador.eventolog.php";
// require_once "../modelos/eventolog.modelo.php"; 
// require_once "../controladores/controlador.empleados.php"; 
// require_once "../modelos/empleados.modelo.php"; 


// Asegurarse de que la sesión esté iniciada para usar $_SESSION
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Comprobación de permisos de sesión
if(isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok"){
    if($_SESSION["perfil"] == "Especial" || $_SESSION["perfil"] == "Vendedor"){
      echo '<script>window.location = "inicio";</script>';
      return;
    }
} else {
    // Si no hay sesión iniciada, redirige al login o maneja el error
    echo '<script>window.location = "login";</script>'; 
    return;
}

// Llama al controlador para obtener los datos
$item = null;
$valor = null;
$eventosLog = ControladorEventoLog::ctrMostrarEventosLog($item, $valor); 

?>

<div class="content-wrapper">

  <section class="content-header">
    <h1>Registro De Eventos</h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Registro De Eventos</li>
    </ol>
  </section>

  <section class="content">

    <div class="box">
        
    	 
        <div class="box-header with-border">
        <div class="card">
    
        <button class="btn-lg btn-primary" onclick="window.location.href = 'extensiones/fpdf/reporteEventosFPDF.php';">
          <i class="fa fa-file-pdf-o"></i> Reporte de Eventos
        </button>
       
        </div>
      <div class="box-body">
        
        <table class="table table-bordered table-striped dt-responsive tablas" width="100%">
          
          <thead>
            
            <tr>
              <th style="width:10px">Id</th>
              <th>Tipo de Evento</th>
              <th>Descripción</th>
              <th>Empleado</th> 
              <th>Tabla Afectada</th>
              <th>ID Fila Afectada</th>
              <th>Fecha y Hora</th> 
            </tr> 

          </thead>

          <tbody>
            <?php
            // Se verifica si se obtuvieron datos y si es un array no vacío
            if($eventosLog && is_array($eventosLog) && count($eventosLog) > 0){ 
                $i = 1; // Contador para la columna secuencial
                foreach ($eventosLog as $key => $value) {
                    // Formatear la fecha y hora del evento
                    $fechaEvento = "N/A";
                    if (isset($value["timestamp"]) && !empty($value["timestamp"])) {
                         try {
                            $fechaEvento = (new DateTime($value["timestamp"]))->format("d/m/Y H:i:s");
                         } catch (Exception $e) {
                            $fechaEvento = "Fecha inválida";
                         }
                    }
                   
                    // Lógica para obtener el nombre completo del empleado
                    $nombreCompletoEmpleado = "N/A"; 
                    if (isset($value["nombre"]) && isset($value["apellido"]) && !empty($value["nombre"]) && !empty($value["apellido"])) {
                        $nombreCompletoEmpleado = ucwords($value["nombre"] . " " . $value["apellido"]);
                    } else if (isset($value["employee_cedula"]) && !empty($value["employee_cedula"])) {
                        $nombreCompletoEmpleado = "Cédula: " . $value["employee_cedula"] . " (No encontrado)";
                    }
                    
                    // Imprime la fila de la tabla con los datos del evento
                    echo '<tr>
                              <td>'.(isset($value["id"]) ? $value["id"] : "N/A").'</td>
                              <td>'.(isset($value["event_type"]) ? $value["event_type"] : "N/A").'</td>
                              <td>'.(isset($value["description"]) ? $value["description"] : "N/A").'</td>
                              <td>'.$nombreCompletoEmpleado.'</td>
                              <td>'.(isset($value["affected_table"]) ? $value["affected_table"] : "N/A").'</td>
                              <td>'.(isset($value["affected_row_id"]) ? $value["affected_row_id"] : "N/A").'</td>
                              <td>'.$fechaEvento.'</td>
                          </tr>';
                }
            } else {
                // Mensaje si no se encuentran registros en la base de datos
                echo '<tr><td colspan="7" class="text-center">No se encontraron registros de eventos.</td></tr>';
            }
            ?>
          </tbody>

        </table>
        
      </div>
      </div>
    </section>
  </div>

<script>
    // Este script de logout debería estar en tu archivo JS principal (plantilla.js o eventos.js)
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

    // La inicialización de DataTables debe ir en un archivo JS externo (plantilla.js o eventos.js)
    // y solo ejecutarse una vez. Si la tienes en otro lugar, no la dupliques aquí.
    /*
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('.tablas')) { 
            $('.tablas').DataTable({
                "language": {
                    "sProcessing":     "Procesando...",
                    "sLengthMenu":     "Mostrar _MENU_ registros",
                    "sZeroRecords":    "No se encontraron resultados",
                    "sEmptyTable":     "Ningún dato disponible en esta tabla",
                    "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":    "",
                    "sSearch":         "Buscar:",
                    "sUrl":            "",
                    "sInfoThousands":  ",",
                    "sLoadingRecords": "Cargando...",
                    "oPaginate": {
                        "sFirst":    "Primero",
                        "sLast":     "Último",
                        "sNext":     "Siguiente",
                        "sPrevious": "Anterior"
                    },
                    "oAria": {
                        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        }
    });
    */
</script>
<div class="card">
    <div class="card-header">
        Configuración de Registro de Eventos
    </div>
   
</div>

