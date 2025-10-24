<?php
session_start();
$mensaje_exito = "";
$mensaje_error = "";
$categorias = [];
$ivas = [];
$tipos_cuenta = [];
$id_tasa_dolar_actual_db = null;
$valor_tasa_dolar_js = 0.0;

require_once '../conexion/conexion.php';

if (!isset($conn) || $conn->connect_error) {
    die("Error fatal: No se pudo conectar a la base de datos para obtener los datos iniciales. " . ($conn->connect_error ?? "Error desconocido."));
}


$sql_tasa_actual = "SELECT id_tasa_dolar, tasa_dolar FROM tasa_dolar ORDER BY fecha_dolar DESC LIMIT 1";
$result_tasa_actual = $conn->query($sql_tasa_actual);
if ($result_tasa_actual && $result_tasa_actual->num_rows > 0) {
    $row_tasa_actual = $result_tasa_actual->fetch_assoc();
    $id_tasa_dolar_actual_db = intval($row_tasa_actual['id_tasa_dolar']);
    $valor_tasa_dolar_js = floatval($row_tasa_actual['tasa_dolar']);
} else {
    $mensaje_error .= "Error crítico: No se encontró una tasa de cambio activa en la base de datos. Registre una tasa antes de añadir productos. ";
}

$sql_categorias = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = 1 ORDER BY nombre_categoria ASC";
$result_categorias = $conn->query($sql_categorias);
if ($result_categorias && $result_categorias->num_rows > 0) {
    while ($row_cat = $result_categorias->fetch_assoc()) {
        $categorias[] = $row_cat;
    }
} else {
    if (!$result_categorias) {
        $mensaje_error .= "Error al obtener categorías: " . $conn->error . ". ";
    } else {
        $mensaje_error .= "No hay categorías activas disponibles. ";
    }
}

$sql_iva = "SELECT id_iva, nombre_iva, valor_iva FROM iva ORDER BY nombre_iva ASC";
$result_iva = $conn->query($sql_iva);
if ($result_iva && $result_iva->num_rows > 0) {
    while ($row_iva = $result_iva->fetch_assoc()) {
        $ivas[] = $row_iva;
    }
} else {
    if (!$result_iva) {
        $mensaje_error .= "Error al obtener tipos de IVA: " . $conn->error . ". ";
    } else {
        $mensaje_error .= "No hay tipos de IVA disponibles. ";
    }
}

$sql_tipos_cuenta = "SELECT id_tipo_cuenta, nom_cuenta FROM tipo_cuenta ORDER BY nom_cuenta ASC";
$result_tipos_cuenta = $conn->query($sql_tipos_cuenta);
if ($result_tipos_cuenta && $result_tipos_cuenta->num_rows > 0) {
    while ($row_tc = $result_tipos_cuenta->fetch_assoc()) {
        $tipos_cuenta[] = $row_tc;
    }
} else {
    if (!$result_tipos_cuenta) {
        $mensaje_error .= "Error al obtener tipos de cuenta: " . $conn->error . ". ";
    } else {
        $mensaje_error .= "No hay tipos de cuenta disponibles. ";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn) || $conn->connect_error) {
        $mensaje_error = "Error de conexión al procesar el formulario: " . ($conn->connect_error ?? "Desconocido");
    } else {
        $nombre_producto = isset($_POST['nombre_producto']) ? htmlspecialchars(trim($_POST['nombre_producto'])) : "";
        $costo_input = isset($_POST['costo']) ? trim($_POST['costo']) : "";
        $ganancia_input = isset($_POST['ganancia']) ? trim($_POST['ganancia']) : "";
        $codigo = isset($_POST['codigo']) ? htmlspecialchars(trim($_POST['codigo'])) : "";
        $descripcion_prod = isset($_POST['descripcion_prod']) ? htmlspecialchars(trim($_POST['descripcion_prod'])) : "";

        $id_categoria = isset($_POST['id_categoria_hidden']) ? intval($_POST['id_categoria_hidden']) : 0;

        $id_iva = isset($_POST['id_iva']) ? intval($_POST['id_iva']) : 0;
        $id_tipo_cuenta = isset($_POST['id_tipo_cuenta']) ? intval($_POST['id_tipo_cuenta']) : 0;

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
        
        // Calcular el precio de venta si no hay errores
        if (empty($mensaje_error)) {
            $precio_venta_calculado = $costo * (1 + $ganancia / 100);
            $precio = $precio_venta_calculado; // El campo 'precio' en la BD ahora contendrá el precio de venta calculado
        }
        
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

        if (empty($mensaje_error)) {
            $conn->begin_transaction(); 
            try {
                $sql_insert_producto = "INSERT INTO producto (
                                    nombre_producto,
                                    cantidad,
                                    precio,
                                    costo,
                                    ganancia,
                                    codigo,
                                    descrip_prod,
                                    id_tasa_dolar,
                                    id_categoria,
                                    estado_producto,
                                    id_iva,
                                    id_tipo_cuenta
                                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $stmt_insert_producto = $conn->prepare($sql_insert_producto);

                if (!$stmt_insert_producto) {
                    throw new Exception("Error preparando la inserción del producto: " . $conn->error);
                }

                $estado_predeterminado = 1;
                $cantidad_inicial = 0; 

                if ($id_tipo_cuenta == 1) { 
                    $cantidad_inicial = 99999999; 
                } elseif ($id_tipo_cuenta == 2) { 
                    $cantidad_inicial = 0; 
                }
                $stmt_insert_producto->bind_param(
                    "sidddssiiiii",
                    $nombre_producto,
                    $cantidad_inicial,
                    $precio,
                    $costo,
                    $ganancia,
                    $codigo,
                    $descripcion_prod,
                    $id_tasa_dolar_actual_db, 
                    $id_categoria,
                    $estado_predeterminado,
                    $id_iva,
                    $id_tipo_cuenta
                );

                if (!$stmt_insert_producto->execute()) {
                    throw new Exception("Error al registrar el producto: " . $stmt_insert_producto->error);
                }

                $id_producto_insertado = $stmt_insert_producto->insert_id;
                $mensaje_exito = "¡Producto '" . htmlspecialchars($nombre_producto) . "' registrado exitosamente!";
                $stmt_insert_producto->close();

                $conn->commit(); 
                $_POST = array(); 
                $nombre_producto = $costo_input = $ganancia_input = $codigo = $descripcion_prod = "";
                $id_categoria = $id_iva = $id_tipo_cuenta = 0; 

            } catch (Exception $e) {
                $conn->rollback(); 
                $mensaje_error = "Error en la transacción: " . $e->getMessage();
            }
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
    <style>
        /* Estilos del Modal */
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
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
            border-radius: 10px;
            animation-name: animatetop;
            animation-duration: 0.4s
        }

        @keyframes animatetop {
            from {top: -300px; opacity: 0}
            to {top: 0; opacity: 1}
        }

        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .container {
            max-width: 900px;
            margin: auto;
        }
    </style>
    <title>Registro de Productos</title>
</head>
<body>
    <?php include "../assets/lista_gerente.php" ?>

    <div class="container">
        <h1>Gestión de Productos</h1>
        
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
        <button id="openModalBtn">Registrar Nuevo Producto</button>

       
    </div>

    <!-- El Modal -->
    <div id="registroModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeModalBtn">&times;</span>
            <h2>Registro de Productos</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registroProductoForm">
                <div class="form-group">
                    <label for="nombre_producto">Nombre del Producto:</label>
                    <input type="text" name="nombre_producto" id="nombre_producto" value="<?php echo isset($_POST['nombre_producto']) ? htmlspecialchars($_POST['nombre_producto']) : ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="costo">Costo (USD):</label>
                    <input type="number" name="costo" id="costo" step="any" min="0.0001" value="<?php echo isset($_POST['costo']) ? htmlspecialchars($_POST['costo']) : ''; ?>" required pattern="[0-9]+([,.][0-9]+)?" title="Ingrese el costo del producto en USD (ej: 10.50 o 0.15)">
                </div>

                <div class="form-group">
                    <label for="ganancia">Margen de Ganancia (%):</label>
                    <input type="number" name="ganancia" id="ganancia" step="any" min="0" value="<?php echo isset($_POST['ganancia']) ? htmlspecialchars($_POST['ganancia']) : '0'; ?>" required pattern="[0-9]+([,.][0-9]+)?" title="Ingrese el porcentaje de ganancia (ej: 20 para 20%)">
                </div>

                <div class="form-group">
                    <label for="precio_venta_display">Precio de Venta Calculado (USD):</label>
                    <p id="precio_venta_display" style="margin-top: 5px; color: #555; font-size: 0.9em;">
                        Precio de venta: <span id="precio_venta_valor">0.00</span>
                    </p>
                    <p id="precio_bs_display" style="margin-top: 5px; color: #555; font-size: 0.9em;">
                        Precio en Bs.S: <span id="precio_bs_valor">Calculando...</span>
                    </p>
                </div>

                <div class="form-group">
                    <label for="categoria_input" class="text">Categoría:</label>
                    <input type="text" list="categorias_datalist" name="categoria_nombre" id="categoria_input" placeholder="Buscar categoría..." class="input" required
                           value="<?php
                           if (isset($_POST['id_categoria_hidden']) && $_POST['id_categoria_hidden'] > 0) {
                               $selected_cat_id = $_POST['id_categoria_hidden'];
                               $found_cat_name = '';
                               foreach ($categorias as $cat) {
                                   if ($cat['id_categoria'] == $selected_cat_id) {
                                       $found_cat_name = $cat['nombre_categoria'];
                                       break;
                                   }
                               }
                               echo htmlspecialchars($found_cat_name);
                           }
                           ?>">
                    <input type="hidden" name="id_categoria_hidden" id="id_categoria_hidden" required
                           value="<?php echo isset($_POST['id_categoria_hidden']) ? htmlspecialchars($_POST['id_categoria_hidden']) : ''; ?>">

                    <datalist id="categorias_datalist">
                        <?php
                        foreach ($categorias as $cat_item):
                            ?>
                            <option value="<?php echo htmlspecialchars($cat_item['nombre_categoria']); ?>"
                                    data-id="<?php echo htmlspecialchars($cat_item['id_categoria']); ?>">
                            </option>
                        <?php endforeach; ?>
                    </datalist>
                    <?php if (empty($categorias) && empty($mensaje_error)): ?>
                        <p style="color: orange;">Advertencia: No hay categorías activas registradas. Debería agregar al menos una.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Tipo de IVA:</label>
                    <div class="radio-group">
                        <?php if (empty($ivas)): ?>
                            <p style="color: #777;">No hay tipos de IVA disponibles</p>
                        <?php else: ?>
                            <?php foreach ($ivas as $iva_item): ?>
                                <div class="radio-option">
                                    <input type="radio"
                                           name="id_iva"
                                           id="iva_<?php echo $iva_item['id_iva']; ?>"
                                           value="<?php echo htmlspecialchars($iva_item['id_iva']); ?>"
                                           <?php echo (isset($_POST['id_iva']) && $_POST['id_iva'] == $iva_item['id_iva']) ? 'checked' : ''; ?>
                                           <?php if ($iva_item === reset($ivas)) echo 'required'; ?>
                                           style="margin-right: 5px;">
                                    <label for="iva_<?php echo $iva_item['id_iva']; ?>">
                                        <?php echo htmlspecialchars($iva_item['nombre_iva']) . (isset($iva_item['valor_iva']) ? ' (' . htmlspecialchars($iva_item['valor_iva']) . '%)' : ''); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tipo de Cantidad (Stock):</label>
                    <div class="radio-group">
                        <?php if (empty($tipos_cuenta)): ?>
                            <p style="color: #777;">No hay tipos de cuenta disponibles</p>
                        <?php else: ?>
                            <?php foreach ($tipos_cuenta as $tc_item): ?>
                                <div class="radio-option">
                                    <input type="radio"
                                           name="id_tipo_cuenta"
                                           id="tc_<?php echo $tc_item['id_tipo_cuenta']; ?>"
                                           value="<?php echo htmlspecialchars($tc_item['id_tipo_cuenta']); ?>"
                                           <?php echo (isset($_POST['id_tipo_cuenta']) && $_POST['id_tipo_cuenta'] == $tc_item['id_tipo_cuenta']) ? 'checked' : ''; ?>
                                           <?php if ($tc_item === reset($tipos_cuenta)) echo 'required'; ?>
                                           style="margin-right: 5px;">
                                    <label for="tc_<?php echo $tc_item['id_tipo_cuenta']; ?>">
                                        <?php echo htmlspecialchars($tc_item['nom_cuenta']); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php if (empty($tipos_cuenta) && empty($mensaje_error)): ?>
                        <p style="color: orange;">Advertencia: No hay tipos de cuenta registrados.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="codigo">Código del Producto:</label>
                    <input type="text" name="codigo" id="codigo" value="<?php echo isset($_POST['codigo']) ? htmlspecialchars($_POST['codigo']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="descripcion_prod">Descripción del Producto:</label>
                    <textarea name="descripcion_prod" id="descripcion_prod" required><?php echo isset($_POST['descripcion_prod']) ? htmlspecialchars($_POST['descripcion_prod']) : ''; ?></textarea>
                </div>

                <button type="submit" <?php if (empty($categorias) || empty($ivas) || empty($tipos_cuenta) || $id_tasa_dolar_actual_db === null) echo 'disabled'; ?>>Registrar Producto</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lógica de cálculo de precios
            const costoInput = document.getElementById('costo');
            const gananciaInput = document.getElementById('ganancia');
            const precioVentaValorSpan = document.getElementById('precio_venta_valor');
            const precioBsValorSpan = document.getElementById('precio_bs_valor');
            const tasaDolar = <?php echo json_encode($valor_tasa_dolar_js); ?>;

            function actualizarPrecios() {
                const costoString = costoInput.value;
                const gananciaString = gananciaInput.value;
                const costo = parseFloat(costoString.replace(',', '.'));
                const ganancia = parseFloat(gananciaString.replace(',', '.'));

                if (!isNaN(costo) && costo > 0 && !isNaN(ganancia) && ganancia >= 0) {
                    const precioVenta = costo * (1 + ganancia / 100);
                    precioVentaValorSpan.textContent = precioVenta.toLocaleString('es-VE', { style: 'currency', currency: 'USD', minimumFractionDigits: 2, maximumFractionDigits: 4 });

                    if (tasaDolar > 0) {
                        const precioBs = precioVenta * tasaDolar;
                        precioBsValorSpan.textContent = precioBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    } else {
                        precioBsValorSpan.textContent = 'Tasa de cambio no disponible';
                    }
                } else if (tasaDolar <= 0) {
                    precioBsValorSpan.textContent = 'Tasa de cambio no disponible';
                    precioVentaValorSpan.textContent = '0.00';
                } else {
                    precioVentaValorSpan.textContent = '0.00';
                    precioBsValorSpan.textContent = '0.00';
                }
            }
            if (costoInput && gananciaInput) {
                costoInput.addEventListener('input', actualizarPrecios);
                gananciaInput.addEventListener('input', actualizarPrecios);
                actualizarPrecios();
            }

            // Lógica de validación de categoría
            const categoriaInput = document.getElementById('categoria_input');
            const idCategoriaHidden = document.getElementById('id_categoria_hidden');
            const categoriasDatalist = document.getElementById('categorias_datalist');
            const registroProductoForm = document.getElementById('registroProductoForm');

            categoriaInput.addEventListener('input', function() {
                let selectedOption = null;
                for (let i = 0; i < categoriasDatalist.options.length; i++) {
                    if (categoriasDatalist.options[i].value === categoriaInput.value) {
                        selectedOption = categoriasDatalist.options[i];
                        break;
                    }
                }
                if (selectedOption) {
                    idCategoriaHidden.value = selectedOption.getAttribute('data-id');
                } else {
                    idCategoriaHidden.value = '';
                }
            });

            registroProductoForm.addEventListener('submit', function(event) {
                if (categoriaInput.value.trim() !== '') { 
                    let isValidCategory = false;
                    for (let i = 0; i < categoriasDatalist.options.length; i++) {
                        if (categoriasDatalist.options[i].value === categoriaInput.value) {
                            isValidCategory = true;
                            break;
                        }
                    }
                    if (!isValidCategory || idCategoriaHidden.value === '') {
                        alert('Por favor, seleccione una categoría válida de la lista de sugerencias.');
                        categoriaInput.focus();
                        event.preventDefault(); 
                    }
                } else {
                    if (idCategoriaHidden.value !== '') {
                        idCategoriaHidden.value = ''; 
                    }
                }
            });

            if (categoriaInput.value) {
                let foundOption = null;
                for (let i = 0; i < categoriasDatalist.options.length; i++) {
                    if (categoriasDatalist.options[i].value === categoriaInput.value) {
                        foundOption = categoriasDatalist.options[i];
                        break;
                    }
                }
                if (foundOption) {
                    idCategoriaHidden.value = foundOption.getAttribute('data-id');
                } else {
                    idCategoriaHidden.value = '';
                }
            }

            // Lógica del Modal
            const modal = document.getElementById("registroModal");
            const openModalBtn = document.getElementById("openModalBtn");
            const closeModalBtn = document.getElementById("closeModalBtn");

            openModalBtn.onclick = function() {
                modal.style.display = "block";
            }
            closeModalBtn.onclick = function() {
                modal.style.display = "none";
            }
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
            
            // Reabrir el modal si hay un mensaje de error o éxito
            const mensajeExito = document.querySelector('.mensaje-exito');
            const mensajeError = document.querySelector('.mensaje-error');
            if (mensajeExito || mensajeError) {
                modal.style.display = "block";
            }
        });
    </script>
</body>
</html>

