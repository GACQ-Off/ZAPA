<?php
session_start();
require_once '../conexion/conexion.php';

$alert_message = '';
$alert_type = '';
$rif_input = "";
$nombre_input = "";
$telefono_input = "";
$correo_input = "";

if (!isset($conn) || $conn->connect_error) {
    die("Error crítico de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido al incluir conexion.php"));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rif_input = isset($_POST['rif']) ? htmlspecialchars(trim($_POST['rif'])) : '';
    $nombre_input = isset($_POST['nombre_provedor']) ? htmlspecialchars(trim($_POST['nombre_provedor'])) : '';
    $telefono_input = isset($_POST['telefono_pro']) ? htmlspecialchars(trim($_POST['telefono_pro'])) : '';
    $correo_input = isset($_POST['correo_pro']) ? htmlspecialchars(trim($_POST['correo_pro'])) : '';
    $estado = 1;

    $error_list = [];
    if (empty($rif_input)) {
        $error_list[] = "El RIF del proveedor es obligatorio.";
    }
    if (empty($nombre_input)) {
        $error_list[] = "El Nombre del Proveedor es obligatorio.";
    }
    if (!empty($correo_input) && !filter_var($correo_input, FILTER_VALIDATE_EMAIL)) {
        $error_list[] = "El formato del correo electrónico no es válido.";
    }

    if (empty($error_list)) {
        $stmt_check = $conn->prepare("SELECT RIF FROM proveedor WHERE RIF = ?");
        if (!$stmt_check) {
            $error_list[] = "Error preparando la consulta de verificación: " . $conn->error;
        } else {
            $stmt_check->bind_param("s", $rif_input);
            $stmt_check->execute();
            $stmt_check->store_result();
            if ($stmt_check->num_rows > 0) {
                $error_list[] = "El RIF '" . htmlspecialchars($rif_input) . "' ya está registrado.";
            }
            $stmt_check->close();
        }
    }

    if (empty($error_list)) {
        $sql = "INSERT INTO proveedor (RIF, nombre_provedor, telefono_pro, correo_pro, estado_proveedor) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $error_list[] = "Error preparando la consulta de inserción: " . $conn->error;
        } else {
            $stmt->bind_param("ssssi", $rif_input, $nombre_input, $telefono_input, $correo_input, $estado);
            if ($stmt->execute()) {
                $alert_message = "Proveedor '" . htmlspecialchars($nombre_input) . "' registrado exitosamente.";
                $alert_type = 'success';
                $rif_input = $nombre_input = $telefono_input = $correo_input = "";
            } else {
                if ($conn->errno == 1062) {
                    $error_list[] = "Error: El RIF '" . htmlspecialchars($rif_input) . "' ya existe. No se puede duplicar.";
                } else {
                    $error_list[] = "Error al registrar el proveedor: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }

    if (!empty($error_list)) {
        $alert_message = "Se encontraron los siguientes errores:<br>- " . implode("<br>- ", $error_list);
        $alert_type = 'error';
    }
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Proveedores</title>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/proveedor.css">
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
        .modal-footer {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php"?>

    <div class="form-container">
        <h2>Registrar Nuevo Proveedor</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="rif">RIF:</label>
                <input type="text" id="rif" name="rif" required
                       value="<?php echo htmlspecialchars($rif_input); ?>" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="nombre_proveedor">Nombre del Proveedor:</label>
                <input type="text" id="nombre_provedor" name="nombre_provedor" required
                       value="<?php echo htmlspecialchars($nombre_input); ?>" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="telefono_pro">Teléfono:</label>
                <input type="tel" id="telefono_pro" name="telefono_pro"
                       value="<?php echo htmlspecialchars($telefono_input); ?>" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="correo_pro">Correo Electrónico:</label>
                <input type="email" id="correo_pro" name="correo_pro"
                       value="<?php echo htmlspecialchars($correo_input); ?>" autocomplete="off">
            </div>
            <button type="submit">Registrar Proveedor</button>
        </form>

        <div class="navegacion">
            <a href='../listas/lista_provedor.php'>Regresar</a>
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
                    window.location.href = '../listas/lista_provedor.php';
                }, 3000);
                <?php endif; ?>
            }
        });
    </script>
</body>
</html>