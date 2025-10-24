<?php
session_start();
include "../conexion/conexion.php";

$alert_message = '';
$alert_type = '';

if (!empty($_POST)) {
    if (empty($_POST['cargo'])) {
        $alert_message = 'Todos los campos son obligatorios.';
        $alert_type = 'error';
    } else {
        $Nombre = ucwords($_POST['cargo']);

        $query_insert = mysqli_prepare($conn, "INSERT INTO cargo(nom_cargo, estado_cargo) VALUES(?, 1)");
        mysqli_stmt_bind_param($query_insert, "s", $Nombre);

        if (mysqli_stmt_execute($query_insert)) {
            $alert_message = "Cargo registrado exitosamente.";
            $alert_type = 'success';
        } else {
            $alert_message = "Error al registrar el cargo.";
            $alert_type = 'error';
        }
        mysqli_stmt_close($query_insert);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/registrar_cargo.css">
    <title>Registro de Cargo</title>
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
        <form action="" method="post" autocomplete="off">
            <h2 class="title">Registro de Cargo</h2>
            <label for="cargo">Nombre:</label>
            <input type="text" required="" name="cargo" placeholder="Nombre del cargo" autocomplete="off">
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button class="input" type="submit">Crear</button>
                <a href="../listas/lista_cargos.php" class="btn btn--cancel">Regresar</a>
            </div>
        </form>
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
                    window.location.href = '../listas/lista_cargos.php';
                }, 3000);
                <?php endif; ?>
            }
        });
    </script>
</body>
</html>