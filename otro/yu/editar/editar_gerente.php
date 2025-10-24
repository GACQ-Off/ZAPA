<?php
session_start();
require_once '../conexion/conexion.php';
include ('Encriptar.php');

$id_usuario_editar = null;
$nombre_usuario_actual = '';
$clave_actual = '';
$alert_message = '';
$alert_type = '';
$mostrar_formulario = false;

if (!isset($conn) || $conn->connect_error) {
    error_log("Error de conexión a la base de datos: " . ($conn->connect_error ?? "Error desconocido."));
    die('<p style="color: red; font-weight: bold;">Error crítico: No se pudo conectar con la base de datos. Por favor, contacte al administrador.</p>');
}

$stmt_get_unico = $conn->prepare("SELECT id_usuario, nombre_usuario, clave FROM usuario LIMIT 1");
if ($stmt_get_unico) {
    $stmt_get_unico->execute();
    $result_get_unico = $stmt_get_unico->get_result();

    if ($result_get_unico->num_rows === 1) {
        $usuario_data = $result_get_unico->fetch_assoc();
        $id_usuario_editar = $usuario_data['id_usuario'];
        $nombre_usuario_actual = $usuario_data['nombre_usuario'];
        $clave_actual = $usuario_data['clave'];

        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            $mostrar_formulario = true;
        }
    } else {
        $alert_message = 'Error crítico: No se encontró ningún usuario registrado en el sistema.';
        $alert_type = 'error';
        $mostrar_formulario = false;
    }
    $stmt_get_unico->close();
} else {
    error_log("Error preparando consulta para buscar al único usuario: " . $conn->error);
    $alert_message = 'Error al intentar obtener la información del usuario.';
    $alert_type = 'error';
    $mostrar_formulario = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario_nuevo = trim($_POST['nombre_usuario'] ?? '');
    $clave_nueva = trim($_POST['nueva_clave'] ?? '');
    $confirmar_clave_nueva = trim($_POST['confirmar_nueva_clave'] ?? '');

    if (!isset($_POST['nombre_usuario'], $_POST['nueva_clave'], $_POST['confirmar_nueva_clave'])) {
        $alert_message = 'Error: Faltan datos del formulario (nombre o claves).';
        $alert_type = 'error';
        $mostrar_formulario = true;
    } else if ($id_usuario_editar === null) {
        $alert_message = 'Error: No se pudo identificar al usuario a editar.';
        $alert_type = 'error';
        $mostrar_formulario = false;
    } else {
        if (empty($nombre_usuario_nuevo)) {
            $alert_message .= 'El nombre de usuario no puede estar vacío.';
            $alert_type = 'error';
        }
        if (empty($clave_nueva)) {
            $alert_message .= 'Debes ingresar la nueva contraseña.';
            $alert_type = 'error';
        }
        if ($clave_nueva !== $confirmar_clave_nueva) {
            $alert_message .= 'La nueva contraseña y la confirmación no coinciden.';
            $alert_type = 'error';
        }

        if ($alert_type === '') {
            $stmt_check = $conn->prepare("SELECT id_usuario FROM usuario WHERE nombre_usuario = ? AND id_usuario != ?");
            if ($stmt_check) {
                $stmt_check->bind_param("si", $nombre_usuario_nuevo, $id_usuario_editar);
                $stmt_check->execute();
                $stmt_check->store_result();
                if ($stmt_check->num_rows > 0) {
                    $alert_message .= 'El nombre de usuario \'' . htmlspecialchars($nombre_usuario_nuevo) . '\' ya está en uso.';
                    $alert_type = 'error';
                }
                $stmt_check->close();
            } else {
                error_log("Error preparando consulta de verificación de nombre: " . $conn->error);
                $alert_message .= 'Error al verificar la disponibilidad del nombre de usuario.';
                $alert_type = 'error';
            }
        }

        if ($alert_type === '') {
            $clave_encriptada = encriptar($clave_nueva, $nombre_usuario_nuevo);
            $stmt_update = $conn->prepare("UPDATE usuario SET nombre_usuario = ?, clave = ? WHERE id_usuario = ?");
            if ($stmt_update) {
                $stmt_update->bind_param("ssi", $nombre_usuario_nuevo, $clave_encriptada, $id_usuario_editar);

                if ($stmt_update->execute()) {
                    $alert_message = 'Usuario actualizado correctamente.';
                    $alert_type = 'success';
                } else {
                    error_log("Error al actualizar usuario ID " . $id_usuario_editar . ": " . $stmt_update->error);
                    $alert_message = 'No se pudo actualizar el usuario en la base de datos. Intente más tarde.';
                    $alert_type = 'error';
                }
                $stmt_update->close();
            } else {
                error_log("Error preparando consulta UPDATE para usuario: " . $conn->error);
                $alert_message = 'Error al preparar la actualización del usuario.';
                $alert_type = 'error';
            }
        }
        $mostrar_formulario = true;
    }
}

$desencriptado=desencriptar($clave_actual, $nombre_usuario_actual);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <?php include "../assets/head_gerente.php"?>
    <title>Actualizar Usuario</title>
    <style>
        /* Estilos CSS existentes */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            max-width: 500px;
            margin: 50px auto; /* Centrar en la pantalla */
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

        .msg_error, .msg_save, .msg_info {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: left;
            border: 1px solid;
        }
        .msg_error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .msg_save {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .msg_info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
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
        .form-group input.visual-input[readonly] {
            background-color: #e9ecef;
            cursor: default;
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
                        window.location.href = '../menu.php';
                    }, 3000);
                }
            });
        </script>
    <?php else: ?>
        <div class="auth-container">
            <?php if ($mostrar_formulario && $id_usuario_editar !== null): ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" novalidate>
                    <h2>Actualizar Datos de Usuario (Gerente)</h2>
                    <?php if ($alert_type === 'error'): ?>
                        <div class=""><?php echo '<p class="msg_error">' . htmlspecialchars($alert_message) . '</p>'; ?></div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="nombre_usuario">Nombre de usuario:</label>
                        <input type="text" required name="nombre_usuario" id="nombre_usuario" placeholder="Ingrese el nuevo nombre de usuario" value="<?php echo htmlspecialchars($nombre_usuario_actual); ?>" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="clave_actual_visual">Contraseña Actual:</label>
                        <input type="text" class="visual-input" id="clave_actual_visual" value="<?php echo htmlspecialchars($desencriptado); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label for="nueva_clave">Nueva Contraseña:</label>
                        <input type="password" required name="nueva_clave" id="nueva_clave" placeholder="Ingrese la nueva contraseña" autocomplete="off">
                        <small>Se requiere ingresar una nueva contraseña para actualizar.</small>
                    </div>

                    <div class="form-group">
                        <label for="confirmar_nueva_clave">Confirmar Nueva Contraseña:</label>
                        <input type="password" required name="confirmar_nueva_clave" id="confirmar_nueva_clave" placeholder="Confirme la nueva contraseña" autocomplete="off">
                    </div>
                    <div class="form-actions">
                        <button type="submit">Actualizar Usuario</button>
                        <a href="../menu.php" class="btn-cancel">Regresar</a>
                    </div>
                </form>
            <?php else: ?>
                <h2>Editar Usuario</h2>
                <?php
                if (!empty($alert_message)) {
                    echo '<p class="msg_error">' . htmlspecialchars($alert_message) . '</p>';
                } else {
                    echo '<p class="msg_error">No se pudo cargar la información del usuario para editar. Verifique que el ID sea correcto o contacte al administrador.</p>';
                }
                ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
<script src="../assets/js/menu.js"></script>
</html>
<?php mysqli_close($conn); ?>