<?php
session_start();
require_once '../conexion/conexion.php';

$productos = [];
$alert_message = '';
$alert_type = '';

if (!$conn) {
    $alert_message = "Error crítico: No se pudo establecer la conexión a la base de datos. Por favor, contacta al soporte técnico.";
    $alert_type = 'error';
} else {
    try {
        $result_productos_datalist = $conn->query("SELECT id_pro, nombre_producto, costo FROM producto WHERE estado_producto = 1 ORDER BY nombre_producto ASC");
        if ($result_productos_datalist) {
            while ($row = $result_productos_datalist->fetch_assoc()) {
                $productos[] = $row;  
            }
        } else {
            throw new Exception("Error al cargar los productos: " . $conn->error);
        }
    } catch (Exception $e) {
        $alert_message = $e->getMessage();
        $alert_type = 'error';
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre_producto_seleccionado = filter_input(INPUT_POST, 'product_name', FILTER_SANITIZE_STRING);
    $cant_input = filter_input(INPUT_POST, 'cant', FILTER_VALIDATE_INT);

    $id_pro = null;
    $costo_producto = null;
    
    if (!empty($nombre_producto_seleccionado)) {
        $stmt_get_id = $conn->prepare("SELECT id_pro, costo FROM producto WHERE nombre_producto = ? AND estado_producto = 1 LIMIT 1");
        if ($stmt_get_id) {
            $stmt_get_id->bind_param("s", $nombre_producto_seleccionado);
            $stmt_get_id->execute();
            $result_get_id = $stmt_get_id->get_result();
            if ($row_id = $result_get_id->fetch_assoc()) {
                $id_pro = $row_id['id_pro'];
                $costo_producto = (float)$row_id['costo'];
            }
            $stmt_get_id->close();
        }
    }

    if (empty($id_pro)) {
        $alert_message = 'Por favor, selecciona un producto válido de la lista o verifica el nombre.';
        $alert_type = 'error';
    } elseif (empty($cant_input) || $cant_input <= 0) {
        $alert_message = 'Por favor, ingresa una cantidad válida (mayor que cero).';
        $alert_type = 'error';
    } elseif ($costo_producto === null || $costo_producto <= 0) {
        $alert_message = 'El producto seleccionado no tiene un costo válido registrado.';
        $alert_type = 'error';
    } else {
        $cant = $cant_input;
        $conn->begin_transaction();
        try {
            $valor_unitario_base_perdida = $costo_producto;
            $precio_perdida_total = $valor_unitario_base_perdida * $cant;
            $fecha_perdida_actual = date('Y-m-d H:i:s');

            $sql_insert_perdida = "INSERT INTO perdida (cant, precio_perdida, fecha_perdida, id_pro) VALUES (?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert_perdida);
            if (!$stmt_insert) {
                throw new Exception("Error preparando inserción en perdida: " . $conn->error);
            }
            $stmt_insert->bind_param("idsi", $cant, $precio_perdida_total, $fecha_perdida_actual, $id_pro);
            
            if (!$stmt_insert->execute()) {
                throw new Exception("Error al registrar la pérdida: " . $stmt_insert->error);
            }
            $stmt_insert->close();

            $sql_check_stock = "SELECT cantidad FROM producto WHERE id_pro = ?";
            $stmt_check_stock = $conn->prepare($sql_check_stock);
            if (!$stmt_check_stock) {
                throw new Exception("Error preparando la verificación de stock: " . $conn->error);
            }
            $stmt_check_stock->bind_param("i", $id_pro);
            $stmt_check_stock->execute();
            $result_stock = $stmt_check_stock->get_result();
            $producto_stock_data = $result_stock->fetch_assoc();
            $stmt_check_stock->close();

            if (!$producto_stock_data || (int)$producto_stock_data['cantidad'] < $cant) {
                throw new Exception("No hay suficiente stock del producto para registrar esta cantidad de pérdida. Stock actual: " . ($producto_stock_data['cantidad'] ?? 0));
            }

            $sql_update_stock = "UPDATE producto SET cantidad = cantidad - ? WHERE id_pro = ?";
            $stmt_update_stock = $conn->prepare($sql_update_stock);
            if (!$stmt_update_stock) {
                throw new Exception("Error preparando actualización de stock: " . $conn->error);
            }
            $stmt_update_stock->bind_param("ii", $cant, $id_pro);
            if (!$stmt_update_stock->execute()) {
                throw new Exception("Error al actualizar el stock del producto: " . $stmt_update_stock->error);
            }
            $stmt_update_stock->close();

            $id_fondo_principal = 1;

            if ($precio_perdida_total > 0) {
                $sql_update_fondo = "UPDATE fondo SET fondo = fondo - ? WHERE id_fondo = ? AND fondo >= ?";
                $stmt_update_fondo = $conn->prepare($sql_update_fondo);
                if (!$stmt_update_fondo) {
                    throw new Exception("Error preparando actualización de fondo: " . $conn->error);
                }
                $stmt_update_fondo->bind_param("did", $precio_perdida_total, $id_fondo_principal, $precio_perdida_total);
                if (!$stmt_update_fondo->execute()) {
                    throw new Exception("Error al actualizar el fondo: " . $stmt_update_fondo->error);
                }
                if ($stmt_update_fondo->affected_rows === 0) {
                    throw new Exception("No se pudo actualizar el fondo. Verifique que el fondo principal (ID: {$id_fondo_principal}) exista y tenga saldo suficiente.");
                }
                $stmt_update_fondo->close();
            }

            $conn->commit();
            $alert_message = 'Pérdida registrada exitosamente. Monto de pérdida: $' . number_format($precio_perdida_total, 2);
            $alert_type = 'success';

        } catch (Exception $e) {
            $conn->rollback();
            $alert_message = 'Error en la transacción: ' . $e->getMessage();
            $alert_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Pérdida de Producto</title>
    <?php include "../assets/head_gerente.php"?>
    
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; color: #333; }
        .container { background-color: #fff; padding: 20px; margin-top: 50px; margin-left: 200px; border-radius: 8px; box-shadow: 0 2px 10px #0056b3; width: 500px; max-width: 100% ; }
        h1, h2 { color: #333; text-align: center; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input[type="number"], input[type="text"] { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button, .btn { background-color: #3533cd; color: white; padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; text-decoration: none; display: inline-block; }
        button:hover, .btn:hover { background-color:#0056b3; }
        .btn--cancel { background-color: #6c757d; }
        .btn--cancel:hover { background-color: #5a6268; }
        .form-group.buttons { margin-top: 15px; text-align: right; }
        .form-group.buttons .btn { margin-left: 10px; }
        .msg_error { background-color: #f2dede; color: #a94442; border: 1px solid #ebccd1; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .msg_success { background-color: #dff0d8; color: #3c763d; border: 1px solid #d6e9c6; padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            position: relative;
            color: #333;
        }
        
        .success-icon {
            color: #4CAF50;
            font-size: 60px;
            margin-bottom: 10px;
        }

        .error-icon {
            color: #F44336;
            font-size: 60px;
            margin-bottom: 10px;
        }
        
        .product-info {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 4px solid #3533cd;
        }
        
        .product-info p {
            margin: 5px 0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"?>
    <div class="container">
        <h2>Registrar Pérdida de Producto</h2>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="perdidaForm">
            <div class="form-group">
                <label for="product_name">Producto:</label>
                <input type="text" id="product_name" name="product_name" list="productos_datalist" placeholder="Escribe el nombre del producto..." class="input" required>
                
                <datalist id="productos_datalist">
                    <?php foreach ($productos as $producto): ?>
                        <option value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>"
                                data-id="<?php echo htmlspecialchars($producto['id_pro']); ?>"
                                data-costo="<?php echo htmlspecialchars($producto['costo']); ?>">
                    <?php endforeach; ?>
                </datalist>
                
                <!-- Información del producto seleccionado -->
                <div id="productInfo" class="product-info" style="display: none;">
                    <p><strong>Costo unitario:</strong> $<span id="costoUnitario">0.00</span></p>
                    <p><strong>Stock disponible:</strong> <span id="stockDisponible">0</span> unidades</p>
                </div>
            </div>

            <div class="form-group">
                <label for="cant">Cantidad Perdida:</label>
                <input type="number" name="cant" id="cant" min="1" required class="input">
                <p id="costoTotal" style="font-size: 0.9em; color: #666; margin-top: 5px;"></p>
            </div>

            <div class="form-group buttons">
                <button type="submit">Registrar Pérdida</button>
                <a href="../listas/lista_perdida.php" class="btn btn--cancel">Regresar</a>
            </div>
        </form>
    </div>

    <?php if (!empty($alert_message)): ?>
    <div id="miModal" class="modal">
        <div class="modal-content">
            <?php if ($alert_type === 'success'): ?>
                <span class="success-icon">&#10004;</span>
                <h2>Éxito</h2>
            <?php else: ?>
                <span class="error-icon">&#10006;</span>
                <h2>Error</h2>
            <?php endif; ?>
            <p><?php echo htmlspecialchars($alert_message); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('miModal');
            if (modal) {
                modal.style.display = 'flex';
                
                setTimeout(function() {
                    window.location.href = "../listas/lista_perdida.php";
                }, 3000);
            }

            // Función para cargar información del producto seleccionado
            const productNameInput = document.getElementById('product_name');
            const productInfoDiv = document.getElementById('productInfo');
            const costoUnitarioSpan = document.getElementById('costoUnitario');
            const stockDisponibleSpan = document.getElementById('stockDisponible');
            const cantInput = document.getElementById('cant');
            const costoTotalP = document.getElementById('costoTotal');

            if (productNameInput) {
                productNameInput.addEventListener('change', function() {
                    const selectedProduct = this.value;
                    const datalistOptions = document.getElementById('productos_datalist').options;
                    
                    let productFound = false;
                    
                    for (let option of datalistOptions) {
                        if (option.value === selectedProduct) {
                            const costo = option.getAttribute('data-costo');
                            const productId = option.getAttribute('data-id');
                            
                            if (costo && productId) {
                                // Mostrar información del producto
                                costoUnitarioSpan.textContent = parseFloat(costo).toFixed(2);
                                productInfoDiv.style.display = 'block';
                                productFound = true;
                                
                                // Cargar stock disponible via AJAX
                                cargarStockProducto(productId);
                            }
                            break;
                        }
                    }
                    
                    if (!productFound) {
                        productInfoDiv.style.display = 'none';
                        costoTotalP.textContent = '';
                    }
                });
            }

            // Calcular costo total cuando cambia la cantidad
            if (cantInput) {
                cantInput.addEventListener('input', function() {
                    calcularCostoTotal();
                });
            }

            function cargarStockProducto(productId) {
                // Hacer una petición AJAX para obtener el stock actual
                fetch('../funciones/obtener_stock.php?id=' + productId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            stockDisponibleSpan.textContent = data.stock;
                        } else {
                            stockDisponibleSpan.textContent = 'Error';
                        }
                    })
                    .catch(error => {
                        console.error('Error al cargar stock:', error);
                        stockDisponibleSpan.textContent = 'Error';
                    });
            }

            function calcularCostoTotal() {
                const costoUnitario = parseFloat(costoUnitarioSpan.textContent) || 0;
                const cantidad = parseInt(cantInput.value) || 0;
                
                if (costoUnitario > 0 && cantidad > 0) {
                    const costoTotal = costoUnitario * cantidad;
                    costoTotalP.textContent = 'Costo total de la pérdida: $' + costoTotal.toFixed(2);
                } else {
                    costoTotalP.textContent = '';
                }
            }

            // Validar formulario antes de enviar
            const form = document.getElementById('perdidaForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const stockDisponible = parseInt(stockDisponibleSpan.textContent) || 0;
                    const cantidad = parseInt(cantInput.value) || 0;
                    
                    if (cantidad > stockDisponible) {
                        e.preventDefault();
                        alert('Error: La cantidad ingresada (' + cantidad + ') excede el stock disponible (' + stockDisponible + ' unidades).');
                        return false;
                    }
                    
                    if (cantidad <= 0) {
                        e.preventDefault();
                        alert('Error: La cantidad debe ser mayor a cero.');
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>