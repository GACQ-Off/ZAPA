<?php 
session_start();
include '../assets/lista_gerente.php';  ?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respaldar Base de Datos</title>
    <?php include "../assets/head_gerente.php"; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            max-width: 500px;
            margin: 50px 0 50px 200px; 
            padding: 40px; 
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px #0056b3;
            text-align: center; 
        }

        .auth-container h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 30px; 
            font-size: 1.8em;
        }

        .form-actions {
            margin-top: 30px;
            display: flex;
            justify-content: center; 
            gap: 20px; 
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
        .form-actions input[type="submit"]:active {
            transform: translateY(1px);
        }

        .form-actions .btn-cancel {
            background-color: #6c757d; 
            color: white;
        }
        .form-actions .btn-cancel:hover {
            background-color: #5a6268;
        }
        .form-actions .btn-cancel:active {
            transform: translateY(1px);
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <form action="../Exportar_BD.php" method="post">
            <h2>Extraer Base de Datos</h2>
            <p style="color: #555; margin-bottom: 25px; line-height: 1.6;">
                Haz clic en "Exportar" para generar un archivo de respaldo de la base de datos.
                Guarda este archivo en un lugar seguro.
            </p>
            <div class="form-actions">
                <input type="submit" value="Exportar">
                <a href="../menu.php" class="btn-cancel">Regresar</a>
            </div>
        </form>
    </div>
</body>
</html>
