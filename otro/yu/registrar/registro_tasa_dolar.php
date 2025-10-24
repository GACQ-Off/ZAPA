<?php
session_start();
include "../conexion/conexion.php";
date_default_timezone_set('America/Caracas');

if (!empty($_POST)) {
    $alert = '';
    if (empty($_POST['tasa_dolar'])) {
        $alert = '<p class="msg_error">Todos los campos son obligatorios.</p>';
    } else {
        $tasa_dolar = floatval($_POST['tasa_dolar']);
        $id_usuario = $_SESSION['id_usuario'];
        $fecha_actual = date('Y-m-d H:i:s');

        $query_insert = mysqli_query($conn, "INSERT INTO tasa_dolar(tasa_dolar, fecha_dolar, id_usuario)
                                             VALUES('$tasa_dolar', '$fecha_actual', '$id_usuario')");
        if ($query_insert) {
            header("Location: registro_tasa_dolar.php?exito=1");
            exit;
        } else {
            $alert = '<p class="msg_error">Error al registrar la tasa de dólar.</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php include "../assets/head_gerente.php"?>
    <link rel="stylesheet" href="../assets/css/registrar_cargo.css">
    <title>Tasa del dolar</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
        }

        .modal-content h2 {
            color: #28a745;
            margin-bottom: 10px;
        }

        .modal-content p {
            font-size: 1.1em;
            color: #555;
        }

        .modal-content .close-btn,
        .modal-content .btn-aceptar {
            cursor: pointer;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            margin-top: 20px;
        }

        .modal-content .close-btn {
            color: #aaa;
            background-color: transparent;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .modal-content .btn-aceptar {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <?php include "../assets/lista_gerente.php"?>
    <div class="form-container">
        <form action="" method="post">
            <h2 class="title">Ingreso de Tasa del Dólar</h2>
            <?php echo isset($alert) ? $alert : ''; ?>
            <label for="tasa_dolar">Precio:</label>
            <input type="text" required="" name="tasa_dolar" id="tasa_dolar" placeholder="Ingrese en Bolívares la tasa" autocomplete="off">
            <button class="input" type="submit">Crear</button>
        </form>
    </div>

    <div id="modalExito" class="modal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>¡Éxito!</h2>
            <p>Tasa actualizada con éxito.</p>
            <button class="btn-aceptar">Aceptar</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const urlParams = new URLSearchParams(window.location.search);
            const modal = document.getElementById('modalExito');
            const closeBtn = document.querySelector('.close-btn');
            const aceptarBtn = document.querySelector('.btn-aceptar');

            function closeModal() {
                modal.style.display = 'none';
            }

            if (urlParams.has('exito')) {
                modal.style.display = 'block';
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            closeBtn.addEventListener('click', closeModal);
            aceptarBtn.addEventListener('click', closeModal);

            window.onclick = function(event) {
                if (event.target == modal) {
                    closeModal();
                }
            };
        });
    </script>
<script src="../assets/js/alerta_dolar.js"></script>
</body>
</html>