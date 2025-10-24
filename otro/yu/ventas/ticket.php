<?php
require_once '../conexion/conexion.php';

// Validar ID de venta
$id_venta = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id_venta <= 0) {
    die("
        <html>
        <body style='font-family: Arial, sans-serif; padding: 20px; text-align: center;'>
            <h3 style='color: red;'>Error: No se recibió un ID de venta válido.</h3>
            <p>Por favor, verifique el enlace o contacte al administrador.</p>
            <p><a href='cajero.php'>Volver al Cajero</a></p>
        </body>
        </html>
    ");
}

try {
    // Consulta los datos principales de la venta (SIN telefono_empresa)
    // Consulta los datos principales de la venta (SIN telefono_empresa)
$stmt = $conn->prepare("SELECT 
    v.id_ventas, v.subtotal_venta, v.total_iva_venta, v.total_neto_venta, 
    v.fecha_venta, v.estado_venta,
    e.nombre_empresa, e.RIF_empresa, e.direccion_empresa,
    COALESCE(cg.nombre, cm.nombre, 'Cliente Ocasional') as nombre_cliente,
    COALESCE(cg.cedula, cm.cedula_identidad, 'N/A') as identificacion_cliente
FROM ventas v
CROSS JOIN empresa e  -- Une con el único registro de empresa
LEFT JOIN cliente_generico cg ON v.id_cliente_generico = cg.id_cliente_generico
LEFT JOIN cliente_mayor cm ON v.id_cliente_mayor = cm.id_cliente_mayor
WHERE v.id_ventas = ?");
    
    if (!$stmt) {
        throw new Exception("Error al preparar la consulta: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id_venta);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
    }
    
    $res = $stmt->get_result();
    if (!$res || $res->num_rows === 0) {
        throw new Exception("Venta #{$id_venta} no encontrada en la base de datos.");
    }
    
    $venta = $res->fetch_assoc();
    $stmt->close();

    // Validar datos críticos
   $campos_requeridos = [
    'subtotal_venta', 'total_iva_venta', 'total_neto_venta',
    'nombre_empresa', 'RIF_empresa', 'direccion_empresa', 'fecha_venta'
];
    
    foreach ($campos_requeridos as $campo) {
        if (!isset($venta[$campo]) || $venta[$campo] === null) {
            throw new Exception("Campo requerido '{$campo}' no está disponible.");
        }
    }

    // Consultar productos vendidos con más detalles
    $stmt = $conn->prepare("SELECT 
        dv.cantidad_vendida, 
        p.nombre_producto, 
        p.codigo,
        dv.precio_unitario_venta_sin_iva,
        dv.subtotal_linea_sin_iva,
        dv.porcentaje_iva_aplicado,
        dv.monto_iva_linea,
        dv.total_linea_con_iva
    FROM detalle_venta dv
    JOIN producto p ON p.id_pro = dv.id_pro
    WHERE dv.id_ventas = ?
    ORDER BY dv.id_detalle_venta");
    
    if (!$stmt) {
        throw new Exception("Error al preparar consulta de productos: " . $conn->error);
    }
    
    $stmt->bind_param("i", $id_venta);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al ejecutar consulta de productos: " . $stmt->error);
    }
    
    $res_items = $stmt->get_result();
    $items = [];
    $total_items = 0;
    
    while ($row = $res_items->fetch_assoc()) {
        $items[] = [
            'nombre' => $row['nombre_producto'],
            'codigo' => $row['codigo'],
            'cant' => $row['cantidad_vendida'],
            'precio_unitario' => $row['precio_unitario_venta_sin_iva'],
            'subtotal_sin_iva' => $row['subtotal_linea_sin_iva'],
            'iva_porcentaje' => $row['porcentaje_iva_aplicado'],
            'iva_monto' => $row['monto_iva_linea'],
            'subtotal_con_iva' => $row['total_linea_con_iva']
        ];
        $total_items += $row['cantidad_vendida'];
    }
    $stmt->close();

    if (count($items) === 0) {
        throw new Exception("No hay productos registrados en la venta #{$id_venta}.");
    }

    // Consultar tasa de dólar actual
    $tasa_dolar_actual = 0;
    $tasa_stmt = $conn->query("SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC, id_tasa_dolar DESC LIMIT 1");
    if ($tasa_stmt && $tasa_row = $tasa_stmt->fetch_assoc()) {
        $tasa_dolar_actual = floatval($tasa_row['tasa_dolar']);
    }
    if ($tasa_dolar_actual <= 0) {
        $tasa_dolar_actual = 1.0; // Valor por defecto para evitar divisiones por cero
    }

    // Consultar métodos de pago si existen
    $pagos = [];
    $pago_stmt = $conn->prepare("SELECT 
        tp.tipo_pago, 
        pv.monto_transaccion,
        pv.codigo_moneda_transaccion,
        pv.referencia_pago
    FROM pagos_venta pv
    JOIN tipo_pago tp ON pv.id_tipo_pago = tp.id_tipo_pago
    WHERE pv.id_ventas = ?");
    
    if ($pago_stmt) {
        $pago_stmt->bind_param("i", $id_venta);
        $pago_stmt->execute();
        $pago_res = $pago_stmt->get_result();
        while ($pago_row = $pago_res->fetch_assoc()) {
            $pagos[] = $pago_row;
        }
        $pago_stmt->close();
    }

    // Preparar datos para la vista
    $total_neto = floatval($venta['total_neto_venta']);
    $iva_total = floatval($venta['total_iva_venta']);
    $subtotal_general = floatval($venta['subtotal_venta']);

    $nombre_negocio = htmlspecialchars($venta['nombre_empresa']);
    $rif_negocio = htmlspecialchars($venta['RIF_empresa']);
    $direccion_negocio = htmlspecialchars($venta['direccion_empresa']);
    $fecha_venta = $venta['fecha_venta'];
    $estado_venta = $venta['estado_venta'];
    $nombre_cliente = htmlspecialchars($venta['nombre_cliente']);
    $identificacion_cliente = htmlspecialchars($venta['identificacion_cliente']);

} catch (Exception $e) {
    die("
        <html>
        <body style='font-family: Arial, sans-serif; padding: 20px; text-align: center;'>
            <h3 style='color: red;'>Error al generar ticket</h3>
            <p><strong>Detalles:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <p>Venta ID: #" . htmlspecialchars($id_venta) . "</p>
            <p>Por favor, contacte al administrador del sistema.</p>
            <p><a href='cajero.php'>Volver al Cajero</a></p>
        </body>
        </html>
    ");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=58mm, initial-scale=1.0">
    <title>Ticket de Venta #<?php echo $id_venta; ?> - <?php echo $nombre_negocio; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 9px;
            width: 58mm;
            margin: 0 auto;
            padding: 3px;
            line-height: 1.2;
        }
        .ticket-container {
            width: 100%;
            max-width: 58mm;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 11px;
            font-weight: bold;
            margin: 2px 0;
        }
        .header p {
            margin: 1px 0;
        }
        .separator {
            border-bottom: 1px dashed #000;
            margin: 4px 0;
        }
        .double-separator {
            border-bottom: 2px double #000;
            margin: 5px 0;
        }
        .item-list {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin: 3px 0;
        }
        .item-list th {
            border-bottom: 1px solid #000;
            padding: 2px 1px;
            text-align: left;
            font-weight: bold;
        }
        .item-list td {
            padding: 1px;
            vertical-align: top;
        }
        .item-name {
            max-width: 35mm;
            word-wrap: break-word;
        }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .small { font-size: 7px; }
        .info-section {
            margin: 3px 0;
        }
        .summary-table {
            width: 100%;
            margin: 3px 0;
        }
        .summary-table td {
            padding: 1px 0;
        }
        .total-amount {
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            margin: 5px 0;
            padding: 3px;
            border: 1px solid #000;
        }
        .pago-info {
            background-color: #f0f0f0;
            padding: 2px;
            margin: 2px 0;
            border-radius: 2px;
        }
        .estado-venta {
            padding: 2px;
            margin: 2px 0;
            border-radius: 2px;
            text-align: center;
            font-weight: bold;
        }
        .estado-completada { background-color: #d4edda; color: #155724; }
        .estado-pendiente { background-color: #fff3cd; color: #856404; }
        .estado-credito { background-color: #cce5ff; color: #004085; }
        
        @media print {
            body {
                margin: 0;
                padding: 2px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <!-- Encabezado -->
        <div class="header">
            <h1><?php echo $nombre_negocio; ?></h1>
            <p class="small"><?php echo $direccion_negocio; ?></p>
            <p class="small">RIF: <?php echo $rif_negocio; ?></p>
            <p class="small">Tel: N/A</p>
        </div>

        <div class="separator"></div>

        <!-- Información de la venta -->
        <div class="info-section">
            <p><strong>TICKET DE VENTA: #<?php echo $id_venta; ?></strong></p>
            <p>Fecha: <?php echo date("d/m/Y H:i A", strtotime($fecha_venta)); ?></p>
            <p>Cliente: <?php echo $nombre_cliente; ?></p>
            <p>ID: <?php echo $identificacion_cliente; ?></p>
            
            <!-- Estado de la venta -->
            <div class="estado-venta estado-<?php echo strtolower(str_replace(' ', '-', $estado_venta)); ?>">
                Estado: <?php echo $estado_venta; ?>
            </div>
        </div>

        <div class="separator"></div>

        <!-- Lista de productos -->
        <table class="item-list">
            <thead>
                <tr>
                    <th class="text-left">Producto</th>
                    <th class="text-right">Cant.</th>
                    <th class="text-right">P.Unit</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $item): ?>
                <tr>
                    <td class="item-name">
                        <?php echo htmlspecialchars($item['nombre']); ?>
                        <br><span class="small">Cod: <?php echo htmlspecialchars($item['codigo']); ?></span>
                        <?php if ($item['iva_porcentaje'] > 0): ?>
                        <br><span class="small">IVA: <?php echo $item['iva_porcentaje']; ?>%</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right"><?php echo number_format($item['cant'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($item['precio_unitario'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($item['subtotal_con_iva'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="separator"></div>

        <!-- Resumen de la venta -->
        <table class="summary-table">
            <tr>
                <td class="text-left">Subtotal (USD):</td>
                <td class="text-right"><?php echo number_format($subtotal_general, 2, '.', ','); ?></td>
            </tr>
            <tr>
                <td class="text-left">IVA (USD):</td>
                <td class="text-right"><?php echo number_format($iva_total, 2, '.', ','); ?></td>
            </tr>
            <tr class="bold">
                <td class="text-left">TOTAL (USD):</td>
                <td class="text-right"><?php echo number_format($total_neto, 2, '.', ','); ?></td>
            </tr>
            <tr class="bold">
                <td class="text-left">TOTAL (BS):</td>
                <td class="text-right"><?php echo number_format($total_neto * $tasa_dolar_actual, 2, '.', ','); ?></td>
            </tr>
            <tr>
                <td colspan="2" class="text-center small">
                    Tasa: <?php echo number_format($tasa_dolar_actual, 2); ?> BS/USD
                </td>
            </tr>
        </table>

        <!-- Información de pagos -->
        <?php if (!empty($pagos)): ?>
        <div class="separator"></div>
        <div class="info-section">
            <p class="bold">MÉTODOS DE PAGO:</p>
            <?php foreach ($pagos as $pago): ?>
            <div class="pago-info small">
                <?php echo htmlspecialchars($pago['tipo_pago']); ?>: 
                <?php echo number_format($pago['monto_transaccion'], 2); ?> 
                <?php echo $pago['codigo_moneda_transaccion']; ?>
                <?php if (!empty($pago['referencia_pago'])): ?>
                <br>Ref: <?php echo htmlspecialchars($pago['referencia_pago']); ?>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="double-separator"></div>

        <!-- Pie de página -->
        <div class="footer">
            <p class="bold">¡Gracias por su compra!</p>
            <p class="small">Venta #<?php echo $id_venta; ?></p>
            <p class="small">Total items: <?php echo $total_items; ?></p>
            <p class="small"><?php echo date('d/m/Y H:i:s'); ?></p>
        </div>

        <!-- Botón de impresión (solo visible en navegador) -->
        <div class="no-print" style="text-align: center; margin-top: 10px;">
            <button onclick="window.print()" style="padding: 5px 10px; font-size: 10px;">
                🖨️ Imprimir Ticket
            </button>
            <button onclick="window.close()" style="padding: 5px 10px; font-size: 10px; margin-left: 5px;">
                ❌ Cerrar
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-imprimir si se solicita
            const urlParams = new URLSearchParams(window.location.search);
            const autoPrint = urlParams.get('imprimir');
            
            if (autoPrint === '1') {
                setTimeout(() => {
                    window.print();
                }, 500);
            }

            // Auto-cerrar después de imprimir
            window.onafterprint = function() {
                setTimeout(() => {
                    if (urlParams.get('cerrar') === '1') {
                        window.close();
                    }
                }, 1000);
            };
        });
    </script>
</body>
</html>