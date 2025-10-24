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
$sql_caja = "SELECT id_caja FROM caja WHERE id_usuario = ? AND estado = 'Abierta' LIMIT 1";
$stmt_caja = $conn->prepare($sql_caja);
$stmt_caja->bind_param("i", $id_usuario_actual);
$stmt_caja->execute();
$resultado_caja = $stmt_caja->get_result();

if ($fila_caja = $resultado_caja->fetch_assoc()) {
    $id_caja_activa = $fila_caja['id_caja'];
}
$stmt_caja->close();

// 3. VALIDAR SI SE ENCONTRÓ UNA CAJA ABIERTA
if ($id_caja_activa === null) {
    // Si no hay caja abierta, no se puede procesar la venta.
    // Guardamos un mensaje de error en la sesión y redirigimos al usuario.
    $_SESSION['error_venta'] = "No tienes una caja abierta. Por favor, realiza la apertura de caja antes de registrar una venta.";
    header('Location: caja.php'); // Redirigir a la página de apertura de caja
    exit();
}

// Obtener productos activos
function obtener_productos_activos($conn) {
    $productos = [];
    $result = $conn->query("SELECT p.id_pro, p.nombre_producto, p.precio, p.codigo, p.cantidad, p.id_tipo_cuenta, i.valor_iva
                            FROM producto p
                            JOIN iva i ON p.id_iva = i.id_iva
                            WHERE p.estado_producto = '1'
                            ORDER BY p.nombre_producto");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $productos[] = $row;
        }
        $result->free();
    }
    return $productos;
}

// Obtener tasa de dólar actual
$tasa_dolar_actual = 0;
$stmt_tasa_actual_query = $conn->query("SELECT tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1");
if($stmt_tasa_actual_query){
    if($tasa_row_actual = $stmt_tasa_actual_query->fetch_assoc()){
        $tasa_dolar_actual = (float)$tasa_row_actual['tasa_dolar'];
    }
    $stmt_tasa_actual_query->free();
} else {
    $alertas['error'][] = "Error al obtener la tasa de dólar actual: " . $conn->error;
}

// Obtener clientes genéricos
function obtener_clientes_genericos($conn) {
    $clientes = [];
    $result = $conn->query("SELECT id_cliente_generico, cedula, nombre, apellido_cliente_generico FROM cliente_generico ORDER BY nombre, apellido_cliente_generico");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $clientes[] = $row;
        }
        $result->free();
    }
    return $clientes;
}

// Obtener tipos de pago
function obtener_tipos_pago($conn) {
    $tipos = [];
    $result = $conn->query("SELECT id_tipo_pago, tipo_pago FROM tipo_pago ORDER BY tipo_pago");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $tipos[] = $row;
        }
        $result->free();
    }
    return $tipos;
}

// Procesar venta cuando se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['procesar_venta'])) {
    $conn->begin_transaction();
    try {
        $id_cliente_generico_venta = !empty($_POST['id_cliente_generico']) ? (int)$_POST['id_cliente_generico'] : null;
        $productos_venta = $_POST['productos'] ?? [];
        $pagos_venta_form = $_POST['pagos'] ?? [];
        $tipo_pago = $_POST['tipo_pago'] ?? 1; // Default to Efectivo

        $id_fondo_venta = 1; // Asumiendo que el fondo de caja es siempre ID 1

        if (empty($productos_venta)) {
            throw new Exception("Debe agregar al menos un producto a la venta.");
        }

        // Insertar venta
        $estado_venta_inicial = 'Pendiente';
        $stmt_venta = $conn->prepare("INSERT INTO ventas (fecha_venta, id_fondo, id_cliente_generico, estado_venta, id_usuario_registro, id_caja) VALUES (NOW(), ?, ?, ?, ?, ?)");
        if (!$stmt_venta) throw new Exception("Error al preparar la consulta de inserción de venta: " . $conn->error);
        
        $stmt_venta->bind_param("iisii", $id_fondo_venta, $id_cliente_generico_venta, $estado_venta_inicial, $id_usuario_actual, $id_caja_activa);
        $stmt_venta->execute();
        $id_nueva_venta = $conn->insert_id;
        if ($id_nueva_venta == 0) {
            throw new Exception("Error al crear la cabecera de la venta: " . $stmt_venta->error);
        }
        $stmt_venta->close();

        $subtotal_venta_calculado = 0;
        $total_iva_venta_calculado = 0;
        $total_neto_venta_calculado = 0;

        // Procesar productos
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

                // Insertar detalle de venta
                $stmt_detalle = $conn->prepare("INSERT INTO detalle_venta (id_ventas, id_pro, cantidad_vendida, precio_unitario_venta_sin_iva, subtotal_linea_sin_iva, id_iva_aplicado, porcentaje_iva_aplicado, monto_iva_linea, total_linea_con_iva) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if (!$stmt_detalle) {
                    throw new Exception("Error al preparar la consulta de inserción de detalle de venta: " . $conn->error);
                }
                $stmt_detalle->bind_param("iidddiddd", $id_nueva_venta, $id_pro_actual, $cantidad_vendida_actual, $precio_unitario_actual_sin_iva, $subtotal_linea_sin_iva_calc, $id_iva_prod_actual, $porcentaje_iva_actual, $monto_iva_linea_calc, $total_linea_con_iva_calc);
                $stmt_detalle->execute();
                if ($stmt_detalle->affected_rows == 0) throw new Exception("Error al insertar detalle de venta para producto ID {$id_pro_actual}: " . $stmt_detalle->error);
                $stmt_detalle->close();

                // Descontar stock solo si no es servicio
                if ($id_tipo_cuenta_prod != 1) {
                    $stmt_stock = $conn->prepare("UPDATE producto SET cantidad = cantidad - ? WHERE id_pro = ?");
                    if (!$stmt_stock) {
                        throw new Exception("Error al preparar la consulta de actualización de stock: " . $conn->error);
                    }
                    $stmt_stock->bind_param("di", $cantidad_vendida_actual, $id_pro_actual);
                    $stmt_stock->execute();
                    if ($stmt_stock->affected_rows == 0) {
                        throw new Exception("Error al actualizar inventario para producto ID {$id_pro_actual}.");
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

        // Actualizar totales de venta
        $stmt_update_venta_totales = $conn->prepare("UPDATE ventas SET subtotal_venta = ?, total_iva_venta = ?, total_neto_venta = ? WHERE id_ventas = ?");
        if (!$stmt_update_venta_totales) {
            throw new Exception("Error al preparar la consulta de actualización de totales de venta: " . $conn->error);
        }
        $stmt_update_venta_totales->bind_param("dddi", $subtotal_venta_calculado, $total_iva_venta_calculado, $total_neto_venta_calculado, $id_nueva_venta);
        $stmt_update_venta_totales->execute();
        $stmt_update_venta_totales->close();

        // Procesar pagos
        $total_pagado_moneda_principal = 0;
        $monto_usd = (float)$_POST['monto_usd'];
        $monto_bs = (float)$_POST['monto_bs'];
        
        if ($monto_usd > 0) {
            $stmt_pago = $conn->prepare("INSERT INTO pagos_venta (id_ventas, id_tipo_pago, monto_pagado_moneda_principal, monto_transaccion, codigo_moneda_transaccion, id_usuario_registro) VALUES (?, ?, ?, ?, 'USD', ?)");
            $stmt_pago->bind_param("iiddi", $id_nueva_venta, $tipo_pago, $monto_usd, $monto_usd, $id_usuario_actual);
            $stmt_pago->execute();
            $stmt_pago->close();
            $total_pagado_moneda_principal += $monto_usd;
        }
        
        if ($monto_bs > 0) {
            $monto_usd_equiv = $monto_bs / $tasa_dolar_actual;
            $stmt_pago = $conn->prepare("INSERT INTO pagos_venta (id_ventas, id_tipo_pago, monto_pagado_moneda_principal, monto_transaccion, codigo_moneda_transaccion, id_tasa_dolar_aplicada, id_usuario_registro) VALUES (?, ?, ?, ?, 'BS', ?, ?)");
            $stmt_pago->bind_param("iiddii", $id_nueva_venta, $tipo_pago, $monto_usd_equiv, $monto_bs, $tasa_dolar_actual, $id_usuario_actual);
            $stmt_pago->execute();
            $stmt_pago->close();
            $total_pagado_moneda_principal += $monto_usd_equiv;
        }

        // Actualizar fondo de caja
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

        // Actualizar estado de venta
        $estado_venta_final = 'Completada';
        $stmt_estado_venta = $conn->prepare("UPDATE ventas SET estado_venta = ? WHERE id_ventas = ?");
        if (!$stmt_estado_venta) {
            throw new Exception("Error al preparar la consulta de actualización de estado de venta: " . $conn->error);
        }
        $stmt_estado_venta->bind_param("si", $estado_venta_final, $id_nueva_venta);
        $stmt_estado_venta->execute();
        $stmt_estado_venta->close();

        $conn->commit();
        $alertas['success'][] = "Venta #{$id_nueva_venta} procesada exitosamente.";

    } catch (Exception $e) {
        $conn->rollback();
        $alertas['error'][] = "Error al procesar la venta: " . $e->getMessage();
    }
}

// Obtener datos necesarios
$lista_productos = obtener_productos_activos($conn);
$lista_clientes_genericos = obtener_clientes_genericos($conn);
$lista_tipos_pago = obtener_tipos_pago($conn);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venta Rápida - Sistema Yu</title>
    <style>
    :root {
        --accent: #0077ff;
        --danger: #e60000;
        --success: #22c55e;
        --bg: #f7f9fc;
        --card: #ffffff;
        --text: #222;
    }
    * {
        box-sizing: border-box;
        font-family: system-ui, sans-serif;
    }
    body {
        margin: 0;
        background: var(--bg);
        color: var(--text);
    }
    header {
        background: var(--accent);
        color: #fff;
        padding: .75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    #buscador_rapido {
        position: sticky;
        top: 0;
        z-index: 10;
        width: 100%;
        padding: .75rem 1rem;
        font-size: 1.1rem;
        border: none;
        border-bottom: 1px solid #ccc;
    }
    #resultados_busqueda {
        position: absolute;
        z-index: 20;
        background: var(--card);
        width: 100%;
        max-height: 220px;
        overflow-y: auto;
        border: 1px solid #ccc;
        border-top: none;
        display: none;
    }
    .item {
        padding: .5rem .75rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
    }
    .item:hover, .item.selected {
        background: var(--accent);
        color: #fff;
    }
    main {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1rem;
    }
    #carrito {
        flex: 2;
        min-width: 300px;
        background: var(--card);
        border-radius: .5rem;
        padding: 1rem;
    }
    #resumen {
        flex: 1;
        min-width: 260px;
        background: var(--card);
        border-radius: .5rem;
        padding: 1rem;
        position: sticky;
        top: 4rem;
        height: fit-content;
    }
    .linea {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .35rem 0;
    }
    .linea button {
        background: var(--danger);
        color: #fff;
        border: none;
        border-radius: 3px;
        padding: .25rem .5rem;
        cursor: pointer;
    }
    #btn_pago {
        width: 100%;
        background: var(--accent);
        color: #fff;
        border: none;
        padding: .75rem;
        border-radius: .3rem;
        font-size: 1.1rem;
        cursor: pointer;
        margin-top: 1rem;
    }
    .modal {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,.45);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-content {
        background: var(--card);
        width: 90%;
        max-width: 420px;
        border-radius: .5rem;
        padding: 1rem;
    }
    .modal h3 {
        margin-top: 0;
    }
    .modal-row {
        display: flex;
        justify-content: space-between;
        margin: .5rem 0;
        align-items: center;
    }
    .modal-row input, .modal-row select {
        width: 45%;
        padding: .5rem;
    }
    .toast {
        position: fixed;
        bottom: 1rem;
        right: 1rem;
        background: #222;
        color: #fff;
        padding: .75rem 1.2rem;
        border-radius: .3rem;
        animation: fade .3s;
        z-index: 1100;
    }
    @keyframes fade {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    kbd {
        background: #eee;
        border: 1px solid #ccc;
        border-radius: 3px;
        padding: 2px 4px;
        font-size: .85em;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 4px;
        margin: 10px 0;
    }
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
        margin: 10px 0;
    }
    </style>
</head>
<body>
    <header>
        <span><strong>Venta Rápida</strong> - Sistema Yu</span>
        <span><kbd>Ctrl+B</kbd> buscar <kbd>Ctrl+P</kbd> pagar</span>
    </header>

    <?php foreach ($alertas['success'] as $msg): ?>
        <div class="alert-success"><?php echo htmlspecialchars($msg); ?></div>
    <?php endforeach; ?>
    <?php foreach ($alertas['error'] as $msg): ?>
        <div class="alert-error"><?php echo htmlspecialchars($msg); ?></div>
    <?php endforeach; ?>

    <input id="buscador_rapido" type="text" placeholder="Buscar producto por nombre o código...">
    <div id="resultados_busqueda"></div>

    <main>
        <section id="carrito">
            <h3>Carrito</h3>
            <div id="items"></div>
            <button id="agregar_linea" style="margin-top:.5rem">+ Agregar línea manual</button>
        </section>

        <aside id="resumen">
            <h3>Resumen</h3>
            <div class="linea"><span>Subtotal USD:</span><span id="sub_usd">0.00</span></div>
            <div class="linea"><span>IVA USD:</span><span id="iva_usd">0.00</span></div>
            <div class="linea"><strong>Total USD:</strong><strong id="total_usd">0.00</strong></div>
            <div class="linea"><span>Total BS:</span><span id="total_bs">0.00</span></div>
            <hr>
            <div class="linea">Cliente: <span id="cliente_txt">Público</span> <a href="#" id="cambiar_cliente">Cambiar</a></div>
            <input type="hidden" name="id_cliente_generico" id="id_cliente_generico" value="">
            <button id="btn_pago">💳 PROCESAR VENTA</button>
        </aside>
    </main>

    <!-- Modal de pago -->
    <div id="modal_pago" class="modal">
        <div class="modal-content">
            <h3>Procesar Pago</h3>
            <form id="form_pago" method="POST">
                <input type="hidden" name="procesar_venta" value="1">
                <input type="hidden" name="tipo_pago" id="tipo_pago_input" value="1">
                
                <div class="modal-row">
                    <label>Tipo de Pago:</label>
                    <select id="tipo_pago_select">
                        <?php foreach ($lista_tipos_pago as $tipo): ?>
                            <option value="<?php echo $tipo['id_tipo_pago']; ?>"><?php echo htmlspecialchars($tipo['tipo_pago']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="modal-row">
                    <label>Monto recibido USD:</label>
                    <input id="monto_usd" name="monto_usd" type="number" step="0.01" min="0" value="0">
                </div>
                <div class="modal-row">
                    <label>Monto recibido BS:</label>
                    <input id="monto_bs" name="monto_bs" type="number" step="0.01" min="0" value="0">
                </div>
                <div class="modal-row">
                    <button type="button" id="btn_exacto">Pago exacto</button>
                    <button type="button" id="btn_bsal_dia">BS al día</button>
                </div>
                <hr>
                <div class="modal-row"><span>Cambio USD:</span><span id="cambio_usd">0.00</span></div>
                <div class="modal-row"><span>Cambio BS:</span><span id="cambio_bs">0.00</span></div>
                <div style="display:flex;gap:.5rem;margin-top:1rem">
                    <button type="submit" id="confirmar_pago" style="flex:1">Confirmar</button>
                    <button type="button" id="cancelar_pago" style="flex:1;background:#ccc">Cancelar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de selección de cliente -->
    <div id="modal_cliente" class="modal">
        <div class="modal-content">
            <h3>Seleccionar Cliente</h3>
            <div style="margin-bottom: 1rem;">
                <input type="text" id="buscar_cliente_input" placeholder="Buscar cliente..." style="width: 100%; padding: 8px;">
            </div>
            <div id="lista_clientes" style="max-height: 300px; overflow-y: auto;">
                <div class="item" data-id="" onclick="seleccionarCliente('', 'Público')">
                    <span>Público General</span>
                </div>
                <?php foreach ($lista_clientes_genericos as $cliente): ?>
                    <div class="item" data-id="<?php echo $cliente['id_cliente_generico']; ?>" onclick="seleccionarCliente(<?php echo $cliente['id_cliente_generico']; ?>, '<?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido_cliente_generico']); ?>')">
                        <span><?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido_cliente_generico']); ?></span>
                        <span><?php echo htmlspecialchars($cliente['cedula']); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="margin-top: 1rem; display: flex; gap: 10px;">
                <button type="button" id="confirmar_cliente" style="flex:1">Confirmar</button>
                <button type="button" id="cancelar_cliente" style="flex:1;background:#ccc">Cancelar</button>
            </div>
        </div>
    </div>

<script>
const productos = <?= json_encode($lista_productos) ?>;
const tasa = <?= $tasa_dolar_actual ?>;
let carrito = [];
let clienteSeleccionado = {id: null, nombre: 'Público'};
const d = document;
const $ = id => d.getElementById(id);

// Toast de notificación
function toast(msg, tipo = 'success') {
    const t = d.createElement('div');
    t.className = 'toast';
    t.textContent = msg;
    if (tipo === 'error') t.style.background = 'var(--danger)';
    d.body.appendChild(t);
    setTimeout(() => t.remove(), 3000);
}

// Búsqueda de productos
let idx = -1;
$('buscador_rapido').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    const res = $('resultados_busqueda');
    res.innerHTML = '';
    idx = -1;
    
    if (!q) {
        res.style.display = 'none';
        return;
    }
    
    const filtro = productos.filter(p => 
        p.nombre_producto.toLowerCase().includes(q) || 
        p.codigo.toLowerCase().includes(q)
    );
    
    res.style.display = 'block';
    filtro.forEach((p, i) => {
        const div = d.createElement('div');
        div.className = 'item';
        div.dataset.id = p.id_pro;
        div.innerHTML = `<span>${p.nombre_producto}</span><span>$${p.precio}</span>`;
        div.onclick = () => agregarProducto(p);
        res.appendChild(div);
    });
});

// Navegación con teclado
d.addEventListener('keydown', e => {
    const items = [...$('resultados_busqueda').querySelectorAll('.item')];
    
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        idx = (idx + 1) % items.length;
        actualizarSeleccion(items);
    }
    
    if (e.key === 'ArrowUp') {
        e.preventDefault();
        idx = (idx - 1 + items.length) % items.length;
        actualizarSeleccion(items);
    }
    
    if (e.key === 'Enter' && idx >= 0) {
        e.preventDefault();
        const producto = productos.find(p => p.id_pro == items[idx].dataset.id);
        agregarProducto(producto);
        $('buscador_rapido').value = '';
        $('resultados_busqueda').innerHTML = '';
        idx = -1;
    }
    
    if (e.key === 'Escape') {
        $('resultados_busqueda').innerHTML = '';
        idx = -1;
    }
    
    if (e.ctrlKey && e.key === 'b') {
        e.preventDefault();
        $('buscador_rapido').focus();
    }
    
    if (e.ctrlKey && e.key === 'p') {
        e.preventDefault();
        abrirPago();
    }
});

function actualizarSeleccion(items) {
    items.forEach((it, i) => it.classList.toggle('selected', i === idx));
}

// Gestión del carrito
function agregarProducto(p) {
    const linea = carrito.find(l => l.id_pro === p.id_pro);
    
    if (linea) {
        // Verificar stock si no es servicio
        if (p.id_tipo_cuenta !== 1 && linea.cantidad >= p.cantidad) {
            toast('No hay suficiente stock', 'error');
            return;
        }
        linea.cantidad++;
    } else {
        // Verificar stock para nuevo producto
        if (p.id_tipo_cuenta !== 1 && p.cantidad < 1) {
            toast('No hay stock disponible', 'error');
            return;
        }
        carrito.push({...p, cantidad: 1});
    }
    
    renderCarrito();
    toast('Agregado: ' + p.nombre_producto);
}

function renderCarrito() {
    const cont = $('items');
    cont.innerHTML = '';
    
    // Crear inputs hidden para el formulario
    const form = $('form_pago');
    
    // Eliminar inputs antiguos
    document.querySelectorAll('input[name^="productos"]').forEach(input => input.remove());
    
    carrito.forEach((l, i) => {
        const div = d.createElement('div');
        div.className = 'linea';
        div.innerHTML = `
            <span>${l.nombre_producto} (${l.cantidad})</span>
            <span>$${(l.cantidad * l.precio).toFixed(2)}
            <button onclick="quitarProducto(${i})">✖</button></span>
        `;
        cont.appendChild(div);
        
        // Agregar inputs hidden al formulario
        const idInput = d.createElement('input');
        idInput.type = 'hidden';
        idInput.name = `productos[${i}][id_pro]`;
        idInput.value = l.id_pro;
        form.appendChild(idInput);
        
        const cantInput = d.createElement('input');
        cantInput.type = 'hidden';
        cantInput.name = `productos[${i}][cantidad]`;
        cantInput.value = l.cantidad;
        form.appendChild(cantInput);
    });
    
    calcularTotales();
}

function quitarProducto(i) {
    carrito.splice(i, 1);
    renderCarrito();
    toast('Producto eliminado', 'error');
}

function calcularTotales() {
    let sub = 0, iva = 0;
    
    carrito.forEach(l => {
        sub += l.cantidad * l.precio;
        iva += l.cantidad * l.precio * l.valor_iva / 100;
    });
    
    const total = sub + iva;
    
    $('sub_usd').textContent = sub.toFixed(2);
    $('iva_usd').textContent = iva.toFixed(2);
    $('total_usd').textContent = total.toFixed(2);
    $('total_bs').textContent = (total * tasa).toFixed(2);
}

// Gestión de pagos
$('btn_pago').onclick = abrirPago;

function abrirPago() {
    if (!carrito.length) {
        toast('Carrito vacío', 'error');
        return;
    }
    
    $('modal_pago').style.display = 'flex';
    const total = parseFloat($('total_usd').textContent);
    $('monto_usd').value = total.toFixed(2);
    $('monto_bs').value = '';
    calcularCambio();
}

['monto_usd', 'monto_bs'].forEach(id => $(id).oninput = calcularCambio);

function calcularCambio() {
    const total = parseFloat($('total_usd').textContent);
    const usd = parseFloat($('monto_usd').value) || 0;
    const bs = parseFloat($('monto_bs').value) || 0;
    const usdEquiv = bs / tasa;
    const recibido = usd + usdEquiv;
    const cambio = recibido - total;
    
    $('cambio_usd').textContent = Math.max(0, cambio).toFixed(2);
    $('cambio_bs').textContent = (Math.max(0, cambio) * tasa).toFixed(2);
}

$('btn_exacto').onclick = () => {
    const t = parseFloat($('total_usd').textContent);
    $('monto_usd').value = t.toFixed(2);
    $('monto_bs').value = '';
    calcularCambio();
};

$('btn_bsal_dia').onclick = () => {
    const t = parseFloat($('total_usd').textContent);
    $('monto_bs').value = (t * tasa).toFixed(2);
    $('monto_usd').value = '';
    calcularCambio();
};

$('cancelar_pago').onclick = () => $('modal_pago').style.display = 'none';

// Gestión de clientes
$('cambiar_cliente').onclick = () => {
    $('modal_cliente').style.display = 'flex';
    $('buscar_cliente_input').value = '';
    filtrarClientes();
};

$('cancelar_cliente').onclick = () => $('modal_cliente').style.display = 'none';
$('confirmar_cliente').onclick = () => $('modal_cliente').style.display = 'none';

function seleccionarCliente(id, nombre) {
    clienteSeleccionado = {id, nombre};
    $('id_cliente_generico').value = id;
    $('cliente_txt').textContent = nombre;
    // Cierra el modal de selección
    $('modal_cliente').style.display = 'none';
}

// Búsqueda de clientes
$('buscar_cliente_input').addEventListener('input', filtrarClientes);

function filtrarClientes() {
    const q = $('buscar_cliente_input').value.toLowerCase();
    const lista = $('lista_clientes');
    
    [...lista.querySelectorAll('.item')].forEach(item => {
        const nombre = item.textContent.toLowerCase();
        item.style.display = nombre.includes(q) ? 'flex' : 'none';
    });
}
</script>
</body>
</html>
<?php
$conn->close();
?>
