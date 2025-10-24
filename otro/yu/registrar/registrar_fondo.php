<?php
include "../conexion/conexion.php";
session_start();
$id_fondo = '';
$fondo = '';
$id_usuario = '';
$alert_message = '';
$alert_type = '';
$modo_edicion = false;

if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../ingreso.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

$query_load = mysqli_query($conn, "SELECT * FROM fondo WHERE id_usuario = $id_usuario LIMIT 1");
$data = mysqli_fetch_assoc($query_load);

if ($data) {
    $id_fondo = $data['id_fondo'];
    $fondo = $data['fondo'];
    $modo_edicion = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['fondo'])) {
        $alert_message = 'El campo Fondo es obligatorio.';
        $alert_type = 'error';
    } else {
        $fondo_post = $_POST['fondo'];

        $query_update = mysqli_query($conn, "UPDATE fondo SET fondo = '$fondo_post' WHERE id_fondo = $id_fondo AND id_usuario = $id_usuario");

        if ($query_update) {
            $alert_message = 'Fondo actualizado con éxito.';
            $alert_type = 'success';
        } else {
            $alert_message = 'Error al registrar el fondo.';
            $alert_type = 'error';
        }
    }
}
?>

<html>
<head>
    <title>Registro de Fondo</title>
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
    <form action="" method="post" class="formulario" enctype="multipart/form-data" id="valid">
        <h2 class="title">Registro de fondo</h2>
        <label for="fondo" class="text">Fondo:</label>
        <input type="text" required name="fondo" id="fondo" value="<?php echo htmlspecialchars($fondo); ?>" placeholder="Ingrese el fondo" class="input">
        <input type="submit" value="<?php echo $modo_edicion ? 'Actualizar Fondo' : 'Guardar Fondo'; ?>" class="input">
        <a href="../menu.html" class="btn btn--cancel">Regresar</a>
    </form>

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
            <p><?php echo htmlspecialchars($alert_message); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('miModal');
            if (modal) {
                modal.style.display = 'flex';
                
                setTimeout(function() {
                    window.location.href = "../menu.html";
                }, 3000);
            }
        });
    </script>
</body>
</html>