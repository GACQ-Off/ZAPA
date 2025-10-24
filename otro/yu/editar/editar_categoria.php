<?php
session_start();
require_once '../conexion/conexion.php';
include '../assets/head_gerente.php';

$mensaje_exito = "";
$mensaje_error = "";
$categoria = null;
$id_categoria_editar = 0;
$alert_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_original = isset($_POST['id_original']) ? intval($_POST['id_original']) : 0;
    $nombre_categoria = trim($_POST['nombre_categoria'] ?? '');

    if (empty($id_original) || !is_numeric($id_original) || $id_original <= 0) {
        $mensaje_error = 'Error: ID de categoría original inválido o faltante.';
        $alert_type = 'error';
    } elseif (empty($nombre_categoria)) {
        $mensaje_error = 'Error: El nombre de la categoría no puede estar vacío.';
        $alert_type = 'error';
    } else {
        $id_categoria_editar = $id_original;

        if ($conn->connect_error) {
            $mensaje_error = "Error de conexión: " . $conn->connect_error;
            $alert_type = 'error';
        } else {
            $sql = "UPDATE categoria SET
                      nombre_categoria = ?
                    WHERE id_categoria = ? AND estado_categoria = 1";

            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("si", $nombre_categoria, $id_original);

                if ($stmt->execute()) {
                    $mensaje_exito = 'Categoría Actualizada correctamente.';
                    $alert_type = 'success';
                } else {
                    $mensaje_error = "Error al actualizar la categoría: " . htmlspecialchars($stmt->error);
                    $alert_type = 'error';
                }
                $stmt->close();
            } else {
                $mensaje_error = "Error al preparar la consulta de actualización: " . htmlspecialchars($conn->error);
                $alert_type = 'error';
            }

            $conn->close();
        }
    }

} else {
    if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
        $mensaje_error = 'Error: No se especificó un ID de categoría válido.';
        $alert_type = 'error';
    } else {
        $id_categoria_editar = intval($_GET['id']);

        if ($conn->connect_error) {
            $mensaje_error = "Error de conexión: " . $conn->connect_error;
            $alert_type = 'error';
        } else {
            $sql_select = "SELECT id_categoria, nombre_categoria
                           FROM categoria
                           WHERE id_categoria = ? AND estado_categoria = 1";

            $stmt_select = $conn->prepare($sql_select);

            if (!$stmt_select) {
                $mensaje_error = "Error al preparar la consulta: " . htmlspecialchars($conn->error);
                $alert_type = 'error';
            } else {
                $stmt_select->bind_param("i", $id_categoria_editar);
                $stmt_select->execute();
                $result_select = $stmt_select->get_result();

                if ($result_select->num_rows === 0) {
                    $mensaje_error = "Categoría no encontrada o no está activa (ID: " . htmlspecialchars($id_categoria_editar) . "). No se puede editar.";
                    $alert_type = 'error';
                } else {
                    $categoria = $result_select->fetch_assoc();
                }

                $stmt_select->close();
            }
            $conn->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoría</title>
    <link rel="stylesheet" href="../assets/css/editar_provedor.css">
    <style>
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
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <?php include '../assets/lista_gerente.php'; ?>

    <?php if ($alert_type === 'success'): ?>
        <div id="success-modal" class="modal" style="display: flex;">
            <div class="modal-content">
                <span class="success-icon">&#10004;</span>
                <h2>Éxito</h2>
                <p><?php echo htmlspecialchars($mensaje_exito); ?></p>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = document.getElementById('success-modal');
                if (modal) {
                    setTimeout(function() {
                        window.location.href = '../listas/lista_categoria.php';
                    }, 3000);
                }
            });
        </script>
    <?php else: ?>
        <div class="container mt-4">
            <div class="card form-card">
                <div class="card-header form-header">
                    <h2>Editar Categoría</h2>
                </div>
                <div class="card-body form-body">
                    <?php if (!empty($mensaje_error)): ?>
                        <div class='alert alert-danger'><?php echo htmlspecialchars($mensaje_error); ?></div>
                        <a href="../listas/lista_categoria.php" class="btn btn-secondary">Volver a la lista</a>
                    <?php elseif ($categoria): ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo $id_categoria_editar; ?>" method="post">
                            <input type="hidden" name="id_original" value="<?php echo htmlspecialchars($id_categoria_editar); ?>">
                            <div class="mb-3">
                                <label for="nombre_categoria" class="form-label">Nombre de la Categoría:</label>
                                <input type="text" class="form-control" id="nombre_categoria" name="nombre_categoria"
                                       value="<?php echo htmlspecialchars(
                                           isset($_POST['nombre_categoria']) ? $_POST['nombre_categoria'] : ($categoria['nombre_categoria'] ?? '')
                                       ); ?>" required autocomplete="off">
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                <a href="../listas/lista_categoria.php" class="btn btn-secondary me-2">Cancelar</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="alert alert-info">Cargando datos de la categoría...</p>
                        <a href="../listas/lista_categoria.php" class="btn btn-secondary">Volver a la lista</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="../assets/js/menu.js"></script>
</body>
</html>