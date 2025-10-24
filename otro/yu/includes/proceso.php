<?php 
if (!isset($conn) || $conn->connect_error) {
    die(json_encode(['error' => 'Error de conexión a la base de datos']));
}

// Variables para mensajes de éxito/error
$mensaje_exito = "";
$mensaje_error = "";
require_once '../conexion/conexion.php';
include '../includes/config.php';

// --- Lógica para manejar envíos POST ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn) || $conn->connect_error) {
        $mensaje_error = "Error de conexión al procesar el formulario: " . ($conn->connect_error ?? "Desconocido");
    } else {
        $action_type = $_POST['action_type'] ?? '';

        if ($action_type === 'register_product') {
            // --- Lógica para registrar un nuevo producto ---
            $nombre_producto = isset($_POST['new_product_name']) ? htmlspecialchars(trim($_POST['new_product_name'])) : "";
            $precio_input = isset($_POST['new_product_cost']) ? trim($_POST['new_product_cost']) : "";
            $codigo = isset($_POST['new_product_code']) ? htmlspecialchars(trim($_POST['new_product_code'])) : "";
            $descripcion_prod = isset($_POST['new_product_description']) ? htmlspecialchars(trim($_POST['new_product_description'])) : "";
            $id_categoria = isset($_POST['new_product_id_categoria_hidden']) ? intval($_POST['new_product_id_categoria_hidden']) : 0;
            $id_iva = isset($_POST['new_product_id_iva']) ? intval($_POST['new_product_id_iva']) : 0;
            $id_tipo_cuenta = isset($_POST['new_product_id_tipo_cuenta']) ? intval($_POST['new_product_id_tipo_cuenta']) : 0;
            $id_tasa_dolar = isset($_POST['id_tasa_dolar_actual_db_hidden']) ? intval($_POST['id_tasa_dolar_actual_db_hidden']) : 0;

            $precio_numeric = str_replace(',', '.', $precio_input); 

            // Validaciones
            if (empty($nombre_producto)) {
                $mensaje_error .= "Error: El nombre del producto no puede estar vacío. ";
            }
            if (empty($descripcion_prod)) {
                $mensaje_error .= "Error: La descripción del producto no puede estar vacía. ";
            }
            if (!is_numeric($precio_numeric) || floatval($precio_numeric) <= 0) {
                $mensaje_error .= "Error: El precio debe ser un valor numérico positivo. ";
            } else {
                $precio = floatval($precio_numeric);
            }
            if ($id_categoria <= 0) {
                $mensaje_error .= "Error: Debe seleccionar una categoría válida. ";
            }
            if ($id_iva <= 0) {
                $mensaje_error .= "Error: Debe seleccionar un tipo de IVA válido. ";
            }
            if ($id_tipo_cuenta <= 0) {
                $mensaje_error .= "Error: Debe seleccionar un tipo de cuenta válido. ";
            }
            if ($id_tasa_dolar <= 0) {
                $mensaje_error .= "Error: No se encontró una tasa de cambio actual. ";
            }
            
            // Verificar si el producto ya existe
            if (empty($mensaje_error)) {
                $stmt_check = $conn->prepare("SELECT id_pro FROM producto WHERE nombre_producto = ?");
                if (!$stmt_check) {
                    $mensaje_error = "Error preparando la consulta de verificación de producto: " . $conn->error;
                } else {
                    $stmt_check->bind_param("s", $nombre_producto);
                    $stmt_check->execute();
                    $stmt_check->store_result();

                    if ($stmt_check->num_rows > 0) {
                        $mensaje_error = "Error: El producto con el nombre '" . htmlspecialchars($nombre_producto) . "' ya existe.";
                    }
                    $stmt_check->close();
                }
            }

            // Insertar producto si no hay errores
            if (empty($mensaje_error)) {
                $conn->begin_transaction(); 
                try {
                    $sql_insert_producto = "INSERT INTO producto (
                                        nombre_producto,
                                        cantidad,
                                        precio,
                                        codigo,
                                        descrip_prod,
                                        id_tasa_dolar,
                                        id_categoria,
                                        estado_producto,
                                        id_iva,
                                        id_tipo_cuenta
                                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                    $stmt_insert_producto = $conn->prepare($sql_insert_producto);

                    if (!$stmt_insert_producto) {
                        throw new Exception("Error preparando la inserción del producto: " . $conn->error);
                    }

                    $estado_predeterminado = 1;
                    $cantidad_inicial = ($id_tipo_cuenta == 1) ? 99999999 : 0; // 1 para Servicio, 2 para Físico
                    
                    $stmt_insert_producto->bind_param(
                        "sidssiiiii",
                        $nombre_producto,
                        $cantidad_inicial,
                        $precio,
                        $codigo,
                        $descripcion_prod,
                        $id_tasa_dolar, 
                        $id_categoria,
                        $estado_predeterminado,
                        $id_iva,
                        $id_tipo_cuenta
                    );

                    if (!$stmt_insert_producto->execute()) {
                        throw new Exception("Error al registrar el producto: " . $stmt_insert_producto->error);
                    }

                    $mensaje_exito = "¡Producto '" . htmlspecialchars($nombre_producto) . "' registrado exitosamente!";
                    $stmt_insert_producto->close();

                    $conn->commit(); 
                    // Limpiar campos del formulario después del éxito
                    $_POST = array(); 

                } catch (Exception $e) {
                    $conn->rollback(); 
                    $mensaje_error = "Error en la transacción al registrar producto: " . $e->getMessage();
                }
            }

        } elseif ($action_type === 'register_purchase') {
            // --- Lógica para registrar una nueva compra ---
            $provider_rif = $_POST['provider_rif'] ?? '';
            $is_credit = isset($_POST['is_credit']) ? 1 : 0;
            $due_date = $is_credit ? ($_POST['credit_term'] ?? null) : null;
            $payment_method_id = $is_credit ? null : ($_POST['payment_method'] ?? null);
            $exchange_rate = $is_credit ? null : (isset($_POST['exchange_rate']) ? floatval($_POST['exchange_rate']) : null);
            $payment_reference = $is_credit ? null : (trim($_POST['payment_reference'] ?? ''));
            $user_id = $_SESSION['id_usuario'];
            $purchase_items_json = $_POST['purchase_items_data'] ?? '[]';
            $purchase_items = json_decode($purchase_items_json, true);

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
            if (!$is_credit && (empty($payment_method_id) || empty($payment_reference))) {
                $mensaje_error .= "Error: Método de pago y referencia son requeridos para compras al contado. ";
            }
            if (!$is_credit && $payment_method_id && ($payment_method_id == 1 || $payment_method_id == 2) && ($exchange_rate === null || $exchange_rate <= 0)) { // Asumiendo 1 y 2 son métodos en BS
                 // Obtener la moneda del método de pago seleccionado
                $stmt_payment_currency = $conn->prepare("SELECT tipo_pago FROM tipo_pago WHERE id_tipo_pago = ?");
                $stmt_payment_currency->bind_param("i", $payment_method_id);
                $stmt_payment_currency->execute();
                $result_payment_currency = $stmt_payment_currency->get_result();
                $payment_currency_row = $result_payment_currency->fetch_assoc();
                $stmt_payment_currency->close();

                if ($payment_currency_row && (stripos($payment_currency_row['tipo_pago'], '(BS)') !== false || stripos($payment_currency_row['tipo_pago'], 'Bolívares') !== false)) {
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
            }

            if (empty($mensaje_error)) {
                $conn->begin_transaction();
                try {
                    // Calcular totales
                    $subtotal_compra = 0;
                    foreach ($purchase_items as $item) {
                        $subtotal_compra += $item['quantity'] * $item['unitCost'];
                    }
                    $iva_compra = $subtotal_compra * 0.16; // Asumiendo 16% IVA fijo
                    $total_compra = $subtotal_compra + $iva_compra;

                    // Insertar en la tabla 'compra'
                    $sql_insert_compra = "INSERT INTO compra (
                                            RIF_proveedor, 
                                            fecha_compra, 
                                            subtotal_compra, 
                                            iva_compra, 
                                            total_compra, 
                                            tipo_compra, 
                                            fecha_vencimiento_credito, 
                                            id_tipo_pago, 
                                            referencia_pago, 
                                            tasa_cambio, 
                                            id_usuario
                                        ) VALUES (?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt_compra = $conn->prepare($sql_insert_compra);
                    if (!$stmt_compra) {
                        throw new Exception("Error preparando la inserción de la compra: " . $conn->error);
                    }

                    $tipo_compra = $is_credit ? 'credito' : 'contado';
                    $stmt_compra->bind_param(
                        "sdddsisdsi",
                        $provider_rif,
                        $subtotal_compra,
                        $iva_compra,
                        $total_compra,
                        $tipo_compra,
                        $due_date,
                        $payment_method_id,
                        $payment_reference,
                        $exchange_rate,
                        $user_id
                    );

                    if (!$stmt_compra->execute()) {
                        throw new Exception("Error al registrar la compra: " . $stmt_compra->error);
                    }
                    $id_compra_insertada = $stmt_compra->insert_id;
                    $stmt_compra->close();

                    // Insertar en la tabla 'detalle_compra' y actualizar stock
                    foreach ($purchase_items as $item) {
                        $sql_insert_detalle = "INSERT INTO detalle_compra (
                                                id_compra, 
                                                id_producto, 
                                                cantidad_comprada, 
                                                costo_unitario
                                            ) VALUES (?, ?, ?, ?)";
                        $stmt_detalle = $conn->prepare($sql_insert_detalle);
                        if (!$stmt_detalle) {
                            throw new Exception("Error preparando la inserción del detalle de compra: " . $conn->error);
                        }
                        $stmt_detalle->bind_param(
                            "iiid",
                            $id_compra_insertada,
                            $item['id'],
                            $item['quantity'],
                            $item['unitCost']
                        );
                        if (!$stmt_detalle->execute()) {
                            throw new Exception("Error al registrar el detalle de compra para producto " . $item['name'] . ": " . $stmt_detalle->error);
                        }
                        $stmt_detalle->close();

                        // Actualizar stock del producto (solo para productos físicos, id_tipo_cuenta = 2)
                        // Primero, obtener el id_tipo_cuenta del producto
                        $stmt_get_product_type = $conn->prepare("SELECT id_tipo_cuenta FROM producto WHERE id_pro = ?");
                        $stmt_get_product_type->bind_param("i", $item['id']);
                        $stmt_get_product_type->execute();
                        $result_product_type = $stmt_get_product_type->get_result();
                        $product_type_row = $result_product_type->fetch_assoc();
                        $stmt_get_product_type->close();

                        if ($product_type_row && $product_type_row['id_tipo_cuenta'] == 2) { // Si es producto físico
                            $sql_update_stock = "UPDATE producto SET cantidad = cantidad + ? WHERE id_pro = ?";
                            $stmt_update_stock = $conn->prepare($sql_update_stock);
                            if (!$stmt_update_stock) {
                                throw new Exception("Error preparando la actualización de stock: " . $conn->error);
                            }
                            $stmt_update_stock->bind_param("ii", $item['quantity'], $item['id']);
                            if (!$stmt_update_stock->execute()) {
                                throw new Exception("Error al actualizar stock para producto " . $item['name'] . ": " . $stmt_update_stock->error);
                            }
                            $stmt_update_stock->close();
                        }
                    }

                    $mensaje_exito = "¡Compra registrada exitosamente con ID: " . $id_compra_insertada . "!";
                    $conn->commit();
                    // Limpiar campos del formulario después del éxito
                    $_POST = array(); // Limpia todos los datos POST
                    header("Location: compras.php?success_purchase=" . urlencode($mensaje_exito)); // Redirige para limpiar POST y mostrar mensaje
                    exit();

                } catch (Exception $e) {
                    $conn->rollback();
                    $mensaje_error = "Error en la transacción al registrar compra: " . $e->getMessage();
                }
            }
        }
    }
}
?>