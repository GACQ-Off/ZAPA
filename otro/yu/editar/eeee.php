<?php
session_start();
include "../conexion/conexion.php";

$alert = '';
$gasto_data = [
    'id_gastos' => '?',
    'descripcion_gasto' => '?',
    'monto_gasto' => '?',
    'fecha_gasto' => '?',
    'id_categoria_gasto' => '?',
    'id_fondo' => '?'
];

if (!empty($_POST)) {
    if (empty($_POST['id_gasto_editar']) || empty($_POST['descripcion_gasto']) || empty($_POST['monto_gasto']) || empty($_POST['fecha_gasto']) || empty($_POST['id_categoria_gasto']) || empty($_POST['id_fondo'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios para actualizar.</p>';
        $gasto_data['id_gastos'] = $_POST['id_gasto_editar'] ?? '';
        $gasto_data['descripcion_gasto'] = $_POST['descripcion_gasto'] ?? '';
        $gasto_data['monto_gasto'] = $_POST['monto_gasto'] ?? '';
        $gasto_data['fecha_gasto'] = $_POST['fecha_gasto'] ?? '';
        $gasto_data['id_categoria_gasto'] = $_POST['id_categoria_gasto'] ?? '';
        $gasto_data['id_fondo'] = $_POST['id_fondo'] ?? '';
    } else {
        $id_gasto_actualizar = mysqli_real_escape_string($conn, $_POST['id_gasto_editar']);
        $descripcion         = mysqli_real_escape_string($conn, $_POST['descripcion_gasto']);
        $monto               = mysqli_real_escape_string($conn, $_POST['monto_gasto']);
        $fecha               = mysqli_real_escape_string($conn, $_POST['fecha_gasto']);
        $id_categoria_gasto  = mysqli_real_escape_string($conn, $_POST['id_categoria_gasto']);
        $id_fondo            = mysqli_real_escape_string($conn, $_POST['id_fondo']);

        $sql_actualizar = "UPDATE gastos
                           SET descripcion_gasto = '$descripcion',
                               monto_gasto = '$monto',
                               fecha_gasto = '$fecha',
                               id_categoria_gasto = '$id_categoria_gasto',
                               id_fondo = '$id_fondo'
                           WHERE id_gastos = $id_gasto_actualizar";

        $query_update = mysqli_query($conn, $sql_actualizar);

        if ($query_update) {
            $alert = '<p class="msg_save">Gasto actualizado correctamente. <a href="../listas/lista_gastos.php">Ver lista de gastos</a></p>';
            $id_gasto_param = $id_gasto_actualizar;
        } else {
            $alert = '<p class="msg_error">Error al actualizar el gasto: ' . mysqli_error($conn) . '</p>';
            $gasto_data['id_gastos'] = $_POST['id_gasto_editar'];
            $gasto_data['descripcion_gasto'] = $_POST['descripcion_gasto'];
            $gasto_data['monto_gasto'] = $_POST['monto_gasto'];
            $gasto_data['fecha_gasto'] = $_POST['fecha_gasto'];
            $gasto_data['id_categoria_gasto'] = $_POST['id_categoria_gasto'];
            $gasto_data['id_fondo'] = $_POST['id_fondo'];
        }
    }
}

if (empty($_POST) || !empty($id_gasto_param)) {
    if (empty($_REQUEST["id_gasto"]) && empty($id_gasto_param)) {
        header('Location: ../listas/lista_gastos.php');
        exit;
    }

    $id_gasto_a_cargar = !empty($id_gasto_param) ? $id_gasto_param : $_REQUEST['id_gasto'];
    $id_gasto_esc = mysqli_real_escape_string($conn, $id_gasto_a_cargar);

    $sql_gasto = mysqli_query($conn, "SELECT * FROM gastos WHERE id_gastos = '$id_gasto_esc'");
    
    if (mysqli_num_rows($sql_gasto) > 0) {
        $gasto_data = mysqli_fetch_assoc($sql_gasto);
    } else {
        $alert = '<p class="msg_error">Gasto no encontrado.</p>';
    }
}

?>
<html>
<head>
    <?php include "../assets/head_gerente.php"; ?>
    <link rel="stylesheet" href="../assets/css/registrar_empleado.css">
    <title>Editar Gasto</title>
</head>
<body>
    <?php include "../assets/lista_gerente.php"; ?>
    <section>
        <div class="container_form">
            <div class="form_header">
                <h2>Editar Gasto</h2>
            </div>
            <?php echo isset($alert) ? $alert : ''; ?>
            <form action="" method="post" class="formulario" id="form_editar_gasto">
                <input type="hidden" name="id_gasto_editar" value="<?php echo htmlspecialchars($gasto_data['id_gastos']); ?>">

                <div class="form-group">
                    <label for="descripcion_gasto">Descripción del Gasto:</label>
                    <textarea name="descripcion_gasto" id="descripcion_gasto" placeholder="Ingrese una descripción del gasto" class="input_textarea" required><?php echo htmlspecialchars($gasto_data['descripcion_gasto']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="monto_gasto">Monto:</label>
                    <input type="number" name="monto_gasto" id="monto_gasto" placeholder="Ingrese el Monto" class="input_field" value="<?php echo htmlspecialchars($gasto_data['monto_gasto']); ?>" step="0.01" required>
                </div>

                <div class="form-group">
                    <label for="fecha_gasto">Fecha:</label>
                    <input type="date" name="fecha_gasto" id="fecha_gasto" class="input_field" value="<?php echo htmlspecialchars($gasto_data['fecha_gasto']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="id_categoria_gasto">Categoría del Gasto:</label>
                    <select name="id_categoria_gasto" id="id_categoria_gasto" class="input_select" required>
                        <option value="" hidden>Seleccione una categoría</option>
                        <?php
                        $query_categorias = mysqli_query($conn, "SELECT id_categoria_gasto, nombre_categoria_gasto FROM categoria_gasto WHERE estado_categoria_gasto = 1 ORDER BY nombre_categoria_gasto ASC");
                        while ($categoria = mysqli_fetch_array($query_categorias)) {
                            $selected = ($categoria['id_categoria_gasto'] == $gasto_data['id_categoria_gasto']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($categoria['id_categoria_gasto']) . '" ' . $selected . '>' . htmlspecialchars($categoria['nombre_categoria_gasto']) . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_fondo">Fondo:</label>
                    <select name="id_fondo" id="id_fondo" class="input_select" required>
                        <option value="" hidden>Seleccione un fondo</option>
                        <?php
                        $query_fondos = mysqli_query($conn, "SELECT id_fondo, nombre_fondo FROM fondos WHERE estado_fondo = 1 ORDER BY nombre_fondo ASC");
                        if ($query_fondos) {
                            while ($fondo = mysqli_fetch_array($query_fondos)) {
                                $selected = ($fondo['id_fondo'] == $gasto_data['id_fondo']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($fondo['id_fondo']) . '" ' . $selected . '>' . htmlspecialchars($fondo['nombre_fondo']) . '</option>';
                            }
                        } else {
                            echo '<option value="">Error al cargar fondos</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group buttons">
                    <a href="../listas/lista_gastos.php" class="btn btn--cancel">Cancelar</a>
                    <input type="submit" value="Actualizar Gasto" class="btn btn--save">
                </div>
            </form>
        </div>
    </section>
    <?php mysqli_close($conn); ?>
</body>
</html>