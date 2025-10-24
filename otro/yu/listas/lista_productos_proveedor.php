<?php
session_start();
include('../conexion/conexion.php');
include('../assets/head_gerente.php');

if ($conn->connect_error) {
    die("Error crítico de conexión a la base de datos: " . $conn->connect_error);
}

$rif_proveedor = isset($_GET['rif']) ? $conn->real_escape_string($_GET['rif']) : null;
$nombre_proveedor = "Desconocido";
$productos_proveedor = [];
$tasaCambio = 0;
$error_tasa = "";

$sql_tasa = "SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$result_tasa = $conn->query($sql_tasa);

if ($result_tasa && $result_tasa->num_rows > 0) {
    $tasa_row = $result_tasa->fetch_assoc();
    $tasaCambio = (float)$tasa_row['tasa_dolar'];
    if ($tasaCambio <= 0) {
        $error_tasa = "Advertencia: La tasa de cambio obtenida no es válida (Bs. " . htmlspecialchars($tasa_row['tasa_dolar']) . "). Se usará 0 para cálculos en VES.";
        $tasaCambio = 0; 
    }
} else {
    $error_tasa = "Error: No se encontró una tasa de cambio. Por favor, registre una tasa válida. Se usará 0 para cálculos en VES.";
}


if ($rif_proveedor) {
    $sql_nombre_prov = "SELECT nombre_provedor FROM proveedor WHERE RIF = '$rif_proveedor'";
    $result_nombre_prov = $conn->query($sql_nombre_prov);
    if ($result_nombre_prov && $result_nombre_prov->num_rows > 0) {
        $row_nombre = $result_nombre_prov->fetch_assoc();
        $nombre_proveedor = $row_nombre['nombre_provedor'];
    }

    $sql_productos = "SELECT 
                        p.id_pro,
                        p.nombre_producto,
                        p.codigo AS codigo_producto,
                        c.nombre_categoria,
                        pp.cantidad_compra,
                        pp.costo_compra,
                        pp.fecha AS fecha_compra
                      FROM 
                        producto_proveedor pp
                      JOIN 
                        producto p ON pp.id_pro = p.id_pro
                      LEFT JOIN 
                        categoria c ON p.id_categoria = c.id_categoria
                      WHERE 
                        pp.RIF = '$rif_proveedor' AND p.estado_producto = 1
                      ORDER BY 
                        pp.fecha DESC, p.nombre_producto ASC";

    $result_productos = $conn->query($sql_productos);
    if ($result_productos) {
        while ($row = $result_productos->fetch_assoc()) {
            $productos_proveedor[] = $row;
        }
    } else {
        echo "<p class='mensaje-error'>Error al consultar los productos del proveedor: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    echo "<p class='mensaje-error'>No se especificó un RIF de proveedor.</p>";
}

?>
<link rel="stylesheet" href="../assets/css/lista_productos_proveedor.css"> 

<body>
    <?php include('../assets/lista_gerente.php'); ?>
    <div class="container">
        <h1>Productos Suministrados por: <?php echo htmlspecialchars($nombre_proveedor); ?></h1>
        <p>RIF: <?php echo htmlspecialchars($rif_proveedor); ?></p>

        <?php if (!empty($error_tasa)): ?>
            <p class="mensaje-advertencia"><?php echo $error_tasa; ?></p>
        <?php endif; ?>

        <?php if ($rif_proveedor && !empty($productos_proveedor)): ?>

            <table>
                <thead>
                    <tr>
                        <th>ID Producto</th>
                        <th>Nombre Producto</th>
                        <th>Código</th>
                        <th>Categoría</th>
                        <th>Cantidad Comprada</th>
                        <th>Costo Compra (USD)</th>
                        <?php if ($tasaCambio > 0): ?>
                        <th>Costo Compra (VES)</th>
                        <?php endif; ?>
                        <th>Fecha Compra</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos_proveedor as $producto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['id_pro']); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre_producto']); ?></td>
                            <td><?php echo htmlspecialchars($producto['codigo_producto'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre_categoria'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($producto['cantidad_compra']); ?></td>
                            <td><?php echo htmlspecialchars(number_format((float)$producto['costo_compra'], 2)); ?></td>
                            <?php if ($tasaCambio > 0): 
                                $costo_ves = (float)$producto['costo_compra'] * $tasaCambio;
                            ?>
                            <td><?php echo htmlspecialchars(number_format($costo_ves, 2)); ?></td>
                            <?php endif; ?>
                            <td><?php echo htmlspecialchars(date("d-m-Y", strtotime($producto['fecha_compra']))); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($rif_proveedor): ?>
            <p>No se encontraron productos para este proveedor o el proveedor no tiene productos activos registrados.</p>
        <?php endif; ?>
        <?php if ($rif_proveedor && !empty($productos_proveedor)): ?>
            <div class="resumen-productos" style="margin-top: 20px; padding: 10px; background-color: #e9ecef; border-radius: 5px; text-align: center;">
                <p><strong>Total de registros de productos suministrados por este proveedor: <?php echo count($productos_proveedor); ?></strong></p>
            </div>
        <?php endif; ?>
        <div style="margin-top: 20px;">
            <a href="lista_provedor.php" class="btn-regresar">Regresar a Lista de Proveedores</a>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>
</html>

