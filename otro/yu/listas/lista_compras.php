<?php
$fecha = date("Y-m-d");
session_start();
require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../ingreso.php');
    exit();
}

if (!$conn || $conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos.");
}

setlocale(LC_MONETARY, 'es_VE');
date_default_timezone_set('America/Caracas');

// Parámetros de búsqueda y paginación
$search_term = trim($_GET['search'] ?? '');
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';
$tipo_compra_filtro = in_array($_GET['tipo_compra'] ?? '', ['contado', 'credito']) ? $_GET['tipo_compra'] : '';

// Validar fechas
if ($fecha_inicio && !DateTime::createFromFormat('Y-m-d', $fecha_inicio)) {
    $fecha_inicio = '';
}
if ($fecha_fin && !DateTime::createFromFormat('Y-m-d', $fecha_fin)) {
    $fecha_fin = '';
}

// Configuración de paginación
$registros_por_pagina = 20;
$pagina_actual = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;

$compras = [];
$inversion_total_usd = 0;
$total_productos_comprados = 0;
$alert = '';
$total_registros = 0;
$total_paginas = 0;

// Consulta base
$sql_base = "
    SELECT
        pp.id_producto_proveedor, 
        pp.fecha,
        pr.nombre_provedor,
        p.nombre_producto,
        pp.costo_compra,
        p.id_tipo_cuenta,
        pp.cantidad_compra,
        CASE 
            WHEN p.id_tipo_cuenta = 1 THEN pp.costo_compra
            ELSE (pp.costo_compra * pp.cantidad_compra)
        END AS costo_total_compra_usd,
        (SELECT td.tasa_dolar
         FROM tasa_dolar td
         WHERE DATE(td.fecha_dolar) <= DATE(pp.fecha) 
         ORDER BY td.fecha_dolar DESC
         LIMIT 1) AS tasa_dolar_aplicada,
        pp.id_compra_credito_fk,
        cc.monto_abonado AS credito_monto_abonado,
        (cc.monto_total_credito - cc.monto_abonado) AS credito_saldo_pendiente,
        cc.fecha_vencimiento AS credito_fecha_vencimiento,
        cc.estado_credito AS credito_estado,
        tp.tipo_pago AS metodo_pago_contado,
        pp.monto_moneda_pago_contado,
        pp.codigo_moneda_pago_contado,
        pp.referencia_pago_contado
    FROM producto_proveedor pp
    JOIN proveedor pr ON pp.RIF = pr.RIF
    JOIN producto p ON pp.id_pro = p.id_pro
    LEFT JOIN tipo_pago tp ON pp.id_tipo_pago_contado = tp.id_tipo_pago
    LEFT JOIN compras_credito cc ON pp.id_compra_credito_fk = cc.id_compra_credito
";

$conditions = [];
$params = [];
$types = "";

if (!empty($search_term)) {
    $conditions[] = "(p.nombre_producto LIKE ? OR pr.nombre_provedor LIKE ?)";
    $params[] = "%$search_term%";
    $params[] = "%$search_term%";
    $types .= "ss";
}

if (!empty($fecha_inicio)) {
    $conditions[] = "pp.fecha >= ?";
    $params[] = $fecha_inicio;
    $types .= "s";
}

if (!empty($fecha_fin)) {
    $conditions[] = "pp.fecha <= ?";
    $params[] = $fecha_fin;
    $types .= "s";
}

if ($tipo_compra_filtro === 'contado') {
    $conditions[] = "pp.id_compra_credito_fk IS NULL";
} elseif ($tipo_compra_filtro === 'credito') {
    $conditions[] = "pp.id_compra_credito_fk IS NOT NULL";
}

// Construir consulta WHERE
$sql_where = "";
if (!empty($conditions)) {
    $sql_where = " WHERE " . implode(" AND ", $conditions);
}

try {
    // Consulta para contar total de registros (CORREGIDA)
    $sql_count = "SELECT COUNT(*) as total 
                  FROM producto_proveedor pp 
                  JOIN proveedor pr ON pp.RIF = pr.RIF
                  JOIN producto p ON pp.id_pro = p.id_pro
                  LEFT JOIN tipo_pago tp ON pp.id_tipo_pago_contado = tp.id_tipo_pago
                  LEFT JOIN compras_credito cc ON pp.id_compra_credito_fk = cc.id_compra_credito" . $sql_where;
    
    $stmt_count = $conn->prepare($sql_count);
    if ($stmt_count) {
        if (!empty($types) && !empty($params)) {
            $stmt_count->bind_param($types, ...$params);
        }
        
        if ($stmt_count->execute()) {
            $result_count = $stmt_count->get_result();
            $row_count = $result_count->fetch_assoc();
            $total_registros = $row_count['total'];
            $total_paginas = ceil($total_registros / $registros_por_pagina);
        } else {
            throw new Exception("Error al contar registros: " . $stmt_count->error);
        }
        $stmt_count->close();
    } else {
        throw new Exception("Error al preparar consulta de conteo: " . $conn->error);
    }

    // Consulta principal con paginación (CORREGIDA)
    $sql_query = $sql_base . $sql_where . " ORDER BY pp.fecha DESC, pp.id_producto_proveedor DESC LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql_query);
    
    if ($stmt) {
        // Preparar parámetros correctamente
        if (!empty($types) && !empty($params)) {
            // Crear array de parámetros para bind_param
            $parametros = $params;
            $parametros[] = $registros_por_pagina;
            $parametros[] = $offset;
            
            // Crear tipos para bind_param
            $tipos_completos = $types . "ii";
            
            // Usar call_user_func_array para pasar los parámetros correctamente
            $stmt->bind_param($tipos_completos, ...$parametros);
        } else {
            // Solo parámetros de paginación
            $stmt->bind_param("ii", $registros_por_pagina, $offset);
        }
        
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $compras[] = $row;
                $inversion_total_usd += (float)$row['costo_total_compra_usd'];
                $total_productos_comprados += (int)$row['cantidad_compra'];
            }
        } else {
            throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        $stmt->close();
    } else {
        throw new Exception("Error al preparar consulta principal: " . $conn->error);
    }
    
} catch (Exception $e) {
    $alert = '<p class="msg_error">' . htmlspecialchars($e->getMessage()) . '</p>';
    error_log($e->getMessage());
    
  
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <?php include "../assets/head_gerente.php"; ?>
    <link rel="stylesheet" href="assets/fonts/google-icons/index.css">
    <style>
        :root {
            --primary-color: #3533cd;
            --secondary-color: #0056b3;
            --danger-color: #dc3545;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 0; 
            padding: 0; 
            background-color: #f4f4f4; 
            color: #333; 
        }
        
        .container { 
            width: 900px; 
            max-width: 100%;
            margin:0px 0px 0px 20px ; 
            background-color: #fff; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); 
        }
        
        h1 { 
            color: var(--primary-color); 
            text-align: center; 
            margin-bottom: 25px; 
            font-size: 2rem;
        }
        
        .search-form { 
            margin-bottom: 25px; 
            padding: 20px; 
            background-color: #e9ecef; 
            border-radius: 8px; 
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            align-items: flex-end; 
        }
        
        .form-group { 
            display: flex; 
            flex-direction: column; 
        }
        
        .form-group label { 
            font-weight: 600; 
            margin-bottom: 8px; 
            color: #495057;
        }
        
        .form-control {
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.15s;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(53, 51, 205, 0.25);
        }
        
        .btn {
            padding: 10px 15px;
            border-radius: 4px;
            border: none;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
        }
        
        .btn-secondary {
            text-decoration: none;
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 0.9em;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #f1f1f1;
        }
        
        .number {
            text-align: right;
            font-family: 'Courier New', monospace;
        }
        
        .summary {
            margin-top: 25px;
            padding: 20px;
            background-color: #e2f3f8;
            border-left: 5px solid var(--primary-color);
            border-radius: 5px;
        }
        
        .summary h3 {
            margin-top: 0;
            color: var(--primary-color);
            font-size: 1.3rem;
        }
        
        .summary p {
            margin: 10px 0;
            font-size: 1.1em;
            display: flex;
            justify-content: space-between;
            max-width: 400px;
        }
        
        .summary p strong {
            font-weight: 600;
        }
        
        .msg_error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .no-results {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            font-size: 1.2em;
            border: 1px dashed #ddd;
            border-radius: 5px;
            margin: 20px 0;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .badge-warning {
            background-color: var(--warning-color);
            color: #212529;
        }
        
        .badge-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .text-success {
            color: var(--success-color);
        }
        
        .text-danger {
            color: var(--danger-color);
        }
        
        .text-warning {
            color: var(--warning-color);
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
            gap: 10px;
        }
        
        .pagination a, .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: var(--primary-color);
            border-radius: 4px;
        }
        
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        
        .pagination .current {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .pagination .disabled {
            color: #6c757d;
            cursor: not-allowed;
        }
        
        .pagination-info {
            text-align: center;
            margin: 10px 0;
            color: #6c757d;
            font-size: 0.9em;
        }
        
        .search-loading {
            display: none;
            color: var(--primary-color);
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            .search-form {
                grid-template-columns: 1fr;
            }
            
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
            
            .pagination {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php"; ?>

    <div class="container">
        <h1><span class="material-symbols-outlined ico-shopping_cart"></span> Historial de Compras</h1>

        <?php if (!empty($alert)) echo $alert; ?>

        <!-- DEBUG: Información de depuración -->
        <?php if (empty($compras) && empty($alert)): ?>
            <div style="background: #fff3cd; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
                <strong>DEBUG:</strong> 
                Total registros: <?= $total_registros ?>, 
                Páginas: <?= $total_paginas ?>,
                Página actual: <?= $pagina_actual ?>,
                Offset: <?= $offset ?>
            </div>
        <?php endif; ?>

        <form action="" method="GET" class="search-form" id="searchForm">
            <input type="hidden" name="pagina" id="paginaHidden" value="1">
            
            <div class="form-group">
                <label for="search"><span class="material-symbols-outlined ico-search"></span> Buscar:</label>
                <input type="text" class="form-control" name="search" id="search" 
                       value="<?= htmlspecialchars($search_term) ?>" 
                       placeholder="Producto o proveedor">
                <div class="search-loading" id="searchLoading">Buscando...</div>
            </div>
            
            <div class="form-group">
                <label for="fecha_inicio"><span class="material-symbols-outlined ico-calendar_month"></span> Desde:</label>
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" 
                       value="<?= htmlspecialchars($fecha_inicio) ?>">
            </div>
            
            <div class="form-group">
                <label for="fecha_fin"><span class="material-symbols-outlined ico-calendar_month"></span> Hasta:</label>
                <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" 
                       value="<?= htmlspecialchars($fecha_fin) ?>">
            </div>
            
            <div class="form-group">
                <label for="tipo_compra"><span class="material-symbols-outlined ico-credit_card"></span> Tipo:</label>
                <select class="form-control" name="tipo_compra" id="tipo_compra">
                    <option value="">Todas</option>
                    <option value="contado" <?= $tipo_compra_filtro == 'contado' ? 'selected' : '' ?>>Contado</option>
                    <option value="credito" <?= $tipo_compra_filtro == 'credito' ? 'selected' : '' ?>>Crédito</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <span class="material-symbols-outlined ico-filter_alt"></span> Filtrar
            </button>
            
            <a href="lista_compras.php" class="btn btn-secondary">
                <span class="material-symbols-outlined ico-history"></span> Limpiar
            </a>
        </form>

        <?php if (!empty($compras)): ?>
            <!-- Información de paginación -->
            <div class="pagination-info">
                Mostrando <?= count($compras) ?> de <?= number_format($total_registros, 0) ?> registros
                <?php if ($total_paginas > 1): ?>
                    - Página <?= $pagina_actual ?> de <?= $total_paginas ?>
                <?php endif; ?>
            </div>

            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Proveedor</th>
                            <th>Producto</th>
                            <th>Tipo</th>
                            <th class="number">Unit. ($)</th>
                            <th class="number">Cant.</th>
                            <th>Fecha</th>
                            <th class="number">Tasa</th>
                            <th class="number">Total ($)</th>
                            <th class="number">Total (Bs)</th>
                            <th>Vence</th>
                            <th class="number">Abonado</th>
                            <th class="number">Saldo</th>
                            <th>Estado</th>
                            <th>Método Pago</th>
                            <th>Monto Pagado</th>
                            <th>Ref.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compras as $compra): ?>
                            <?php
                                $es_servicio = ($compra['id_tipo_cuenta'] == 1);
                                $tipo_compra_display = $compra['id_compra_credito_fk'] !== null ? 'Crédito' : 'Contado';
                                $cantidad_display = $es_servicio ? '-' : number_format($compra['cantidad_compra'], 0);
                                $costo_unitario_display = $es_servicio ? '-' : number_format($compra['costo_compra'], 2);
                                
                                $costo_total_bs = null;
                                if ($compra['tasa_dolar_aplicada'] !== null && $compra['costo_total_compra_usd'] > 0) {
                                    $costo_total_bs = $compra['costo_total_compra_usd'] * $compra['tasa_dolar_aplicada'];
                                }
                                
                                $estado_clase = '';
                                if ($compra['id_compra_credito_fk'] !== null) {
                                    switch ($compra['credito_estado']) {
                                        case 'Pagado Totalmente':
                                            $estado_clase = 'badge-success';
                                            break;
                                        case 'Pendiente':
                                            $estado_clase = 'badge-warning';
                                            break;
                                        case 'Vencido':
                                            $estado_clase = 'badge-danger';
                                            break;
                                        default:
                                            $estado_clase = 'badge-info';
                                    }
                                }
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($compra['id_producto_proveedor']) ?></td>  
                                <td><?= htmlspecialchars($compra['nombre_provedor']) ?></td>
                                <td><?= htmlspecialchars($compra['nombre_producto']) ?></td>
                                <td><?= $tipo_compra_display ?></td>
                                <td class="number"><?= $costo_unitario_display ?></td>
                                <td class="number"><?= $cantidad_display ?></td>
                                <td><?= date("d/m/Y", strtotime($compra['fecha'])) ?></td>
                                <td class="number">
                                    <?= $compra['tasa_dolar_aplicada'] !== null ? number_format($compra['tasa_dolar_aplicada'], 2) : 'N/A' ?>
                                </td>
                                <td class="number"><?= number_format($compra['costo_total_compra_usd'], 2) ?></td>
                                <td class="number">
                                    <?= $costo_total_bs !== null ? number_format($costo_total_bs, 2) : 'N/A' ?>
                                </td>
                                <td>
                                    <?= ($compra['id_compra_credito_fk'] !== null && $compra['credito_fecha_vencimiento']) ? 
                                        date("d/m/Y", strtotime($compra['credito_fecha_vencimiento'])) : '-' ?>
                                </td>
                                <td class="number">
                                    <?= ($compra['id_compra_credito_fk'] !== null) ? 
                                        number_format($compra['credito_monto_abonado'] ?? 0, 2) : '-' ?>
                                </td>
                                <td class="number">
                                    <?= ($compra['id_compra_credito_fk'] !== null) ? 
                                        number_format($compra['credito_saldo_pendiente'] ?? $compra['costo_total_compra_usd'], 2) : '-' ?>
                                </td>
                                <td>
                                    <?php if ($compra['id_compra_credito_fk'] !== null): ?>
                                        <span class="badge <?= $estado_clase ?>">
                                            <?= htmlspecialchars($compra['credito_estado'] ?? 'N/A') ?>
                                        </span>
                                        <?php if ($compra['credito_estado'] !== 'Pagado Totalmente'): ?>
                                            <a href="credito_especifico.php?id_credito=<?= $compra['id_compra_credito_fk'] ?>" 
                                               title="Realizar Abono" 
                                               style="color: var(--success-color); margin-left: 5px;">
                                                <span class="material-symbols-outlined ico-payments"></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= ($tipo_compra_display === 'Contado' && !$es_servicio) ? 
                                        htmlspecialchars($compra['metodo_pago_contado'] ?? 'N/A') : '-' ?>
                                </td>
                                <td>
                                    <?= ($tipo_compra_display === 'Contado' && !$es_servicio && $compra['monto_moneda_pago_contado'] !== null) ? 
                                        number_format($compra['monto_moneda_pago_contado'], 2) . ' ' . 
                                        htmlspecialchars($compra['codigo_moneda_pago_contado']) : '-' ?>
                                </td>
                                <td>
                                    <?= ($tipo_compra_display === 'Contado' && !$es_servicio) ? 
                                        htmlspecialchars($compra['referencia_pago_contado'] ?? '-') : '-' ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
                <div class="pagination">
                    <?php if ($pagina_actual > 1): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => 1])) ?>">« Primera</a>
                        <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual - 1])) ?>">‹ Anterior</a>
                    <?php else: ?>
                        <span class="disabled">« Primera</span>
                        <span class="disabled">‹ Anterior</span>
                    <?php endif; ?>

                    <?php
                    // Mostrar números de página
                    $inicio = max(1, $pagina_actual - 2);
                    $fin = min($total_paginas, $pagina_actual + 2);
                    
                    for ($i = $inicio; $i <= $fin; $i++): 
                    ?>
                        <?php if ($i == $pagina_actual): ?>
                            <span class="current"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagina_actual < $total_paginas): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina_actual + 1])) ?>">Siguiente ›</a>
                        <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $total_paginas])) ?>">Última »</a>
                    <?php else: ?>
                        <span class="disabled">Siguiente ›</span>
                        <span class="disabled">Última »</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="summary">
                <h3><span class="material-symbols-outlined ico-trending_up"></span> Resumen de Compras</h3>
                <?php
                    $periodo = "";
                    if (!empty($fecha_inicio)) $periodo .= "Desde " . date("d/m/Y", strtotime($fecha_inicio)) . " ";
                    if (!empty($fecha_fin)) $periodo .= "hasta " . date("d/m/Y", strtotime($fecha_fin)) . " ";
                    
                    $filtros = [];
                    if (!empty($search_term)) $filtros[] = "Término: '" . htmlspecialchars($search_term) . "'";
                    if (!empty($tipo_compra_filtro)) $filtros[] = "Tipo: " . ucfirst($tipo_compra_filtro);
                    
                    if (!empty($filtros)) {
                        $periodo .= "(" . implode(", ", $filtros) . ")";
                    } elseif (empty($periodo)) {
                        $periodo = "(Todas las compras)";
                    }
                ?>
                <p><strong>Período:</strong> <?= htmlspecialchars($periodo) ?></p>
                <p><strong>Inversión Total:</strong> $<?= number_format($inversion_total_usd, 2) ?></p>
                <p><strong>Total Productos:</strong> <?= number_format($total_productos_comprados, 0) ?> unidades</p>
            </div>

        <?php elseif (empty($alert)): ?>
            <div class="no-results">
               <span class="material-symbols-outlined ico-error"></span>
                <p>No se encontraron compras que coincidan con los criterios de búsqueda.</p>
            </div>
        <?php endif; ?>

        <div class="action-buttons">
            <a href="../registrar/compras.php" class="btn btn-primary">
               <span class="material-symbols-outlined ico-add_circle"></span> Nueva Compra
            </a>
            <a href="../funciones/reporte_compras.php" 
               class="btn btn-secondary">
               <span class="material-symbols-outlined ico-picture_as_pdf"></span>Generar PDF
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Búsqueda en tiempo real
            let searchTimeout;
            const searchInput = document.getElementById('search');
            const searchLoading = document.getElementById('searchLoading');
            const paginaHidden = document.getElementById('paginaHidden');
            const searchForm = document.getElementById('searchForm');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchLoading.style.display = 'block';
                    
                    searchTimeout = setTimeout(function() {
                        // Reiniciar a página 1 cuando se busca
                        paginaHidden.value = '1';
                        searchForm.submit();
                    }, 1200); // 800ms de delay
                });
            }

            // También buscar cuando cambien otros filtros
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');
            const tipoCompra = document.getElementById('tipo_compra');

            if (fechaInicio) {
                fechaInicio.addEventListener('change', function() {
                    paginaHidden.value = '1';
                    searchForm.submit();
                });
            }

            if (fechaFin) {
                fechaFin.addEventListener('change', function() {
                    paginaHidden.value = '1';
                    searchForm.submit();
                });
            }

            if (tipoCompra) {
                tipoCompra.addEventListener('change', function() {
                    paginaHidden.value = '1';
                    searchForm.submit();
                });
            }
        });
    </script>
</body>
</html>