<?php
session_start();
$_tabs_atr = 99;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/_general.php'; 
require_once 'includes/_messages.php';

$error_display = '';
$registro_id = $_POST['id'] ?? null;
$tipo_registro = $_POST['entidad'] ?? null;
$nombre_registro = $_POST['nombre'] ?? 'Registro Desconocido';
$tab_id = $_POST['tab'] ?? '1'; 
switch ($tab_id) {
    case '1':
        $return_url = 'list_o.php';
        break;
    case '2':
        $return_url = 'list_a.php';
        break;
    case '6':
        $return_url = 'list_co.php';
        break;
    case '8':
        $return_url = 'list_r.php';
        break;
    default:
        $return_url = 'index.php';
}
$full_return_url = $return_url;

if (!empty($_POST['confirmar_eliminar']) && $_POST['confirmar_eliminar'] === 'true') {
    $tabla = $_POST['tabla_oculta'];
    $columna_id = $_POST['columna_oculta'];
    $status = $_POST['status_oculto'];
    
    if (!$registro_id || empty($tabla)) { 
        $_SESSION['error_code'] = 2; 
        header("Location: {$full_return_url}"); 
        exit();
    }
    
    $sql_deshabilitar = "UPDATE {$tabla} SET {$status} = 0 WHERE {$columna_id} = ?";
    
    $tipos = 's';
    if ($columna_id === 'id_mantenimiento' || $columna_id === 'id_categoria' || $columna_id === 'id_tecnica' || $columna_id === 'id_material' || $columna_id === 'id_area') {
        $tipos = 'i'; 
        $registro_id = (int)$registro_id;
    }

    $parametros = [$registro_id];
    
    if ($bd->accionRegistro($sql_deshabilitar, $tipos, $parametros)['success']) {
        $_SESSION['success_code'] = 4;
        header("Location: {$full_return_url}");
        exit();
    } else {
        $_SESSION['error_code'] = 2;
        header("Location: {$full_return_url}");
        exit();
    }
}
if (!$registro_id || !$tipo_registro || !$nombre_registro) {
    $_SESSION['error_code'] = 98; 
    header("Location: {$return_url}");
    exit();
}

$logic_file = 'includes/scripts/' . str_replace('list_', 'list_', $return_url);
if (file_exists($logic_file)) {
    $tabla = ''; $columna_id = ''; $status = '';
    include $logic_file; 
    
    if (empty($tabla) || empty($columna_id) || empty($status)) {
        $_SESSION['error_code'] = 2;
        header("Location: {$return_url}");
        exit();
    }
} else {
     $_SESSION['error_code'] = 2; 
    header("Location: {$return_url}");
    exit();
}
?>

<! DOCTYPE html>
<html lang="es">

<head>
    <?php include "includes/_head.php"; ?>
    <title>Eliminar Registro | Confirmación</title>
    <style>
        #delete_container {
            background-color: #fff;
            padding: 3rem;
            border-radius: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            margin: auto;
        }
        #delete_container h1 {
            color: #d9534f;
            margin-bottom: 1rem;
            font-size: 2em;
        }
        #delete_container p {
            font-size: 1.1em;
            margin-bottom: 1.5rem;
            color: #333;
        }
        .registro_nombre {
            font-weight: bold;
            color: #5b8079;
        }
        #delete_container .modal_botones a {
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 2rem;
            font-weight: bold;
            transition: 0.2s;
        }
        #btn_cancelar {
            background-color: #ccc;
            color: #333;
        }
        #btn_cancelar:hover {
            background-color: #aaa;
        }
        #btn_confirmar {
            background-color: #d9534f;
            color: white;
            border: none;
            cursor: pointer;
        }
        #btn_confirmar:hover {
            background-color: #c9302c;
        }
    </style>
</head>

<body>
    <div id="fondo"></div>
    <main>
        <section id="delete_container">
            <h1>Confirmación de Eliminación</h1>
            <p>¿Está **ABSOLUTAMENTE SEGURO** de que desea deshabilitar el siguiente registro?</p>
            
            <p>
                Tipo: <span class="registro_nombre"><?php echo htmlspecialchars($tipo_registro); ?></span><br>
                Registro: <span class="registro_nombre"><?php echo htmlspecialchars($nombre_registro); ?></span>
            </p>

            <p style="color: #c9302c; font-weight: bold;">
                Esta acción deshabilitará permanentemente el registro en el sistema y solo puede ser revertida a nivel de base de datos.
            </p>

            <form method="POST" action="">
                <input type="hidden" name="confirmar_eliminar" value="true">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($registro_id); ?>">
                <input type="hidden" name="entidad" value="<?php echo htmlspecialchars($tipo_registro); ?>">
                <input type="hidden" name="tab" value="<?php echo htmlspecialchars($tab_id); ?>">
                
                <input type="hidden" name="tabla_oculta" value="<?php echo htmlspecialchars($tabla); ?>">
                <input type="hidden" name="columna_oculta" value="<?php echo htmlspecialchars($columna_id); ?>">
                <input type="hidden" name="status_oculto" value="<?php echo htmlspecialchars($status); ?>">


                <div class="modal_botones">
                    <a href="<?php echo htmlspecialchars($full_return_url); ?>" id="btn_cancelar">
                        Cancelar
                    </a>
                    <button type="submit" id="btn_confirmar">
                        Sí, Deshabilitar
                    </button>
                </div>
            </form>
        </section>
    </main>
    <?php include 'includes/_bar.php';
    include 'includes/_messages.php'; ?>
</body>
</html>