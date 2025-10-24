<?php
session_start();
include "../assets/lista_gerente.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Pérdidas</title>
    <?php include "../assets/head_gerente.php";?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin-left: 150px;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px #0056b3;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 25px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
            display: block;
        }
        select,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #3533cd;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        option.error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Generar Reporte de Pérdidas</h2>
        <form action="reporte_perdida_funcion.php" method="GET" target="_blank">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required>

            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required>

            <label for="id_categoria">Selecciona una Categoría de Producto:</label>
            <select name="id_categoria" id="id_categoria" required>
                <option value="">-- Selecciona --</option>
                <?php
                include "../conexion/conexion.php";

                if ($conn->connect_error) {
                    error_log("Error de conexión a la base de datos en reporte_perdida.php: " . $conn->connect_error);
                    die("<option value='' class='error'>Error de conexión a la base de datos. Por favor, inténtalo más tarde.</option>");
                }

                $sql_categorias = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = '1' ORDER BY nombre_categoria ASC";
                $result_categorias = $conn->query($sql_categorias);

                if ($result_categorias && $result_categorias->num_rows > 0) {
                    while($row_cat = $result_categorias->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row_cat["id_categoria"]) . "'>" . htmlspecialchars($row_cat["nombre_categoria"]) . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No se encontraron categorías activas o hubo un problema al cargar la lista.</option>";
                    if (!$result_categorias) {
                        error_log("Error al ejecutar la consulta de categorías: " . $conn->error);
                    }
                }
                $conn->close();
                ?>
            </select>
            <button type="submit">Generar Reporte de Pérdidas</button>
        </form>
    </div>
</body>
</html>
<script>
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("fecha_inicio");
        const hoy = new Date();
        const anio = hoy.getFullYear();
        let mes = hoy.getMonth() + 1;
        let dia = hoy.getDate();
        mes = mes < 10 ? '0' + mes : mes;
        dia = dia < 10 ? '0' + dia : dia;
        const fechaMaxima = `${anio}-${mes}-${dia}`;
        inputFecha.max = fechaMaxima;
    });
        document.addEventListener("DOMContentLoaded", function() {
        const inputFecha = document.getElementById("fecha_fin");
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