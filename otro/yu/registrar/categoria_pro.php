<?php
session_start();
require_once '../conexion/conexion.php';
$alert_message = "";
$alert_type = "";
$nombre_categoria_input = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn) || $conn->connect_error) {
        $alert_message = "Error de conexión a la base de datos: " . ($conn->connect_error ?? "Desconocido");
        $alert_type = 'error';
    } else {
        $nombre_categoria_input = isset($_POST['nombre_categoria']) ? htmlspecialchars(trim($_POST['nombre_categoria'])) : "";

        if (empty($nombre_categoria_input)) {
            $alert_message = "Error: El nombre de la categoría no puede estar vacío.";
            $alert_type = 'error';
        } else {
            $stmt_check = $conn->prepare("SELECT id_categoria FROM categoria WHERE nombre_categoria = ?");
            if (!$stmt_check) {
                $alert_message = "Error preparando la consulta de verificación: " . $conn->error;
                $alert_type = 'error';
            } else {
                $stmt_check->bind_param("s", $nombre_categoria_input);
                $stmt_check->execute();
                $stmt_check->store_result();
                if ($stmt_check->num_rows > 0) {
                    $alert_message = "Error: La categoría '" . htmlspecialchars($nombre_categoria_input) . "' ya existe.";
                    $alert_type = 'error';
                }
                $stmt_check->close();
            }

            if (empty($alert_message)) {
                $sql = "INSERT INTO categoria (nombre_categoria, estado_categoria) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    $alert_message = "Error preparando la consulta de inserción: " . $conn->error;
                    $alert_type = 'error';
                } else {
                    $estado_predeterminado = 1;
                    $stmt->bind_param("si", $nombre_categoria_input, $estado_predeterminado);
                    if ($stmt->execute()) {
                        $alert_message = "¡Categoría '" . htmlspecialchars($nombre_categoria_input) . "' registrada exitosamente!";
                        $alert_type = 'success';
                        $nombre_categoria_input = "";
                    } else {
                        $alert_message = "Error al registrar la categoría: " . $stmt->error;
                        $alert_type = 'error';
                    }
                    $stmt->close();
                }
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/categoria_pro.css">
    <meta charset="UTF-8">
    <title>Registrar Categorías</title>
    <style>
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
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php"?>

    <div class="container-categoria">
        <h1>Registrar Nueva Categoría</h1>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nombre_categoria">Nombre de la Nueva Categoría:</label>
            <input type="text" name="nombre_categoria" id="nombre_categoria" value="<?php echo htmlspecialchars($nombre_categoria_input); ?>" required autocomplete="off">
            <button type="submit">Guardar Categoría</button>
        </form>
        <div class="navegacionn">
            <a href="../listas/lista_categoria.php">Regresar</a>
        </div>
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
            <p><?php echo $alert_message; ?></p>
            <?php if ($alert_type === 'error'): ?>
            <div class="modal-footer">
                <a href="javascript:history.go(-1)" class="btn btn--cancel">Cerrar</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('miModal');
            if (modal) {
                modal.style.display = 'flex';
                

                <?php if ($alert_type === 'success'): ?>
                setTimeout(function() {
                    modal.style.display = 'none';
                    window.location.href = '../listas/lista_categoria.php';
                }, 3000);
                <?php endif; ?>
            }
        });
    </script>
</body>
</html>