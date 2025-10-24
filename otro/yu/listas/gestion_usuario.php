<?php
session_start();
include ('../editar/Encriptar.php');
if (!isset($_SESSION['id_usuario']) && !isset($_SESSION['nombre_usuario'])) {
    die("Error: Sesión no iniciada. No se puede verificar la contraseña.");
}

$alert = '';

if (!empty($_POST)) {
    if (empty($_POST['Clave'])) {
        $alert = 'Ingrese su contraseña';
    } else {
        require_once "../conexion/conexion.php";

        $id_usuario_sesion = $_SESSION['id_usuario'] ?? null;
        $nombre_usuario_sesion = $_SESSION['nombre_usuario'] ?? null;

        $pass = $_POST['Clave'];
        $clave_encriptada=encriptar($pass, $nombre_usuario_sesion);

        $sql = "SELECT clave FROM usuario WHERE " . ($id_usuario_sesion ? "id_usuario = ?" : "nombre_usuario = ?");
        $param_value = $id_usuario_sesion ?: $nombre_usuario_sesion;
        $param_type = $id_usuario_sesion ? "i" : "s";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, $param_type, $param_value);
        mysqli_stmt_execute($stmt);
        $resultado_consulta = mysqli_stmt_get_result($stmt);

        if ($data = mysqli_fetch_assoc($resultado_consulta)) {
            $hash_guardado = $data['clave'];
            if ($clave_encriptada === $hash_guardado) {
                header('location: ../editar/editar_gerente.php');
                exit();
            } else {
                $alert = 'Contraseña incorrecta.';
            }
        } else {
            $alert = 'Error al verificar el usuario.';
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingrese su contraseña de verificación</title>
    <?php include "../assets/head_gerente.php"?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .auth-container {
            max-width: 450px;
            margin: 50px 0 50px 200px; 
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 86, 179, 0.1);
            text-align: left; 
        }

        .auth-container h2 {
            color: #333;
            margin-top: 0;
            margin-bottom: 15px;
            text-align: center;
            font-size: 1.8em;
        }

        .auth-container p {
            color: #555;
            margin-bottom: 20px;
            line-height: 1.6;
            text-align: center;
        }

        .alert.alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #444;
        }

        .form-group input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1em;
        }
        .form-group input[type="password"]:focus {
            border-color: #0056b3;
            box-shadow: 0 0 0 0.2rem rgba(0, 86, 179, 0.25);
            outline: none;
        }

        .form-actions {
            margin-top: 25px;
            display: flex;
            justify-content: flex-end; 
            gap: 15px;
        }

        .form-actions button[type="submit"],
        .form-actions .btn-cancel {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .form-actions button[type="submit"] {
            background-color: #3533cd;
            color: white;
        }
        .form-actions button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-actions .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        .form-actions .btn-cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
<?php include "../assets/lista_gerente.php"?>
<div class="auth-container">
            <h2>Verificar Contraseña</h2>
            <p>Hola <?php echo htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario'); ?>, ingresa tu contraseña para continuar.</p>
            <?php if (!empty($alert)): ?>
                <div class="alert alert-danger"><?php echo $alert; ?></div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="Clave">Contraseña:</label>
                    <input type="password" id="Clave" name="Clave" placeholder="Tu contraseña" required="">
                </div>
                <div class="form-actions">
                    <button type="submit">Aceptar</button>
                    <a href="menu.php" class="btn-cancel">Cancelar</a>
                </div>
            </form>
</div>
</body>
<script src="../assets/js/menu.js"></script>
</html>