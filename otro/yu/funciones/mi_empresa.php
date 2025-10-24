<?php
require_once '../conexion/conexion.php';
session_start();

$uploadDir = '../assets/images/';
$targetFilePath = $uploadDir . 'empresa.png';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$message = "";
$empresa_data = null;
$logo_fs_path = '../assets/images/empresa.png';
$logo_fs_exists = file_exists($logo_fs_path);

$sql_fetch_empresa = "SELECT * FROM empresa LIMIT 1";
$result_empresa = $conn->query($sql_fetch_empresa);
if ($result_empresa && $result_empresa->num_rows > 0) {
    $empresa_data = $result_empresa->fetch_assoc();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $logo_data_for_db = null;

    $rif_empresa = $_POST['RIF_empresa'];
    $cedula_representante = $_POST['cedula_representante'];
    $nombre_representante = $_POST['nombre_representante'];
    $apellido_representante = $_POST['apellido_representante'];
    $telefono_representante = $_POST['telefono_representante'];
    $direccion_empresa = $_POST['direccion_representante'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $id_usuario = $_SESSION['id_usuario'] ?? 1;

    if (isset($_FILES["logo_empresa_fs"]) && $_FILES["logo_empresa_fs"]["error"] == 0) {
        $uploadedFileFS = $_FILES["logo_empresa_fs"];
        if (move_uploaded_file($uploadedFileFS["tmp_name"], $targetFilePath)) {
            $message .= "El logo se ha guardado en el sistema de archivos.<br>";
        } else {
            $message .= "Error al subir el archivo del logo para archivo.<br>";
        }
    }

    if (isset($_FILES["logo_para_db"]) && $_FILES["logo_para_db"]["error"] == 0) {
        $uploadedFileDB = $_FILES["logo_para_db"];
        $logo_data_for_db = file_get_contents($uploadedFileDB["tmp_name"]);
        if ($logo_data_for_db === false) {
             $message .= "Error al leer el contenido del logo.<br>";
             $logo_data_for_db = null;
        } else {
            $message .= "Contenido del Logo leído exitosamente.<br>";
        }
    } elseif (isset($_FILES["logo_para_db"]) && $_FILES["logo_para_db"]["error"] != UPLOAD_ERR_NO_FILE) {
        $message .= "Error al subir el Logo" . $_FILES["logo_para_db"]["error"] . "<br>";
    }


    $sql_check = "SELECT RIF_empresa FROM empresa WHERE RIF_empresa = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $rif_empresa);
    $stmt_check->execute();
    $stmt_check->store_result();
    $empresa_existe = $stmt_check->num_rows > 0;
    $stmt_check->close();

    if ($empresa_existe) {
        $sql_update_parts = [];
        $update_params_values = [];
        $update_types = "";
        $logo_content_for_send_long_data = null;
        $logo_param_index_in_query = -1;

        $sql_update_parts[] = "cedula_representante = ?"; $update_params_values[] = $cedula_representante; $update_types .= "s";
        $sql_update_parts[] = "nombre_representante = ?"; $update_params_values[] = $nombre_representante; $update_types .= "s";
        $sql_update_parts[] = "apellido_representante = ?"; $update_params_values[] = $apellido_representante; $update_types .= "s";
        $sql_update_parts[] = "telefono_representante = ?"; $update_params_values[] = $telefono_representante; $update_types .= "s";
        $sql_update_parts[] = "direccion_empresa = ?"; $update_params_values[] = $direccion_empresa; $update_types .= "s";
        $sql_update_parts[] = "nombre_empresa = ?"; $update_params_values[] = $nombre_empresa; $update_types .= "s";
        $sql_update_parts[] = "id_usuario = ?"; $update_params_values[] = $id_usuario; $update_types .= "i";

        if ($logo_data_for_db !== null) {
            $sql_update_parts[] = "logo_empresa = ?";
            $update_params_values[] = null;
            $update_types .= "b";
            $logo_content_for_send_long_data = $logo_data_for_db;
            $logo_param_index_in_query = count($update_params_values) - 1;
        }

        if (!empty($sql_update_parts)) {
            $sql = "UPDATE empresa SET " . implode(", ", $sql_update_parts) . " WHERE RIF_empresa = ?";
            $update_types .= "s";
            $update_params_values[] = $rif_empresa;

            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $bind_params_ref = [$update_types];
                foreach ($update_params_values as $key => &$val) {
                    $bind_params_ref[] = &$val;
                }
                unset($val);

                call_user_func_array([$stmt, 'bind_param'], $bind_params_ref);

                if ($logo_content_for_send_long_data !== null && $logo_param_index_in_query !== -1) {
                    $chunk_size = 8192;
                    for ($i = 0; $i < strlen($logo_content_for_send_long_data); $i += $chunk_size) {
                        $chunk = substr($logo_content_for_send_long_data, $i, $chunk_size);
                        $stmt->send_long_data($logo_param_index_in_query, $chunk);
                    }
                }

                if ($stmt->execute()) {
                    $message .= "Registro de empresa actualizado exitosamente.";
                } else {
                    $message .= "Error al actualizar el registro de empresa: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message .= "Error al preparar la consulta de actualización: " . $conn->error;
            }
        }
    } else {
        if ($logo_data_for_db !== null) {
            $sql = "INSERT INTO empresa (RIF_empresa, cedula_representante, nombre_representante, apellido_representante, telefono_representante, direccion_empresa, nombre_empresa, logo_empresa, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $null_blob_placeholder = null;
                $stmt->bind_param("sssssssbi", $rif_empresa, $cedula_representante, $nombre_representante, $apellido_representante, $telefono_representante, $direccion_empresa, $nombre_empresa, $null_blob_placeholder, $id_usuario);

                $logo_param_index_insert = 7;
                $chunk_size = 8192;
                for ($i = 0; $i < strlen($logo_data_for_db); $i += $chunk_size) {
                    $chunk = substr($logo_data_for_db, $i, $chunk_size);
                    $stmt->send_long_data($logo_param_index_insert, $chunk);
                }

                if ($stmt->execute()) {
                    $message .= "Nuevo registro de empresa creado exitosamente en la base de datos.";
                } else {
                    $message .= "Error al insertar en la base de datos: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message .= "Error al preparar la consulta de inserción: " . $conn->error;
            }
        } else {
            $sql = "INSERT INTO empresa (RIF_empresa, cedula_representante, nombre_representante, apellido_representante, telefono_representante, direccion_empresa, nombre_empresa, id_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sssssssi", $rif_empresa, $cedula_representante, $nombre_representante, $apellido_representante, $telefono_representante, $direccion_empresa, $nombre_empresa, $id_usuario);
                if ($stmt->execute()) {
                    $message .= "Nuevo registro de empresa creado (sin logo para BD).";
                } else {
                    $message .= "Error al insertar en la base de datos (sin logo): " . $stmt->error;
                }
                $stmt->close();
            } else {
                $message .= "Error al preparar la consulta de inserción (sin logo): " . $conn->error;
            }
        }
    }

    if (strpos($message, "exitosamente") !== false || strpos($message, "creado") !== false) {
        $result_empresa = $conn->query($sql_fetch_empresa);
        if ($result_empresa && $result_empresa->num_rows > 0) {
            $empresa_data = $result_empresa->fetch_assoc();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Empresa</title>
    <?php include "../assets/head_gerente.php"?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            width: 900px;
            max-width: 650px;
            margin: 30px 0 50px 100px;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 10px #0056b3;
            text-align: left;
        }

        .auth-container h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 25px;
            text-align: center;
            font-size: 1.8em;
        }

        .message {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: left;
            border: 1px solid;
            line-height: 1.5;
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
        .form-group input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="file"]:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
            outline: none;
        }
        .form-group input[type="file"] {
            padding: 8px;
        }

        .form-group small {
            display: block;
            margin-top: 5px;
            font-size: 0.85em;
            color: #6c757d;
        }

        .logo-preview-container {
            margin-top: 10px;
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
            text-align: center;
        }
        .logo-preview-container p {
            margin: 0 0 8px 0;
            font-weight: bold;
            color: #555;
        }
        .logo-preview-img {
            max-width: 150px;
            max-height: 100px;
            border: 1px solid #ddd;
            padding: 5px;
            background-color: #fff;
        }
        .db-logo-notice {
            margin-top: 8px;
            font-size: 0.9em;
            color: #155724;
            background-color: #d4edda;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #c3e6cb;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
        }

        .form-actions button[type="submit"] {
            background-color: #3533cd;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.05em;
            transition: background-color 0.3s ease;
        }
        .form-actions button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <?php include '../assets/lista_gerente.php'; ?>
    <div class="auth-container">
        <h2>Mi Empresa</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo (strpos(strtolower($message), "error") !== false) ? 'msg_error' : 'msg_save'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
            <label for="RIF_empresa">RIF Empresa:</label>
            <input type="text" id="RIF_empresa" name="RIF_empresa" required value="<?php echo isset($empresa_data['RIF_empresa']) ? htmlspecialchars($empresa_data['RIF_empresa']) : ''; ?>">
        </div>

            <div class="form-group">
            <label for="cedula_representante">Cédula Representante:</label>
            <input type="text" id="cedula_representante" name="cedula_representante" required value="<?php echo isset($empresa_data['cedula_representante']) ? htmlspecialchars($empresa_data['cedula_representante']) : ''; ?>">
        </div>

            <div class="form-group">
            <label for="nombre_representante">Nombre Representante:</label>
            <input type="text" id="nombre_representante" name="nombre_representante" required value="<?php echo isset($empresa_data['nombre_representante']) ? htmlspecialchars($empresa_data['nombre_representante']) : ''; ?>">
        </div>

            <div class="form-group">
            <label for="apellido_representante">Apellido Representante:</label>
            <input type="text" id="apellido_representante" name="apellido_representante" required value="<?php echo isset($empresa_data['apellido_representante']) ? htmlspecialchars($empresa_data['apellido_representante']) : ''; ?>">
        </div>

            <div class="form-group">
            <label for="telefono_representante">Teléfono Representante:</label>
            <input type="text" id="telefono_representante" name="telefono_representante" required value="<?php echo isset($empresa_data['telefono_representante']) ? htmlspecialchars($empresa_data['telefono_representante']) : ''; ?>">
        </div>

            <div class="form-group">
            <label for="direccion_empresa">Dirección:</label>
            <input type="text" id="direccion_representante" name="direccion_representante" required value="<?php echo isset($empresa_data['direccion_empresa']) ? htmlspecialchars($empresa_data['direccion_empresa']) : ''; ?>">
        </div>

            <div class="form-group">
            <label for="nombre_empresa">Nombre Empresa:</label>
            <input type="text" id="nombre_empresa" name="nombre_empresa" required value="<?php echo isset($empresa_data['nombre_empresa']) ? htmlspecialchars($empresa_data['nombre_empresa']) : ''; ?>">
        </div>

            <div class="form-group">
            <label for="logo_empresa_fs">Logo visual del sistema</label>
            <input type="file" id="logo_empresa_fs" name="logo_empresa_fs" accept="image/*">
            <small>Inserte su logo archivos compactibles (PNG, JPG, GIF.)</small>
            <?php if ($logo_fs_exists): ?>
                <div class="logo-preview-container">
                    <p>Logo visual del sistema:</p>
                    <img src="<?php echo $logo_fs_path . '?t=' . time(); ?>" alt="Logo FS actual" class="logo-preview-img">
                </div>
            <?php endif; ?>
        </div>

            <div class="form-group">
            <label for="logo_para_db">Logo para (Facturas y Recibos):</label>
            <input type="file" id="logo_para_db" name="logo_para_db" accept="image/*">
            <small>Inserte su logo archivos compactibles (PNG, JPG, GIF.)</small>
            <?php if (isset($empresa_data['logo_empresa']) && !empty($empresa_data['logo_empresa'])): ?>
                <p class="db-logo-notice">Ya existe un logo para facturas/recibos en la base de datos. Subir uno nuevo lo reemplazará.</p>
            <?php endif; ?>
        </div>
            <div class="form-actions">
                <button type="submit">Guardar Cambios</button>
            </div>
        </form>
    </div>
    <?php $conn->close(); ?>
</body>
</html>