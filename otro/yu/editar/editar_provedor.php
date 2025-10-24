<?php
session_start();
require_once '../conexion/conexion.php';

$page_title = "Editar Proveedor";
$alert_message = "";
$alert_type = "";
$proveedor = null;
$rif_proveedor = $_GET['rif'] ?? null;

// Procesar el formulario POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rif_original = $_POST['rif_original'] ?? '';
    $rif_nuevo = $_POST['rif'] ?? '';
    $nombre = $_POST['nombre_provedor'] ?? '';
    $telefono = $_POST['telefono_pro'] ?? '';
    $correo = $_POST['correo_pro'] ?? '';

    // Validar campos
    if (empty($rif_nuevo) || empty($nombre)) {
        $alert_message = "Todos los campos obligatorios deben ser completados.";
        $alert_type = "error";
    } else {
        // Preparar y ejecutar la actualización
        $sql = "UPDATE proveedor SET RIF = ?, nombre_provedor = ?, telefono_pro = ?, correo_pro = ? WHERE RIF = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssss", $rif_nuevo, $nombre, $telefono, $correo, $rif_original);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $alert_message = "Proveedor actualizado correctamente.";
                    $alert_type = "success";
                    $rif_proveedor = $rif_nuevo;
                } else {
                    $alert_message = "No se realizaron cambios. Los datos pueden ser los mismos.";
                    $alert_type = "info";
                    $rif_proveedor = $rif_nuevo;
                }
            } else {
                $alert_message = "Error al actualizar el proveedor: " . htmlspecialchars($stmt->error);
                $alert_type = "error";
            }
            $stmt->close();
        } else {
            $alert_message = "Error al preparar la consulta de actualización: " . htmlspecialchars($conn->error);
            $alert_type = "error";
        }
    }
}

if (!empty($rif_proveedor)) {
    if ($conn->connect_error) {
        $error_message = "Error de conexión: " . $conn->connect_error;
    } else {
        $sql = "SELECT RIF, nombre_provedor, telefono_pro, correo_pro FROM proveedor WHERE RIF = ? AND estado_proveedor = 1";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $error_message = "Error al preparar la consulta: " . htmlspecialchars($conn->error);
        } else {
            $stmt->bind_param("s", $rif_proveedor);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                if ($alert_type !== "success" && $alert_type !== "info") {
                    $error_message = "Proveedor no encontrado o no está activo (RIF: " . htmlspecialchars($rif_proveedor) . "). No se puede editar.";
                }
            } else {
                $proveedor = $result->fetch_assoc();
            }
            $stmt->close();
        }
    }
} else {
    $error_message = "Error: No se especificó el RIF del proveedor.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include '../assets/head_gerente.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
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
        .msg_error {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }
        .msg_save {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: .25rem;
        }
    </style>
</head>
<body>
    <?php include '../assets/lista_gerente.php'; ?>

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
                        window.location.href = '../listas/lista_provedor.php';
                    }, 3000);
                }
            });
        </script>
    <?php else: ?>
        <div class="container mt-4">
            <div class="card form-card">
                <div class="card-header form-header">
                    <h2><?php echo htmlspecialchars($page_title); ?></h2>
                </div>
                <div class="card-body form-body">
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                            <p class="mt-2"><a href="../listas/lista_provedor.php" class="btn btn-secondary btn-sm">Volver a la lista</a></p>
                        </div>
                    <?php elseif ($proveedor): ?>
                        <?php if ($alert_type === 'error'): ?>
                            <div class="msg_error"><?php echo htmlspecialchars($alert_message); ?></div>
                        <?php endif; ?>
                        <form action="editar_provedor.php?rif=<?php echo htmlspecialchars($proveedor['RIF']); ?>" method="post" id="editarProveedorForm">
                            <input type="hidden" name="rif_original" value="<?php echo htmlspecialchars($proveedor['RIF']); ?>">

                            <div class="mb-3">
                                <label for="rif" class="form-label">RIF:</label>
                                <input type="text" class="form-control" id="rif" name="rif" value="<?php echo htmlspecialchars($proveedor['RIF']); ?>" required autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="nombre_provedor" class="form-label">Nombre o Razón Social:</label>
                                <input type="text" class="form-control" id="nombre_provedor" name="nombre_provedor" value="<?php echo htmlspecialchars($proveedor['nombre_provedor']); ?>" required autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="telefono_pro" class="form-label">Teléfono:</label>
                                <input type="tel" class="form-control" id="telefono_pro" name="telefono_pro" value="<?php echo htmlspecialchars($proveedor['telefono_pro']); ?>" autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="correo_pro" class="form-label">Correo Electrónico:</label>
                                <input type="email" class="form-control" id="correo_pro" name="correo_pro" value="<?php echo htmlspecialchars($proveedor['correo_pro']); ?>" autocomplete="off">
                            </div>

                            <div class="form-buttons-container d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                <a href="../listas/lista_provedor.php" class="btn btn-secondary me-2">Cancelar</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning" role="alert">
                            No se pudieron cargar los datos del proveedor.
                            <p class="mt-2"><a href="../listas/lista_provedor.php" class="btn btn-secondary btn-sm">Volver a la lista</a></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="../assets/js/menu.js"></script>
</body>
</html>

<?php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>