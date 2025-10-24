<?php
// INICIO: CONFIGURACIÓN DE DEPURACIÓN (DESACTIVAR EN PRODUCCIÓN)
ini_set("display_errors", 1);
error_reporting(E_ALL);
// FIN: CONFIGURACIÓN DE DEPURACIÓN

// **MUY IMPORTANTE:** Ajusta estas rutas según la ubicación de este archivo (ej. si está en 'vistas/modulos/')

// Incluir el controlador del historial del dólar
require_once "controladores/historialDolar.controlador.php";
require_once "modelos/historialDolar.modelo.php";


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
    echo '<script>window.location = "login";</script>';
    return;
}

// Llama al controlador para obtener los datos del historial del dólar
$historialDolar = ControladorHistorialDolar::ctrMostrarHistorialDolarCompleto();

?>

<div class="content-wrapper">

  <section class="content-header">
    <h1>
      Historial Precio del Dólar
      <small>Control de la tasa de cambio histórica</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
      <li class="active">Historial Precio del Dólar</li>
    </ol>
  </section>

  <section class="content">

    <div class="box">
        <div class="box-header with-border">
  
        <button class="btn-lg btn-primary" onclick="window.location.href = 'extensiones/fpdf/reporte-historial-dolar.php';">
          <i class="fa fa-file-pdf-o"></i> Reporte  Historial Precio del Dólar
        </button>
        


      </div>

      <div class="box-body">

        <table class="table table-bordered table-striped dt-responsive tablas" width="100%">

          <thead>
            <tr>
              <th style="width:10px">Id</th>
              <th>Precio Actualizado</th>
              <th>Precio Anterior</th>
              <th>Estado</th>
              <th>Fecha y Hora</th>
            </tr>
          </thead>

          <tbody>
            <?php
            if ($historialDolar && is_array($historialDolar) && count($historialDolar) > 0) {
                $i = 1;
                foreach ($historialDolar as $key => $value) {
                    $precio_actual = floatval($value["precio_dolar"]);
                    // Se usa precio_anterior de la BD
                    $precio_anterior = $value["precio_anterior"] !== null ? number_format(floatval($value["precio_anterior"]), 2, ',', '.') : "N/A";
                    // Se usa estado_cambio de la BD
                    $estado_cambio_texto = $value["estado_cambio"] ?? "N/A";
                    $clase_estado = "";
                    $icono_estado = "";

                    if ($estado_cambio_texto == "Subió") {
                        $clase_estado = "text-success";
                        $icono_estado = "<i class='fa fa-arrow-up'></i>";
                    } elseif ($estado_cambio_texto == "Bajó") {
                        $clase_estado = "text-danger";
                        $icono_estado = "<i class='fa fa-arrow-down'></i>";
                    } elseif ($estado_cambio_texto == "Sin Cambios") {
                        $clase_estado = "text-info";
                        $icono_estado = "<i class='fa fa-arrows-h'></i>";
                    } elseif ($estado_cambio_texto == "Inicial") {
                        $clase_estado = "text-muted";
                        $icono_estado = ""; // No hay ícono para estado inicial o N/A
                    }

                    echo '<tr>
                            <td>'.($i++).'</td>
                            <td>'.number_format($precio_actual, 2, ',', '.').'</td>
                            <td>'.$precio_anterior.'</td>
                            <td class="'.$clase_estado.'">'.$estado_cambio_texto.' '.$icono_estado.'</td>
                            <td>'.$value["fecha_cambio"].'</td>
                          </tr>';
                }
            } else {
                echo '<tr><td colspan="5">No hay registros en el historial del dólar.</td></tr>';
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

    /*
    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('.tablas')) {
            $('.tablas').DataTable({
                "language": {
                    "sProcessing":       "Procesando...",
                    "sLengthMenu":       "Mostrar _MENU_ registros",
                    "sZeroRecords":      "No se encontraron resultados",
                    "sEmptyTable":       "Ningún dato disponible en esta tabla",
                    "sInfo":             "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
                    "sInfoEmpty":        "Mostrando registros del 0 al 0 de un total de 0",
                    "sInfoFiltered":     "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":      "",
                    "sSearch":           "Buscar:",
                    "sUrl":              "",
                    "sInfoThousands":    ",",
                    "sLoadingRecords":   "Cargando...",
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