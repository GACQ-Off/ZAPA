<?php
session_start();
include "../conexion/conexion.php";

function obtenerDatos($conn, $tabla) {
    $sql = "SELECT * FROM $tabla";
    $result = $conn->query($sql);
    $datos = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $datos[] = $row;
        }
    }
    return $datos;
}

$tabla_seleccionada_inicial = isset($_GET['tabla']) ? $_GET['tabla'] : 'producto';
$datos_iniciales = obtenerDatos($conn, $tabla_seleccionada_inicial);
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/fonts/google-icons/index.css">

    <title>Lista de Datos</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .papelera-main-container {
            padding: 20px;
            width: 900px;
            max-width: 100%;
        }
        .barra-busqueda {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #c8d0d8;
            border-radius: 5px;
        }
        .barra-busqueda label {
            margin-right: 10px;
           background-color: #c8d0d8;
        }
        .barra-busqueda select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #c8d0d8;
        }
        .acciones a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
        }
        .acciones a:hover {
            background-color: #0056b3;
        }
    </style>
<?php include "../assets/head_gerente.php"?>
</head>
<body>
    <?php  include "../assets/lista_gerente.php"; ?>
    <div class="papelera-main-container">
        <div class="barra-busqueda">
            <label for="tabla">Mostrar Lista de:</label>
            <select name="tabla" id="tabla">
                <option value="producto" <?php if ($tabla_seleccionada_inicial == 'producto') echo 'selected'; ?>>Productos</option>
                <option value="empleado" <?php if ($tabla_seleccionada_inicial == 'empleado') echo 'selected'; ?>>Empleados</option>
                <option value="proveedor" <?php if ($tabla_seleccionada_inicial == 'proveedor') echo 'selected'; ?>>Proveedores</option>
                <option value="categoria" <?php if ($tabla_seleccionada_inicial == 'categoria') echo 'selected'; ?>>Categorias</option>
                <option value="categoria_gasto" <?php if ($tabla_seleccionada_inicial == 'categoria_gasto') echo 'selected'; ?>>Categorias (Gastos)</option>
                <option value="gastos" <?php if ($tabla_seleccionada_inicial == 'gastos') echo 'selected'; ?>>Gastos</option>
                <option value="cargo" <?php if ($tabla_seleccionada_inicial == 'cargo') echo 'selected'; ?>>Cargos</option>
                <option value="usuario" <?php if ($tabla_seleccionada_inicial == 'usuario') echo 'selected'; ?>>Cajero</option>
            </select>
            <label for="buscar">Buscar por Nombre:</label>
            <input type="text" name="buscar" id="buscar" onkeyup="buscarNombre()">
        </div>

        <table id="tabla-datos">
            <thead>
                <tr>
                    <th id="columna-id">ID</th>
                    <th id="columna-nombre">Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="cuerpo-tabla">
                <?php if (!empty($datos_iniciales)): ?>
                    <?php foreach ($datos_iniciales as $dato): ?>
                        <tr>
                            <td class="id-celda"><?php
                                $id_value = isset($dato['id_pro']) ? $dato['id_pro'] : (isset($dato['cedula_emple']) ? $dato['cedula_emple'] : (isset($dato['RIF']) ? $dato['RIF'] : (isset($dato['id_categoria']) ? $dato['id_categoria'] : (isset($dato['id_categoria_gasto']) ? $dato['id_categoria_gasto'] : (isset($dato['id_gastos']) ? $dato['id_gastos'] : (isset($dato['id_cargo']) ? $dato['id_cargo'] : (isset($dato['id_usuario']) ? $dato['id_usuario'] : '')))))));
                                echo htmlspecialchars($id_value);
                                ?></td>
                            <td class="nombre-celda"><?php
                                $nombre_value = isset($dato['nombre_producto']) ? $dato['nombre_producto'] : (isset($dato['nombre_emp']) ? $dato['nombre_emp'] : (isset($dato['nombre_provedor']) ? $dato['nombre_provedor'] : (isset($dato['nombre_categoria']) ? $dato['nombre_categoria'] : (isset($dato['nombre_categoria_gasto']) ? $dato['nombre_categoria_gasto'] : (isset($dato['descripcion_gasto']) ? $dato['descripcion_gasto'] : (isset($dato['nom_cargo']) ? $dato['nom_cargo'] : (isset($dato['nombre_usuario']) ? $dato['nombre_usuario'] : '')))))));
                                echo htmlspecialchars($nombre_value);
                                ?></td>
                            <td class="acciones">
                                <a href="activar_<?php echo $tabla_seleccionada_inicial; ?>.php?id=<?php
                                    $id_accion = isset($dato['id_pro']) ? $dato['id_pro'] : (isset($dato['cedula_emple']) ? $dato['cedula_emple'] : (isset($dato['RIF']) ? $dato['RIF'] : (isset($dato['id_categoria']) ? $dato['id_categoria'] : (isset($dato['id_categoria_gasto']) ? $dato['id_categoria_gasto'] : (isset($dato['id_gastos']) ? $dato['id_gastos'] : (isset($dato['id_cargo']) ? $dato['id_cargo'] : (isset($dato['id_usuario']) ? $dato['id_usuario'] : '')))))));
                                    echo htmlspecialchars($id_accion);
                                    ?>"><span class="material-symbols-outlined ico-mode_off_on"></span></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No se encontraron datos.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>
        const selectTabla = document.getElementById('tabla');
        const cuerpoTabla = document.getElementById('cuerpo-tabla');
        const columnaId = document.getElementById('columna-id');
        const columnaNombre = document.getElementById('columna-nombre');
        const inputBuscar = document.getElementById('buscar');
        let tablaActual = '<?php echo $tabla_seleccionada_inicial; ?>';
        let datosCompletos = {};
        let datosMostrados = []; // Almacena los datos actualmente mostrados

        selectTabla.addEventListener('change', function() {
            const tablaSeleccionada = this.value;
            tablaActual = tablaSeleccionada;
            cargarDatos(tablaSeleccionada);
        });

        function cargarDatos(tabla) {
            if (datosCompletos[tabla]) {
                mostrarDatos(datosCompletos[tabla], tabla);
                return;
            }

            fetch(`../funciones/obtener_datos.php?tabla=${tabla}`)
                .then(response => response.json())
                .then(data => {
                    datosCompletos[tabla] = data;
                    mostrarDatos(data, tabla);
                })
                .catch(error => {
                    console.error('Error al cargar los datos:', error);
                    cuerpoTabla.innerHTML = '<tr><td colspan="3">Error al cargar los datos.</td></tr>';
                });
        }

        function mostrarDatos(data, tabla) {
            cuerpoTabla.innerHTML = '';
            datosMostrados = data;

            let idCampo = '';
            let nombreCampo = '';

            switch (tabla) {
                case 'producto':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'ID';
                    columnaNombre.textContent = 'Nombre';
                    break;
                case 'empleado':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'Cédula';
                    columnaNombre.textContent = 'Nombre';
                    break;
                case 'proveedor':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'RIF';
                    columnaNombre.textContent = 'Nombre';
                    break;
                case 'categoria':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'ID';
                    columnaNombre.textContent = 'Nombre';
                    break;
                case 'categoria_gasto':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'ID';
                    columnaNombre.textContent = 'Nombre';
                    break;
                case 'gastos':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'ID';
                    columnaNombre.textContent = 'Descripción';
                    break;
                case 'cargo':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'ID';
                    columnaNombre.textContent = 'Nombre';
                    break;
                case 'usuario':
                    idCampo = 'id';
                    nombreCampo = 'nombre';
                    columnaId.textContent = 'ID';
                    columnaNombre.textContent = 'Nombre';
                    break;
            }

            if (data && data.length > 0) {
                data.forEach(item => {
                    const fila = cuerpoTabla.insertRow();
                    const celdaId = fila.insertCell();
                    const celdaNombre = fila.insertCell();
                    const celdaAcciones = fila.insertCell();

                    celdaId.textContent = item[idCampo] || '';
                    celdaNombre.textContent = item[nombreCampo] || '';
                    celdaAcciones.innerHTML = `<a href="../funciones/activar.php?id=${item[idCampo] || ''}&tabla=${tablaActual}"><span class="material-symbols-outlined ico-mode_off_on"></span></a>`;
                });
            } else {
                cuerpoTabla.innerHTML = '<tr><td colspan="3">No se encontraron datos.</td></tr>';
            }
            buscarNombre();
        }

        function buscarNombre() {
            const filtro = inputBuscar.value.toLowerCase();
            cuerpoTabla.innerHTML = '';

            let idCampoActual = '';
            let nombreCampoActual = '';

            switch (tablaActual) {
                case 'producto':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
                case 'empleado':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
                case 'proveedor':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
                case 'categoria':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
                case 'categoria_gasto':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
                case 'gastos':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
                case 'cargo':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
                case 'usuario':
                    idCampoActual = 'id';
                    nombreCampoActual = 'nombre';
                    break;
            }

            const resultados = datosMostrados.filter(item => {
                const idValor = String(item[idCampoActual]).toLowerCase();
                const nombreValor = String(item[nombreCampoActual]).toLowerCase();
                return idValor.includes(filtro) || nombreValor.includes(filtro);
            });

            if (resultados.length > 0) {
                resultados.forEach(item => {
                    const fila = cuerpoTabla.insertRow();
                    const celdaId = fila.insertCell();
                    const celdaNombre = fila.insertCell();
                    const celdaAcciones = fila.insertCell();

                    celdaId.textContent = item[idCampoActual] || '';
                    celdaNombre.textContent = item[nombreCampoActual] || '';
                    celdaAcciones.innerHTML = `<a href="../funciones/activar.php?id=${item[idCampoActual] || ''}&tabla=${tablaActual}"><span class="material-symbols-outlined ico-mode_off_on"></span></a>`;
                });
            } else {
                cuerpoTabla.innerHTML = '<tr><td colspan="3">No se encontraron resultados para la búsqueda.</td></tr>';
            }
        }

        cargarDatos(tablaActual);
    </script>
</body>
</html>