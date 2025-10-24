<?php
session_start();
include "../conexion/conexion.php";

$alert_message = '';
$alert_type = '';

if (!empty($_POST)) {
    if (empty($_POST['Nombre'])) {
        $alert_message = 'Todos los campos son obligatorios.';
        $alert_type = 'error';
    } else {
        $cargo_2 = $_POST['cargo_2'];
        $nombre_cargo = $_POST['Nombre'];

        $sql_Actualizar = mysqli_query($conn, "UPDATE cargo
                                             SET nom_cargo='$nombre_cargo'
                                             WHERE id_cargo=$cargo_2");

        if ($sql_Actualizar) {
            $alert_message = 'Cargo Actualizado correctamente.';
            $alert_type = 'success';
        } else {
            $alert_message = 'Error al Actualizar el Cargo';
            $alert_type = 'error';
        }
    }
}

// Verifica si se proporcionó un ID de cargo para editar
if (empty($_REQUEST["Cargo"])) {
    header('Location: ../listas/lista_cargos.php');
    exit;
}

$cargo_2 = $_REQUEST['Cargo'];
$sql = mysqli_query($conn, "SELECT *FROM cargo WHERE id_cargo='$cargo_2'");

$result_sql = mysqli_num_rows($sql);
if ($result_sql == 0) {
    header('location: ../listas/lista_cargos.php');
    exit;
} else {
    while ($Datos = mysqli_fetch_array($sql)) {
        $cargo_2 = $Datos['id_cargo'];
        $nombre_cargo = $Datos['nom_cargo'];
    }
}
mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
    <head>
        <?php include "../assets/head_gerente.php"?>
        <link rel="stylesheet" href="../assets/css/registrar_cargo.css">
        <title> Editar Cargo</title>
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
                padding: 10px 15px;
                margin-bottom: 20px;
                border-radius: 4px;
                text-align: center;
                border: 1px solid #f5c6cb;
                background-color: #f8d7da;
                color: #721c24;
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
                            window.location.href = '../listas/lista_cargos.php';
                        }, 3000);
                    }
                });
            </script>
        <?php else: ?>
            <div class="form-container">
                <form action="" method="post">
                    <h2 class="title">Editar de Cargo</h2>
                    <?php if ($alert_type === 'error'): ?>
                        <div class=""><?php echo '<p class="msg_error">' . htmlspecialchars($alert_message) . '</p>'; ?></div>
                    <?php endif; ?>
                    <label for="Nombre">Nombre:</label>
                    <input type="text" required="" id="Nombre" name="Nombre" placeholder="Nombre del cargo" value="<?php echo htmlspecialchars($nombre_cargo); ?>" autocomplete="off">
                    <input type="hidden" name="cargo_2" value="<?php echo $cargo_2; ?>">
                    <div class="botones-container">
                        <button class="input" type="submit">Actualizar</button>
                        <a href="../listas/lista_cargos.php" class="btn btn--cancel">Regresar</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </body>
</html>