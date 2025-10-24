<?php
session_start();
include "../conexion/conexion.php";

$alert_message = '';
$alert_type = '';
$id_categoria_gasto = null;
$nombre_categoria_actual = '';

if (isset($_GET['Categoria']) && is_numeric($_GET['Categoria'])) {
    $id_categoria_gasto = $_GET['Categoria'];

    $query_select = mysqli_query($conn, "SELECT nombre_categoria_gasto FROM categoria_gasto WHERE id_categoria_gasto = $id_categoria_gasto");
    $resultado_select = mysqli_fetch_assoc($query_select);

    if ($resultado_select) {
        $nombre_categoria_actual = $resultado_select['nombre_categoria_gasto'];
    } else {
        $alert_message = 'No se encontró la categoría de gasto.';
        $alert_type = 'error';
    }
} else {
    $alert_message = 'ID de categoría de gasto inválido.';
    $alert_type = 'error';
}

if (!empty($_POST)) {
    if (empty($_POST['categoria'])) {
        $alert_message = 'El nombre de la categoría es obligatorio.';
        $alert_type = 'error';
    } else {
        $Nombre = ucwords($_POST['categoria']);
        $id_categoria_gasto_post = isset($_GET['Categoria']) ? intval($_GET['Categoria']) : null;
        
        if ($id_categoria_gasto_post) {
            $query_update = mysqli_query($conn, "UPDATE categoria_gasto SET nombre_categoria_gasto = '$Nombre' WHERE id_categoria_gasto = $id_categoria_gasto_post");

            if ($query_update) {
                $alert_message = 'Categoría de Gasto Actualizada correctamente.';
                $alert_type = 'success';
            } else {
                $alert_message = 'Error al actualizar la categoría de gasto.';
                $alert_type = 'error';
            }
        } else {
            $alert_message = 'ID de categoría de gasto inválido para actualizar.';
            $alert_type = 'error';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/registrar_cargo.css">
    <title> Editar Categoria (Gasto)</title>
    <style>
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
                    window.location.href = '../listas/lista_categoria_gasto.php';
                }, 3000);
            }
        });
    </script>
<?php else: ?>
    <div class="form-container">
        <form action="" method="post">
            <h2 class="title">Editar Categoria (Gasto)</h2>
            <?php if ($alert_type === 'error' && !empty($alert_message)): ?>
                <?php echo '<p class="msg_error">' . htmlspecialchars($alert_message) . '</p>'; ?>
            <?php endif; ?>
            <?php if ($id_categoria_gasto !== null): ?>
                <label for="categoria">Nombre:</label>
                <input type="text" required name="categoria" id="categoria" placeholder="Nuevo nombre de la categoria" value="<?php echo htmlspecialchars($nombre_categoria_actual); ?>" autocomplete="off">
                <button class="input" type="submit" >Actualizar</button>
            <?php else: ?>
                <p>No se puede mostrar el formulario de edición.</p>
            <?php endif; ?>
            <a href="../listas/lista_categoria_gasto.php" class="btn btn--cancel">Regresar a la lista</a>
        </form>
    </div>
<?php endif; ?>
</body>
</html>