<?php
session_start();
$mensaje_exito = "";
$mensaje_error = "";
$categorias = [];
$ivas = [];
$tipos_cuenta = [];
$producto = null;
$id_tasa_dolar_actual_db = null;
$valor_tasa_dolar_js = 0.0;

require_once '../conexion/conexion.php';

if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['id_tipo_usuario']) || $_SESSION['id_tipo_usuario'] != 1) {
    header('Location: ../ingreso.php');
    exit();
}

if (!isset($conn) || $conn->connect_error) {
    die("Error fatal: No se pudo conectar a la base de datos. " . ($conn->connect_error ?? "Error desconocido."));
}

$id_producto = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_producto <= 0) {
    header('Location: ../listas/lista_productos.php');
    exit();
}

// Obtener datos del producto
$sql_producto = "SELECT p.*, c.nombre_categoria, i.nombre_iva, i.valor_iva, tc.nom_cuenta 
                 FROM producto p 
                 LEFT JOIN categoria c ON p.id_categoria = c.id_categoria 
                 LEFT JOIN iva i ON p.id_iva = i.id_iva 
                 LEFT JOIN tipo_cuenta tc ON p.id_tipo_cuenta = tc.id_tipo_cuenta 
                 WHERE p.id_pro = ?";
$stmt_producto = $conn->prepare($sql_producto);
$stmt_producto->bind_param("i", $id_producto);
$stmt_producto->execute();
$result_producto = $stmt_producto->get_result();

if ($result_producto->num_rows === 0) {
    $mensaje_error = "Producto no encontrado.";
    $stmt_producto->close();
} else {
    $producto = $result_producto->fetch_assoc();
    $stmt_producto->close();
}

$sql_tasa_actual = "SELECT id_tasa_dolar, tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$result_tasa_actual = $conn->query($sql_tasa_actual);
if ($result_tasa_actual && $result_tasa_actual->num_rows > 0) {
    $row_tasa_actual = $result_tasa_actual->fetch_assoc();
    $id_tasa_dolar_actual_db = intval($row_tasa_actual['id_tasa_dolar']);
    $valor_tasa_dolar_js = floatval($row_tasa_actual['tasa_dolar']);
} else {
    $mensaje_error .= "Error: No se encontró una tasa de cambio activa. ";
}

$sql_categorias = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = 1 ORDER BY nombre_categoria ASC";
$result_categorias = $conn->query($sql_categorias);
if ($result_categorias && $result_categorias->num_rows > 0) {
    while ($row_cat = $result_categorias->fetch_assoc()) {
        $categorias[] = $row_cat;
    }
}

$sql_iva = "SELECT id_iva, nombre_iva, valor_iva FROM iva ORDER BY nombre_iva ASC";
$result_iva = $conn->query($sql_iva);
if ($result_iva && $result_iva->num_rows > 0) {
    while ($row_iva = $result_iva->fetch_assoc()) {
        $ivas[] = $row_iva;
    }
}

$sql_tipos_cuenta = "SELECT id_tipo_cuenta, nom_cuenta FROM tipo_cuenta ORDER BY nom_cuenta ASC";
$result_tipos_cuenta = $conn->query($sql_tipos_cuenta);
if ($result_tipos_cuenta && $result_tipos_cuenta->num_rows > 0) {
    while ($row_tc = $result_tipos_cuenta->fetch_assoc()) {
        $tipos_cuenta[] = $row_tc;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $producto) {
    $nombre_producto = isset($_POST['nombre_producto']) ? htmlspecialchars(trim($_POST['nombre_producto'])) : "";
    $costo_input = isset($_POST['costo']) ? trim($_POST['costo']) : "";
    $ganancia_input = isset($_POST['ganancia']) ? trim($_POST['ganancia']) : "";
    $codigo = isset($_POST['codigo']) ? htmlspecialchars(trim($_POST['codigo'])) : "";
    $codigo_barras = isset($_POST['codigo_barras']) ? htmlspecialchars(trim($_POST['codigo_barras'])) : "";
    $descripcion_prod = isset($_POST['descripcion_prod']) ? htmlspecialchars(trim($_POST['descripcion_prod'])) : "";
    $id_categoria = isset($_POST['id_categoria_hidden']) ? intval($_POST['id_categoria_hidden']) : 0;
    $id_iva = isset($_POST['id_iva']) ? intval($_POST['id_iva']) : 0;
    $id_tipo_cuenta = isset($_POST['id_tipo_cuenta']) ? intval($_POST['id_tipo_cuenta']) : 0;
    $materia_prima = isset($_POST['materia_prima']) ? intval($_POST['materia_prima']) : 0;
    $estado_producto = isset($_POST['estado_producto']) ? intval($_POST['estado_producto']) : 1;

    $costo_numeric = str_replace(',', '.', $costo_input);
    $ganancia_numeric = str_replace(',', '.', $ganancia_input);

    if (empty($nombre_producto)) {
        $mensaje_error .= "Error: El nombre del producto no puede estar vacío. ";
    }
    if (empty($descripcion_prod)) {
        $mensaje_error .= "Error: La descripción del producto no puede estar vacía. ";
    }
    if (!is_numeric($costo_numeric) || floatval($costo_numeric) <= 0) {
        $mensaje_error .= "Error: El costo debe ser un valor numérico positivo. ";
    } else {
        $costo = floatval($costo_numeric);
    }
    if (!is_numeric($ganancia_numeric) || floatval($ganancia_numeric) < 0) {
        $mensaje_error .= "Error: La ganancia debe ser un valor numérico no negativo. ";
    } else {
        $ganancia = floatval($ganancia_numeric);
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

    // Validar código de barras único (excepto para el mismo producto)
    if (!empty($codigo_barras)) {
        $stmt_check_barras = $conn->prepare("SELECT id_pro, nombre_producto FROM producto WHERE codigo_barras = ? AND id_pro != ?");
        if ($stmt_check_barras) {
            $stmt_check_barras->bind_param("si", $codigo_barras, $id_producto);
            $stmt_check_barras->execute();
            $stmt_check_barras->store_result();
            
            if ($stmt_check_barras->num_rows > 0) {
                $stmt_check_barras->bind_result($exist_id, $exist_nombre);
                $stmt_check_barras->fetch();
                $mensaje_error .= "Error: El código de barras '" . htmlspecialchars($codigo_barras) . "' ya está registrado para el producto: " . htmlspecialchars($exist_nombre) . ". ";
            }
            $stmt_check_barras->close();
        }
    }

    if (empty($mensaje_error)) {
        $stmt_check_nombre = $conn->prepare("SELECT id_pro FROM producto WHERE nombre_producto = ? AND id_pro != ?");
        if ($stmt_check_nombre) {
            $stmt_check_nombre->bind_param("si", $nombre_producto, $id_producto);
            $stmt_check_nombre->execute();
            $stmt_check_nombre->store_result();

            if ($stmt_check_nombre->num_rows > 0) {
                $mensaje_error = "Error: El producto con el nombre '" . htmlspecialchars($nombre_producto) . "' ya existe.";
            }
            $stmt_check_nombre->close();
        }
    }

    if (empty($mensaje_error)) {
        $precio_venta_calculado = $costo * (1 + $ganancia / 100);
        $precio = $precio_venta_calculado;
    }

    if (empty($mensaje_error)) {
        $conn->begin_transaction();
        try {
            $sql_update = "UPDATE producto SET 
                nombre_producto = ?,
                precio = ?,
                costo = ?,
                ganancia = ?,
                codigo = ?,
                codigo_barras = ?,
                descrip_prod = ?,
                id_categoria = ?,
                estado_producto = ?,
                id_iva = ?,
                id_tipo_cuenta = ?,
                materia_prima = ?
                WHERE id_pro = ?";

            $stmt_update = $conn->prepare($sql_update);
            
            if (!$stmt_update) {
                throw new Exception("Error preparando la actualización: " . $conn->error);
            }

            $stmt_update->bind_param(
                "sdddsssiiiiii",
                $nombre_producto,
                $precio,
                $costo,
                $ganancia,
                $codigo,
                $codigo_barras,
                $descripcion_prod,
                $id_categoria,
                $estado_producto,
                $id_iva,
                $id_tipo_cuenta,
                $materia_prima,
                $id_producto
            );

            if (!$stmt_update->execute()) {
                throw new Exception("Error al actualizar el producto: " . $stmt_update->error);
            }

            $mensaje_exito = "¡Producto '" . htmlspecialchars($nombre_producto) . "' actualizado exitosamente!";
            $stmt_update->close();

            $producto['nombre_producto'] = $nombre_producto;
            $producto['precio'] = $precio;
            $producto['costo'] = $costo;
            $producto['ganancia'] = $ganancia;
            $producto['codigo'] = $codigo;
            $producto['codigo_barras'] = $codigo_barras;
            $producto['descrip_prod'] = $descripcion_prod;
            $producto['id_categoria'] = $id_categoria;
            $producto['estado_producto'] = $estado_producto;
            $producto['id_iva'] = $id_iva;
            $producto['id_tipo_cuenta'] = $id_tipo_cuenta;
            $producto['materia_prima'] = $materia_prima;

            $conn->commit();

        } catch (Exception $e) {
            $conn->rollback();
            $mensaje_error = "Error en la transacción: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "../assets/head_gerente.php" ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/productos.css">
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128&display=swap" rel="stylesheet">
    <title>Editar Producto</title>
</head>
<body>
    <?php include "../assets/lista_gerente.php" ?>

    <div class="container">
        <h1>Editar Producto</h1>

        <?php if ($mensaje_error && strpos($mensaje_error, 'Producto no encontrado') !== false): ?>
            <div class="mensaje-error"><?php echo htmlspecialchars($mensaje_error); ?></div>
            <div class="navegacion">
                <a href='../listas/lista_productos.php'>Volver a la lista de productos</a>
            </div>
        <?php else: ?>

            <?php
            if (!empty($mensaje_exito)) {
                echo "<div class='mensaje-exito'>" . htmlspecialchars($mensaje_exito) . "</div>";
            }

            if (!empty($mensaje_error)) {
                $errores_lista = explode(" Error:", $mensaje_error);
                echo "<div class='mensaje-error'>";
                foreach ($errores_lista as $i => $err) {
                    if (!empty(trim($err))) {
                        echo ($i > 0 ? "Error:" : "") . htmlspecialchars(trim($err)) . "<br>";
                    }
                }
                echo "</div>";
            }
            ?>

            <?php if ($producto): ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id_producto; ?>" method="post" id="modificarProductoForm">
                <div class="form-group">
                    <label for="nombre_producto">Nombre del Producto:</label>
                    <input type="text" name="nombre_producto" id="nombre_producto" value="<?php echo htmlspecialchars($producto['nombre_producto']); ?>" required autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="costo">Costo (USD):</label>
                    <input type="number" name="costo" id="costo" step="any" min="0.0001" value="<?php echo htmlspecialchars($producto['costo']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="ganancia">Margen de Ganancia (%):</label>
                    <input type="number" name="ganancia" id="ganancia" step="any" min="0" value="<?php echo htmlspecialchars($producto['ganancia']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="precio_venta_display">Precio de Venta Calculado (USD):</label>
                    <p id="precio_venta_display" style="margin-top: 5px; color: #555; font-size: 0.9em;">
                        Precio de venta: <span id="precio_venta_valor"><?php echo number_format($producto['precio'], 2); ?></span>
                    </p>
                    <p id="precio_bs_display" style="margin-top: 5px; color: #555; font-size: 0.9em;">
                        Precio en Bs.S: <span id="precio_bs_valor">Calculando...</span>
                    </p>
                </div>

                <div class="form-group">
                    <label for="categoria_input" class="text">Categoría:</label>
                    <input type="text" list="categorias_datalist" name="categoria_nombre" id="categoria_input" placeholder="Buscar categoría..." class="input" required
                           value="<?php echo htmlspecialchars($producto['nombre_categoria']); ?>" autocomplete="off">
                    <input type="hidden" name="id_categoria_hidden" id="id_categoria_hidden" required
                           value="<?php echo htmlspecialchars($producto['id_categoria']); ?>">
                    <datalist id="categorias_datalist">
                        <?php foreach ($categorias as $cat_item): ?>
                            <option value="<?php echo htmlspecialchars($cat_item['nombre_categoria']); ?>"
                                    data-id="<?php echo htmlspecialchars($cat_item['id_categoria']); ?>">
                            </option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="form-group">
                    <label>Tipo de IVA:</label>
                    <div class="radio-group">
                        <?php foreach ($ivas as $iva_item): ?>
                            <div class="radio-option">
                                <input type="radio" name="id_iva" id="iva_<?php echo $iva_item['id_iva']; ?>"
                                       value="<?php echo htmlspecialchars($iva_item['id_iva']); ?>"
                                       <?php echo ($producto['id_iva'] == $iva_item['id_iva']) ? 'checked' : ''; ?> required>
                                <label for="iva_<?php echo $iva_item['id_iva']; ?>">
                                    <?php echo htmlspecialchars($iva_item['nombre_iva']) . ' (' . htmlspecialchars($iva_item['valor_iva']) . '%)'; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tipo de Cantidad (Stock):</label>
                    <div class="radio-group">
                        <?php foreach ($tipos_cuenta as $tc_item): ?>
                            <div class="radio-option">
                                <input type="radio" name="id_tipo_cuenta" id="tc_<?php echo $tc_item['id_tipo_cuenta']; ?>"
                                       value="<?php echo htmlspecialchars($tc_item['id_tipo_cuenta']); ?>"
                                       <?php echo ($producto['id_tipo_cuenta'] == $tc_item['id_tipo_cuenta']) ? 'checked' : ''; ?> required>
                                <label for="tc_<?php echo $tc_item['id_tipo_cuenta']; ?>">
                                    <?php echo htmlspecialchars($tc_item['nom_cuenta']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="materia_prima">Tipo de Producto:</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" name="materia_prima" id="producto_venta" value="0"
                                   <?php echo ($producto['materia_prima'] == 0) ? 'checked' : ''; ?>>
                            <label for="producto_venta">Producto de Venta</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" name="materia_prima" id="materia_prima" value="1"
                                   <?php echo ($producto['materia_prima'] == 1) ? 'checked' : ''; ?>>
                            <label for="materia_prima">Materia Prima</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="codigo">Código del Producto:</label>
                    <input type="text" name="codigo" id="codigo" value="<?php echo htmlspecialchars($producto['codigo']); ?>">
                </div>

                <div class="form-group">
                    <label for="codigo_barras">Código de Barras:</label>
                    <input type="text" name="codigo_barras" id="codigo_barras" 
                           value="<?php echo htmlspecialchars($producto['codigo_barras']); ?>"
                           placeholder="Escanea o ingresa el código de barras">
                    <small>Dejar en blanco si no tiene código de barras</small>
                </div>

                <div class="form-group">
                    <label for="descripcion_prod">Descripción del Producto:</label>
                    <textarea name="descripcion_prod" id="descripcion_prod" required><?php echo htmlspecialchars($producto['descrip_prod']); ?></textarea>
                </div>



                

                <button type="submit">Actualizar Producto</button>
            </form>

            <div class="navegacion">
                <a href='../listas/lista_productos.php'>Regresar</a>
            </div>

            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const costoInput = document.getElementById('costo');
            const gananciaInput = document.getElementById('ganancia');
            const precioVentaValorSpan = document.getElementById('precio_venta_valor');
            const precioBsValorSpan = document.getElementById('precio_bs_valor');
            const tasaDolar = <?php echo json_encode($valor_tasa_dolar_js); ?>;

            function actualizarPrecios() {
                const costo = parseFloat(costoInput.value);
                const ganancia = parseFloat(gananciaInput.value);

                if (!isNaN(costo) && costo > 0 && !isNaN(ganancia) && ganancia >= 0) {
                    const precioVenta = costo * (1 + ganancia / 100);
                    precioVentaValorSpan.textContent = precioVenta.toFixed(2);
                    
                    if (tasaDolar > 0) {
                        const precioBs = precioVenta * tasaDolar;
                        precioBsValorSpan.textContent = precioBs.toFixed(2);
                    } else {
                        precioBsValorSpan.textContent = 'Tasa no disponible';
                    }
                }
            }

            if (costoInput && gananciaInput) {
                costoInput.addEventListener('input', actualizarPrecios);
                gananciaInput.addEventListener('input', actualizarPrecios);
                actualizarPrecios(); // Calcular inicialmente
            }

            // Manejo de categorías
            const categoriaInput = document.getElementById('categoria_input');
            const idCategoriaHidden = document.getElementById('id_categoria_hidden');
            const categoriasDatalist = document.getElementById('categorias_datalist');

            categoriaInput.addEventListener('input', function() {
                let selectedOption = null;
                for (let i = 0; i < categoriasDatalist.options.length; i++) {
                    if (categoriasDatalist.options[i].value === categoriaInput.value) {
                        selectedOption = categoriasDatalist.options[i];
                        break;
                    }
                }
                idCategoriaHidden.value = selectedOption ? selectedOption.getAttribute('data-id') : '';
            });
            document.addEventListener('keydown', function(e) {
    if (e.target === codigoBarrasInput) return;
    
    const currentTime = Date.now();
    if (currentTime - lastKeyTime > 100) {
        barcodeBuffer = '';
    }
    lastKeyTime = currentTime;
    
    if (e.key === 'Enter' && barcodeBuffer.length > 0) {
        codigoBarrasInput.value = barcodeBuffer;
        codigoBarrasInput.dispatchEvent(new Event('change'));
        barcodeBuffer = '';
        e.preventDefault();
    }
});
            // Validación de código de barras
            const codigoBarrasInput = document.getElementById('codigo_barras');
            codigoBarrasInput.addEventListener('blur', function() {
                const codigo = this.value.trim();
                if (codigo.length > 0) {
                    fetch(`verificar_codigo.php?codigo=${encodeURIComponent(codigo)}&exclude=<?php echo $id_producto; ?>`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.existe) {
                                alert(`❌ El código ${codigo} ya está registrado para: ${data.producto}`);
                                this.focus();
                                this.select();
                            }
                        });
                }
            });
        });
    </script>
</body>
</html>