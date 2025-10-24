<?php
session_start();
include "../conexion/conexion.php";

$alert_message = '';
$alert_type = '';

if (!empty($_POST)) {
    if (
        empty($_POST['descripcion']) || empty($_POST['Monto']) || empty($_POST['fecha']) || empty($_POST['categoria_gasto'])
    ) {
        $alert_message = 'Todos los campos son obligatorios.';
        $alert_type = 'error';
    } else {
        $descripcion = $_POST['descripcion'];
        $Monto_input = $_POST['Monto'];
        $Monto = 0;

        if (!is_numeric($Monto_input) || floatval($Monto_input) <= 0) {
            $alert_message = 'El monto debe ser un número positivo.';
            $alert_type = 'error';
        } else {
            $Monto = floatval($Monto_input);
        }
        
        $fecha = $_POST['fecha'];
        $categoria = $_POST['categoria_gasto'];

        if (empty($alert_message)) {
            $conn->begin_transaction();

            try {
                $estado_gasto = 1;
                $query_insert = $conn->prepare("INSERT INTO gastos(descripcion_gasto, monto_gasto, fecha_gasto, id_categoria_gasto, estado_gasto) VALUES(?, ?, ?, ?, ?)");
                
                if (!$query_insert) {
                    throw new Exception("Error al preparar la consulta de inserción: " . $conn->error);
                }

                $query_insert->bind_param("sdsii", $descripcion, $Monto, $fecha, $categoria, $estado_gasto);
                
                if ($query_insert->execute()) {
                    $id_fondo_gerente = 1;
                    $query_update_fondo = $conn->prepare("UPDATE fondo SET fondo = fondo - ? WHERE id_fondo = ?");

                    if (!$query_update_fondo) {
                        throw new Exception("Error al preparar la consulta de actualización del fondo: " . $conn->error);
                    }

                    $query_update_fondo->bind_param("di", $Monto, $id_fondo_gerente);
                    
                    if ($query_update_fondo->execute()) {
                        $conn->commit();
                        $alert_message = "Gasto registrado exitosamente.";
                        $alert_type = "success";
                    } else {
                        throw new Exception("Error al actualizar el fondo: " . $query_update_fondo->error);
                    }
                } else {
                    throw new Exception("Error al registrar el gasto: " . $query_insert->error);
                }
            } catch (Exception $e) {
                $conn->rollback();
                $alert_message = 'Error en la transacción: ' . $e->getMessage();
                $alert_type = 'error';
            } finally {
                if (isset($query_insert) && $query_insert instanceof mysqli_stmt) $query_insert->close();
                if (isset($query_update_fondo) && $query_update_fondo instanceof mysqli_stmt) $query_update_fondo->close();
            }
        }
    }
}
?>
<html>
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/registrar_empleado.css">
    <title>Registro Gasto</title>
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
            <h2 class="title">Registro de Gasto</h2>
            <div class="form-group">
                <label for="descripcion" class="text">Descripción Gasto:</label>
                <textarea name="descripcion" required id="descripcion" placeholder="Ingrese una descripción del gasto" class="input"></textarea>
            </div>
            <div class="form-group">
                <label for="Monto" class="text">Monto:</label>
                <input type="number" required name="Monto" id="Monto" placeholder="Ingrese el Monto" class="input" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="fecha" class="text">Fecha:</label>
                <input type="date" required name="fecha" id="fecha" placeholder="">
            </div>
            <div class="form-group">
                <label for="categoria" class="text">Categoría:</label>
                <select class="input" name="categoria_gasto" id="categoria_gasto" required>
                    <option value="" hidden selected>Categoría</option>
                    <?php
                    $sql ="SELECT id_categoria_gasto, nombre_categoria_gasto FROM categoria_gasto WHERE estado_categoria_gasto = 1 ORDER BY nombre_categoria_gasto ASC";
                    $result = mysqli_query($conn, $sql);
                    while ($mostrar = mysqli_fetch_array($result)) {
                    ?>
                        <option value="<?php echo $mostrar['id_categoria_gasto'] ?>"><?php echo $mostrar['nombre_categoria_gasto'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="form-group buttons">
                <input type="submit" value="Crear" class="input">
                <a href="../listas/lista_gastos.php" class="btn btn--cancel">Regresar</a>
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
                    window.location.href = "../listas/lista_gastos.php";
                }, 3000);
            }
        });
    </script>
</body>
</html>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("fecha");
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