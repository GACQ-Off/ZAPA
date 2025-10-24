<?php 
session_start();
include "../assets/lista_gerente.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Inventario</title>
    <?php include "../assets/head_gerente.php"; ?>
</head>
<style>
    .container {
        max-width: 600px;
        width: 80%;
        margin-top: 40px;
        margin-left: 180px;
        padding: 30px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 10px #0056b3;
        text-align: center;
    }
    h2 {
        color: #333;
        margin-bottom: 25px;
    }
    .form-group {
        margin-bottom: 20px;
    }
    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #555;
    }
    select {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-bottom: 20px;
    }
    button {
        background-color: #3533cd;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #0056b3;
    }
</style>
<body>

    <div class="container">
        <h2>Reporte de Inventario por Categoría</h2>
        <form action="reporte_inventario_funcion.php" method="GET" target="_blank">
            <div class="form-group">
            <label for="categoria_id">Selecciona una Categoría:</label>
            <select name="categoria_id" id="categoria_id" required>
                <option value="">-- Selecciona --</option>
                <?php
                include "../conexion/conexion.php";

                if ($conn->connect_error) {
                    die("<option value='' class='error'>Error de conexión: " . $conn->connect_error . "</option>");
                }

                $sql_categorias = "SELECT id_categoria, nombre_categoria FROM categoria WHERE estado_categoria = '1' ORDER BY nombre_categoria ASC";
                $result_categorias = $conn->query($sql_categorias);

                if ($result_categorias->num_rows > 0) {
                    while($row_cat = $result_categorias->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row_cat["id_categoria"]) . "'>" . htmlspecialchars($row_cat["nombre_categoria"]) . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No se encontraron categorías activas.</option>";
                }
                $conn->close();
                ?>
            </select>
            <button type="submit">Generar Reporte</button>
        </form>
    </div>
</body>
</html>