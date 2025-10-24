<?php
session_start();
include('../assets/librerias/simple_html_dom.php');
include('../conexion/conexion.php');

date_default_timezone_set('America/Caracas');

$tabla_tasa = "tasa_dolar";
$columna_tasa = "tasa_dolar";
$columna_fecha = "fecha_dolar";
$columna_id_usuario = "id_usuario";

$hora_inicio_manana_24 = 6;
$hora_fin_manana_24 = 12;
$hora_inicio_tarde_24 = 13;
$hora_fin_tarde_24 = 23;

$mensaje_modal = '';
$tipo_modal = '';

function formatearHora12($hora24) {
    $horas = $hora24 % 12;
    $horas = $horas === 0 ? 12 : $horas;
    $periodo = $hora24 < 12 ? 'am' : 'pm';
    return $horas . $periodo;
}

function obtenerTasaBCV() {
    $url = 'https://www.bcv.org.ve/';
    $html = @file_get_html($url);
    if ($html) {
        $elemento_tasa = $html->find('#dolar .recuadrotsmc .col-sm-6.col-xs-6.centrado strong', 0);
        if ($elemento_tasa) {
            $tasa_texto = trim($elemento_tasa->plaintext);
            preg_match('/[0-9,.]+/', $tasa_texto, $matches);
            if (isset($matches[0])) {
                $tasa = floatval(str_replace(',', '.', $matches[0]));
                $html->clear();
                return $tasa;
            } else {
                $html->clear();
                return false;
            }
        } else {
            $html->clear();
            return false;
        }
    } else {
        return false;
    }
}

$tasa_bcv = obtenerTasaBCV();

if ($tasa_bcv !== false) {
    $fecha_hora_vzla = new DateTime('now', new DateTimeZone('America/Caracas'));
    $fecha_actual_vzla = $fecha_hora_vzla->format('Y-m-d');
    $hora_actual_vzla = intval($fecha_hora_vzla->format('H'));
    $id_usuario_sesion = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;

    $es_manana = ($hora_actual_vzla >= $hora_inicio_manana_24 && $hora_actual_vzla < $hora_fin_manana_24);
    $es_tarde = ($hora_actual_vzla >= $hora_inicio_tarde_24 && $hora_actual_vzla <= $hora_fin_tarde_24);

    $periodo_registro = "";

    if ($es_manana) {
        $periodo_registro = "mañana";
    } elseif ($es_tarde) {
        $periodo_registro = "tarde";
    } else {
        $hora_inicio_manana_12 = formatearHora12($hora_inicio_manana_24);
        $hora_fin_manana_12 = formatearHora12($hora_fin_manana_24 - 1);
        $hora_inicio_tarde_12 = formatearHora12($hora_inicio_tarde_24);
        $hora_fin_tarde_12 = formatearHora12($hora_fin_tarde_24);
        $mensaje_modal = 'Solo se permite registrar la tasa en horarios de la mañana (' . $hora_inicio_manana_12 . ' - ' . $hora_fin_manana_12 . ') o en la tarde (' . $hora_inicio_tarde_12 . ' - ' . $hora_fin_tarde_12 . ').';
        $tipo_modal = 'error';
    }
    
    if (empty($mensaje_modal)) {
        $sql_verificar = "SELECT id_tasa_dolar, $columna_fecha FROM $tabla_tasa
                          WHERE DATE(CONVERT_TZ($columna_fecha, @@session.time_zone, '+04:00')) = ?
                            AND (
                                (HOUR(CONVERT_TZ($columna_fecha, @@session.time_zone, '+04:00')) >= ? AND HOUR(CONVERT_TZ($columna_fecha, @@session.time_zone, '+04:00')) < ?) OR
                                (HOUR(CONVERT_TZ($columna_fecha, @@session.time_zone, '+04:00')) >= ? AND HOUR(CONVERT_TZ($columna_fecha, @@session.time_zone, '+04:00')) <= ?)
                            )";
        $stmt_verificar = $conn->prepare($sql_verificar);

        if ($stmt_verificar) {
            $stmt_verificar->bind_param("siiii", $fecha_actual_vzla, $hora_inicio_manana_24, $hora_fin_manana_24, $hora_inicio_tarde_24, $hora_fin_tarde_24);
            $stmt_verificar->execute();
            $result_verificar = $stmt_verificar->get_result();
            $contador_periodo = 0;
            while ($row = $result_verificar->fetch_assoc()) {
                $fecha_registro = new DateTime($row['fecha_dolar'], new DateTimeZone(date_default_timezone_get()));
                $fecha_registro->setTimezone(new DateTimeZone('America/Caracas'));
                $hora_registro = intval($fecha_registro->format('H'));

                if ($periodo_registro === "mañana" && $hora_registro >= $hora_inicio_manana_24 && $hora_registro < $hora_fin_manana_24) {
                    $contador_periodo++;
                } elseif ($periodo_registro === "tarde" && $hora_registro >= $hora_inicio_tarde_24 && $hora_registro <= $hora_fin_tarde_24) {
                    $contador_periodo++;
                }
            }

            if ($contador_periodo == 0) {
                $sql_registrar = "INSERT INTO $tabla_tasa ($columna_tasa, $columna_fecha, $columna_id_usuario) VALUES (?, ?, ?)";
                $stmt_registrar = $conn->prepare($sql_registrar);
                if ($stmt_registrar) {
                    $stmt_registrar->bind_param("dsi", $tasa_bcv, $fecha_actual, $id_usuario_sesion);
                    if ($stmt_registrar->execute()) {
                        $periodo_registro_formateado = ($periodo_registro === "mañana") ? "mañana" : "tarde";
                        $mensaje_modal = "Tasa del dólar BCV registrada para la " . $periodo_registro_formateado . ": " . $tasa_bcv;
                        $tipo_modal = 'exito';
                    } else {
                        $mensaje_modal = "Error al registrar la tasa: " . $stmt_registrar->error;
                        $tipo_modal = 'error';
                    }
                    $stmt_registrar->close();
                } else {
                    $mensaje_modal = "Error al preparar la consulta de registro: " . $conn->error;
                    $tipo_modal = 'error';
                }
            } else {
                $periodo_registro_formateado = ($periodo_registro === "mañana") ? "mañana" : "tarde";
                $mensaje_modal = "Ya se ha registrado una tasa para el periodo de la " . $periodo_registro_formateado . " hoy. No se permiten más registros en este periodo.";
                $tipo_modal = 'error';
            }
            $stmt_verificar->close();
        } else {
            $mensaje_modal = 'Error al preparar la consulta de verificación: ' . $conn->error;
            $tipo_modal = 'error';
        }
    }
} else {
    $mensaje_modal = 'Error al obtener la tasa del dólar BCV. Por favor, revisa los logs del servidor para más detalles.';
    $tipo_modal = 'error';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/registrar_cargo.css">
    <title>Registro de Tasa</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            text-align: center;
            position: relative;
            max-width: 400px;
        }

        .modal-header.exito {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }

        .modal-header.error {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border-radius: 8px 8px 0 0;
        }

        .modal-body {
            padding: 20px;
            text-align: center;
        }
        
        .modal-footer {
            padding: 10px;
            text-align: right;
        }

        .modal-footer .btn {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal-footer .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"?>
<?php
if (!empty($mensaje_modal)) {
    echo '
    <div id="miModal" class="modal" style="display: block;">
        <div class="modal-content">
            <div class="modal-header ' . $tipo_modal . '">
                <h2>' . ($tipo_modal === 'exito' ? 'Éxito' : 'Error') . '</h2>
            </div>
            <div class="modal-body">
                <p>' . htmlspecialchars($mensaje_modal) . '</p>
            </div>
            <div class="modal-footer">
                <button class="btn" onclick="cerrarModal()">Aceptar</button>
            </div>
        </div>
    </div>';
}
?>

<script>
    function cerrarModal() {
        var modal = document.getElementById('miModal');
        modal.style.display = 'none';
        window.location.href = '../registrar/registro_tasa_dolar.php';
    }
</script>
<script src="../assets/js/busqueda.js"></script>
</body>
</html>