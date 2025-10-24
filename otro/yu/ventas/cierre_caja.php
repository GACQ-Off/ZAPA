<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../ingreso.php");
    exit();
}

$id_usuario_actual = $_SESSION['id_usuario'];
$alertas = ['success' => [], 'error' => []];
$caja_activa = null;
$resumen_pagos = [];
$resumen_ventas = ['numero_ventas' => 0, 'total_ventas_neto' => 0];

$monto_inicial_usd = 0;
$total_efectivo_usd_ventas = 0;
$monto_final_calculado_sistema_usd = 0;
$monto_final_real_usd = 0;
$diferencia_usd = 0;

$monto_inicial_bs = 0;
$total_efectivo_bs_ventas = 0;
$monto_final_calculado_sistema_bs = 0;
$monto_final_real_bs = 0;
$diferencia_bs = 0;

$nombre_usuario_actual = $_SESSION['nombre_usuario'] ?? 'Cajero';


$sql_caja_abierta = "SELECT id_caja, monto_inicial, monto_bs, fecha_apertura, id_usuario FROM caja WHERE estado = 'Abierta' LIMIT 1";
if ($stmt_caja = $conn->prepare($sql_caja_abierta)) {
    $stmt_caja->execute(); // ✅ Sin bind_param (no necesita parámetro de usuario)
    $resultado_caja = $stmt_caja->get_result();
    if ($resultado_caja->num_rows > 0) {
        $caja_activa = $resultado_caja->fetch_assoc();
        $monto_inicial_usd = (float)$caja_activa['monto_inicial'];
        $monto_inicial_bs = (float)$caja_activa['monto_bs'];
    }
    $stmt_caja->close();
}

if ($caja_activa) {
    $id_caja_activa = $caja_activa['id_caja'];

    // El resto del código se mantiene igual...
    $sql_pagos = "SELECT 
                      tp.tipo_pago, 
                      tp.id_tipo_pago,
                      SUM(pv.monto_transaccion) as total_por_metodo
                    FROM pagos_venta pv
                    JOIN ventas v ON pv.id_ventas = v.id_ventas
                    JOIN tipo_pago tp ON pv.id_tipo_pago = tp.id_tipo_pago
                    WHERE v.id_caja = ?
                    GROUP BY pv.id_tipo_pago, tp.tipo_pago
                    ORDER BY tp.tipo_pago";
    if ($stmt_pagos = $conn->prepare($sql_pagos)) {
        $stmt_pagos->bind_param("i", $id_caja_activa);
        $stmt_pagos->execute();
        $res_pagos = $stmt_pagos->get_result();
        while ($fila = $res_pagos->fetch_assoc()) {
            $resumen_pagos[] = $fila;
            if (strpos($fila['tipo_pago'], 'Efectivo (USD)') !== false) {
                $total_efectivo_usd_ventas = (float)$fila['total_por_metodo'];
            } elseif (strpos($fila['tipo_pago'], 'Efectivo (BS)') !== false) {
                $total_efectivo_bs_ventas = (float)$fila['total_por_metodo'];
            }
        }
        $stmt_pagos->close();
    }

    $sql_ventas = "SELECT 
                        COUNT(id_ventas) as numero_ventas,
                        SUM(total_neto_venta) as total_ventas_neto
                       FROM ventas
                       WHERE id_caja = ?";
    if ($stmt_ventas = $conn->prepare($sql_ventas)) {
        $stmt_ventas->bind_param("i", $id_caja_activa);
        $stmt_ventas->execute();
        $res_ventas = $stmt_ventas->get_result()->fetch_assoc();
        if ($res_ventas) {
            $resumen_ventas = $res_ventas;
        }
        $stmt_ventas->close();
    }


    $monto_final_calculado_sistema_usd = $monto_inicial_usd + $total_efectivo_usd_ventas;
    $monto_final_calculado_sistema_bs = $monto_inicial_bs + $total_efectivo_bs_ventas;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cerrar_caja_submit'])) {
    if ($caja_activa) {
        $monto_final_real_usd = filter_input(INPUT_POST, 'monto_final_real_usd', FILTER_VALIDATE_FLOAT);
        $monto_final_real_bs = filter_input(INPUT_POST, 'monto_final_real_bs', FILTER_VALIDATE_FLOAT);

        if ($monto_final_real_usd === false || $monto_final_real_usd < 0 ||
            $monto_final_real_bs === false || $monto_final_real_bs < 0) {
            $alertas['error'][] = "Por favor, introduce montos finales válidos y positivos para ambas monedas.";
        } else {
            $diferencia_usd = $monto_final_real_usd - $monto_final_calculado_sistema_usd;
            $diferencia_bs = $monto_final_real_bs - $monto_final_calculado_sistema_bs;

            $sql_cierre = "UPDATE caja SET 
                    fecha_cierre = NOW(),
                    monto_final_calculado_usd = ?,
                    monto_final_real_usd = ?,
                    diferencia_usd = ?,
                    monto_final_calculado_bs = ?,
                    monto_final_real_bs = ?,
                    diferencia_bs = ?,
                    estado = 'Cerrada',
                    id_usuario_cierre = ?  
               WHERE id_caja = ?"; 

            if ($stmt_cierre = $conn->prepare($sql_cierre)) {
              $stmt_cierre->bind_param("ddddddii",
                     $monto_final_calculado_sistema_usd, $monto_final_real_usd, $diferencia_usd,
                     $monto_final_calculado_sistema_bs, $monto_final_real_bs, $diferencia_bs,
                     $id_usuario_actual, 
                  $id_caja_activa     
              );
                if ($stmt_cierre->execute()) {
                    $alertas['success'][] = "¡Caja cerrada exitosamente! Monto final USD: $" . number_format($monto_final_real_usd, 2) . ", Monto final BS: " . number_format($monto_final_real_bs, 2) . " Bs. Serás redirigido.";
                    header("refresh:3;url=cajero.php");
                    $caja_activa = null; 
                } else {
                    $alertas['error'][] = "Error al cerrar la caja: " . $conn->error;
                }
                $stmt_cierre->close();
            } else {
                $alertas['error'][] = "Error al preparar la consulta de cierre: " . $conn->error;
            }
        }
    } else {
        $alertas['error'][] = "No se encontró una caja abierta para cerrar.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cierre de Caja</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f4f4f4; }
        .container { max-width: 800px; margin-top: 50px; }
        .card-header { background-color: #dc3545; color: white; }
        .summary-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .summary-item strong { color: #333; }
        .diferencia-positiva { color: green; font-weight: bold; }
        .diferencia-negativa { color: red; font-weight: bold; }
        .diferencia-cero { color: blue; font-weight: bold; }
    </style>
</head>
<body>
<?php include "menu_cajero.php"; ?>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Cierre de Caja</h3>
        </div>
        <div class="card-body">
            <?php foreach ($alertas['success'] as $msg): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>
            <?php foreach ($alertas['error'] as $msg): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($msg); ?></div>
            <?php endforeach; ?>

            <?php if ($caja_activa): ?>
                <h4>Resumen de la Sesión</h4>
                <div class="summary-item"><span>Fecha de Apertura:</span> <strong><?php echo date('d/m/Y H:i:s', strtotime($caja_activa['fecha_apertura'])); ?></strong></div>
                <div class="summary-item"><span>Número de Ventas:</span> <strong><?php echo htmlspecialchars($resumen_ventas['numero_ventas']); ?></strong></div>
                <div class="summary-item"><span>Monto Total Vendido (Neto en USD):</span> <strong>$<?php echo number_format($resumen_ventas['total_ventas_neto'], 2); ?></strong></div>
                <hr>

                <h5>Desglose por Método de Pago</h5>
                <?php if (!empty($resumen_pagos)): ?>
                    <?php foreach ($resumen_pagos as $pago):
                        $moneda_simbolo = (strpos($pago['tipo_pago'], '(BS)') !== false) ? 'Bs ' : '$';
                        ?>
                        <div class="summary-item">
                            <span><?php echo htmlspecialchars($pago['tipo_pago']); ?>:</span>
                            <strong><?php echo $moneda_simbolo . number_format($pago['total_por_metodo'], 2); ?></strong>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No se registraron pagos en esta sesión.</p>
                <?php endif; ?>
                <hr>
                
                <form action="cierre_caja.php" method="post">
                    <input type="hidden" name="cerrar_caja_submit" value="1">

                    <h4>Cuadre de Efectivo (USD)</h4>
                    <div class="summary-item"><span>Monto Inicial en Caja (USD):</span> <strong>$<?php echo number_format($monto_inicial_usd, 2); ?></strong></div>
                    <div class="summary-item"><span>(+) Total Ventas en Efectivo (USD):</span> <strong>$<?php echo number_format($total_efectivo_usd_ventas, 2); ?></strong></div>
                    <div class="summary-item"><span>(=) Monto Calculado en Caja (USD):</span> <strong>$<?php echo number_format($monto_final_calculado_sistema_usd, 2); ?></strong></div>
                    
                    <div class="form-group mt-3">
                        <label for="monto_final_real_usd"><strong>Monto Real Contado en Caja (USD):</strong></label>
                        <input type="number" class="form-control" id="monto_final_real_usd" name="monto_final_real_usd" step="0.01" min="0" required value="<?php echo number_format($monto_final_calculado_sistema_usd, 2, '.', ''); ?>">
                    </div>

                    <hr>

                    <h4>Cuadre de Efectivo (BS)</h4>
                    <div class="summary-item"><span>Monto Inicial en Caja (BS):</span> <strong><?php echo number_format($monto_inicial_bs, 2); ?> Bs</strong></div>
                    <div class="summary-item"><span>(+) Total Ventas en Efectivo (BS):</span> <strong><?php echo number_format($total_efectivo_bs_ventas, 2); ?> Bs</strong></div>
                    <div class="summary-item"><span>(=) Monto Calculado en Caja (BS):</span> <strong><?php echo number_format($monto_final_calculado_sistema_bs, 2); ?> Bs</strong></div>

                    <div class="form-group mt-3">
                        <label for="monto_final_real_bs"><strong>Monto Real Contado en Caja (BS):</strong></label>
                        <input type="number" class="form-control" id="monto_final_real_bs" name="monto_final_real_bs" step="0.01" min="0" required value="<?php echo number_format($monto_final_calculado_sistema_bs, 2, '.', ''); ?>">
                    </div>

                    <button type="submit" class="btn btn-danger btn-block mt-4">Cerrar Caja y Finalizar Sesión</button>
                </form>

            <?php else: ?>
                <div class="alert alert-info">
                    No tienes ninguna caja abierta. <a href="caja.php">Haz clic aquí para abrir una.</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const montoRealUsdInput = document.getElementById('monto_final_real_usd');
        const montoRealBsInput = document.getElementById('monto_final_real_bs');
        
        const montoCalculadoUsd = parseFloat(montoRealUsdInput.value);
        const montoCalculadoBs = parseFloat(montoRealBsInput.value);

        function updateDiferencia(inputElement, calculatedAmount, targetDivId) {
            const realAmount = parseFloat(inputElement.value);
            const diferencia = realAmount - calculatedAmount;
            const targetDiv = document.getElementById(targetDivId);
            
            if (targetDiv) {
                let claseDiferencia = '';
                if (diferencia > 0) {
                    claseDiferencia = 'diferencia-positiva';
                } else if (diferencia < 0) {
                    claseDiferencia = 'diferencia-negativa';
                } else {
                    claseDiferencia = 'diferencia-cero';
                }
                
                let simboloMoneda = (inputElement.id === 'monto_final_real_usd') ? '$' : 'Bs ';
                targetDiv.innerHTML = `<span>Diferencia:</span> <strong class="${claseDiferencia}">${simboloMoneda}${diferencia.toFixed(2)}</strong>`;
            }
        }

        const usdCuadreDiv = document.querySelector('h4:contains("Cuadre de Efectivo (USD)")').nextElementSibling.nextElementSibling.nextElementSibling;
        if (usdCuadreDiv && !document.getElementById('diferencia_usd_display')) {
            const div = document.createElement('div');
            div.className = 'summary-item';
            div.id = 'diferencia_usd_display';
            usdCuadreDiv.parentNode.insertBefore(div, usdCuadreDiv.nextSibling);
        }

        const bsCuadreDiv = document.querySelector('h4:contains("Cuadre de Efectivo (BS)")').nextElementSibling.nextElementSibling.nextElementSibling;
        if (bsCuadreDiv && !document.getElementById('diferencia_bs_display')) {
            const div = document.createElement('div');
            div.className = 'summary-item';
            div.id = 'diferencia_bs_display';
            bsCuadreDiv.parentNode.insertBefore(div, bsCuadreDiv.nextSibling);
        }

        montoRealUsdInput.addEventListener('input', function() {
            updateDiferencia(montoRealUsdInput, montoCalculadoUsd, 'diferencia_usd_display');
        });
        montoRealBsInput.addEventListener('input', function() {
            updateDiferencia(montoRealBsInput, montoCalculadoBs, 'diferencia_bs_display');
        });

        updateDiferencia(montoRealUsdInput, montoCalculadoUsd, 'diferencia_usd_display');
        updateDiferencia(montoRealBsInput, montoCalculadoBs, 'diferencia_bs_display');
    });

    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
    }
    if (!Element.prototype.closest) {
        Element.prototype.closest = function(s) {
            var el = this;
            do {
                if (el.matches(s)) return el;
                el = el.parentElement || el.parentNode;
            } while (el !== null && el.nodeType === 1);
            return null;
        };
    }
</script>
</body>
</html>