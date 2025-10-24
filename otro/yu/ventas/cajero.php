<?php
session_start();
require_once '../conexion/conexion.php';

// 1. MODIFICACIÓN: Permitir acceso a tipo de usuario 1 (Gerente) y 2 (Cajero)
if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_tipo_usuario']) || ($_SESSION['id_tipo_usuario'] != 2 && $_SESSION['id_tipo_usuario'] != 1)) {
    header('Location: ../ingreso.php');
    exit();
}

if (!isset($conn) || $conn->connect_error) {
    die("Error crítico: No se pudo establecer la conexión a la base de datos. Por favor, contacte al administrador.");
}

date_default_timezone_set('America/Caracas');

$id_usuario_actual = $_SESSION['id_usuario'];
$nombre_usuario_actual = $_SESSION['nombre_usuario'] ?? 'Usuario';
$id_caja_activa = null;

$alertas = ['success' => [], 'error' => []];

// Esta consulta busca la caja que el usuario actual tiene en estado 'Abierta'.
// Esta consulta busca ANY caja que esté en estado 'Abierta' (sin importar el usuario)
$sql_caja = "SELECT id_caja FROM caja WHERE estado = 'Abierta' LIMIT 1";
$stmt_caja = $conn->prepare($sql_caja);
$stmt_caja->execute();
$resultado_caja = $stmt_caja->get_result();

if ($fila_caja = $resultado_caja->fetch_assoc()) {
    $id_caja_activa = $fila_caja['id_caja'];
} else {
    $id_caja_activa = null;
}
$stmt_caja->close();

// 3. VALIDAR SI SE ENCONTRÓ UNA CAJA ABIERTA
if ($id_caja_activa === null) {
    // Si no hay caja abierta, no se puede procesar la venta.
    $_SESSION['error_venta'] = "No hay una caja abierta en el sistema. Por favor, realiza la apertura de caja antes de registrar una venta.";
    header('Location: caja.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['registrar_cliente_generico'])) {
        $cedula_gen = trim($_POST['cedula_cliente_generico']);
        $nombre_gen = trim($_POST['nombre_cliente_generico']);
        $apellido_gen = trim($_POST['apellido_cliente_generico']);

        if (empty($cedula_gen) || empty($nombre_gen)) {
            $alertas['error'][] = "Cédula y Nombre son obligatorios para cliente genérico.";
        } else {
            try {
                $stmt_check_gen = $conn->prepare("SELECT id_cliente_generico FROM cliente_generico WHERE cedula = ?");
                if (!$stmt_check_gen) {
                    throw new Exception("Error al preparar la consulta de verificación de cliente genérico: " . $conn->error);
                }
                $stmt_check_gen->bind_param("s", $cedula_gen);
                $stmt_check_gen->execute();
                $result_check_gen = $stmt_check_gen->get_result();
                if ($result_check_gen->num_rows > 0) {
                    $alertas['error'][] = "Cliente genérico con cédula {$cedula_gen} ya existe.";
                } else {
                    $stmt_insert_gen = $conn->prepare("INSERT INTO cliente_generico (cedula, nombre, apellido_cliente_generico, id_usuario) VALUES (?, ?, ?, ?)");
                    if (!$stmt_insert_gen) {
                        throw new Exception("Error al preparar la consulta de inserción de cliente genérico: " . $conn->error);
                    }
                    $stmt_insert_gen->bind_param("sssi", $cedula_gen, $nombre_gen, $apellido_gen, $id_usuario_actual);
                    if ($stmt_insert_gen->execute()) {
                        $alertas['success'][] = "Cliente genérico registrado exitosamente.";
                    } else {
                        $alertas['error'][] = "Error al registrar cliente genérico: " . $stmt_insert_gen->error;
                    }
                    $stmt_insert_gen->close();
                }
                $stmt_check_gen->close();
            } catch (Exception $e) {
                $alertas['error'][] = "Error de base de datos: " . $e->getMessage();
            }
        }
    }

    if (isset($_POST['registrar_cliente_mayor'])) {
        $identificacion_mayor = trim($_POST['identificacion_cliente_mayor']);
        $nombre_razon_mayor = trim($_POST['nombre_cliente_mayor']);
        $apellido_mayor = trim($_POST['apellido_cliente_mayor']) ?: null;
        $telefono_mayor = trim($_POST['telefono_cliente_mayor']);
        $correo_mayor = trim($_POST['correo_cliente_mayor']);
        $direccion_mayor = trim($_POST['direccion_cliente_mayor']);

        if (empty($identificacion_mayor) || empty($nombre_razon_mayor) || empty($telefono_mayor) || empty($direccion_mayor)) {
            $alertas['error'][] = "Identificación, Nombre/Razón Social, Teléfono y Dirección son obligatorios para cliente mayorista.";
        } else {
            try {
                $stmt_check_mayor = $conn->prepare("SELECT id_cliente_mayor FROM cliente_mayor WHERE cedula_identidad = ?");
                if (!$stmt_check_mayor) {
                    throw new Exception("Error al preparar la consulta de verificación de cliente mayorista: " . $conn->error);
                }
                $stmt_check_mayor->bind_param("s", $identificacion_mayor);
                $stmt_check_mayor->execute();
                $result_check_mayor = $stmt_check_mayor->get_result();
                if ($result_check_mayor->num_rows > 0) {
                    $alertas['error'][] = "Cliente mayorista con identificación {$identificacion_mayor} ya existe.";
                } else {
                    $stmt_insert_mayor = $conn->prepare("INSERT INTO cliente_mayor (cedula_identidad, nombre, apellido, telefono, correo, direccion, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    if (!$stmt_insert_mayor) {
                        throw new Exception("Error al preparar la consulta de inserción de cliente mayorista: " . $conn->error);
                    }
                    $stmt_insert_mayor->bind_param("ssssssi", $identificacion_mayor, $nombre_razon_mayor, $apellido_mayor, $telefono_mayor, $correo_mayor, $direccion_mayor, $id_usuario_actual);
                    if ($stmt_insert_mayor->execute()) {
                        $alertas['success'][] = "Cliente mayorista registrado exitosamente.";
                    } else {
                        $alertas['error'][] = "Error al registrar cliente mayorista: " . $stmt_insert_mayor->error;
                    }
                    $stmt_insert_mayor->close();
                }
                $stmt_check_mayor->close();
            } catch (Exception $e) {
                $alertas['error'][] = "Error de base de datos: " . $e->getMessage();
            }
        }
    }

    if (isset($_POST['procesar_venta'])) {
        $conn->begin_transaction();
        try {
            $id_cliente_generico_venta = !empty($_POST['id_cliente_generico']) ? (int)$_POST['id_cliente_generico'] : null;
            $id_cliente_mayor_venta = !empty($_POST['id_cliente_mayor']) ? (int)$_POST['id_cliente_mayor'] : null;
            $productos_venta = $_POST['productos'] ?? [];
            $pagos_venta_form = $_POST['pagos'] ?? [];
            $tipo_cliente_seleccionado = $_POST['tipo_cliente_venta'] ?? '';

            // Registrar nuevo cliente genérico si es necesario
            if ($tipo_cliente_seleccionado === 'generico' && empty($id_cliente_generico_venta) && isset($_POST['registrar_nuevo_cliente_generico_flag']) && $_POST['registrar_nuevo_cliente_generico_flag'] === '1') {
                $cedula_gen_nuevo = trim($_POST['cedula_nuevo_cliente_generico']);
                $nombre_gen_nuevo = trim($_POST['nombre_nuevo_cliente_generico']);
                $apellido_gen_nuevo = trim($_POST['apellido_nuevo_cliente_generico']);

                if (empty($cedula_gen_nuevo) || empty($nombre_gen_nuevo)) {
                    throw new Exception("Cédula y Nombre son obligatorios para el nuevo cliente genérico.");
                }
                $stmt_check_gen = $conn->prepare("SELECT id_cliente_generico FROM cliente_generico WHERE cedula = ?");
                if (!$stmt_check_gen) throw new Exception("Error preparando verificación de cliente genérico: " . $conn->error);
                $stmt_check_gen->bind_param("s", $cedula_gen_nuevo);
                $stmt_check_gen->execute();
                if ($stmt_check_gen->get_result()->num_rows > 0) {
                    throw new Exception("Cliente genérico con cédula {$cedula_gen_nuevo} ya existe. Selecciónelo de la lista.");
                }
                $stmt_check_gen->close();

                $stmt_insert_gen = $conn->prepare("INSERT INTO cliente_generico (cedula, nombre, apellido_cliente_generico, id_usuario) VALUES (?, ?, ?, ?)");
                if (!$stmt_insert_gen) throw new Exception("Error preparando inserción de cliente genérico: " . $conn->error);
                $stmt_insert_gen->bind_param("sssi", $cedula_gen_nuevo, $nombre_gen_nuevo, $apellido_gen_nuevo, $id_usuario_actual);
                if ($stmt_insert_gen->execute()) {
                    $id_cliente_generico_venta = $conn->insert_id;
                } else {
                    throw new Exception("Error al registrar nuevo cliente genérico: " . $stmt_insert_gen->error);
                }
                $stmt_insert_gen->close();
            }

            // Registrar nuevo cliente mayorista si es necesario
            if ($tipo_cliente_seleccionado === 'mayorista' && empty($id_cliente_mayor_venta) && isset($_POST['registrar_nuevo_cliente_mayorista_flag']) && $_POST['registrar_nuevo_cliente_mayorista_flag'] === '1') {
                $identificacion_mayor_nuevo = trim($_POST['identificacion_nuevo_cliente_mayor']);
                $nombre_razon_mayor_nuevo = trim($_POST['nombre_nuevo_cliente_mayor']);
                $apellido_mayor_nuevo = trim($_POST['apellido_nuevo_cliente_mayor']) ?: null;
                $telefono_mayor_nuevo = trim($_POST['telefono_nuevo_cliente_mayor']);
                $correo_mayor_nuevo = trim($_POST['correo_nuevo_cliente_mayor']);
                $direccion_mayor_nuevo = trim($_POST['direccion_nuevo_cliente_mayor']);

                if (empty($identificacion_mayor_nuevo) || empty($nombre_razon_mayor_nuevo) || empty($telefono_mayor_nuevo) || empty($direccion_mayor_nuevo)) {
                    throw new Exception("Identificación, Nombre/Razón Social, Teléfono y Dirección son obligatorios para el nuevo cliente mayorista.");
                }
                // Similar verificación e inserción que para cliente genérico, adaptada a cliente_mayor
                $stmt_check_mayor = $conn->prepare("SELECT id_cliente_mayor FROM cliente_mayor WHERE cedula_identidad = ?");
                if (!$stmt_check_mayor) throw new Exception("Error preparando verificación de cliente mayorista: " . $conn->error);
                $stmt_check_mayor->bind_param("s", $identificacion_mayor_nuevo);
                $stmt_check_mayor->execute();
                if ($stmt_check_mayor->get_result()->num_rows > 0) {
                    throw new Exception("Cliente mayorista con identificación {$identificacion_mayor_nuevo} ya existe. Selecciónelo de la lista.");
                }
                $stmt_check_mayor->close();

                $stmt_insert_mayor = $conn->prepare("INSERT INTO cliente_mayor (cedula_identidad, nombre, apellido, telefono, correo, direccion, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt_insert_mayor) throw new Exception("Error preparando inserción de cliente mayorista: " . $conn->error);
                $stmt_insert_mayor->bind_param("ssssssi", $identificacion_mayor_nuevo, $nombre_razon_mayor_nuevo, $apellido_mayor_nuevo, $telefono_mayor_nuevo, $correo_mayor_nuevo, $direccion_mayor_nuevo, $id_usuario_actual);
                if ($stmt_insert_mayor->execute()) {
                    $id_cliente_mayor_venta = $conn->insert_id;
                } else {
                    throw new Exception("Error al registrar nuevo cliente mayorista: " . $stmt_insert_mayor->error);
                }
                $stmt_insert_mayor->close();
            }

            $es_credito = isset($_POST['es_credito_venta']) && $id_cliente_mayor_venta !== null; // Ahora $id_cliente_mayor_venta puede ser de un nuevo cliente
            $fecha_vencimiento_credito = $es_credito ? ($_POST['fecha_vencimiento_credito'] ?: null) : null;

            $id_fondo_venta = 1; // Asumiendo que el fondo de caja es siempre ID 1

            if (empty($productos_venta)) {
                throw new Exception("Debe agregar al menos un producto a la venta.");
            }
            if ($id_cliente_generico_venta && $id_cliente_mayor_venta) {
                throw new Exception("Una venta no puede tener un cliente genérico y mayorista al mismo tiempo.");
            }
            if ($es_credito && !$id_cliente_mayor_venta) {
                throw new Exception("Las ventas a crédito solo aplican a clientes mayoristas.");
            }
            // 2. CORRECCIÓN: Simplificar el INSERT inicial. Los totales se calculan y actualizan después.
            // El estado 'Pendiente' es temporal hasta que se calculen y paguen los totales.
            $estado_venta_inicial = 'Pendiente';
            $stmt_venta = $conn->prepare("INSERT INTO ventas (fecha_venta, id_fondo, id_cliente_generico, id_cliente_mayor, estado_venta, id_usuario_registro, id_caja) VALUES (NOW(), ?, ?, ?, ?, ?, ?)");
            if (!$stmt_venta) throw new Exception("Error al preparar la consulta de inserción de venta: " . $conn->error);
            // Se eliminó el parámetro incorrecto para subtotal_venta y se ajustó el bind_param.
            $stmt_venta->bind_param("iiisii", $id_fondo_venta, $id_cliente_generico_venta, $id_cliente_mayor_venta, $estado_venta_inicial, $id_usuario_actual, $id_caja_activa);
            $stmt_venta->execute();
            $id_nueva_venta = $conn->insert_id;
            if ($id_nueva_venta == 0) {
                throw new Exception("Error al crear la cabecera de la venta: " . $stmt_venta->error);
            }
            $stmt_venta->close();

            $subtotal_venta_calculado = 0;
            $total_iva_venta_calculado = 0;
            $total_neto_venta_calculado = 0;

            foreach ($productos_venta as $prod) {
                $id_pro_actual = (int)$prod['id_pro'];
                $cantidad_vendida_actual = (float)$prod['cantidad'];

                if ($cantidad_vendida_actual <= 0) {
                    throw new Exception("La cantidad para el producto ID {$id_pro_actual} debe ser mayor a cero.");
                }

                $stmt_prod_info = $conn->prepare("SELECT precio, id_iva, id_tipo_cuenta, (SELECT valor_iva FROM iva WHERE iva.id_iva = producto.id_iva) as porcentaje_iva, cantidad as stock_disponible FROM producto WHERE id_pro = ?");
                if (!$stmt_prod_info) {
                    throw new Exception("Error al preparar la consulta de información de producto: " . $conn->error);
                }
                $stmt_prod_info->bind_param("i", $id_pro_actual);
                $stmt_prod_info->execute();
                $res_prod_info = $stmt_prod_info->get_result();
                if ($prod_db = $res_prod_info->fetch_assoc()) {
                    $precio_unitario_actual_sin_iva = (float)$prod_db['precio'];
                    $id_iva_prod_actual = (int)$prod_db['id_iva'];
                    $porcentaje_iva_actual = (float)$prod_db['porcentaje_iva'];
                    $id_tipo_cuenta_prod = (int)$prod_db['id_tipo_cuenta'];
                    $stock_disponible = (float)$prod_db['stock_disponible'];

                    // Validación de stock solo si el tipo de cuenta no es 1 (servicios)
                    if ($id_tipo_cuenta_prod != 1 && $cantidad_vendida_actual > $stock_disponible) {
                        throw new Exception("No hay suficiente stock para el producto ID {$id_pro_actual}. Disponible: {$stock_disponible}, Solicitado: {$cantidad_vendida_actual}.");
                    }

                    $subtotal_linea_sin_iva_calc = $cantidad_vendida_actual * $precio_unitario_actual_sin_iva;
                    $monto_iva_linea_calc = $subtotal_linea_sin_iva_calc * ($porcentaje_iva_actual / 100);
                    $total_linea_con_iva_calc = $subtotal_linea_sin_iva_calc + $monto_iva_linea_calc;

                    $stmt_detalle = $conn->prepare("INSERT INTO detalle_venta (id_ventas, id_pro, cantidad_vendida, precio_unitario_venta_sin_iva, subtotal_linea_sin_iva, id_iva_aplicado, porcentaje_iva_aplicado, monto_iva_linea, total_linea_con_iva) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    if (!$stmt_detalle) {
                        throw new Exception("Error al preparar la consulta de inserción de detalle de venta: " . $conn->error);
                    }
                    $stmt_detalle->bind_param("iidddiddd", $id_nueva_venta, $id_pro_actual, $cantidad_vendida_actual, $precio_unitario_actual_sin_iva, $subtotal_linea_sin_iva_calc, $id_iva_prod_actual, $porcentaje_iva_actual, $monto_iva_linea_calc, $total_linea_con_iva_calc);
                    $stmt_detalle->execute();
                    if ($stmt_detalle->affected_rows == 0) throw new Exception("Error al insertar detalle de venta para producto ID {$id_pro_actual}: " . $stmt_detalle->error);
                    $stmt_detalle->close();

                    // Descontar stock solo si el tipo de cuenta no es 1 (servicios)
                    if ($id_tipo_cuenta_prod != 1) {
                        $stmt_stock = $conn->prepare("UPDATE producto SET cantidad = cantidad - ? WHERE id_pro = ?");
                        if (!$stmt_stock) {
                            throw new Exception("Error al preparar la consulta de actualización de stock: " . $conn->error);
                        }
                        $stmt_stock->bind_param("di", $cantidad_vendida_actual, $id_pro_actual);
                        $stmt_stock->execute();
                        if ($stmt_stock->affected_rows == 0) {
                            throw new Exception("Error al actualizar inventario para producto ID {$id_pro_actual}. (Podría ser un problema de concurrencia o stock insuficiente, aunque ya se validó antes).");
                        }
                        $stmt_stock->close();
                    }

                    $subtotal_venta_calculado += $subtotal_linea_sin_iva_calc;
                    $total_iva_venta_calculado += $monto_iva_linea_calc;
                    $total_neto_venta_calculado += $total_linea_con_iva_calc;

                } else {
                    throw new Exception("Producto con ID {$id_pro_actual} no encontrado en la base de datos.");
                }
                $stmt_prod_info->close();
            }

            $stmt_update_venta_totales = $conn->prepare("UPDATE ventas SET subtotal_venta = ?, total_iva_venta = ?, total_neto_venta = ? WHERE id_ventas = ?");
            if (!$stmt_update_venta_totales) {
                throw new Exception("Error al preparar la consulta de actualización de totales de venta: " . $conn->error);
            }
            $stmt_update_venta_totales->bind_param("dddi", $subtotal_venta_calculado, $total_iva_venta_calculado, $total_neto_venta_calculado, $id_nueva_venta);
            $stmt_update_venta_totales->execute();
            $stmt_update_venta_totales->close();

            $total_pagado_moneda_principal = 0;
            if (!empty($pagos_venta_form)) {
                foreach ($pagos_venta_form as $pago) {
                    $id_tipo_pago_actual = (int)$pago['id_tipo_pago'];
                    $monto_transaccion_actual = (float)$pago['monto_transaccion'];
                    $codigo_moneda_trans_actual = $pago['codigo_moneda_transaccion'] ?: 'USD'; // Moneda principal por defecto
                    $referencia_pago_actual = !empty($pago['referencia_pago']) ? trim($pago['referencia_pago']) : null;
                    $id_tasa_aplicada_pago = null;
                    $monto_pagado_principal_actual = $monto_transaccion_actual;

                    if ($monto_transaccion_actual <= 0) {
                        throw new Exception("El monto de un pago no puede ser cero o negativo.");
                    }

                    if ($codigo_moneda_trans_actual === 'BS' && $monto_transaccion_actual > 0) {
                        $stmt_tasa = $conn->prepare("SELECT id_tasa_dolar, tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1");
                        if (!$stmt_tasa) {
                            throw new Exception("Error al preparar la consulta de tasa de dólar: " . $conn->error);
                        }
                        $stmt_tasa->execute();
                        $res_tasa = $stmt_tasa->get_result();
                        if ($tasa_row = $res_tasa->fetch_assoc()) {
                            $id_tasa_aplicada_pago = $tasa_row['id_tasa_dolar'];
                            $tasa_actual_valor = (float)$tasa_row['tasa_dolar'];
                            if ($tasa_actual_valor > 0) {
                                $monto_pagado_principal_actual = $monto_transaccion_actual / $tasa_actual_valor;
                            } else {
                                throw new Exception("Tasa de dólar inválida (cero) para conversión.");
                            }
                        } else {
                            throw new Exception("No se encontró tasa de dólar actual para conversión de BSa USD.");
                        }
                        $stmt_tasa->close();
                    }

                    $stmt_pago = $conn->prepare("INSERT INTO pagos_venta (id_ventas, id_tipo_pago, monto_pagado_moneda_principal, monto_transaccion, codigo_moneda_transaccion, id_tasa_dolar_aplicada, referencia_pago, id_usuario_registro) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    if (!$stmt_pago) {
                        throw new Exception("Error al preparar la consulta de inserción de pago: " . $conn->error);
                    }
                    $stmt_pago->bind_param("iiddssii", $id_nueva_venta, $id_tipo_pago_actual, $monto_pagado_principal_actual, $monto_transaccion_actual, $codigo_moneda_trans_actual, $id_tasa_aplicada_pago, $referencia_pago_actual, $id_usuario_actual);
                    $stmt_pago->execute();
                    if ($stmt_pago->affected_rows == 0) throw new Exception("Error al registrar un pago: " . $stmt_pago->error);
                    $stmt_pago->close();
                    $total_pagado_moneda_principal += $monto_pagado_principal_actual;
                }
            }

            if ($total_pagado_moneda_principal > 0) {
                $stmt_fondo = $conn->prepare("UPDATE fondo SET fondo = fondo + ? WHERE id_fondo = ?");
                if (!$stmt_fondo) {
                    throw new Exception("Error al preparar la consulta de actualización de fondo: " . $conn->error);
                }
                $stmt_fondo->bind_param("di", $total_pagado_moneda_principal, $id_fondo_venta);
                $stmt_fondo->execute();
                if ($stmt_fondo->affected_rows == 0) throw new Exception("Error al actualizar el fondo.");
                $stmt_fondo->close();
            }

            $estado_venta_final = 'Completada';
            if ($es_credito) {
                $monto_abonado_credito = $total_pagado_moneda_principal;
                $saldo_pendiente_credito = $total_neto_venta_calculado - $monto_abonado_credito;

                $estado_credito_actual = 'Pendiente';
                if ($monto_abonado_credito > 0 && $monto_abonado_credito < $total_neto_venta_calculado - 0.005) { // Tolerancia
                    $estado_credito_actual = 'Pagado Parcialmente';
                } elseif ($monto_abonado_credito >= $total_neto_venta_calculado - 0.005) { // Tolerancia
                    $estado_credito_actual = 'Pagado Totalmente';
                }

                if ($saldo_pendiente_credito <= 0.005) { // Si el saldo pendiente es prácticamente cero
                    $estado_venta_final = 'Completada';
                    $estado_credito_actual = 'Pagado Totalmente';
                } else {
                    $estado_venta_final = 'Credito Pendiente';
                }

                $stmt_credito = $conn->prepare("INSERT INTO creditos_venta (id_ventas, monto_total_credito, monto_abonado, fecha_credito, fecha_vencimiento, estado_credito, id_usuario_registro) VALUES (?, ?, ?, CURDATE(), ?, ?, ?)");
                if (!$stmt_credito) {
                    throw new Exception("Error al preparar la consulta de inserción de crédito: " . $conn->error);
                }
                $stmt_credito->bind_param("iddssi", $id_nueva_venta, $total_neto_venta_calculado, $monto_abonado_credito, $fecha_vencimiento_credito, $estado_credito_actual, $id_usuario_actual); // saldo_pendiente y notas_credito se omiten, usarán DEFAULT o NULL
                $stmt_credito->execute();
                if ($stmt_credito->affected_rows == 0) throw new Exception("Error al registrar el crédito de la venta: " . $stmt_credito->error);
                $stmt_credito->close();
            } else {
                if ($total_pagado_moneda_principal >= ($total_neto_venta_calculado - 0.005) || $total_neto_venta_calculado == 0) { // Tolerancia para comparar flotantes
                    $estado_venta_final = 'Completada';
                } else {
                    $estado_venta_final = 'Pago Pendiente'; // Si no es crédito y falta pagar
                }
            }

            $stmt_estado_venta = $conn->prepare("UPDATE ventas SET estado_venta = ? WHERE id_ventas = ?");
            if (!$stmt_estado_venta) {
                throw new Exception("Error al preparar la consulta de actualización de estado de venta: " . $conn->error);
            }
            $stmt_estado_venta->bind_param("si", $estado_venta_final, $id_nueva_venta);
            $stmt_estado_venta->execute();
            $stmt_estado_venta->close();

            $conn->commit();
            $alertas['success'][] = "Venta #{$id_nueva_venta} procesada exitosamente.";
            // Guardar datos para ticket y abrir ventana
            $_SESSION['ticket_id'] = $id_nueva_venta;
        } catch (Exception $e) {
            $conn->rollback();
            $alertas['error'][] = "Error al procesar la venta: " . $e->getMessage();
        }
    }
}

$action = $_GET['action'] ?? 'nueva_venta';

function obtener_clientes_genericos($conn) {
    $clientes = [];
    $result = $conn->query("SELECT id_cliente_generico, cedula, nombre, apellido_cliente_generico FROM cliente_generico ORDER BY nombre, apellido_cliente_generico");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
        $result->free(); // ✅ CORRECTO
    }
    return $clientes;
}

function obtener_clientes_mayoristas($conn) {
    $clientes = [];
    $result = $conn->query("SELECT id_cliente_mayor, cedula_identidad, nombre, apellido FROM cliente_mayor ORDER BY nombre, apellido");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
        $result->free(); // ✅ CORRECTO
    }
    return $clientes;
}

function obtener_productos_activos($conn) {
    $productos = [];
    $result = $conn->query("SELECT p.id_pro, p.nombre_producto, p.precio, p.codigo, p.cantidad, p.id_tipo_cuenta, i.valor_iva
                            FROM producto p
                            JOIN iva i ON p.id_iva = i.id_iva
                            WHERE p.estado_producto = '1' 
                            AND p.materia_prima = 0
                            ORDER BY p.nombre_producto");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        $result->free(); // Liberar el conjunto de resultados
    }
    return $productos;
}

function obtener_tipos_pago($conn) {
    $tipos = [];
    $result = $conn->query("SELECT id_tipo_pago, tipo_pago FROM tipo_pago ORDER BY tipo_pago");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
        $result->free(); // Liberar el conjunto de resultados
    }
    return $tipos;
}

$tasa_dolar_actual = 0;
// Usar prepare/execute para mayor seguridad y manejo de errores consistente, aunque para un SELECT simple query() es aceptable
$stmt_tasa_actual_query = $conn->query("SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1");
if($stmt_tasa_actual_query){
    if($tasa_row_actual = $stmt_tasa_actual_query->fetch_assoc()){
        $tasa_dolar_actual = (float)$tasa_row_actual['tasa_dolar'];
    }
    $stmt_tasa_actual_query->free(); // ✅ CORRECTO
} else {
    $alertas['error'][] = "Error al obtener la tasa de dólar actual: " . $conn->error;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo Cajero - Sistema Yu</title>

    
 <link rel="stylesheet" href="assets/fonts/google-icons/index.css">
    <link rel="stylesheet" href="../assets/css/cajero.css">

</head>
<body>
    <?php include "menu_cajero.php"; ?>

    <div class="container">
        <?php foreach ($alertas['success'] as $msg): ?>
            <div class="alert-success"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>
        <?php foreach ($alertas['error'] as $msg): ?>
            <div class="alert-error"><?php echo htmlspecialchars($msg); ?></div>
        <?php endforeach; ?>

    
        <?php if ($action === 'nueva_venta'):
            $lista_clientes_genericos = obtener_clientes_genericos($conn);
            $lista_clientes_mayoristas = obtener_clientes_mayoristas($conn);
            $lista_productos = obtener_productos_activos($conn);
            $lista_tipos_pago = obtener_tipos_pago($conn);
        ?>
        <script>
// Mover aquí las variables JavaScript
let listaClientesGenericos = <?= json_encode($lista_clientes_genericos) ?>;
let listaClientesMayoristas = <?= json_encode($lista_clientes_mayoristas) ?>;
</script>
            <h2><span class="material-symbols-outlined ico-shopping_cart" style="vertical-align: middle;"></span> Nueva Venta</h2>
            <form action="cajero.php?action=nueva_venta" method="POST" id="form_nueva_venta">
                <input type="hidden" name="procesar_venta" value="1">

<div class="form-section">
    <h3>Datos del Cliente</h3>
    <div class="form-group">
        <label for="tipo_cliente_venta">Tipo de Cliente:</label>
        <select name="tipo_cliente_venta" id="tipo_cliente_venta" class="form-control" required>
            <option value="">Seleccione...</option>
            <option value="generico">Cliente Genérico</option>
            <option value="mayorista">Cliente Mayorista</option>
        </select>
    </div>
    
    <!-- Cliente Genérico -->
    <div class="form-group" id="cliente_generico_select_div" style="display:none;">
        <label for="buscar_cliente_generico">Buscar Cliente Genérico:</label>
        <div class="search-container">
            <input type="text" id="buscar_cliente_generico" class="form-control" placeholder="Buscar por cédula, nombre o apellido...">
            <div id="resultados_cliente_generico" class="search-results"></div>
        </div>
        <input type="hidden" name="id_cliente_generico" id="id_cliente_generico" value="">
        <div id="info_cliente_generico_seleccionado" style="margin-top: 10px; padding: 10px; background-color: #e9ffe9; border-radius: 5px; display: none;">
            <strong>Cliente seleccionado:</strong> <span id="cliente_generico_nombre"></span> - <span id="cliente_generico_cedula"></span>
            <button type="button" id="quitar_cliente_generico" style="margin-left: 10px;" class="btn btn-sm btn-danger">Quitar</button>
        </div>
        <button type="button" id="btn_registrar_cliente_generico" class="btn" style="background-color: #28a745; margin-top: 10px;">
            + Registrar Nuevo Cliente Genérico
        </button>
        
        <!-- Campos para nuevo cliente genérico (ocultos inicialmente) -->
        <div id="nuevo_cliente_generico_fields" class="hidden-fields">
            <h4>Registrar Nuevo Cliente Genérico</h4>
            <input type="hidden" name="registrar_nuevo_cliente_generico_flag" id="registrar_nuevo_cliente_generico_flag" value="0">
            <div class="form-group">
                <label for="cedula_nuevo_cliente_generico">Cédula:</label>
                <input type="text" name="cedula_nuevo_cliente_generico" id="cedula_nuevo_cliente_generico" class="form-control">
            </div>
            <div class="form-group">
                <label for="nombre_nuevo_cliente_generico">Nombre:</label>
                <input type="text" name="nombre_nuevo_cliente_generico" id="nombre_nuevo_cliente_generico" class="form-control">
            </div>
            <div class="form-group">
                <label for="apellido_nuevo_cliente_generico">Apellido:</label>
                <input type="text" name="apellido_nuevo_cliente_generico" id="apellido_nuevo_cliente_generico" class="form-control">
            </div>
            <button type="button" id="cancelar_registro_generico" class="btn btn-secondary">Cancelar</button>
        </div>
    </div>
    
    <!-- Cliente Mayorista -->
    <div class="form-group" id="cliente_mayor_select_div" style="display:none;">
        <label for="buscar_cliente_mayorista">Buscar Cliente Mayorista:</label>
        <div class="search-container">
            <input type="text" id="buscar_cliente_mayorista" class="form-control" placeholder="Buscar por identificación, nombre o razón social...">
            <div id="resultados_cliente_mayorista" class="search-results"></div>
        </div>
        <input type="hidden" name="id_cliente_mayor" id="id_cliente_mayor" value="">
        <div id="info_cliente_mayor_seleccionado" style="margin-top: 10px; padding: 10px; background-color: #e9ffe9; border-radius: 5px; display: none;">
            <strong>Cliente seleccionado:</strong> <span id="cliente_mayor_nombre"></span> - <span id="cliente_mayor_identificacion"></span>
            <button type="button" id="quitar_cliente_mayor" style="margin-left: 10px;" class="btn btn-sm btn-danger">Quitar</button>
        </div>
        <button type="button" id="btn_registrar_cliente_mayorista" class="btn" style="background-color: #28a745; margin-top: 10px;">
            + Registrar Nuevo Cliente Mayorista
        </button>
        
        <!-- Campos para nuevo cliente mayorista (ocultos inicialmente) -->
        <div id="nuevo_cliente_mayorista_fields" class="hidden-fields">
            <h4>Registrar Nuevo Cliente Mayorista</h4>
            <input type="hidden" name="registrar_nuevo_cliente_mayorista_flag" id="registrar_nuevo_cliente_mayorista_flag" value="0">
            <div class="form-group">
                <label for="identificacion_nuevo_cliente_mayor">Cédula/RIF:</label>
                <input type="text" name="identificacion_nuevo_cliente_mayor" id="identificacion_nuevo_cliente_mayor" class="form-control">
            </div>
            <div class="form-group">
                <label for="nombre_nuevo_cliente_mayor">Nombre o Razón Social:</label>
                <input type="text" name="nombre_nuevo_cliente_mayor" id="nombre_nuevo_cliente_mayor" class="form-control">
            </div>
            <div class="form-group">
                <label for="apellido_nuevo_cliente_mayor">Apellido (si aplica):</label>
                <input type="text" name="apellido_nuevo_cliente_mayor" id="apellido_nuevo_cliente_mayor" class="form-control">
            </div>
            <div class="form-group">
                <label for="telefono_nuevo_cliente_mayor">Teléfono:</label>
                <input type="text" name="telefono_nuevo_cliente_mayor" id="telefono_nuevo_cliente_mayor" class="form-control">
            </div>
            <div class="form-group">
                <label for="correo_nuevo_cliente_mayor">Correo Electrónico:</label>
                <input type="email" name="correo_nuevo_cliente_mayor" id="correo_nuevo_cliente_mayor" class="form-control">
            </div>
            <div class="form-group">
                <label for="direccion_nuevo_cliente_mayor">Dirección:</label>
                <textarea name="direccion_nuevo_cliente_mayor" id="direccion_nuevo_cliente_mayor" class="form-control"></textarea>
            </div>
            <button type="button" id="cancelar_registro_mayorista" class="btn btn-secondary">Cancelar</button>
        </div>
    </div>
</div>

                <div class="form-section">
                    <h3>Productos</h3>
                    <table id="productos_venta_tabla">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unit. (USD)</th>
                                <th>IVA (%)</th>
                                <th>Stock Disponible</th>
                                <th>Subtotal (USD)</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="productos_venta_tbody">
                        </tbody>
                    </table>
                    <button type="button" id="btn_agregar_producto" class="btn" style="background-color: #28a745;">+ Agregar Producto</button>
                    <datalist id="global_product_datalist">
                        <?php foreach ($lista_productos as $prod_item): ?>
                            <option value="<?php echo htmlspecialchars($prod_item['nombre_producto'] . ' (Cod: ' . $prod_item['codigo'] . ')'); ?>"
                                            data-id="<?php echo $prod_item['id_pro']; ?>"
                                            data-precio="<?php echo $prod_item['precio']; ?>"
                                            data-iva_valor="<?php echo $prod_item['valor_iva']; ?>"
                                            data-stock="<?php echo $prod_item['cantidad']; ?>"
                                            data-tipo_cuenta="<?php echo $prod_item['id_tipo_cuenta']; ?>">
                            </option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="form-section">
                    <h3>Pagos</h3>
                    <p>Tasa de Dólar Actual para conversión: <strong><?php echo number_format($tasa_dolar_actual, 4, ',', '.'); ?> BS/USD</strong></p>
                    <table id="pagos_tabla">
                        <thead>
                            <tr>
                                <th>Tipo de Pago</th>
                                <th>Monto Transacción</th>
                                <th>Moneda</th>
                                <th>Referencia</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody id="pagos_tbody">
                        </tbody>
                    </table>
                    <button type="button" id="btn_agregar_pago" class="btn" style="background-color: #17a2b8;">+ Agregar Pago</button>
                </div>

                <div class="form-section" id="credito_section" style="display:none;">
                    <h3>Venta a Crédito (Solo Clientes Mayoristas)</h3>
                    <div class="form-group">
                        <input type="checkbox" name="es_credito_venta" id="es_credito_venta" value="1">
                        <label for="es_credito_venta">Marcar como venta a crédito</label>
                    </div>
                    <div class="form-group" id="fecha_vencimiento_div" style="display:none;">
                        <label for="fecha_vencimiento_credito">Fecha de Vencimiento del Crédito:</label>
                        <input type="date" name="fecha_vencimiento_credito" id="fecha_vencimiento_credito" class="form-control">
                    </div>
                </div>

                <div class="form-section">
                    <h3>Resumen de Venta</h3>
                    <p>Subtotal (USD): <span id="resumen_subtotal_usd">0.00</span> | Subtotal (BS): <span id="resumen_subtotal_bs">0.00</span></p>
                    <p>Total IVA (USD): <span id="resumen_iva_usd">0.00</span> | Total IVA (BS): <span id="resumen_iva_bs">0.00</span></p>
                    <p>
                        <strong>Total Neto (USD):</strong> <strong id="resumen_total_neto_usd">0.00</strong> |
                        <strong>Total Neto (BS):</strong> <strong id="resumen_total_neto_bs">0.00</strong>
                    </p>
                    <p>Total Pagado (USD): <span id="resumen_total_pagado_usd">0.00</span> | Total Pagado (BS): <span id="resumen_total_pagado_bs">0.00</span></p>
                    <p>
                        Cambio/Pendiente (USD): <span id="resumen_cambio_pendiente_usd">0.00</span> |
                        Cambio/Pendiente (BS): <span id="resumen_cambio_pendiente_bs">0.00</span>
                    </p>

                </div>

                <button type="submit" class="btn">Procesar Venta</button>
            </form>

        <?php endif; ?>
    </div>
<script>
// Abrir ticket al finalizar venta - VERSIÓN COMPATIBLE
function abrirTicket(idVenta) {
    if (idVenta && idVenta > 0) {
        var url = 'ticket.php?id=' + idVenta;
        window.open(url, '_blank', 'width=320,height=600');
    }
}

// Llamar al abrir ticket después de procesar la venta
<?php if (isset($_SESSION['ticket_id']) && $_SESSION['ticket_id'] > 0): ?>
    if (document.addEventListener) {
        document.addEventListener('DOMContentLoaded', function() {
            abrirTicket(<?php echo $_SESSION['ticket_id']; ?>);
            <?php unset($_SESSION['ticket_id']); ?>
        });
    } else {
        // Fallback para IE antiguo
        window.attachEvent('onload', function() {
            abrirTicket(<?php echo $_SESSION['ticket_id']; ?>);
            <?php unset($_SESSION['ticket_id']); ?>
        });
    }
<?php endif; ?>
</script>

<script>
// Función de compatibilidad para addEventListener
function addEvent(element, eventName, fn) {
    if (element.addEventListener) {
        element.addEventListener(eventName, fn, false);
    } else if (element.attachEvent) {
        element.attachEvent('on' + eventName, fn);
    }
}

// Función de compatibilidad para querySelector
function $(selector) {
    return document.querySelector(selector);
}

function $$(selector) {
    return document.querySelectorAll(selector);
}

// Polyfill para forEach en NodeList (para navegadores antiguos)
if (window.NodeList && !NodeList.prototype.forEach) {
    NodeList.prototype.forEach = function(callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}

// Polyfill para Array.from (para navegadores antiguos)
if (!Array.from) {
    Array.from = function(object) {
        return [].slice.call(object);
    };
}

document.addEventListener('DOMContentLoaded', function() {
    var tipoClienteSelect = document.getElementById('tipo_cliente_venta');
    var tasaDolarActualJS = parseFloat(<?php echo json_encode($tasa_dolar_actual); ?>);
    var clienteGenericoDiv = document.getElementById('cliente_generico_select_div');
    var clienteMayorDiv = document.getElementById('cliente_mayor_select_div');
    var creditoSection = document.getElementById('credito_section');
    var esCreditoCheckbox = document.getElementById('es_credito_venta');
    var fechaVencimientoDiv = document.getElementById('fecha_vencimiento_div');

    var selectClienteMayor = document.getElementById('id_cliente_mayor');
    var selectClienteGenerico = document.getElementById('id_cliente_generico');

    var nuevoClienteGenericoFields = document.getElementById('nuevo_cliente_generico_fields');
    var nuevoClienteMayoristaFields = document.getElementById('nuevo_cliente_mayorista_fields');

    var flagNuevoClienteGenerico = document.getElementById('registrar_nuevo_cliente_generico_flag');
    var flagNuevoClienteMayorista = document.getElementById('registrar_nuevo_cliente_mayorista_flag');

    var cedulaNuevoGen = document.getElementById('cedula_nuevo_cliente_generico');
    var nombreNuevoGen = document.getElementById('nombre_nuevo_cliente_generico');
    var identificacionNuevoMay = document.getElementById('identificacion_nuevo_cliente_mayor');
    var nombreNuevoMay = document.getElementById('nombre_nuevo_cliente_mayor');
    var telefonoNuevoMay = document.getElementById('telefono_nuevo_cliente_mayor');
    var direccionNuevoMay = document.getElementById('direccion_nuevo_cliente_mayor');

    // Variables para almacenar listas de clientes
    var listaClientesGenericos = <?php echo json_encode($lista_clientes_genericos); ?>;
    var listaClientesMayoristas = <?php echo json_encode($lista_clientes_mayoristas); ?>;

    // Buscador de clientes genéricos - VERSIÓN COMPATIBLE
    var buscarClienteGenerico = document.getElementById('buscar_cliente_generico');
    var resultadosClienteGenerico = document.getElementById('resultados_cliente_generico');

    if (buscarClienteGenerico) {
        addEvent(buscarClienteGenerico, 'input', function() {
            var query = this.value.toLowerCase();
            resultadosClienteGenerico.innerHTML = '';
            resultadosClienteGenerico.style.display = 'none';
            
            if (query.length < 2) return;
            
            var resultados = [];
            for (var i = 0; i < listaClientesGenericos.length; i++) {
                var cliente = listaClientesGenericos[i];
                if (cliente.cedula.toLowerCase().indexOf(query) !== -1 || 
                    cliente.nombre.toLowerCase().indexOf(query) !== -1 ||
                    (cliente.apellido_cliente_generico && cliente.apellido_cliente_generico.toLowerCase().indexOf(query) !== -1)) {
                    resultados.push(cliente);
                }
            }
            
            if (resultados.length > 0) {
                resultadosClienteGenerico.style.display = 'block';
                for (var j = 0; j < resultados.length; j++) {
                    var cliente = resultados[j];
                    var div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.innerHTML = cliente.nombre + ' ' + (cliente.apellido_cliente_generico || '') + ' - ' + cliente.cedula;
                    div.onclick = (function(clienteObj) {
                        return function() { seleccionarClienteGenerico(clienteObj); };
                    })(cliente);
                    resultadosClienteGenerico.appendChild(div);
                }
            }
        });
    }

    // Buscador de clientes mayoristas - VERSIÓN COMPATIBLE
    var buscarClienteMayorista = document.getElementById('buscar_cliente_mayorista');
    var resultadosClienteMayorista = document.getElementById('resultados_cliente_mayorista');

    if (buscarClienteMayorista) {
        addEvent(buscarClienteMayorista, 'input', function() {
            var query = this.value.toLowerCase();
            resultadosClienteMayorista.innerHTML = '';
            resultadosClienteMayorista.style.display = 'none';
            
            if (query.length < 2) return;
            
            var resultados = [];
            for (var i = 0; i < listaClientesMayoristas.length; i++) {
                var cliente = listaClientesMayoristas[i];
                if (cliente.cedula_identidad.toLowerCase().indexOf(query) !== -1 || 
                    cliente.nombre.toLowerCase().indexOf(query) !== -1 ||
                    (cliente.apellido && cliente.apellido.toLowerCase().indexOf(query) !== -1)) {
                    resultados.push(cliente);
                }
            }
            
            if (resultados.length > 0) {
                resultadosClienteMayorista.style.display = 'block';
                for (var j = 0; j < resultados.length; j++) {
                    var cliente = resultados[j];
                    var div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.innerHTML = cliente.nombre + ' ' + (cliente.apellido || '') + ' - ' + cliente.cedula_identidad;
                    div.onclick = (function(clienteObj) {
                        return function() { seleccionarClienteMayorista(clienteObj); };
                    })(cliente);
                    resultadosClienteMayorista.appendChild(div);
                }
            }
        });
    }

    // Funciones para seleccionar clientes
    function seleccionarClienteGenerico(cliente) {
        document.getElementById('id_cliente_generico').value = cliente.id_cliente_generico;
        document.getElementById('cliente_generico_nombre').textContent = cliente.nombre + ' ' + (cliente.apellido_cliente_generico || '');
        document.getElementById('cliente_generico_cedula').textContent = cliente.cedula;
        document.getElementById('info_cliente_generico_seleccionado').style.display = 'block';
        document.getElementById('buscar_cliente_generico').value = '';
        document.getElementById('resultados_cliente_generico').style.display = 'none';
        
        // Ocultar campos de registro si están visibles
        document.getElementById('nuevo_cliente_generico_fields').style.display = 'none';
        document.getElementById('registrar_nuevo_cliente_generico_flag').value = "0";
    }

    function seleccionarClienteMayorista(cliente) {
        document.getElementById('id_cliente_mayor').value = cliente.id_cliente_mayor;
        document.getElementById('cliente_mayor_nombre').textContent = cliente.nombre + ' ' + (cliente.apellido || '');
        document.getElementById('cliente_mayor_identificacion').textContent = cliente.cedula_identidad;
        document.getElementById('info_cliente_mayor_seleccionado').style.display = 'block';
        document.getElementById('buscar_cliente_mayorista').value = '';
        document.getElementById('resultados_cliente_mayorista').style.display = 'none';
        
        // Ocultar campos de registro si están visibles
        document.getElementById('nuevo_cliente_mayorista_fields').style.display = 'none';
        document.getElementById('registrar_nuevo_cliente_mayorista_flag').value = "0";
    }

    // Botones para quitar selección de cliente
    addEvent(document.getElementById('quitar_cliente_generico'), 'click', function() {
        document.getElementById('id_cliente_generico').value = '';
        document.getElementById('info_cliente_generico_seleccionado').style.display = 'none';
    });

    addEvent(document.getElementById('quitar_cliente_mayor'), 'click', function() {
        document.getElementById('id_cliente_mayor').value = '';
        document.getElementById('info_cliente_mayor_seleccionado').style.display = 'none';
    });

    // Botones para registrar nuevos clientes
    addEvent(document.getElementById('btn_registrar_cliente_generico'), 'click', function() {
        document.getElementById('nuevo_cliente_generico_fields').style.display = 'block';
        document.getElementById('registrar_nuevo_cliente_generico_flag').value = "1";
        // Limpiar selección previa si existe
        document.getElementById('id_cliente_generico').value = '';
        document.getElementById('info_cliente_generico_seleccionado').style.display = 'none';
    });

    addEvent(document.getElementById('btn_registrar_cliente_mayorista'), 'click', function() {
        document.getElementById('nuevo_cliente_mayorista_fields').style.display = 'block';
        document.getElementById('registrar_nuevo_cliente_mayorista_flag').value = "1";
        // Limpiar selección previa si existe
        document.getElementById('id_cliente_mayor').value = '';
        document.getElementById('info_cliente_mayor_seleccionado').style.display = 'none';
    });

    // Botones para cancelar registro
    addEvent(document.getElementById('cancelar_registro_generico'), 'click', function() {
        document.getElementById('nuevo_cliente_generico_fields').style.display = 'none';
        document.getElementById('registrar_nuevo_cliente_generico_flag').value = "0";
    });

    addEvent(document.getElementById('cancelar_registro_mayorista'), 'click', function() {
        document.getElementById('nuevo_cliente_mayorista_fields').style.display = 'none';
        document.getElementById('registrar_nuevo_cliente_mayorista_flag').value = "0";
    });

    // Ocultar resultados de búsqueda al hacer clic fuera
    addEvent(document, 'click', function(e) {
        e = e || window.event;
        var target = e.target || e.srcElement;
        
        if (!target.closest) {
            // Polyfill para closest
            target.closest = function(selector) {
                var element = this;
                while (element) {
                    if (element.matches(selector)) return element;
                    element = element.parentElement;
                }
                return null;
            };
        }
        
        if (!target.closest('.search-container')) {
            document.getElementById('resultados_cliente_generico').style.display = 'none';
            document.getElementById('resultados_cliente_mayorista').style.display = 'none';
        }
    });

    // Función para inicializar/actualizar el estado de la sección de crédito y cliente
    function updateClienteAndCreditoVisibility() {
        addEvent(tipoClienteSelect, 'change', updateClienteAndCreditoVisibility);
        
        clienteGenericoDiv.style.display = (tipoClienteSelect.value === 'generico') ? 'block' : 'none';
        clienteMayorDiv.style.display = (tipoClienteSelect.value === 'mayorista') ? 'block' : 'none';

        // Resetear campos de búsqueda y selección
        document.getElementById('buscar_cliente_generico').value = '';
        document.getElementById('resultados_cliente_generico').style.display = 'none';
        document.getElementById('id_cliente_generico').value = '';
        document.getElementById('info_cliente_generico_seleccionado').style.display = 'none';
        
        document.getElementById('buscar_cliente_mayorista').value = '';
        document.getElementById('resultados_cliente_mayorista').style.display = 'none';
        document.getElementById('id_cliente_mayor').value = '';
        document.getElementById('info_cliente_mayor_seleccionado').style.display = 'none';
        
        // Ocultar campos de registro
        document.getElementById('nuevo_cliente_generico_fields').style.display = 'none';
        document.getElementById('registrar_nuevo_cliente_generico_flag').value = "0";
        
        document.getElementById('nuevo_cliente_mayorista_fields').style.display = 'none';
        document.getElementById('registrar_nuevo_cliente_mayorista_flag').value = "0";

        // Lógica para la sección de crédito
        if (tipoClienteSelect.value === 'mayorista') {
            creditoSection.style.display = 'block';
            esCreditoCheckbox.disabled = false;
        } else {
            creditoSection.style.display = 'none';
            esCreditoCheckbox.checked = false;
            esCreditoCheckbox.disabled = true;
            fechaVencimientoDiv.style.display = 'none';
        }
        
        fechaVencimientoDiv.style.display = (esCreditoCheckbox.checked && creditoSection.style.display === 'block') ? 'block' : 'none';
        if (!esCreditoCheckbox.checked) {
            document.getElementById('fecha_vencimiento_credito').value = '';
        }
    }

    // Llamar la función al cargar la página para establecer el estado inicial correcto
    updateClienteAndCreditoVisibility();

    // Lógica para agregar productos a la venta
    var btnAgregarProducto = document.getElementById('btn_agregar_producto');
    var productosVentaTbody = document.getElementById('productos_venta_tbody');
    var globalProductDatalist = document.getElementById('global_product_datalist');

    function calcularTotalesVenta() {
        var subtotal = 0;
        var totalIva = 0;
        var totalNeto = 0;

        var filas = productosVentaTbody.getElementsByTagName('tr');
        for (var i = 0; i < filas.length; i++) {
            var row = filas[i];
            var cantidadInput = row.querySelector('input[name^="productos"][name$="[cantidad]"]');
            var precioUnitarioText = row.querySelector('[data-precio-unitario]').textContent;
            var ivaPorcentajeText = row.querySelector('[data-iva-porcentaje]').textContent;

            var precioUnitario = parseFloat(precioUnitarioText);
            var ivaPorcentaje = parseFloat(ivaPorcentajeText);
            var cantidad = parseInt(cantidadInput.value, 10);

            if (!isNaN(cantidad) && cantidad > 0 && !isNaN(precioUnitario) && !isNaN(ivaPorcentaje)) {
                var subtotalLineaSinIva = cantidad * precioUnitario;
                var montoIvaLinea = subtotalLineaSinIva * (ivaPorcentaje / 100);
                var totalLineaConIva = subtotalLineaSinIva + montoIvaLinea;

                row.querySelector('[data-subtotal-linea]').textContent = subtotalLineaSinIva.toFixed(2);

                subtotal += subtotalLineaSinIva;
                totalIva += montoIvaLinea;
                totalNeto += totalLineaConIva;
            } else {
                row.querySelector('[data-subtotal-linea]').textContent = '0.00';
            }
        }

        document.getElementById('resumen_subtotal_usd').textContent = subtotal.toFixed(2);
        document.getElementById('resumen_iva_usd').textContent = totalIva.toFixed(2);
        document.getElementById('resumen_total_neto_usd').textContent = totalNeto.toFixed(2);

        document.getElementById('resumen_subtotal_bs').textContent = (subtotal * tasaDolarActualJS).toFixed(2);
        document.getElementById('resumen_iva_bs').textContent = (totalIva * tasaDolarActualJS).toFixed(2);
        document.getElementById('resumen_total_neto_bs').textContent = (totalNeto * tasaDolarActualJS).toFixed(2);

        calcularCambioPendiente();
    }

    function calcularCambioPendiente() {
        var totalNeto = parseFloat(document.getElementById('resumen_total_neto_usd').textContent);
        var totalPagado = 0;

        var filasPagos = document.getElementById('pagos_tbody').getElementsByTagName('tr');
        for (var i = 0; i < filasPagos.length; i++) {
            var row = filasPagos[i];
            var montoPrincipalInput = row.querySelector('input[name^="pagos"][name$="[monto_pagado_principal]"]');
            if (montoPrincipalInput) {
                var montoPrincipal = parseFloat(montoPrincipalInput.value);
                if (!isNaN(montoPrincipal)) {
                    totalPagado += montoPrincipal;
                }
            }
        }

        document.getElementById('resumen_total_pagado_usd').textContent = totalPagado.toFixed(2);
        document.getElementById('resumen_total_pagado_bs').textContent = (totalPagado * tasaDolarActualJS).toFixed(2);

        var cambioPendiente = totalPagado - totalNeto;
        document.getElementById('resumen_cambio_pendiente_usd').textContent = cambioPendiente.toFixed(2);
        document.getElementById('resumen_cambio_pendiente_bs').textContent = (cambioPendiente * tasaDolarActualJS).toFixed(2);

        var cambioPendienteElementUSD = document.getElementById('resumen_cambio_pendiente_usd');
        var cambioPendienteElementBS = document.getElementById('resumen_cambio_pendiente_bs');
        var colorCambio = (cambioPendiente < -0.005) ? 'red' : ((cambioPendiente > 0.005) ? 'green' : 'black');

        cambioPendienteElementUSD.style.color = colorCambio;
        cambioPendienteElementBS.style.color = colorCambio;
    }

    addEvent(btnAgregarProducto, 'click', function() {
        var newRow = productosVentaTbody.insertRow();
        var currentIndex = productosVentaTbody.rows.length;
        newRow.innerHTML = '<td><input type="text" list="global_product_datalist" class="form-control product-search" placeholder="Buscar producto" required><input type="hidden" name="productos[' + currentIndex + '][id_pro]" class="product-id"></td><td><input type="number" step="1" name="productos[' + currentIndex + '][cantidad]" class="form-control product-quantity" value="1" required></td><td data-precio-unitario="0.00">0.00</td><td data-iva-porcentaje="0.00">0.00</td><td data-stock-display="N/A">N/A</td><td data-subtotal-linea="0.00">0.00</td><td><button type="button" class="btn btn-danger btn-remove-product" style="background-color: #dc3545;">Quitar</button></td>';

        var productSearchInput = newRow.querySelector('.product-search');
        var quantityInput = newRow.querySelector('.product-quantity');
        var removeButton = newRow.querySelector('.btn-remove-product');

        addEvent(productSearchInput, 'input', function() {
            var selectedOption = null;
            var options = globalProductDatalist.getElementsByTagName('option');
            for (var i = 0; i < options.length; i++) {
                if (options[i].value === this.value) {
                    selectedOption = options[i];
                    break;
                }
            }

            if (!selectedOption) {
                newRow.querySelector('.product-id').value = '';
                newRow.querySelector('[data-precio-unitario]').textContent = '0.00';
                newRow.querySelector('[data-iva-porcentaje]').textContent = '0.00';
                newRow.querySelector('[data-subtotal-linea]').textContent = '0.00';
                newRow.querySelector('[data-stock-display]').textContent = 'N/A';
                quantityInput.removeAttribute('data-stock');
                quantityInput.removeAttribute('data-tipo-cuenta');
                quantityInput.removeAttribute('max');
                calcularTotalesVenta();
                return;
            }

            var productId = selectedOption.getAttribute('data-id');
            var productPrice = selectedOption.getAttribute('data-precio');
            var productIva = selectedOption.getAttribute('data-iva_valor');
            var productStock = selectedOption.getAttribute('data-stock');
            var productTipoCuenta = selectedOption.getAttribute('data-tipo_cuenta');

            newRow.querySelector('.product-id').value = productId;
            newRow.querySelector('[data-precio-unitario]').textContent = parseFloat(productPrice).toFixed(2);
            newRow.querySelector('[data-iva-porcentaje]').textContent = parseFloat(productIva).toFixed(2);
            newRow.querySelector('[data-stock-display]').textContent = productTipoCuenta === '1' ? 'Siempre en Existencia' : parseFloat(productStock).toFixed(2);
            quantityInput.setAttribute('data-stock', productStock);
            quantityInput.setAttribute('data-tipo-cuenta', productTipoCuenta);

            if (productTipoCuenta !== '1') {
                quantityInput.setAttribute('max', parseFloat(productStock));
                if (parseFloat(quantityInput.value) > parseFloat(productStock)) {
                    quantityInput.value = parseFloat(productStock);
                    alert('Cantidad ajustada al stock disponible: ' + productStock);
                }
            } else {
                quantityInput.removeAttribute('data-stock');
                quantityInput.removeAttribute('max');
            }

            calcularTotalesVenta();
        });

        addEvent(quantityInput, 'input', function() {
            var stock = parseFloat(this.getAttribute('data-stock'));
            var tipoCuenta = this.getAttribute('data-tipo-cuenta');
            var requestedQuantity = parseInt(this.value, 10);
            
            if (isNaN(requestedQuantity) || requestedQuantity < 1) {
                this.value = 1;
                requestedQuantity = 1;
            }

            if (tipoCuenta !== '1' && !isNaN(stock) && requestedQuantity > stock) {
                alert('La cantidad excede el stock disponible (' + stock + ').');
                this.value = stock;
            }
            calcularTotalesVenta();
        });

        addEvent(removeButton, 'click', function() {
            newRow.parentNode.removeChild(newRow);
            calcularTotalesVenta();
        });

        calcularTotalesVenta();
    });

    // Lógica para agregar pagos a la venta
    var btnAgregarPago = document.getElementById('btn_agregar_pago');
    var pagosTbody = document.getElementById('pagos_tbody');
    var tasaDolarActual = parseFloat(<?php echo json_encode($tasa_dolar_actual); ?>);

    addEvent(btnAgregarPago, 'click', function() {
        var newRow = pagosTbody.insertRow();
        var currentIndex = pagosTbody.rows.length;
        var tipoPagoOptions = <?= json_encode($lista_tipos_pago) ?>;
        
        var optionsHTML = '';
        for (var i = 0; i < tipoPagoOptions.length; i++) {
            optionsHTML += '<option value="' + tipoPagoOptions[i].id_tipo_pago + '">' + tipoPagoOptions[i].tipo_pago + '</option>';
        }
        
        newRow.innerHTML = '<td><select name="pagos[' + currentIndex + '][id_tipo_pago]" class="form-control tipo-pago-select" required><option value="">Seleccione Tipo</option>' + optionsHTML + '</select></td><td><input type="text" class="form-control monto-transaccion currency-input" name="pagos[' + currentIndex + '][monto_transaccion]" value="0.00" required></td><td><select name="pagos[' + currentIndex + '][codigo_moneda_transaccion]" class="form-control moneda-select"><option value="USD">USD</option><option value="BS">BS</option></select><input type="hidden" name="pagos[' + currentIndex + '][monto_pagado_principal]" class="monto-pagado-principal" value="0.00"></td><td><input type="text" name="pagos[' + currentIndex + '][referencia_pago]" class="form-control referencia-pago"></td><td><button type="button" class="btn btn-danger btn-remove-pago" style="background-color: #dc3545;">Quitar</button></td>';

        var montoTransaccionInput = newRow.querySelector('.monto-transaccion');
        var monedaSelect = newRow.querySelector('.moneda-select');
        var montoPagadoPrincipalInput = newRow.querySelector('.monto-pagado-principal');
        var removeButton = newRow.querySelector('.btn-remove-pago');

        function actualizarMontoPrincipal() {
            var montoTransaccion = parseFloat(montoTransaccionInput.value.replace(/,/g, ''));
            if (isNaN(montoTransaccion) || montoTransaccion <= 0) {
                montoTransaccion = 0.00;
                montoTransaccionInput.value = '0.00';
            }

            if (monedaSelect.value === 'BS') {
                if (tasaDolarActual > 0) {
                    montoPagadoPrincipalInput.value = (montoTransaccion / tasaDolarActualJS).toFixed(2);
                } else {
                    montoPagadoPrincipalInput.value = '0.00';
                    alert('Error: No se puede convertir BS a USD sin una tasa de dólar válida. Por favor, contacte al administrador.');
                }
            } else {
                montoPagadoPrincipalInput.value = montoTransaccion.toFixed(2);
            }
            calcularCambioPendiente();
        }

        addEvent(montoTransaccionInput, 'input', actualizarMontoPrincipal);
        addEvent(monedaSelect, 'change', actualizarMontoPrincipal);
        addEvent(removeButton, 'click', function() {
            newRow.parentNode.removeChild(newRow);
            calcularCambioPendiente();
        });

        actualizarMontoPrincipal();
    });

    // Validar el formulario antes de enviar
    var formNuevaVenta = document.getElementById('form_nueva_venta');
    if (formNuevaVenta) {
        addEvent(formNuevaVenta, 'submit', function(event) {
            var totalNeto = parseFloat(document.getElementById('resumen_total_neto_usd').textContent);
            var totalPagado = parseFloat(document.getElementById('resumen_total_pagado_usd').textContent);
            var esCredito = esCreditoCheckbox.checked;
            var idClienteMayor = document.getElementById('id_cliente_mayor').value;
            var tipoCliente = document.getElementById('tipo_cliente_venta').value;

            // Validación de productos
            if (productosVentaTbody.rows.length === 0) {
                alert('Debe agregar al menos un producto a la venta.');
                event.preventDefault();
                return;
            }

            var productosValidos = true;
            var filas = productosVentaTbody.getElementsByTagName('tr');
            for (var i = 0; i < filas.length; i++) {
                var row = filas[i];
                var productIdInput = row.querySelector('.product-id');
                var productSearchInput = row.querySelector('.product-search');
                var cantidadInput = row.querySelector('.product-quantity');

                if (!productIdInput || productIdInput.value === '') {
                    productosValidos = false;
                    productSearchInput.style.borderColor = 'red';
                } else {
                    productSearchInput.style.borderColor = '';
                }
                if (!cantidadInput || parseFloat(cantidadInput.value) <= 0) {
                    productosValidos = false;
                    cantidadInput.style.borderColor = 'red';
                } else {
                    cantidadInput.style.borderColor = '';
                }
            }

            if (!productosValidos) {
                alert('Por favor, seleccione productos válidos de la lista autocompletable para cada fila.');
                event.preventDefault();
                return;
            }

            // Validar cliente
            if (!tipoCliente) {
                alert('Debe seleccionar un tipo de cliente (Genérico o Mayorista).');
                event.preventDefault();
                return;
            }

            if (tipoCliente === 'generico' && selectClienteGenerico.value === '') {
                if (!cedulaNuevoGen.value || !nombreNuevoGen.value) {
                    alert('Debe seleccionar un cliente genérico o completar los datos para registrar uno nuevo (Cédula y Nombre son obligatorios).');
                    cedulaNuevoGen.style.borderColor = cedulaNuevoGen.value ? '' : 'red';
                    nombreNuevoGen.style.borderColor = nombreNuevoGen.value ? '' : 'red';
                    event.preventDefault();
                    return;
                }
                cedulaNuevoGen.style.borderColor = '';
                nombreNuevoGen.style.borderColor = '';
            }

            if (tipoCliente === 'mayorista' && selectClienteMayor.value === '') {
                if (!identificacionNuevoMay.value || !nombreNuevoMay.value || !telefonoNuevoMay.value || !direccionNuevoMay.value) {
                    alert('Debe seleccionar un cliente mayorista o completar los datos para registrar uno nuevo (Identificación, Nombre, Teléfono y Dirección son obligatorios).');
                    identificacionNuevoMay.style.borderColor = identificacionNuevoMay.value ? '' : 'red';
                    nombreNuevoMay.style.borderColor = nombreNuevoMay.value ? '' : 'red';
                    telefonoNuevoMay.style.borderColor = telefonoNuevoMay.value ? '' : 'red';
                    direccionNuevoMay.style.borderColor = direccionNuevoMay.value ? '' : 'red';
                    event.preventDefault();
                    return;
                }
                identificacionNuevoMay.style.borderColor = '';
                nombreNuevoMay.style.borderColor = '';
            }

            // Validación de pagos y crédito
            if (!esCredito) {
                if (totalPagado < totalNeto - 0.01 && totalNeto > 0) {
                    alert('El monto pagado es menor que el total de la venta. Por favor, agregue más pagos o marque como venta a crédito.');
                    event.preventDefault();
                    return;
                }
            } else {
                if (tipoCliente !== 'mayorista') {
                    alert('Las ventas a crédito solo aplican a clientes mayoristas.');
                    event.preventDefault();
                    return;
                }
                var fechaVencimiento = document.getElementById('fecha_vencimiento_credito').value;
                if (!fechaVencimiento) {
                    alert('Debe especificar una fecha de vencimiento para las ventas a crédito.');
                    event.preventDefault();
                    return;
                }
            }

            if (!esCredito && totalNeto > 0 && pagosTbody.rows.length === 0) {
                alert('Debe registrar al menos un pago para la venta, o marcarla como a crédito si aplica.');
                event.preventDefault();
                return;
            }
        });
    }

    addEvent(document, 'input', function(e) {
        e = e || window.event;
        var target = e.target || e.srcElement;
        
        if (!target.classList.contains('currency-input')) return;

        var value = target.value.replace(/[^\d]/g, '');

        if (value === '') {
            target.value = '0.00';
            return;
        }

        var int = parseInt(value, 10);
        var dollars = Math.floor(int / 100);
        var cents = int % 100;
        var formatted = dollars.toLocaleString('en-US') + '.' + cents.toString().padStart(2, '0');
        target.value = formatted;

        // Disparar el evento interno para recalcular montos
        var evt;
        if (document.createEvent) {
            evt = document.createEvent('HTMLEvents');
            evt.initEvent('input', true, true);
            target.dispatchEvent(evt);
        } else {
            evt = document.createEventObject();
            evt.eventType = 'input';
            target.fireEvent('on' + evt.eventType, evt);
        }
    });

});
</script>
</body>
</html>