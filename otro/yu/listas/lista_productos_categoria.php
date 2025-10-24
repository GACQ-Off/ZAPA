<?php
session_start();
require_once '../conexion/conexion.php';
include('../assets/head_gerente.php');

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    die("Error crítico de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido"));
}

$id_categoria = isset($_GET['id']) ? (int)$_GET['id'] : null;
$nombre_categoria = "Desconocida";
$productos_categoria = [];
$tasaCambio = 1.0; 
$mensaje_tasa = "";

if ($id_categoria) {
    $sql_nombre_cat = "SELECT nombre_categoria FROM categoria WHERE id_categoria = ?";
    $stmt_nombre_cat = $conn->prepare($sql_nombre_cat);
    if ($stmt_nombre_cat) {
        $stmt_nombre_cat->bind_param("i", $id_categoria);
        $stmt_nombre_cat->execute();
        $result_nombre = $stmt_nombre_cat->get_result();
        if ($result_nombre && $result_nombre->num_rows > 0) {
            $row_nombre = $result_nombre->fetch_assoc();
            $nombre_categoria = $row_nombre['nombre_categoria'];
        }
        $stmt_nombre_cat->close();
    }
}

$sqlTasa = "SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$resultTasa = $conn->query($sqlTasa);

if ($resultTasa && $resultTasa->num_rows > 0) {
    $rowTasa = $resultTasa->fetch_assoc();
    $tasaObtenida = floatval(str_replace(',', '.', $rowTasa['tasa_dolar']));
    if ($tasaObtenida > 0) {
        $tasaCambio = $tasaObtenida;
    } else {
        $mensaje_tasa = "<p class='mensaje-advertencia'>Advertencia: Tasa de cambio inválida (<= 0). Usando tasa predeterminada 1.0.</p>";
    }
} else {
    if ($resultTasa === false) {
        $mensaje_tasa = "<p class='mensaje-error'>Error al obtener tasa: " . htmlspecialchars($conn->error) . ". Usando tasa predeterminada 1.0.</p>";
    } else {
        $mensaje_tasa = "<p class='mensaje-advertencia'>Advertencia: No hay tasa de cambio registrada. Usando tasa predeterminada 1.0.</p>";
    }
}

$por_pagina = 10; 
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_actual - 1) * $por_pagina;

if ($id_categoria) {
    $total_productos = 0;
    $sql_total_productos = "SELECT COUNT(*) as total FROM producto WHERE id_categoria = ? AND estado_producto = 1";
    $stmt_total = $conn->prepare($sql_total_productos);
    
    if ($stmt_total) {
        $stmt_total->bind_param("i", $id_categoria);
        if ($stmt_total->execute()) {
            $result_total = $stmt_total->get_result();
            if ($result_total) {
                $total_productos = $result_total->fetch_assoc()['total'];
            }
        } else {
            echo "<p class='mensaje-error'>Error al contar productos: " . htmlspecialchars($conn->error) . "</p>";
        }
        $stmt_total->close();
    } else {
        echo "<p class='mensaje-error'>Error al preparar conteo de productos: " . htmlspecialchars($conn->error) . "</p>";
    }

    $total_paginas = max(1, ceil($total_productos / $por_pagina));

    $sql_productos = "SELECT 
                        p.id_pro,
                        p.nombre_producto,
                        p.cantidad,
                        p.precio,
                        p.codigo,
                        p.descrip_prod,
                        p.id_tipo_cuenta
                    FROM 
                        producto p
                    WHERE 
                        p.id_categoria = ? AND p.estado_producto = 1
                    ORDER BY
                        p.nombre_producto ASC
                    LIMIT ?, ?";
    
    $stmt_productos = $conn->prepare($sql_productos);
    if ($stmt_productos) {
        $stmt_productos->bind_param("iii", $id_categoria, $offset, $por_pagina);
        if ($stmt_productos->execute()) {
            $result_productos = $stmt_productos->get_result();
            if ($result_productos) {
                while ($row = $result_productos->fetch_assoc()) {
                    $productos_categoria[] = $row;
                }
            }
        } else {
            echo "<p class='mensaje-error'>Error al consultar productos: " . htmlspecialchars($conn->error) . "</p>";
        }
        $stmt_productos->close();
    } else {
        echo "<p class='mensaje-error'>Error al preparar consulta de productos: " . htmlspecialchars($conn->error) . "</p>";
    }
} else {
    echo "<p class='mensaje-error'>No se especificó un ID de categoría válido.</p>";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos por Categoría</title>
    <link rel="stylesheet" href="../assets/css/lista_productos.css">
</head>
<body>
    <?php include('../assets/lista_gerente.php'); ?>
    <div class="container mt-4 lista-container">
        <h2>Productos en la Categoría: <?php echo htmlspecialchars($nombre_categoria, ENT_QUOTES, 'UTF-8'); ?></h2>
       

        

        <?php if ($id_categoria && !empty($productos_categoria)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                            <th>Precio (USD)</th>
                            <th>Precio (VES)</th>
                            <th>Código</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_categoria as $producto): 
                            $precio_usd = floatval(str_replace(',', '.', $producto['precio']));
                            $precio_ves = $precio_usd * $tasaCambio;
                            $cantidad_display = ($producto['id_tipo_cuenta'] == 1) ? "Stock en Existencia" : htmlspecialchars($producto['cantidad'], ENT_QUOTES, 'UTF-8');
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($producto['id_pro'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($producto['nombre_producto'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo $cantidad_display; ?></td>
                                <td><?php echo htmlspecialchars(number_format($precio_usd, 4, '.', ','), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars(number_format($precio_ves, 2, '.', ','), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($producto['codigo'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><div class='descripcion-corta' title='<?php echo htmlspecialchars($producto["descrip_prod"], ENT_QUOTES, 'UTF-8'); ?>'><?php echo htmlspecialchars($producto['descrip_prod'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></div></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_paginas > 1): ?>
                <nav aria-label="Paginación de productos">
                    <ul class="pagination justify-content-center" style="margin-top: 20px;">
                        <?php if ($pagina_actual > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?id=<?php echo $id_categoria; ?>&pagina=1" aria-label="Primera">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?id=<?php echo $id_categoria; ?>&pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Anterior">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php 
                        
                        $inicio = max(1, $pagina_actual - 2);
                        $fin = min($total_paginas, $pagina_actual + 2);
                        
                        if ($inicio > 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        
                        for ($i = $inicio; $i <= $fin; $i++): ?>
                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                <a class="page-link" href="?id=<?php echo $id_categoria; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; 
                        
                        if ($fin < $total_paginas) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        ?>

                        <?php if ($pagina_actual < $total_paginas): ?>
                            <li class="page-item"><a class="page-link" href="?id=<?php echo $id_categoria; ?>&pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Siguiente">&raquo;</a></li>
                            <li class="page-item"><a class="page-link" href="?id=<?php echo $id_categoria; ?>&pagina=<?php echo $total_paginas; ?>" aria-label="Última">&raquo;&raquo;</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php elseif ($id_categoria): ?>
            <p class='mensaje-info'>No se encontraron productos activos para esta categoría.</p>
        <?php endif; ?>
        <?php if ($id_categoria && $total_productos > 0): ?>
            <div class="resumen-productos" style="margin-top: 20px; padding: 10px; background-color: #e9ecef; border-radius: 5px; text-align: center;">
                <p><strong>Total de productos en esta categoría: <?php echo $total_productos; ?></strong></p>
            </div>
        <?php endif; ?>

        <div style="margin-top: 20px;">
            <a href="lista_categoria.php" class="btn-regresar">Regresar a Lista de Categorías</a>
        </div>
    </div>

    <?php 
    if (isset($conn)) {
        $conn->close(); 
    }
    ?>
</body>
</html>