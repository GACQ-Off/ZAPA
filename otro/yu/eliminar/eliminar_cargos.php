<?php
session_start();
require_once '../conexion/conexion.php';

$show_modal = false;
$error_message = '';

if (!isset($_GET['Cargo']) || empty($_GET['Cargo'])) {
    $error_message = "Error: No se especificó el id del cargo.";
} else {
    $cargo_id = $_GET['Cargo'];

    if (!isset($conn) || $conn->connect_error) {
        $error_message = "Error crítico de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido.");
    } else {
        $sql = "UPDATE cargo SET estado_cargo = 2 WHERE id_cargo = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $cargo_id);
            if ($stmt->execute()) {
                $show_modal = true;
            } else {
                $error_message = "Error al intentar eliminar el cargo: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Error al preparar la consulta: " . $conn->error;
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Cargo</title>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/lista_empleados.css">
    <link rel="stylesheet" href="assets/fonts/google-icons/index.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
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
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"?>
<?php if ($show_modal): ?>
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="success-icon">&#10004;</span>
            <h2>Éxito</h2>
            <p>Cargo eliminado con éxito.</p>
        </div>
    </div>
<?php endif; ?>

<?php if ($error_message): ?>
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="error-icon">&#10006;</span>
            <h2>Error</h2>
            <p><?php echo htmlspecialchars($error_message); ?></p>
        </div>
    </div>
<?php endif; ?>

<script>
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($show_modal): ?>
            showModal("successModal");
            setTimeout(function() {
                window.location.href = "../listas/lista_cargos.php";
            }, 3000);
        <?php elseif ($error_message): ?>
            showModal("errorModal");
            setTimeout(function() {
                window.location.href = "../listas/lista_cargos.php";
            }, 3000);
        <?php else: ?>
            window.location.href = "../listas/lista_cargos.php";
        <?php endif; ?>
    });
</script>
<script src="../assets/js/busqueda.js"></script>
</body>
</html>