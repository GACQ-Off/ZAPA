<?php 
session_start();
include "../conexion/conexion.php";?>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurar Base de Datos</title>
    <?php include "../assets/head_gerente.php"?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            max-width: 550px; 
            margin: 50px 0 50px 200px; 
            padding: 40px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px #0056b3;
            text-align: left; 
        }

        .auth-container h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 20px; 
            font-size: 1.8em;
            text-align: center;
        }
        .auth-container p {
            color: #555;
            margin-bottom: 25px;
            line-height: 1.6;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }
         .form-group input[type="file"]:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
            outline: none;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end; 
            gap: 15px; 
        }

        .form-actions input[type="submit"],
        .form-actions .btn-cancel {
            padding: 12px 25px; 
            border: none;
            border-radius: 5px; 
            cursor: pointer;
            font-size: 1.05em; 
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.1s ease;
            min-width: 120px; 
        }

        .form-actions input[type="submit"] {
            background-color: #3533cd; 
            color: white;
        }
        .form-actions input[type="submit"]:hover {
            background-color: #0056b3; 
        }

        .form-actions .btn-cancel {
            background-color: #6c757d; 
            color: white;
        }
        .form-actions .btn-cancel:hover {
            background-color: #5a6268;
        }
        .form-actions input[type="submit"]:active,
        .form-actions .btn-cancel:active {
            transform: translateY(1px);
        }
    </style>
</head>

<body>
    <?php  include "../assets/lista_gerente.php"; ?>
<?php
            if(!empty($_POST) && isset($_FILES['bd'])) {
                require ("../Importar_BD.php");
            }
         ?>
            <div class="auth-container">
                <form action="" method="post" enctype="multipart/form-data">
                    <h2>Restaurar Base de Datos</h2>
                    <p>
                        Selecciona el archivo de respaldo (.sql) que deseas importar para restaurar la base de datos.
                        Esta acción sobrescribirá los datos actuales.
                    </p>
                    <div class="form-group">
                        <label for="bd">Archivo de Respaldo (.sql):</label>
                        <input type="file" required id="bd" name="bd" accept=".sql">
                    </div>
                    <div class="form-actions">
                        <input type="submit" value="Importar">
                        <a href="../menu.php" class="btn-cancel">Regresar</a>
                    </div>
            </form>
        </div>
</body>

</html>
