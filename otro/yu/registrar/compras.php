
<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../auth/ingreso.php");
    exit();
}

if (!isset($conn) || $conn->connect_error) {
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

// Variables para mensajes de éxito/error
$mensaje_exito = "";
$mensaje_error = "";
$error = "";

/**
 * Función para obtener datos de forma segura usando sentencias preparadas.
 *
 * @param mysqli $conn Objeto de conexión a la base de datos.
 * @param string $sql La consulta SQL a ejecutar.
 * @param array $params Los parámetros para la sentencia preparada.
 * @param string $types Los tipos de datos de los parámetros.
 * @return array|bool Los resultados de la consulta como un array asociativo o false en caso de error.
 */
function obtenerDatos(mysqli $conn, string $sql, array $params = [], string $types = '') {
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . $conn->error);
        return false;
    }
    
    if ($params && $types) {
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        error_log("Error en la ejecución de la consulta: " . $stmt->error);
        return false;
    }
    
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Variables para el modal de producto
$mensaje_exito_producto = "";
$mensaje_error_producto = "";

// Obtener categorías para el select
$categorias = obtenerDatos($conn, "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = 1 ORDER BY nombre_categoria ASC") ?: [];

// Obtener tipos de IVA
$ivas = obtenerDatos($conn, "SELECT id_iva, nombre_iva, valor_iva FROM iva ORDER BY nombre_iva ASC") ?: [];

// Obtener tipos de cuenta
$tipos_cuenta = obtenerDatos($conn, "SELECT id_tipo_cuenta, nom_cuenta FROM tipo_cuenta ORDER BY nom_cuenta ASC") ?: [];

// Procesar formulario de producto
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action_type_producto'])) {
    $nombre_producto = isset($_POST['nombre_producto']) ? htmlspecialchars(trim($_POST['nombre_producto'])) : "";
    $costo = isset($_POST['costo']) ? floatval(str_replace(',', '.', $_POST['costo'])) : 0;
    $ganancia = isset($_POST['ganancia']) ? floatval(str_replace(',', '.', $_POST['ganancia'])) : 0;
    $codigo = isset($_POST['codigo']) ? htmlspecialchars(trim($_POST['codigo'])) : "";
    $descripcion_prod = isset($_POST['descripcion_prod']) ? htmlspecialchars(trim($_POST['descripcion_prod'])) : "";
    $id_categoria = isset($_POST['id_categoria']) ? intval($_POST['id_categoria']) : 0;
    $id_iva = isset($_POST['id_iva']) ? intval($_POST['id_iva']) : 0;
    $id_tipo_cuenta = isset($_POST['id_tipo_cuenta']) ? intval($_POST['id_tipo_cuenta']) : 0;
    $materia_prima = isset($_POST['materia_prima']) ? intval($_POST['materia_prima']) : 0;
    $codigo_barras = isset($_POST['codigo_barras']) ? htmlspecialchars(trim($_POST['codigo_barras'])) : "";

    // Validaciones
    if (empty($nombre_producto)) {
        $mensaje_error_producto .= "Error: El nombre del producto no puede estar vacío. ";
    }
    if ($costo <= 0) {
        $mensaje_error_producto .= "Error: El costo debe ser un valor positivo. ";
    }
    if ($ganancia < 0) {
        $mensaje_error_producto .= "Error: La ganancia no puede ser negativa. ";
    }
    if ($id_categoria <= 0) {
        $mensaje_error_producto .= "Error: Debe seleccionar una categoría válida. ";
    }
    if ($id_iva <= 0) {
        $mensaje_error_producto .= "Error: Debe seleccionar un tipo de IVA válido. ";
    }
    if ($id_tipo_cuenta <= 0) {
        $mensaje_error_producto .= "Error: Debe seleccionar un tipo de cuenta válido. ";
    }

    // Validar código de barras único
    if (!empty($codigo_barras)) {
        $stmt_check_barras = $conn->prepare("SELECT id_pro FROM producto WHERE codigo_barras = ?");
        if ($stmt_check_barras) {
            $stmt_check_barras->bind_param("s", $codigo_barras);
            $stmt_check_barras->execute();
            $stmt_check_barras->store_result();
            
            if ($stmt_check_barras->num_rows > 0) {
                $mensaje_error_producto .= "Error: El código de barras ya está registrado. ";
            }
            $stmt_check_barras->close();
        }
    }

    if (empty($mensaje_error_producto)) {
    $conn->begin_transaction();
    try {
        // Verificar si el producto ya existe
        $stmt_check = $conn->prepare("SELECT id_pro FROM producto WHERE nombre_producto = ?");
        if (!$stmt_check) {
            throw new Exception("Error preparando verificación de producto: " . $conn->error);
        }
        $stmt_check->bind_param("s", $nombre_producto);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            throw new Exception("El producto con este nombre ya existe.");
        }
        $stmt_check->close();

        // Calcular precio de venta
        $precio = $costo * (1 + ($ganancia / 100));
        
        // Determinar cantidad inicial según tipo de cuenta
        $cantidad_inicial = ($id_tipo_cuenta == 1) ? 99999999 : 0;

        // Insertar producto
        $sql_insert = "INSERT INTO producto (
            nombre_producto, cantidad, precio, costo, ganancia, codigo, 
            codigo_barras, descrip_prod, id_categoria, estado_producto, 
            id_iva, id_tipo_cuenta, materia_prima
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql_insert);
        if (!$stmt) {
            throw new Exception("Error preparando la inserción del producto: " . $conn->error);
        }
        
        // CORRECCIÓN: "sidddsssiiii" - 12 caracteres para 12 parámetros
        $stmt->bind_param(
            "sidddsssiiii", 
            $nombre_producto, 
            $cantidad_inicial, 
            $precio, 
            $costo, 
            $ganancia, 
            $codigo,
            $codigo_barras,
            $descripcion_prod, 
            $id_categoria,
            $id_iva, 
            $id_tipo_cuenta,
            $materia_prima
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error al registrar el producto: " . $stmt->error);
        }
        
        $conn->commit();
        $mensaje_exito_producto = "Producto registrado exitosamente!";
        
        // Limpiar campos
        $_POST = array();
        
    } catch (Exception $e) {
        $conn->rollback();
        $mensaje_error_producto = $e->getMessage();
    }
}
    }

//  MOVEMOS LA CARGA DE DATOS AL INICIO DEL SCRIPT
//  PARA ASEGURAR QUE ESTÉN DISPONIBLES
// =========================================================
$proveedores = obtenerDatos($conn, "SELECT RIF, nombre_provedor FROM proveedor WHERE estado_proveedor = 1 ORDER BY nombre_provedor") ?: [];
$productos_datalist = obtenerDatos($conn, "SELECT id_pro, nombre_producto, codigo, id_tipo_cuenta, ganancia, precio, cantidad FROM producto WHERE estado_producto = 1 ORDER BY nombre_producto") ?: [];
$tipos_pago = [];
$result_tipos_pago = obtenerDatos($conn, "SELECT id_tipo_pago, tipo_pago FROM tipo_pago ORDER BY tipo_pago");

if ($result_tipos_pago) {
    foreach ($result_tipos_pago as $row_tp) {
        if (stripos($row_tp['tipo_pago'], '(BS)') !== false || stripos($row_tp['tipo_pago'], 'Bolívares') !== false) {
            $row_tp['moneda_pago_default'] = 'BS';
        } elseif (stripos($row_tp['tipo_pago'], '(USD)') !== false || stripos($row_tp['tipo_pago'], 'Zelle') !== false || stripos($row_tp['tipo_pago'], 'PayPal') !== false) {
            $row_tp['moneda_pago_default'] = 'USD';
        } else {
            $row_tp['moneda_pago_default'] = null; 
        }
        $tipos_pago[] = $row_tp;
    }
} else {
    $error .= (empty($error) ? "" : " ") . "Advertencia: No se pudieron cargar los métodos de pago.";
}

// Obtener tipos de cuenta
$tipos_cuenta = obtenerDatos($conn, "SELECT id_tipo_cuenta, nom_cuenta FROM tipo_cuenta ORDER BY nom_cuenta ASC") ?: [];

// Obtener la tasa de dólar actual ANTES de procesar cualquier formulario
$sql_tasa_dolar = "SELECT id_tasa_dolar, tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$result_tasa_dolar = $conn->query($sql_tasa_dolar);
if ($result_tasa_dolar && $row_tasa = $result_tasa_dolar->fetch_assoc()) {
    $id_tasa_dolar_actual_db = $row_tasa['id_tasa_dolar'];
    $tasa_dolar_actual = floatval($row_tasa['tasa_dolar']);
} else {
    // Manejar el caso donde no hay tasa de dólar registrada
    die("Error: No se encontró una tasa de dólar registrada en el sistema.");
}
// =========================================================
//  FIN DE LA CARGA DE DATOS INICIALES
// =========================================================

// --- Lógica para manejar envíos POST ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn) || $conn->connect_error) {
        $mensaje_error = "Error de conexión al procesar el formulario: " . ($conn->connect_error ?? "Desconocido");
    } else {
        $action_type = $_POST['action_type'] ?? '';

        if ($action_type === 'register_purchase') {
            // --- Lógica ADAPTADA para registrar una nueva compra en `producto_provedor` ---
            $provider_rif = $_POST['provider_rif'] ?? '';
            $purchase_items_json = $_POST['purchase_items_data'] ?? '[]';
            $purchase_items = json_decode($purchase_items_json, true);
            $is_credit = isset($_POST['payment_type']) && $_POST['payment_type'] === 'credito';
            $due_date = $is_credit ? ($_POST['credit_term'] ?? null) : null;
            $payment_method_id = $is_credit ? null : ($_POST['payment_method'] ?? null);
            $exchange_rate = $is_credit ? null : (isset($_POST['exchange_rate']) ? floatval($_POST['exchange_rate']) : null);
            $payment_reference = $is_credit ? null : (trim($_POST['payment_reference'] ?? ''));
            $user_id = $_SESSION['id_usuario'];
            $moneda_principal_sistema = 'USD'; // Asumimos USD como moneda principal
            $id_fondo_fijo = 1; // ID del fondo fijo para la empresa
            $total_costo_transaccion = 0;

            // Validaciones de compra
            if (empty($provider_rif)) {
                $mensaje_error .= "Error: Debe seleccionar un proveedor. ";
            }
            if (empty($purchase_items)) {
                $mensaje_error .= "Error: La compra debe tener al menos un producto. ";
            }
            if ($is_credit && empty($due_date)) {
                $mensaje_error .= "Error: La fecha de vencimiento es requerida para compras a crédito. ";
            }
            if (!$is_credit && (empty($payment_method_id))) {
                $mensaje_error .= "Error: Método de pago es requerido para compras al contado. ";
            }
             if (!$is_credit && $payment_method_id) {
    $selected_payment_type = array_filter($tipos_pago, function($tp) use ($payment_method_id) {
        return $tp['id_tipo_pago'] == $payment_method_id;
    });
    $selected_payment_type = reset($selected_payment_type);

    if ($selected_payment_type && ($selected_payment_type['moneda_pago_default'] === 'BS')) {
        if ($exchange_rate === null || $exchange_rate <= 0) {
            $mensaje_error .= "Error: La tasa de cambio es requerida para pagos en Bolívares. ";
        }
    }
}


            foreach ($purchase_items as $item) {
                if (!isset($item['id']) || !isset($item['quantity']) || !isset($item['unitCost']) || $item['quantity'] <= 0 || $item['unitCost'] < 0) {
                    $mensaje_error .= "Error: Datos de producto inválidos en la compra. ";
                    break;
                }
                $total_costo_transaccion += $item['quantity'] * $item['unitCost'];
            }

            if (empty($mensaje_error)) {
                $conn->begin_transaction();
                try {
                    $id_compra_credito_fk = null;
                    $id_tipo_pago_contado_db = null;
                    $monto_moneda_pago_contado_db = null;
                    $codigo_moneda_pago_contado_db = null;
                    $tasa_cambio_aplicada_contado_db = null;
                    $referencia_pago_contado_db = null;

                    if ($is_credit) {
                      $stmt_cred = $conn->prepare("INSERT INTO compras_credito (RIF_proveedor, fecha_compra, monto_total_credito, saldo_pendiente, fecha_vencimiento, estado_credito, id_usuario_registro, notas_compra)
                                                      VALUES (?, NOW(), ?, ?, ?, 'Pendiente', ?, '')");
                       if (!$stmt_cred) {
                           throw new Exception("Error al preparar la inserción de compra a crédito: " . $conn->error);
                       }
                       // El saldo pendiente inicial es igual al monto total del crédito
                        $stmt_cred->bind_param("sddsi", $provider_rif, $total_costo_transaccion, $total_costo_transaccion, $due_date, $user_id);
                        $stmt_cred->execute();
                        $id_compra_credito_fk = $stmt_cred->insert_id;
                        $stmt_cred->close();
                    } else {
                        $id_tipo_pago_contado_db = $payment_method_id;
                        $referencia_pago_contado_db = $payment_reference;
                        $selected_payment_type = array_filter($tipos_pago, fn($tp) => $tp['id_tipo_pago'] == $id_tipo_pago_contado_db);
                        $selected_payment_type = reset($selected_payment_type);
                        
                        $codigo_moneda_pago_contado_db = $selected_payment_type['moneda_pago_default'] ?? 'USD';
                        $tasa_cambio_aplicada_contado_db = ($codigo_moneda_pago_contado_db === 'BS') ? $exchange_rate : 1.0;
                        $monto_moneda_pago_contado_db = ($codigo_moneda_pago_contado_db === 'BS') ? $total_costo_transaccion * $tasa_cambio_aplicada_contado_db : $total_costo_transaccion;

                        $stmt_fondo_prod = $conn->prepare("UPDATE fondo SET fondo = fondo - ? WHERE id_fondo = ?");
                        if (!$stmt_fondo_prod) {
                            throw new Exception("Error al preparar la actualización del fondo para producto: " . $conn->error);
                        }
                        $stmt_fondo_prod->bind_param("di", $total_costo_transaccion, $id_fondo_fijo);
                        $stmt_fondo_prod->execute();
                        if ($stmt_fondo_prod->affected_rows === 0) {
                            throw new Exception("Fondos insuficientes (ID: {$id_fondo_fijo}) para realizar la compra al contado o el ID del fondo es incorrecto.");
                        }
                        $stmt_fondo_prod->close();
                    }

                    // Preparamos la consulta SQL para la tabla `producto_provedor`
                    $sql_insert_pp = "INSERT INTO producto_proveedor (
                                        RIF,
                                        id_pro, 
                                        costo_compra, 
                                        cantidad_compra, 
                                        fecha, 
                                        id_compra_credito_fk, 
                                        id_tipo_pago_contado, 
                                        monto_moneda_pago_contado, 
                                        codigo_moneda_pago_contado, 
                                        tasa_cambio_aplicada_contado, 
                                        referencia_pago_contado
                                    ) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?)";
                    $stmt_pp = $conn->prepare($sql_insert_pp);
                    if (!$stmt_pp) {
                        throw new Exception("Error preparando la inserción en producto_provedor: " . $conn->error);
                    }
                    
                    // Iteramos sobre los items de la compra para registrarlos
                    foreach ($purchase_items as $item) {
                        $is_service = ($item['id_tipo_cuenta'] == 1);
                        $quantity_for_db = $is_service ? 0 : $item['quantity'];

                        // Creamos variables para bind_param para evitar el error de "pass by reference"
                        $id_compra_credito_param = $is_credit ? $id_compra_credito_fk : null;
                        $id_tipo_pago_param = !$is_credit ? $id_tipo_pago_contado_db : null;
                        
                        $stmt_pp->bind_param(
                            "sidiisdsds",
                            $provider_rif,
                            $item['id'],
                            $item['unitCost'],
                            $quantity_for_db,
                            $id_compra_credito_param,
                            $id_tipo_pago_param,
                            $monto_moneda_pago_contado_db,
                            $codigo_moneda_pago_contado_db,
                            $tasa_cambio_aplicada_contado_db,
                            $referencia_pago_contado_db
                        );

                        if (!$stmt_pp->execute()) {
                            throw new Exception("Error al registrar el producto " . $item['name'] . " en producto_provedor: " . $stmt_pp->error);
                        }
                        
                        // Actualizar stock del producto (solo para productos físicos, id_tipo_cuenta = 2)
                        if ($item['id_tipo_cuenta'] == 2) { 
                            $sql_update_stock = "UPDATE producto SET cantidad = cantidad + ?, costo = ?, precio = ? WHERE id_pro = ?";
                            $stmt_update_stock = $conn->prepare($sql_update_stock);
                            if (!$stmt_update_stock) {
                                throw new Exception("Error preparando la actualización de stock: " . $conn->error);
                            }
                            
                            // -----------------------------------------------------------------------------------
                            //  CORRECCIÓN CLAVE:
                            //  - Pasamos el costo y el precio con su precisión completa.
                            //  - Es VITAL que las columnas 'costo' and 'precio' en la base de datos
                            //    estén configuradas para aceptar 3 o más decimales (ej. DECIMAL(12, 3)).
                            // -----------------------------------------------------------------------------------
                            $costo_nuevo = $item['unitCost'];
                            $precio_nuevo = $item['unitCost'] * (1 + ($item['ganancia'] / 100));

                            // Usamos "d" para los parámetros de tipo double/decimal
                            $stmt_update_stock->bind_param("dddi", $item['quantity'], $costo_nuevo, $precio_nuevo, $item['id']);
                            
                            if (!$stmt_update_stock->execute()) {
                                throw new Exception("Error al actualizar stock para producto " . $item['name'] . ": " . $stmt_update_stock->error);
                            }
                            $stmt_update_stock->close();
                        }
                    }

                    $stmt_pp->close();
                    $mensaje_exito = "¡Compra registrada exitosamente!";
                    $conn->commit();
                    header("Location: compras.php?success_purchase=" . urlencode($mensaje_exito));
                    exit();

                } catch (Exception $e) {
                    $conn->rollback();
                    $mensaje_error = "Error en la transacción al registrar compra: " . $e->getMessage();
                    header("Location: compras.php?error_purchase=" . urlencode($mensaje_error));
                    exit();
                }
            }
        }
    }
}

// Carga de datos al frontend
$frontend_data = [
    'config' => [
        'tasa_dolar_actual' => $tasa_dolar_actual
    ],
    'proveedores' => $proveedores,
    'productos' => $productos_datalist,
    'tipos_pago' => $tipos_pago,
    'tipos_cuenta' => $tipos_cuenta,
];

// Manejar mensajes de éxito/error de la URL después de una redirección
if (isset($_GET['success_purchase'])) {
    $mensaje_exito = htmlspecialchars($_GET['success_purchase']);
}
if (isset($_GET['error_purchase'])) {
    $mensaje_error = htmlspecialchars($_GET['error_purchase']);
}
if (isset($_GET['success_product'])) {
    $mensaje_exito .= " " . htmlspecialchars($_GET['success_product']);
}
if (isset($_GET['error_product'])) {
    $mensaje_error .= " " . htmlspecialchars($_GET['error_product']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Nueva Compra</title>
    <link rel="stylesheet" href="../assets/css/comprasss.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<?php include "../assets/head_gerente.php"?>
</head>

 <?php include "../assets/lista_gerente.php"?>
<body>
    <div class="purchase-container">
        <h1>Registrar Nueva Compra</h1>
        <?php if (!empty($mensaje_exito)): ?>
            <div class="mensaje-exito"><?= htmlspecialchars($mensaje_exito) ?></div>
        <?php endif; ?>
        <?php if (!empty($mensaje_error)): ?>
            <div class="mensaje-error"><?= htmlspecialchars($mensaje_error) ?></div>
        <?php endif; ?>

        <!-- Buscador de Proveedores -->
        <div class="filters-container">
            <div class="form-group">
                <label for="provider-search">Buscar Proveedor:</label>
                <div class="search-wrapper">
                    <input type="text" id="provider-search" class="form-control" placeholder="Nombre o RIF del proveedor..." >
                    <div id="provider-results" class="search-results-container"></div>
                </div>
            </div>
            <div class="form-group">
                <button id="clear-provider-search" class="btn btn--secondary">
                    <span class="material-symbols-outlined">close</span> Limpiar
                </button>
            </div>
        </div>

        <!-- Buscador de Productos -->
       <div class="filters-container">
            <div class="form-group">
                <label for="product-search">Buscar Producto:</label>
                <div class="search-wrapper">
                    <input type="text" id="product-search" class="form-control" placeholder="Nombre o código del producto...">
                    <div id="product-results" class="search-results-container"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="product-quantity">Cantidad:</label>
                <input type="number" id="product-quantity" class="form-control" min="1" value="1">
            </div>
            <div class="form-group">
                <label for="product-unit-cost">Costo Unitario (USD):</label>
                <input type="number" id="product-unit-cost" class="form-control" min="0.001" step="0.001">
                <div class="currency-conversion">≈ <span class="bs-value" id="product-unit-cost-bs">0.00 BS</span></div>
            </div>
           
           <div class="form-group">
            <label for="product-selling-price">Precio de Venta (USD):</label>
            <input type="number" id="product-selling-price" class="form-control" readonly>
            <div class="currency-conversion">≈ <span class="bs-value" id="product-selling-price-bs">0.00 BS</span></div>
        </div>
        
            <div class="form-group">
                <button id="add-product-btn" class="btn btn--primary" disabled>
                    <span class="material-symbols-outlined">add</span> Añadir Producto
                </button>
            </div>
            <button type="button" id="openProductModalBtn" class="btn btn--secondary">
            <span class="material-symbols-outlined">add</span> Nuevo Producto
            </button>
        </div>
        
        <!-- Tabla de Productos a Comprar -->
        <div class="table-responsive">
            <table class="purchase-items-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Cantidad</th>
                        <th>Costo Unitario</th>
                        <th>Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="purchase-items-body">
                    <!-- Filas de productos agregados dinámicamente -->
                                </tbody>
            </table>
        </div>
        <div class="purchase-totals">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span id="subtotal-display">0.00 USD</span>
                <span class="totals-currency-conversion" id="subtotal-display-bs">0.00 BS</span>
            </div>
            <div class="totals-row">
                <span>IVA (16%):</span>
                <span id="iva-display">0.00 USD</span>
                <span class="totals-currency-conversion" id="iva-display-bs">0.00 BS</span>
            </div>
            <div class="totals-row total-amount">
                <span>Total:</span>
                <span id="total-display">0.00 USD</span>
                <span class="totals-currency-conversion" id="total-display-bs">0.00 BS</span>
            </div>
        </div>

       
        <!-- Formulario de Registro de Compra -->
        <form id="purchase-form" method="post" action="compras.php">
            <input type="hidden" name="action_type" value="register_purchase">
            <input type="hidden" name="provider_rif" id="hidden-provider-rif">
            <input type="hidden" name="purchase_items_data" id="hidden-purchase-items-data">

            <div class="payment-options">
                <h3>Método de Pago</h3>
                <div class="form-group">
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="payment_type" value="contado" checked> Al Contado
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="payment_type" value="credito"> A Crédito
                        </label>
                    </div>
                </div>
            </div>

            <!-- Campos para pago al contado -->
            <div id="contado-fields" class="payment-fields">
                <div class="form-group">
                    <label for="payment-method">Método de Pago:</label>
                    <select id="payment-method" name="payment_method" class="form-control">
                        <option value="">Seleccione...</option>
                        <?php foreach ($frontend_data['tipos_pago'] as $tipo): ?>
                            <option value="<?= htmlspecialchars($tipo['id_tipo_pago']) ?>" data-moneda="<?= htmlspecialchars($tipo['moneda_pago_default']) ?>">
                                <?= htmlspecialchars($tipo['tipo_pago']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="exchange-rate-group" class="form-group" style="display: none;">
                    <label for="exchange-rate">Tasa de Cambio (BS/USD):</label>
                    <input type="text" id="exchange-rate" name="exchange_rate" class="form-control" value="<?= htmlspecialchars($frontend_data['config']['tasa_dolar_actual']) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="payment-reference">Referencia de Pago:</label>
                    <input type="text" id="payment-reference" name="payment_reference" class="form-control" placeholder="Número de referencia...">
                </div>
            </div>

            <!-- Campos para pago a crédito -->
            <div id="credito-fields" class="payment-fields" style="display: none;">
                <div class="form-group">
                    <label for="credit-term">Fecha de Vencimiento:</label>
                    <input type="date" id="credit-term" name="credit_term" class="form-control">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" id="register-purchase-btn" class="btn btn--primary" disabled>
                    <span class="material-symbols-outlined">save</span> Registrar Compra
                </button>
                <a href="compras.php" class="btn btn--secondary">
                    <span class="material-symbols-outlined">cancel</span> Cancelar
                </a>
            </div>
        </form>
    </div>
    
   <!-- Modal para agregar nuevo producto -->
<div id="productModal" class="modal-overlay">
    <div class="modal-content">
        <button class="close-modal-btn" id="closeProductModalBtn">&times;</button>
        <h2>Agregar Nuevo Producto</h2>
        <?php if (!empty($mensaje_exito_producto)): ?>
            <div class="mensaje-exito"><?= htmlspecialchars($mensaje_exito_producto) ?></div>
        <?php endif; ?>
        <?php if (!empty($mensaje_error_producto)): ?>
            <div class="mensaje-error"><?= htmlspecialchars($mensaje_error_producto) ?></div>
        <?php endif; ?>
        <form id="product-form" method="post" action="compras.php">
            <input type="hidden" name="action_type_producto" value="register_product">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="nombre_producto">Nombre del Producto:</label>
                    <input type="text" id="nombre_producto" name="nombre_producto" class="form-control" required value="<?= $_POST['nombre_producto'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label for="codigo">Código:</label>
                    <input type="text" id="codigo" name="codigo" class="form-control" value="<?= $_POST['codigo'] ?? '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="codigo_barras">Código de Barras:</label>
                <input type="text" id="codigo_barras" name="codigo_barras" class="form-control" 
                       value="<?= $_POST['codigo_barras'] ?? '' ?>" 
                       placeholder="Escanea o ingresa el código de barras">
                <small>Dejar en blanco si no tiene código de barras</small>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="costo">Costo (USD):</label>
                    <input type="number" id="costo" name="costo" class="form-control" step="0.001" min="0.001" required value="<?= $_POST['costo'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label for="ganancia">% Ganancia:</label>
                    <input type="number" id="ganancia" name="ganancia" class="form-control" step="0.1" min="0" value="<?= $_POST['ganancia'] ?? '0' ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="precio-display">Precio de Venta (USD):</label>
                <div id="precio-display" class="form-control-static">0.00</div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="id_categoria">Categoría:</label>
                    <select id="id_categoria" name="id_categoria" class="form-control" required>
                        <option value="">Seleccione...</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?= $categoria['id_categoria'] ?>" <?= (isset($_POST['id_categoria']) && $_POST['id_categoria'] == $categoria['id_categoria']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($categoria['nombre_categoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_iva">Tipo de IVA:</label>
                    <div class="radio-group">
                        <?php foreach ($ivas as $iva): ?>
                            <label class="radio-option">
                                <input type="radio" name="id_iva" value="<?= $iva['id_iva'] ?>" required
                                    <?= (isset($_POST['id_iva']) && $_POST['id_iva'] == $iva['id_iva']) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($iva['nombre_iva']) ?> (<?= $iva['valor_iva'] ?>%)
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="id_tipo_cuenta">Tipo de Cuenta:</label>
                <div class="radio-group">
                    <?php foreach ($tipos_cuenta as $tipo): ?>
                        <label class="radio-option">
                            <input type="radio" name="id_tipo_cuenta" value="<?= $tipo['id_tipo_cuenta'] ?>" required 
                                <?= (isset($_POST['id_tipo_cuenta']) && $_POST['id_tipo_cuenta'] == $tipo['id_tipo_cuenta']) ? 'checked' : '' ?>>
                            <?= htmlspecialchars($tipo['nom_cuenta']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="materia_prima">Tipo de Producto:</label>
                <div class="radio-group">
                    <label class="radio-option">
                        <input type="radio" name="materia_prima" value="0" checked>
                        Producto de Venta
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="materia_prima" value="1">
                        Materia Prima
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="descripcion_prod">Descripción:</label>
                <textarea id="descripcion_prod" name="descripcion_prod" class="form-control"><?= $_POST['descripcion_prod'] ?? '' ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn--primary">
                    <span class="material-symbols-outlined">save</span> Guardar Producto
                </button>
                <button type="button" id="cancelProductBtn" class="btn btn--secondary">
                    <span class="material-symbols-outlined">cancel</span> Cancelar
                </button>
            </div>
        </form>
    </div>
</div>
                <?php if (!empty($mensaje_error_producto)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            mostrarErrorModal(<?= json_encode($mensaje_error_producto) ?>);
            document.getElementById('productModal').style.display = 'flex';
        });
    </script>
<?php endif; ?>
            </form>
        </div>
    </div>

<script>
    // Datos para el frontend
    const frontendData = <?= json_encode($frontend_data) ?>;
    const tasaDolarActual = <?= $tasa_dolar_actual ?>;
    const purchaseItems = [];
    let selectedProvider = null;

    // Función para formatear números con separadores de miles y decimales
    function formatNumber(number, decimals = 2) {
        return new Intl.NumberFormat('es-ES', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(number);
    }

    // Función para calcular el precio de venta
    function calculateSellingPrice(cost, profitPercentage) {
        return cost * (1 + (profitPercentage / 100));
    }

    // Función para calcular el precio de venta en tiempo real
    function updateSellingPrice() {
        const costInput = document.getElementById('product-unit-cost');
        const sellingPriceInput = document.getElementById('product-selling-price');
        const sellingPriceBs = document.getElementById('product-selling-price-bs');
        
        // Reemplazar comas por puntos para el análisis numérico
        const costValue = costInput.value.replace(',', '.');
        const cost = parseFloat(costValue) || 0;
        const profitPercentage = parseFloat(window.currentProduct?.ganancia) || 0;
        const sellingPrice = calculateSellingPrice(cost, profitPercentage);
        
        // Usar punto decimal en el valor del campo
        sellingPriceInput.value = sellingPrice.toFixed(2);
        sellingPriceBs.textContent = formatNumber(sellingPrice * tasaDolarActual) + ' BS';
    }

    // Función para renderizar la tabla de items de compra
    function renderPurchaseItemsTable() {
        const tbody = document.getElementById('purchase-items-body');
        tbody.innerHTML = '';
        
        purchaseItems.forEach((item, index) => {
            const subtotal = item.quantity * item.unitCost;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.name}</td>
                <td>${item.id_tipo_cuenta === 1 ? 'Servicio' : 'Producto'}</td>
                <td>${item.quantity}</td>
                <td>${formatNumber(item.unitCost)} USD</td>
                <td>${formatNumber(subtotal)} USD</td>
                <td>
                    <button type="button" class="btn btn--danger" onclick="removePurchaseItem(${index})">
                        <span class="material-symbols-outlined">delete</span> Eliminar
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
        
        updatePurchaseTotals();
        document.getElementById('register-purchase-btn').disabled = purchaseItems.length === 0 || !selectedProvider;
    }

    // Función para actualizar los totales de la compra
    function updatePurchaseTotals() {
        let subtotal = 0;
        
        purchaseItems.forEach(item => {
            subtotal += item.quantity * item.unitCost;
        });
        
        const iva = subtotal * 0.16;
        const total = subtotal + iva;
        
        document.getElementById('subtotal-display').textContent = formatNumber(subtotal) + ' USD';
        document.getElementById('subtotal-display-bs').textContent = formatNumber(subtotal * tasaDolarActual) + ' BS';
        document.getElementById('iva-display').textContent = formatNumber(iva) + ' USD';
        document.getElementById('iva-display-bs').textContent = formatNumber(iva * tasaDolarActual) + ' BS';
        document.getElementById('total-display').textContent = formatNumber(total) + ' USD';
        document.getElementById('total-display-bs').textContent = formatNumber(total * tasaDolarActual) + ' BS';
        
        // Actualizar el campo oculto con los datos de la compra
        document.getElementById('hidden-purchase-items-data').value = JSON.stringify(purchaseItems);
    }

    // Hacer la función removePurchaseItem global para que pueda ser llamada desde los botones
    window.removePurchaseItem = function(index) {
        purchaseItems.splice(index, 1);
        renderPurchaseItemsTable();
        updatePurchaseTotals();
    };

    // Función para buscar productos
    function searchProducts(query) {
        const resultsContainer = document.getElementById('product-results');
        resultsContainer.innerHTML = '';
        resultsContainer.style.display = 'none';

        if (query.length < 2) return;

        // Buscar en los productos disponibles
        const filteredProducts = frontendData.productos.filter(product => {
            const nombre = product.nombre_producto.toLowerCase();
            const codigo = product.codigo ? product.codigo.toLowerCase() : '';
            
            return nombre.includes(query.toLowerCase()) || 
                   codigo.includes(query.toLowerCase());
        });

        if (filteredProducts.length > 0) {
            resultsContainer.style.display = 'block';
            filteredProducts.forEach(product => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <div class="provider-info">
                        <span class="provider-name">${product.nombre_producto}</span>
                    </div>
                    <div class="product-code">Código: ${product.codigo || 'N/A'}</div>
                    <div class="product-type">${product.id_tipo_cuenta === 1 ? 'Siempre en existencia' : 'Stock contable'}</div>
                    <div class="product-stock">
                        ${product.id_tipo_cuenta === 1 ? 'Existente' : `Stock: ${product.cantidad}`}
                    </div>
                `;
                
                // Almacenar datos del producto en el elemento
                div.dataset.productId = product.id_pro;
                div.dataset.productName = product.nombre_producto;
                div.dataset.productCodigo = product.codigo;
                div.dataset.productTipoCuenta = product.id_tipo_cuenta;
                div.dataset.productGanancia = product.ganancia;
                div.dataset.productPrecio = product.precio;
                
                // Event listener para cuando se hace clic en un producto
                div.addEventListener('click', () => {
                    document.getElementById('product-search').value = `${product.nombre_producto} (Cod: ${product.codigo || 'N/A'})`;
                    
                    // Guardar información del producto seleccionado
                    window.currentProduct = {
                        id: product.id_pro,
                        name: product.nombre_producto,
                        codigo: product.codigo,
                        id_tipo_cuenta: product.id_tipo_cuenta,
                        ganancia: parseFloat(product.ganancia) || 0,
                        precio: parseFloat(product.precio) || 0
                    };
                    
                    // Establecer el costo basado en el precio y la ganancia
                    const costoCalculado = product.precio / (1 + (product.ganancia / 100));
                    document.getElementById('product-unit-cost').value = costoCalculado.toFixed(3);
                    
                    // Actualizar conversión a BS del costo unitario
                    document.getElementById('product-unit-cost-bs').textContent = formatNumber(costoCalculado * tasaDolarActual) + ' BS';
                    
                    // Calcular y mostrar precio de venta
                    updateSellingPrice();
                    
                    document.getElementById('add-product-btn').disabled = false;
                    resultsContainer.style.display = 'none';
                    
                    // Marcar visualmente el producto seleccionado
                    document.querySelectorAll('.search-result-item').forEach(item => {
                        item.classList.remove('selected');
                    });
                    div.classList.add('selected');
                });
                
                resultsContainer.appendChild(div);
            });
        }
    }

    // Función para buscar proveedores
    function searchProviders(query) {
        const resultsContainer = document.getElementById('provider-results');
        resultsContainer.innerHTML = '';
        resultsContainer.style.display = 'none';

        if (query.length < 2) return;

        const filtered = frontendData.proveedores.filter(p =>
            p.nombre_provedor.toLowerCase().includes(query.toLowerCase()) ||
            p.RIF.toLowerCase().includes(query.toLowerCase())
        );

        if (filtered.length > 0) {
            resultsContainer.style.display = 'block';
            filtered.forEach(p => {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <strong>${p.nombre_provedor}</strong><br>
                    <small>RIF: ${p.RIF}</small>
                `;
                div.addEventListener('click', () => {
                    document.getElementById('provider-search').value = `${p.nombre_provedor} (${p.RIF})`;
                    document.getElementById('hidden-provider-rif').value = p.RIF;
                    selectedProvider = p;
                    resultsContainer.style.display = 'none';
                    document.getElementById('register-purchase-btn').disabled = purchaseItems.length === 0;
                });
                resultsContainer.appendChild(div);
            });
        }
    }

    // Función para formatear entrada de costo
    function formatCostInput(raw) {
        // Eliminar todo excepto dígitos
        const digits = raw.replace(/\D/g, '');
        if (!digits) return '0.00';

        const num = parseInt(digits, 10);
        return (num / 100).toFixed(2);
    }

    // Función para mostrar error en modal
    function mostrarErrorModal(msg) {
        const banner = document.createElement('div');
        banner.className = 'modal-error-banner';
        banner.textContent = msg;
        const content = document.querySelector('.modal-content');
        content.insertAdjacentElement('afterbegin', banner);
        setTimeout(() => banner.remove(), 6000);
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Búsqueda de proveedores
        document.getElementById('provider-search').addEventListener('input', function(e) {
            searchProviders(e.target.value);
        });

        // Limpiar búsqueda de proveedor
        document.getElementById('clear-provider-search').addEventListener('click', function() {
            document.getElementById('provider-search').value = '';
            document.getElementById('hidden-provider-rif').value = '';
            selectedProvider = null;
            document.getElementById('provider-results').style.display = 'none';
            document.getElementById('register-purchase-btn').disabled = true;
            updatePurchaseTotals();
        });

        // Búsqueda de productos
        document.getElementById('product-search').addEventListener('input', function(e) {
            searchProducts(e.target.value);
        });

        // Actualizar conversión a BS del costo unitario
        document.getElementById('product-unit-cost').addEventListener('input', function(e) {
            const unitCost = parseFloat(e.target.value) || 0;
            document.getElementById('product-unit-cost-bs').textContent = formatNumber(unitCost * tasaDolarActual) + ' BS';
            updateSellingPrice();
        });

        // Añadir producto a la lista
        document.getElementById('add-product-btn').addEventListener('click', function() {
            const productInput = document.getElementById('product-search').value;
            const quantity = parseInt(document.getElementById('product-quantity').value) || 1;
            const unitCost = parseFloat(document.getElementById('product-unit-cost').value) || 0;
            
            if (!productInput || unitCost <= 0) {
                alert('Por favor, complete todos los campos correctamente.');
                return;
            }

            // Obtener datos del producto seleccionado
            const selectedProductElement = document.querySelector('.search-result-item.selected');
            if (!selectedProductElement) {
                alert('Por favor, seleccione un producto de la lista haciendo clic en él.');
                return;
            }

            const selectedProduct = {
                id: selectedProductElement.dataset.productId,
                name: selectedProductElement.dataset.productName,
                quantity: quantity,
                unitCost: unitCost,
                id_tipo_cuenta: parseInt(selectedProductElement.dataset.productTipoCuenta),
                ganancia: parseFloat(selectedProductElement.dataset.productGanancia)
            };

            // Añadir producto a la lista
            purchaseItems.push(selectedProduct);

            // Limpiar campos y actualizar tabla
            document.getElementById('product-search').value = '';
            document.getElementById('product-quantity').value = '1';
            document.getElementById('product-unit-cost').value = '';
            document.getElementById('product-unit-cost').dispatchEvent(new Event('input'));
            document.getElementById('product-selling-price').value = '';
            document.getElementById('product-unit-cost-bs').textContent = '0.00 BS';
            document.getElementById('product-selling-price-bs').textContent = '0.00 BS';
            document.getElementById('add-product-btn').disabled = true;
            
            // Limpiar selección
            document.querySelectorAll('.search-result-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            renderPurchaseItemsTable();
        });

        // Manejo de métodos de pago
        document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('contado-fields').style.display = this.value === 'contado' ? 'block' : 'none';
                document.getElementById('credito-fields').style.display = this.value === 'credito' ? 'block' : 'none';
            });
        });

        // Mostrar/ocultar campo de tasa de cambio según método de pago
        document.getElementById('payment-method').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const moneda = selectedOption.getAttribute('data-moneda');
            document.getElementById('exchange-rate-group').style.display = moneda === 'BS' ? 'block' : 'none';
        });

        // Modal de producto
        document.getElementById('openProductModalBtn').addEventListener('click', function() {
            document.getElementById('productModal').style.display = 'flex';
        });

        document.getElementById('closeProductModalBtn').addEventListener('click', function() {
            document.getElementById('productModal').style.display = 'none';
        });

        document.getElementById('cancelProductBtn').addEventListener('click', function() {
            document.getElementById('productModal').style.display = 'none';
        });

        // Cálculo de precio de venta en el modal
        const costoInput = document.getElementById('costo');
        const gananciaInput = document.getElementById('ganancia');
        const precioDisplay = document.getElementById('precio-display');

        function calcularPrecioVenta() {
            const costo = parseFloat(costoInput.value) || 0;
            const ganancia = parseFloat(gananciaInput.value) || 0;
            const precioVenta = costo * (1 + (ganancia / 100));
            precioDisplay.textContent = formatNumber(precioVenta);
        }

        costoInput.addEventListener('input', calcularPrecioVenta);
        gananciaInput.addEventListener('input', calcularPrecioVenta);

        // Inicializar cálculo de precio
        calcularPrecioVenta();

        // Manejo de errores del modal
        <?php if (!empty($mensaje_error_producto)): ?>
            mostrarErrorModal(<?= json_encode($mensaje_error_producto) ?>);
            document.getElementById('productModal').style.display = 'flex';
        <?php endif; ?>
    });
    // Agregar al final del script existente
// Detección de código de barras en el modal
const codigoBarrasModal = document.getElementById('codigo_barras');
let barcodeBufferModal = '';
let lastKeyTimeModal = Date.now();

document.addEventListener('keydown', function(e) {
    // Solo capturar si estamos en el modal y el campo de código de barras no está enfocado
    if (document.getElementById('productModal').style.display === 'flex' && 
        document.activeElement !== codigoBarrasModal) {
        
        const currentTime = Date.now();
        
        if (currentTime - lastKeyTimeModal > 50) {
            barcodeBufferModal = '';
        }
        
        lastKeyTimeModal = currentTime;
        
        if (e.key === 'Enter') {
            if (barcodeBufferModal.length > 3) {
                codigoBarrasModal.value = barcodeBufferModal;
                document.getElementById('nombre_producto').focus();
            }
            barcodeBufferModal = '';
            e.preventDefault();
        } else if (e.key.length === 1) {
            barcodeBufferModal += e.key;
        }
    }
});

// Botón para generar código automático
const generateBarcodeBtn = document.createElement('button');
generateBarcodeBtn.type = 'button';
generateBarcodeBtn.textContent = 'Generar Automático';
generateBarcodeBtn.className = 'btn btn--secondary';
generateBarcodeBtn.style.marginTop = '5px';
generateBarcodeBtn.onclick = function() {
    const randomBarcode = 'BC' + Date.now() + Math.floor(Math.random() * 1000);
    codigoBarrasModal.value = randomBarcode;
};

codigoBarrasModal.parentNode.appendChild(generateBarcodeBtn);

// Validación en tiempo real del código de barras
codigoBarrasModal.addEventListener('blur', function() {
    const codigo = this.value.trim();
    if (codigo.length > 0) {
        fetch(`verificar_codigo.php?codigo=${encodeURIComponent(codigo)}`)
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    alert(`❌ El código ${codigo} ya está registrado`);
                    this.focus();
                    this.select();
                }
            });
    }
});
</script>
</body>
</html>
