<?php
session_start();
include "../conexion/conexion.php";

$alert_message = '';
$alert_type = '';
$cedula = null;
$nombre = '';
$apellido = '';
$nacimiento = '';
$telefono = '';
$cargo = '';
$sql_cargos = null;

if ($conn) {
    $sql_cargos = mysqli_query($conn, "SELECT * FROM cargo WHERE estado_cargo = 1");
    if (!$sql_cargos) {
        error_log("Error al obtener los cargos: " . mysqli_error($conn));
    }
}

if (!empty($_POST)) {
    $cedula_post = mysqli_real_escape_string($conn, $_POST['Cedula'] ?? '');
    $nombre_post = ucwords(mysqli_real_escape_string($conn, $_POST['Nombre'] ?? ''));
    $apellido_post = ucwords(mysqli_real_escape_string($conn, $_POST['Apellido'] ?? ''));
    $nacimiento_post = mysqli_real_escape_string($conn, $_POST['Fecha_Nacimiento'] ?? '');
    $telefono_post = mysqli_real_escape_string($conn, $_POST['Telefono'] ?? '');
    $cargo_post = mysqli_real_escape_string($conn, $_POST['Cargo'] ?? '');

    if (empty($cedula_post) || empty($nombre_post) || empty($apellido_post) || empty($nacimiento_post) || empty($telefono_post) || empty($cargo_post)) {
        $alert_message = 'Todos los campos son obligatorios.';
        $alert_type = 'error';
    } else {
        $sql_Actualizar = mysqli_query($conn, "UPDATE empleado SET nombre_emp='$nombre_post', apellido_emp='$apellido_post', fecha_nacimiento='$nacimiento_post', telefono_emple='$telefono_post', id_cargo='$cargo_post' WHERE cedula_emple='$cedula_post'");

        if ($sql_Actualizar) {
            $alert_message = 'Empleado actualizado correctamente.';
            $alert_type = 'success';
        } else {
            $alert_message = 'Error al actualizar el empleado.';
            $alert_type = 'error';
        }
    }
    $cedula = $cedula_post;
    $nombre = $nombre_post;
    $apellido = $apellido_post;
    $nacimiento = $nacimiento_post;
    $telefono = $telefono_post;
    $cargo = $cargo_post;

} else {
    if (empty($_REQUEST["Empleado"])) {
        header('Location: ../listas/lista_empleado.php');
        exit();
    }

    $cedula_empleado = mysqli_real_escape_string($conn, $_REQUEST['Empleado']);
    $sql = mysqli_query($conn, "SELECT * FROM empleado WHERE cedula_emple='$cedula_empleado'");
    $result_sql = mysqli_num_rows($sql);

    if ($result_sql == 0) {
        header('Location: ../listas/lista_empleado.php');
        exit();
    } else {
        $Datos = mysqli_fetch_array($sql);
        $cedula = $Datos['cedula_emple'];
        $nombre = $Datos['nombre_emp'];
        $apellido = $Datos['apellido_emp'];
        $nacimiento = $Datos['fecha_nacimiento'];
        $telefono = $Datos['telefono_emple'];
        $cargo = $Datos['id_cargo'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/editar_empleado.css">
    <title>Editar Empleado</title>
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
                        window.location.href = '../listas/lista_empleado.php';
                    }, 3000);
                }
            });
        </script>
    <?php else: ?>
        <section>
            <form action="" method="post">
                <h2 class="title">Editar Datos de Empleado</h2>
                <?php if ($alert_type === 'error'): ?>
                    <div class=""><?php echo '<p class="msg_error">' . htmlspecialchars($alert_message) . '</p>'; ?></div>
                <?php endif; ?>

                <div class="campos-grid-container">
                    <div>
                        <label for="Cedula">Cédula:</label>
                        <input type="text" required name="Cedula" id="Cedula" placeholder="Ingrese Cédula" value="<?php echo htmlspecialchars($cedula); ?>" readonly>
                    </div>
                    <div>
                        <label for="Nombre">Nombre:</label>
                        <input type="text" required name="Nombre" id="Nombre" placeholder="Ingrese el nombre" value="<?php echo htmlspecialchars($nombre); ?>" autocomplete="off">
                    </div>
                    <div>
                        <label for="Apellido">Apellido:</label>
                        <input type="text" required name="Apellido" id="Apellido" placeholder="Ingrese el apellido" value="<?php echo htmlspecialchars($apellido); ?>" autocomplete="off">
                    </div>
                    <div>
                        <label for="Fecha_Nacimiento">Fecha de Nacimiento:</label>
                        <input type="date" required name="Fecha_Nacimiento" id="Fecha_Nacimiento" value="<?php echo htmlspecialchars($nacimiento); ?>">
                    </div>
                    <div>
                        <label for="Telefono">Teléfono:</label>
                        <input type="text" required name="Telefono" id="Telefono" placeholder="Ingrese el teléfono" value="<?php echo htmlspecialchars($telefono); ?>" autocomplete="off">
                    </div>
                    <div>
                        <label for="Cargo">Cargo:</label>
                        <select class="input" name="Cargo" id="Cargo">
                            <?php
                            if ($sql_cargos && mysqli_num_rows($sql_cargos) > 0) {
                                mysqli_data_seek($sql_cargos, 0); 
                                while ($mostrarCargo = mysqli_fetch_array($sql_cargos)) {
                            ?>
                                <option value="<?= htmlspecialchars($mostrarCargo['id_cargo']) ?>" <?= ($cargo == $mostrarCargo['id_cargo']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($mostrarCargo['nom_cargo']) ?>
                                </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="botones-container">
                    <button type="submit">Actualizar</button>
                    <a href="../listas/lista_empleado.php" class="btn-regresar">Regresar</a>
                </div>
            </form>
        </section>
    <?php endif; ?>
</body>
</html>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("Fecha_Nacimiento");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
</script>

