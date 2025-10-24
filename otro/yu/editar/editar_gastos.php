<?php
session_start();
require_once '../conexion/conexion.php';

if (!isset($conn) || !($conn instanceof mysqli) || $conn->connect_error) {
    error_log("Error de conexión a la base de datos en editar_gastos.php: " . (isset($conn) && $conn instanceof mysqli ? $conn->connect_error : 'La variable $conn no es un objeto mysqli válido o la conexión falló.'));
    die('<p style="color: red; text-align: center; font-size: 1.2em; margin-top: 20px; font-family: sans-serif;">Error Crítico: No se pudo establecer la conexión con la base de datos. Por favor, intente más tarde o contacte al administrador del sistema.</p>');
}

include '../assets/head_gerente.php';
$alert_message = '';
$alert_type = '';
$id_gasto_para_formulario = $_GET['id_gasto'] ?? null;

$gasto_data = [
    'id_gastos' => '',
    'descripcion_gasto' => '',
    'monto_gasto' => '',
    'fecha_gasto' => '',
    'id_categoria_gasto' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_gasto_editar_get'])) {
    if (empty($_POST['descripcion_gasto']) || empty($_POST['monto_gasto']) || empty($_POST['fecha_gasto']) || empty($_POST['id_categoria_gasto'])) {
        $alert_message = 'Todos los campos son obligatorios para actualizar.';
        $alert_type = 'error';
        $id_gasto_para_formulario = $_POST['id_gasto_editar_get'];
        $gasto_data['id_gastos'] = $id_gasto_para_formulario;
        $gasto_data['descripcion_gasto'] = $_POST['descripcion_gasto'] ?? '';
        $gasto_data['monto_gasto'] = $_POST['monto_gasto'] ?? '';
        $gasto_data['fecha_gasto'] = $_POST['fecha_gasto'] ?? '';
        $gasto_data['id_categoria_gasto'] = $_POST['id_categoria_gasto'] ?? '';
    } else {
        $id_gasto_actualizar = $_POST['id_gasto_editar_get'];
        $descripcion = $_POST['descripcion_gasto'];
        $monto = $_POST['monto_gasto'];
        $fecha = $_POST['fecha_gasto'];
        $id_categoria_gasto = $_POST['id_categoria_gasto'];
        $id_fondo = null;
        $estado_gasto = 1;

        $sql_actualizar = "UPDATE gastos
                            SET descripcion_gasto = ?,
                                monto_gasto = ?,
                                fecha_gasto = ?,
                                id_categoria_gasto = ?,
                                id_fondo = ?,
                                estado_gasto = ?
                            WHERE id_gastos = ?";

        $stmt_update = $conn->prepare($sql_actualizar);
        if ($stmt_update) {
            $stmt_update->bind_param("sssiisi", $descripcion, $monto, $fecha, $id_categoria_gasto, $id_fondo, $estado_gasto, $id_gasto_actualizar);
            if ($stmt_update->execute()) {
                if ($stmt_update->affected_rows > 0) {
                    $alert_message = 'Gasto actualizado correctamente.';
                    $alert_type = 'success';
                } else {
                    $alert_message = 'No se realizaron cambios. Los datos pueden ser los mismos o el gasto con ID ' . htmlspecialchars($id_gasto_actualizar) . ' no requirió actualización.';
                    $alert_type = 'info';
                }
                $id_gasto_para_formulario = $id_gasto_actualizar;
            } else {
                $alert_message = 'Error al actualizar el gasto: ' . htmlspecialchars($stmt_update->error);
                $alert_type = 'error';
                $id_gasto_para_formulario = $id_gasto_actualizar;
                $gasto_data['id_gastos'] = $id_gasto_actualizar;
                $gasto_data['descripcion_gasto'] = $descripcion;
                $gasto_data['monto_gasto'] = $monto;
                $gasto_data['fecha_gasto'] = $fecha;
                $gasto_data['id_categoria_gasto'] = $id_categoria_gasto;
            }
            $stmt_update->close();
        } else {
            $alert_message = 'Error al preparar la actualización: ' . htmlspecialchars($conn->error);
            $alert_type = 'error';
            $id_gasto_para_formulario = $id_gasto_actualizar;
            $gasto_data['id_gastos'] = $id_gasto_actualizar;
            $gasto_data['descripcion_gasto'] = $descripcion;
            $gasto_data['monto_gasto'] = $monto;
            $gasto_data['fecha_gasto'] = $fecha;
            $gasto_data['id_categoria_gasto'] = $id_categoria_gasto;
        }
    }
}

if ($id_gasto_para_formulario !== null) {
    if (!filter_var($id_gasto_para_formulario, FILTER_VALIDATE_INT) || (int)$id_gasto_para_formulario <= 0) {
        $alert_message .= "<p class='msg_error'>ID de gasto inválido.</p>";
        $alert_type = 'error';
    } else {
        $sql_select = "SELECT id_gastos, descripcion_gasto, monto_gasto, fecha_gasto, id_categoria_gasto
                               FROM gastos
                               WHERE id_gastos = ? AND estado_gasto = 1";

        $stmt_select = $conn->prepare($sql_select);

        if ($stmt_select) {
            $stmt_select->bind_param("i", $id_gasto_para_formulario);
            $stmt_select->execute();
            $result_select = $stmt_select->get_result();

            if ($result_select->num_rows > 0) {
                if ($_SERVER["REQUEST_METHOD"] != "POST" || $alert_type === 'success' || $alert_type === 'info') {
                    $gasto_data = $result_select->fetch_assoc();
                }
            } else {
                if (empty($alert_message) && $_SERVER["REQUEST_METHOD"] !== "POST") {
                    $alert_message = "Gasto no encontrado o no está activo.";
                    $alert_type = 'error';
                    header('Location: ../listas/lista_gastos.php');
                    exit;
                }
            }
            $stmt_select->close();
        } else {
            $alert_message .= "<p class='msg_error'>Error al preparar la consulta de selección.</p>";
            $alert_type = 'error';
            header('Location: ../listas/lista_gastos.php');
            exit;
        }
    }
} else {
    if (empty($alert_message) && $_SERVER["REQUEST_METHOD"] !== "POST") {
        header('Location: ../listas/lista_gastos.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/registrar_empleado.css">
    <title>Editar Gasto</title>
    <style>
        /* Estilos para la modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fefefe;
            padding: 30px;
            border: 1px solid #888;
            width: 90%;
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
        .msg_error {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            border: 1px solid #f5c6cb;
            background-color: #f8d7da;
            color: #721c24;
        }
        .msg_info {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
            border: 1px solid #cce5ff;
            background-color: #e2e3e5;
            color: #0c5460;
        }
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"; ?>

<?php if ($alert_type === 'success' || $alert_type === 'info'): ?>
    <div id="success-modal" class="modal" style="display: flex;">
        <div class="modal-content">
            <?php if ($alert_type === 'success'): ?>
                <span class="success-icon">&#10004;</span>
                <h2>Éxito</h2>
            <?php else: ?>
                <span class="success-icon" style="color: #0c5460;">&#8505;</span>
                <h2>Información</h2>
            <?php endif; ?>
            <p><?php echo htmlspecialchars($alert_message); ?></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('success-modal');
            if (modal) {
                setTimeout(function() {
                    window.location.href = '../listas/lista_gastos.php';
                }, 3000);
            }
        });
    </script>
<?php else: ?>
    <section>
        <form action="editar_gastos.php?id_gasto=<?php echo htmlspecialchars($gasto_data['id_gastos'] ?: ($id_gasto_para_formulario ?? '')); ?>" method="post" class="formulario" id="valid">
            <h2 class="title">Editar Gasto</h2>
            <?php if ($alert_type === 'error'): ?>
                <div class=""><?php echo '<p class="msg_error">' . htmlspecialchars($alert_message) . '</p>'; ?></div>
            <?php endif; ?>
            <input type="hidden" name="id_gasto_editar_get" value="<?php echo htmlspecialchars($gasto_data['id_gastos'] ?: ($id_gasto_para_formulario ?? '')); ?>">
            <div class="form-group">
                <label for="descripcion_gasto" class="text">Descripción Gasto:</label>
                <textarea name="descripcion_gasto" required id="descripcion_gasto" placeholder="Ingrese una descripción del gasto" class="input" autocomplete="off"><?php echo htmlspecialchars($gasto_data['descripcion_gasto']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="monto_gasto" class="text">Monto:</label>
                <input type="number" required name="monto_gasto" id="monto_gasto" placeholder="Ingrese el Monto" class="input" value="<?php echo htmlspecialchars($gasto_data['monto_gasto']); ?>" step="0.01" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="fecha_gasto" class="text">Fecha:</label>
                <input type="date" required name="fecha_gasto" id="fecha_gasto" class="input" value="<?php echo htmlspecialchars($gasto_data['fecha_gasto']); ?>">
            </div>
            <div class="form-group">
                <label for="id_categoria_gasto" class="text">Categoria:</label>
                <select class="input" name="id_categoria_gasto" id="id_categoria_gasto" required>
                    <option value="" hidden>Categoria</option>
                    <?php
                    $query_categorias = mysqli_query($conn, "SELECT id_categoria_gasto, nombre_categoria_gasto FROM categoria_gasto WHERE estado_categoria_gasto = 1 ORDER BY nombre_categoria_gasto ASC");
                    if ($query_categorias) {
                        while ($categoria = mysqli_fetch_array($query_categorias)) {
                            $selected = ($categoria['id_categoria_gasto'] == $gasto_data['id_categoria_gasto']) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($categoria['id_categoria_gasto']) . '" ' . $selected . '>' . htmlspecialchars($categoria['nombre_categoria_gasto']) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group buttons">
                <input type="submit" value="Actualizar Gasto" class="input">
                <a href="../listas/lista_gastos.php" class="btn btn--cancel">Regresar</a>
            </div>
        </form>
    </section>
<?php endif; ?>

<?php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
</body>
</html>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("fecha_gasto");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
</script>