<?php
session_start();
include "../conexion/conexion.php";

$alert_message = '';
$alert_type = '';
$id_tipo_usuario_cajero = 2;

if (!empty($_POST)) {
    if (empty($_POST['nombre_usuario']) || empty($_POST['clave'])) {
        $alert_message = 'El nombre de usuario y la contraseña son obligatorios.';
        $alert_type = 'error';
    } else {
        $nombre_usuario = ucwords($_POST['nombre_usuario']);
        $clave = $_POST['clave'];

        $query_existente = mysqli_prepare($conn, "SELECT id_usuario FROM usuario WHERE nombre_usuario = ?");
        mysqli_stmt_bind_param($query_existente, "s", $nombre_usuario);
        mysqli_stmt_execute($query_existente);
        $resultado_existente = mysqli_stmt_get_result($query_existente);
        $row_existente = mysqli_fetch_array($resultado_existente);

        if ($row_existente) {
            $alert_message = 'Ya existe un usuario registrado con este nombre de usuario.';
            $alert_type = 'error';
        } else {
            $query_insert = mysqli_prepare($conn, "INSERT INTO usuario(nombre_usuario, clave, estado_usuario, id_tipo_usuario) VALUES(?, ?, 1, ?)");
            mysqli_stmt_bind_param($query_insert, "ssi", $nombre_usuario, $clave, $id_tipo_usuario_cajero);

            if (mysqli_stmt_execute($query_insert)) {
                $alert_message = "Cajero registrado exitosamente.";
                $alert_type = 'success';
            } else {
                $alert_message = "Error al registrar el Cajero.";
                $alert_type = 'error';
            }
            mysqli_stmt_close($query_insert);
        }
        mysqli_stmt_close($query_existente);
    }
}
?>
<html>
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/registrar_empleado.css">
    <title>Registro de Cajero</title>
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
    <section>
        <form action="" method="post" class="formulario" id="valid">
            <h2 class="title">Registro de Cajero</h2>
            <div class="form-group">
                <label for="nombre_usuario" class="text">Nombre de Usuario:</label>
                <input type="text" required name="nombre_usuario" id="nombre_usuario" placeholder="Ingrese Nombre de Usuario" class="input" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="clave" class="text">Contraseña:</label>
                <input type="text" required name="clave" id="clave" placeholder="Ingrese Contraseña" class="input" autocomplete="off">
            </div>
            <div class="form-group buttons">
                <input type="submit" value="Crear Cajero" class="input">
                <a href="../listas/lista_cajero.php" class="btn btn--cancel">Regresar</a>
            </div>
        </form>
    </section>

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
                    window.location.href = '../listas/lista_cajero.php';
                }, 3000);
                <?php endif; ?>
            }
        });
    </script>
</body>
</html>