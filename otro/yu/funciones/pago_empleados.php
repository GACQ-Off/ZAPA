<?php
date_default_timezone_set('America/Caracas');
include "../conexion/conexion.php";
session_start();

function getSingleResult($conn, $query, $params = [], $types = '') {
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt === false) {
        error_log("Error al preparar la consulta: " . $conn->error);
        return false;
    }
    if ($params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row;
}

function addErrorAlert(&$alert, $message) {
    $alert .= '<p class="msg_error">' . htmlspecialchars($message) . '</p>';
    error_log($message);
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: pago_empleados.php");
    exit();
}

$id_usuario_sesion = $_SESSION['id_usuario'];
$alert = '';
$empleado_data = null;
$tasa_dolar = null;
$fondo_actual = null;
$id_tasa_dolar_db = null;

$inputs = [
    'pago_neto' => 0,
    'cantidad_horas_extra' => 0,
    'cantidad_dias_feriados' => 0,
    'bono_ali' => 0,
    'bono_produc' => 0
];

$monto_fijo_deducciones_bs = 33.333;

$tasa_data = getSingleResult($conn, "SELECT id_tasa_dolar, tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1");
if ($tasa_data) {
    $tasa_dolar = floatval($tasa_data['tasa_dolar']);
    $id_tasa_dolar_db = $tasa_data['id_tasa_dolar'];
} else {
    addErrorAlert($alert, "No se encontró la tasa de dólar actual");
}

$fondo_data = getSingleResult($conn, "SELECT fondo FROM fondo WHERE id_usuario = ?", [$id_usuario_sesion], 'i');
$fondo_actual = $fondo_data ? floatval($fondo_data['fondo']) : null;
if (!$fondo_data) {
    addErrorAlert($alert, "No se encontró información de fondos para este usuario");
}

$sso_data = getSingleResult($conn, "SELECT valor_deduccion FROM deducciones WHERE id_deduccion = 1");
$faov_data = getSingleResult($conn, "SELECT valor_deduccion FROM deducciones WHERE id_deduccion = 2");

$deducciones_valores = [
    'sso_porcentaje' => $sso_data ? floatval($sso_data['valor_deduccion']) : 0,
    'faov_porcentaje' => $faov_data ? floatval($faov_data['valor_deduccion']) : 0
];

if (!$sso_data) addErrorAlert($alert, "No se encontró el valor de deducción para SSO");
if (!$faov_data) addErrorAlert($alert, "No se encontró el valor de deducción para FAOV");

if (empty($_REQUEST['Empleado'])) {
    header("Location: ../listas/lista_empleado.php");
    mysqli_close($conn);
    exit();
}

$cedula_empleado = $_REQUEST['Empleado'];
if (!preg_match('/^[VEve]?\d{5,8}$/', $cedula_empleado)) {
    addErrorAlert($alert, "Formato de cédula inválido");
    header("Location: ../listas/lista_empleado.php");
    mysqli_close($conn);
    exit();
}

$empleado_data = getSingleResult($conn,
    "SELECT e.cedula_emple, e.nombre_emp, e.apellido_emp, c.nom_cargo
     FROM empleado e
     INNER JOIN cargo c ON e.id_cargo = c.id_cargo
     WHERE e.cedula_emple = ?",
    [$cedula_empleado], 's'
);

if (!$empleado_data) {
    header("Location: ../listas/lista_empleado.php");
    mysqli_close($conn);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_pago'])) {
    foreach ($inputs as $key => $value) {
        $inputs[$key] = filter_input(INPUT_POST, $key, FILTER_VALIDATE_FLOAT, [
            'options' => ['min_range' => 0]
        ]) ?? 0;
    }

    if ($inputs['pago_neto'] <= 0) {
        addErrorAlert($alert, "El salario base debe ser mayor que cero para procesar el pago.");
    } else {
        $valor_dia = $inputs['pago_neto'] / 7;
        $valor_hora = $valor_dia / 8;
        
        $monto_horas_extra = $inputs['cantidad_horas_extra'] * $valor_hora;
        $monto_dias_feriados = $inputs['cantidad_dias_feriados'] * ($valor_dia * 1.5);
        
        $base_deducciones_usd = ($tasa_dolar > 0) ? $monto_fijo_deducciones_bs / $tasa_dolar : 0;
        
        $sso_total = $base_deducciones_usd * ($deducciones_valores['sso_porcentaje'] / 100);
        $faov_total = $base_deducciones_usd * ($deducciones_valores['faov_porcentaje'] / 100);
        $total_deduc = $sso_total + $faov_total;
        
        $sueldo_neto = $inputs['pago_neto'] + $monto_horas_extra +
                                 $monto_dias_feriados + $inputs['bono_ali'] + $inputs['bono_produc'];
        $sueldo_deduc = $inputs['pago_neto'] - $total_deduc;
        $pago_total = $sueldo_neto - $total_deduc;

        if ($id_tasa_dolar_db === null || $tasa_dolar <= 0) {
            addErrorAlert($alert, "No hay una tasa de dólar válida registrada para procesar el pago.");
        } elseif ($fondo_actual === null) {
            addErrorAlert($alert, "No se encontró información de fondos para procesar el pago.");
        } elseif ($pago_total < 0 && $fondo_actual >= 0) {
            addErrorAlert($alert, "El pago total calculado es negativo (" . number_format($pago_total, 2, ',', '.') . " USD). No se puede procesar.");
        } elseif ($fondo_actual < $pago_total) {
            addErrorAlert($alert, "Fondo insuficiente. Disponible: " . number_format($fondo_actual, 2) .
                                 " USD. Requerido: " . number_format($pago_total, 2) . " USD");
        } else {
            mysqli_begin_transaction($conn);
            try {
                $nuevo_fondo = $fondo_actual - $pago_total;
                $update_fondo = mysqli_prepare($conn, "UPDATE fondo SET fondo = ? WHERE id_usuario = ?");
                mysqli_stmt_bind_param($update_fondo, "di", $nuevo_fondo, $id_usuario_sesion);
                if (!mysqli_stmt_execute($update_fondo)) {
                    throw new Exception("Error al actualizar fondo: " . mysqli_stmt_error($update_fondo));
                }
                mysqli_stmt_close($update_fondo);

                $id_fondo_data = getSingleResult($conn,
                    "SELECT id_fondo FROM fondo WHERE id_usuario = ?",
                    [$id_usuario_sesion], 'i'
                );
                if (!$id_fondo_data) throw new Exception("Error al obtener ID de fondo");
                $id_fondo_db = $id_fondo_data['id_fondo'];

                $sql_insert_nomina = "INSERT INTO nomina (
                    pago_neto, fecha_nom, id_tasa_dolar, id_fondo, cedula_emple,
                    horas_extra, dias_feriados, bono_ali, bono_produc,
                    SSO_total, FAOV_total, sueldo_deduc, total_deduc,
                    sueldo_neto, pago_total
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt_nomina = mysqli_prepare($conn, $sql_insert_nomina);
                if (!$stmt_nomina) throw new Exception("Error al preparar inserción de nómina: " . $conn->error);

                $fecha_nomina_db = date('Y-m-d');
                mysqli_stmt_bind_param($stmt_nomina, "dsiisdddddddddd",
                    $inputs['pago_neto'], $fecha_nomina_db, $id_tasa_dolar_db, $id_fondo_db, $cedula_empleado,
                    $monto_horas_extra, $monto_dias_feriados, $inputs['bono_ali'], $inputs['bono_produc'],
                    $sso_total, $faov_total, $sueldo_deduc, $total_deduc,
                    $sueldo_neto, $pago_total
                );

                if (!mysqli_stmt_execute($stmt_nomina)) {
                    throw new Exception("Error al insertar nómina: " . mysqli_stmt_error($stmt_nomina));
                }

                mysqli_commit($conn);
                
                header("Location: " . $_SERVER['PHP_SELF'] . "?Empleado=" . urlencode($cedula_empleado) . "&pago_exitoso=1");
                exit();

            } catch (Exception $e) {
                mysqli_rollback($conn);
                addErrorAlert($alert, "Error en la transacción: " . $e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar Nómina</title>
    <link rel="stylesheet" href="../assets/css/pago_empleado.css">
    <?php include "../assets/head_gerente.php"?>
    <style>
        .input-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .input-group input[type="number"] {
            flex-grow: 1;
            min-width: 100px;
        }
        .equivalente-bs {
            font-size: 0.9em;
            color: #555;
            padding: 6px 8px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
            white-space: nowrap;
            min-width: 120px;
            text-align: right;
        }
        .calculated-field {
            font-weight: bold;
            color: #0056b3;
        }
        .final-payment {
            color: #28a745;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
            text-align: center;
        }
        .close-btn {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-btn:hover,
        .close-btn:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
        .modal-content h2 {
            color: #28a745;
        }
        .modal-content .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 15px;
        }
        .modal-content .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php"?>
    <form method="post" class="formulario">
        <h2 class="title">Procesar Nómina Empleado:</h2>
        <?php if (!empty($alert)) echo $alert; ?>

        <?php if ($empleado_data): ?>
            <div class="empleado-info">
                <p>Cédula: <span><?= htmlspecialchars($empleado_data['cedula_emple']) ?></span></p>
                <p>Nombre: <span><?= htmlspecialchars($empleado_data['nombre_emp']) ?></span></p>
                <p>Apellido: <span><?= htmlspecialchars($empleado_data['apellido_emp']) ?></span></p>
                <p>Cargo: <span><?= htmlspecialchars($empleado_data['nom_cargo']) ?></span></p>
                <p>Fondo Capital: <span><?= $fondo_actual !== null ? number_format($fondo_actual, 2) . ' USD' : 'No disponible' ?></span></p>
                <p>Tasa Dólar de Referencia: <span><?= $tasa_dolar !== null ? number_format($tasa_dolar, 4) . ' Bs/USD' : 'No disponible' ?></span></p>
                <p>Deducción SSO: <span><?= $deducciones_valores['sso_porcentaje'] ?>%</span></p>
                <p>Deducción FAOV: <span><?= $deducciones_valores['faov_porcentaje'] ?>%</span></p>
                <p style="font-size: 0.9em; color: #31708f;"><i>Las deducciones se calculan sobre una base fija de 130 Bs. (convertida a USD).</i></p>
            </div>

            <fieldset>
                <legend>Ingresos (USD)</legend>
                <div>
                    <label for="pago_neto">Salario Base:</label>
                    <div class="input-group">
                        <input type="number" step="0.00001" name="pago_neto" id="pago_neto" value="<?= htmlspecialchars($inputs['pago_neto']) ?>" required>
                        <span class="equivalente-bs">Bs. <span id="pago_neto_bs_valor">0,00</span></span>
                    </div>
                </div>
                <div>
                    <label for="cantidad_horas_extra">Cantidad de Horas Extra:</label>
                    <div class="input-group">
                        <input type="number" step="1" name="cantidad_horas_extra" id="cantidad_horas_extra" value="<?= htmlspecialchars($inputs['cantidad_horas_extra']) ?>">
                    </div>
                </div>
                <div>
                    <label for="cantidad_dias_feriados">Cantidad de Días Feriados:</label>
                    <div class="input-group">
                        <input type="number" step="1" name="cantidad_dias_feriados" id="cantidad_dias_feriados" value="<?= htmlspecialchars($inputs['cantidad_dias_feriados']) ?>">
                    </div>
                </div>
                <div>
                    <label for="bono_ali">Bono Alimentación:</label>
                    <div class="input-group">
                        <input type="number" step="0.00001" name="bono_ali" id="bono_ali" value="<?= htmlspecialchars($inputs['bono_ali']) ?>">
                        <span class="equivalente-bs">Bs. <span id="bono_ali_bs_valor">0,00</span></span>
                    </div>
                </div>
                <div>
                    <label for="bono_produc">Bono Productividad:</label>
                    <div class="input-group">
                        <input type="number" step="0.00001" name="bono_produc" id="bono_produc" value="<?= htmlspecialchars($inputs['bono_produc']) ?>">
                        <span class="equivalente-bs">Bs. <span id="bono_produc_bs_valor">0,00</span></span>
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend>Cálculos de Nómina (USD)</legend>
                <p>Base para Deducciones (130 Bs. / <span id="tasa_dolar_display"><?= number_format($tasa_dolar, 4, ',', '.') ?></span> Bs/USD): <span class="calculated-field" id="base_deducciones_usd_display">0,0000</span></p>
                <hr>
                <p>Valor por Día de Trabajo (Salario Base / 7): <span class="calculated-field" id="valor_dia_display">0,0000</span></p>
                <p>Cantidad Horas Extra: <span class="calculated-field" id="cantidad_horas_extra_display"><?= htmlspecialchars($inputs['cantidad_horas_extra']) ?></span></p>
                <p>Valor por Hora Extra Ordinaria ((Salario Base / 7) / 8): <span class="calculated-field" id="valor_hora_extra_display">0,0000</span></p>
                <p>Monto Total Horas Extra: <span class="calculated-field" id="monto_horas_extra_display">0,00</span></p>
                <p>Cantidad Días Feriados: <span class="calculated-field" id="cantidad_dias_feriados_display"><?= htmlspecialchars($inputs['cantidad_dias_feriados']) ?></span></p>
                <p>Valor por Día Feriado ((Salario Base / 7) * 1.5): <span class="calculated-field" id="valor_dia_feriado_display">0,0000</span></p>
                <p>Monto Total Días Feriados: <span class="calculated-field" id="monto_dias_feriados_display">0,00</span></p>
                <hr>
                <p>SSO Total (calculado): <span class="calculated-field" id="sso_total_display">0,000</span></p>
                <p>FAOV Total (calculado): <span class="calculated-field" id="faov_total_display">0,000</span></p>
                <p>Total Deducciones (calculado): <span class="calculated-field" id="total_deduc_display">0,000</span></p>
                <hr>
                <p>Sueldo Neto (Salario Base + Asignaciones): <span class="calculated-field" id="sueldo_neto_display">0,00</span></p>
                <p>Sueldo Deduc (Salario Base - Deducciones): <span class="calculated-field" id="sueldo_deduc_display">0,00</span></p>
                <hr>
                <p style="font-weight: bold;">Pago Total Neto (A Pagar): <span class="calculated-field final-payment" id="pago_total_display">0,00</span></p>
                <p>Equivalente en Bolívares (Referencial): <span class="calculated-field" id="pago_total_bs_display">N/A</span> Bs.</p>
            </fieldset>

            <div class="flex-row">
                <input type="hidden" name="Empleado" value="<?= htmlspecialchars($_REQUEST['Empleado']) ?>">
                <button type="button" id="visualizarCalculoBtn" class="btn btn--cancell">Actualizar Cálculos</button>
                <input type="submit" name="procesar_pago" value="Realizar el Pago">
                <a href="../listas/lista_empleado.php" class="btn btn--cancel">Regresar</a>
            </div>
        <?php else: ?>
            <p>No se encontraron datos del empleado.</p>
            <a href="../listas/lista_empleado.php" class="btn btn--cancel">Regresar</a>
        <?php endif; ?>
    </form>
    
    <div id="pagoExitoModal" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Pago Realizado</h2>
            <p>La nómina se ha procesado y el pago se ha realizado con éxito.</p>
            <button id="modal-ok-btn" class="btn">Aceptar</button>
        </div>
    </div>

    <script>
    const tasaDolarActual = <?= $tasa_dolar !== null ? json_encode((float)$tasa_dolar) : 'null' ?>;
    const ssoPorcentaje = <?= json_encode((float)$deducciones_valores['sso_porcentaje']) ?>;
    const faovPorcentaje = <?= json_encode((float)$deducciones_valores['faov_porcentaje']) ?>;
    const montoFijoDeduccionesBs = <?= json_encode((float)$monto_fijo_deducciones_bs) ?>;

    function actualizarEquivalenteBs(inputId, outputSpanId) {
        const inputElement = document.getElementById(inputId);
        const outputElement = document.getElementById(outputSpanId);
        
        if (!inputElement || !outputElement) return;

        if (tasaDolarActual === null || tasaDolarActual <= 0) {
            outputElement.textContent = 'Tasa no disp.';
            return;
        }

        const valorUsd = parseFloat(inputElement.value) || 0;
        const valorBs = valorUsd * tasaDolarActual;
        outputElement.textContent = valorBs.toLocaleString('es-VE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function calculatePayroll() {
        const pagoNeto = parseFloat(document.getElementById('pago_neto').value) || 0;
        const cantidadHorasExtra = parseFloat(document.getElementById('cantidad_horas_extra').value) || 0;
        const cantidadDiasFeriados = parseFloat(document.getElementById('cantidad_dias_feriados').value) || 0;
        const bonoAli = parseFloat(document.getElementById('bono_ali').value) || 0;
        const bonoProduc = parseFloat(document.getElementById('bono_produc').value) || 0;

        actualizarEquivalenteBs('pago_neto', 'pago_neto_bs_valor');
        actualizarEquivalenteBs('bono_ali', 'bono_ali_bs_valor');
        actualizarEquivalenteBs('bono_produc', 'bono_produc_bs_valor');

        const valorDia = pagoNeto / 7;
        const valorHora = valorDia / 8;
        
        const montoHorasExtra = cantidadHorasExtra * valorHora;
        const montoDiasFeriados = cantidadDiasFeriados * (valorDia * 1.5);
        
        const baseDeduccionesUsd = (tasaDolarActual > 0) ? montoFijoDeduccionesBs / tasaDolarActual : 0;
        
        const ssoTotal = baseDeduccionesUsd * (ssoPorcentaje / 100);
        const faovTotal = baseDeduccionesUsd * (faovPorcentaje / 100);
        const totalDeduc = ssoTotal + faovTotal;
        
        const sueldoNeto = pagoNeto + montoHorasExtra + montoDiasFeriados + bonoAli + bonoProduc;
        const sueldoDeduc = pagoNeto - totalDeduc;
        const pagoTotal = sueldoNeto - totalDeduc;

        document.getElementById('base_deducciones_usd_display').textContent = baseDeduccionesUsd.toFixed(4);
        document.getElementById('valor_dia_display').textContent = valorDia.toFixed(4);
        document.getElementById('cantidad_horas_extra_display').textContent = cantidadHorasExtra;
        document.getElementById('valor_hora_extra_display').textContent = valorHora.toFixed(4);
        document.getElementById('monto_horas_extra_display').textContent = montoHorasExtra.toFixed(2);
        document.getElementById('cantidad_dias_feriados_display').textContent = cantidadDiasFeriados;
        document.getElementById('valor_dia_feriado_display').textContent = (valorDia * 1.5).toFixed(4);
        document.getElementById('monto_dias_feriados_display').textContent = montoDiasFeriados.toFixed(2);
        
        document.getElementById('sso_total_display').textContent = ssoTotal.toFixed(3);
        document.getElementById('faov_total_display').textContent = faovTotal.toFixed(3);
        document.getElementById('total_deduc_display').textContent = totalDeduc.toFixed(3);
        
        document.getElementById('sueldo_neto_display').textContent = sueldoNeto.toFixed(2);
        document.getElementById('sueldo_deduc_display').textContent = sueldoDeduc.toFixed(2);
        document.getElementById('pago_total_display').textContent = pagoTotal.toFixed(2);

        if (tasaDolarActual !== null && tasaDolarActual > 0) {
            document.getElementById('pago_total_bs_display').textContent = (pagoTotal * tasaDolarActual).toLocaleString('es-VE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        } else {
            document.getElementById('pago_total_bs_display').textContent = 'N/A';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        ['pago_neto', 'bono_ali', 'bono_produc'].forEach(id => {
            const inputField = document.getElementById(id);
            if (inputField) {
                 actualizarEquivalenteBs(id, `${id}_bs_valor`);
            }
        });

        const inputsToListen = ['pago_neto', 'cantidad_horas_extra', 'cantidad_dias_feriados', 'bono_ali', 'bono_produc'];
        inputsToListen.forEach(id => {
            const inputElement = document.getElementById(id);
            if (inputElement) {
                inputElement.addEventListener('input', calculatePayroll);
            }
        });

        const visualizarCalculoBtn = document.getElementById('visualizarCalculoBtn');
        if (visualizarCalculoBtn) {
            visualizarCalculoBtn.addEventListener('click', calculatePayroll);
        }

        calculatePayroll();

        const modal = document.getElementById("pagoExitoModal");
        const closeBtn = document.querySelector(".close-btn");
        const okBtn = document.getElementById("modal-ok-btn");
        
        function closeModal() {
            modal.style.display = "none";
        }
        
        if(closeBtn) {
            closeBtn.onclick = closeModal;
        }
        
        if(okBtn) {
            okBtn.onclick = function() {
                closeModal();
                window.location.href = `pago_empleados.php?Empleado=<?= urlencode($cedula_empleado) ?>`;
            };
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
                window.location.href = `pago_empleados.php?Empleado=<?= urlencode($cedula_empleado) ?>`;
            }
        }
        
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('pago_exitoso')) {
            modal.style.display = "block";
            history.replaceState(null, '', location.href.split('?')[0] + '?Empleado=<?= urlencode($cedula_empleado) ?>');
        }
    });
    </script>
</body>
</html>