<?php
session_start();
require_once '../conexion/conexion.php';
include('Encriptar.php');

$id_usuario_editar = isset($_GET['Cajero']) ? intval($_GET['Cajero']) : null;

$nombre_usuario_actual = '';
$alert_message = '';
$alert_type = '';
$mostrar_formulario = false;

if (!isset($conn) || $conn->connect_error) {
    error_log("Error de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido."));
    die('<p style="color: red; font-weight: bold;">Error crítico: No se pudo conectar con la base de datos. Por favor, contacte al administrador.</p>');
}

if ($id_usuario_editar === null || $id_usuario_editar <= 0) {
    $alert_message = 'Error: No se especificó un ID de usuario válido para editar.';
    $alert_type = 'error';
} else {
    $stmt_get_unico = $conn->prepare("SELECT id_usuario, nombre_usuario FROM usuario WHERE id_usuario = ? LIMIT 1");
    if ($stmt_get_unico) {
        $stmt_get_unico->bind_param("i", $id_usuario_editar);
        $stmt_get_unico->execute();
        $result_get_unico = $stmt_get_unico->get_result();

        if ($result_get_unico->num_rows === 1) {
            $usuario_data = $result_get_unico->fetch_assoc();
            $nombre_usuario_actual = $usuario_data['nombre_usuario'];
            if ($_SERVER["REQUEST_METHOD"] != "POST") {
                $mostrar_formulario = true;
            }
        } else {
            $alert_message = 'Error: No se encontró ningún usuario con el ID especificado.';
            $alert_type = 'error';
            $mostrar_formulario = false;
        }
        $stmt_get_unico->close();
    } else {
        error_log("Error preparando consulta para buscar al usuario con ID " . $id_usuario_editar . ": " . $conn->error);
        $alert_message = 'Error al intentar obtener la información del usuario.';
        $alert_type = 'error';
        $mostrar_formulario = false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['nombre_usuario'], $_POST['clave'])) {
        $alert_message = 'Error: Faltan datos del formulario (nombre o clave).';
        $alert_type = 'error';
        $mostrar_formulario = true;
    } else {
        $nombre_usuario_nuevo = trim($_POST['nombre_usuario']);
        $clave_nueva = trim($_POST['clave']);
        $alert_temp = '';

        if (empty($nombre_usuario_nuevo)) {
            $alert_temp .= 'El nombre de usuario no puede estar vacío.<br>';
        }
        if (empty($clave_nueva)) {
            $alert_temp .= 'Debes ingresar la nueva contraseña.<br>';
        }

        if (empty($alert_temp)) {
            $stmt_check = $conn->prepare("SELECT id_usuario FROM usuario WHERE nombre_usuario = ? AND id_usuario != ?");
            if ($stmt_check) {
                $stmt_check->bind_param("si", $nombre_usuario_nuevo, $id_usuario_editar);
                $stmt_check->execute();
                $stmt_check->store_result();
                if ($stmt_check->num_rows > 0) {
                    $alert_temp .= 'El nombre de usuario \'' . htmlspecialchars($nombre_usuario_nuevo) . '\' ya está en uso.<br>';
                }
                $stmt_check->close();
            } else {
                error_log("Error preparando consulta de verificación de nombre: " . $conn->error);
                $alert_temp .= 'Error al verificar la disponibilidad del nombre de usuario.<br>';
            }
        }
        $alert_message = $alert_temp;
        $alert_type = empty($alert_temp) ? 'success' : 'error';

        if (empty($alert_message)) {
            $clave_encriptada = encriptar($clave_nueva, $nombre_usuario_nuevo);
            $stmt_update = $conn->prepare("UPDATE usuario SET nombre_usuario = ?, clave = ? WHERE id_usuario = ?");
            if ($stmt_update) {
                $stmt_update->bind_param("ssi", $nombre_usuario_nuevo, $clave_encriptada, $id_usuario_editar);

                if ($stmt_update->execute()) {
                    $alert_message = 'Se ha editado con éxito.';
                    $alert_type = 'success';
                    $nombre_usuario_actual = $nombre_usuario_nuevo;
                    $mostrar_formulario = false; // Se establece a falso para no mostrar el formulario
                } else {
                    error_log("Error al actualizar usuario ID " . $id_usuario_editar . ": " . $stmt_update->error);
                    $alert_message = 'No se pudo actualizar el usuario en la base de datos. Intente más tarde.';
                    $alert_type = 'error';
                    $mostrar_formulario = true;
                }
                $stmt_update->close();
            } else {
                error_log("Error preparando consulta UPDATE para usuario: " . $conn->error);
                $alert_message = 'Error al preparar la actualización del usuario.';
                $alert_type = 'error';
                $mostrar_formulario = true;
            }
        } else {
            $mostrar_formulario = true;
            $nombre_usuario_actual = $nombre_usuario_nuevo;
        }
    }
}
?>

<!DOCTYPE html>
<head>
    <?php include "../assets/head_gerente.php"?>
    <title>Editar Cajero - <?php echo htmlspecialchars($nombre_usuario_actual); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            width: 500px;
            max-width: 500px;
            margin: 50px 0 50px 200px;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.1);
            text-align: left;
        }

        .auth-container h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.8em;
        }

        .msg_error {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: left;
            border: 1px solid;
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
            outline: none;
        }
        .form-group small {
            display: block;
            margin-top: 5px;
            font-size: 0.85em;
            color: #6c757d;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            gap: 15px;
        }

        .form-actions button[type="submit"],
        .form-actions .btn-cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .form-actions button[type="submit"] {
            background-color: #3533cd;
            color: white;
        }
        .form-actions button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-actions .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        .form-actions .btn-cancel:hover {
            background-color: #5a6268;
        }
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
    </style>
</head>

<body>
<?php include "../assets/lista_gerente.php"?>
    <?php if ($alert_type === 'success'): ?>
        <div id="success-modal" class="modal" style="display: flex;">
            <div class="modal-content">
                <span class="success-icon">&#10004;</span>
                <h2>Éxito</h2>
                <p><?php echo htmlspecialchars($alert_message); ?></p>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = document.getElementById('success-modal');
                if (modal) {
                    setTimeout(function() {
                        window.location.href = '../listas/lista_cajero.php';
                    }, 3000);
                }
            });
        </script>
    <?php else: ?>
        <div class="auth-container">
            <h2>Editar Cajero</h2>

            <?php if (!empty($alert_message) && $alert_type == 'error'): ?>
                <p class="msg_error"><?php echo htmlspecialchars($alert_message); ?></p>
            <?php endif; ?>

            <?php if ($mostrar_formulario): ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?Cajero=' . $id_usuario_editar); ?>" method="post" novalidate>
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre de usuario:</label>
                        <input type="text" required name="nombre_usuario" id="nombre_usuario" placeholder="Ingrese el nuevo nombre de usuario" value="<?php echo htmlspecialchars($nombre_usuario_actual); ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="clave">Nueva Contraseña:</label>
                        <input type="password" required name="clave" id="clave" placeholder="Ingrese la nueva contraseña" autocomplete="off">
                        <small>Se requiere ingresar una nueva contraseña para actualizar.</small>
                    </div>
                    <div class="form-actions">
                        <button type="submit">Actualizar Cajero</button>
                        <a href="../listas/lista_cajero.php" class="btn-cancel">Regresar a la lista</a>
                    </div>
                </form>
            <?php else: ?>
                <p class="msg_error">No se ha seleccionado un cajero para editar o el ID no es válido.</p>
                <div class="form-actions" style="justify-content: center;">
                    <a href="../listas/lista_cajero.php" class="btn-cancel">Regresar a la lista</a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
<?php mysqli_close($conn); ?>