<?php 
session_start();
$_tabs_atr = 10;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/sett_bd.php';
require_once 'includes/_general.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
?>

<! DOCTYPE html>
<html lang="es">

<head>
    <?php include "includes/_head.php"; ?>
</head>

<body>
    <div id="fondo"></div>
    <main>
        <?php include "includes/_header.php";
        include "includes/sett_tabs.php"; ?>
        <section id="listados_">
            <div id="contenedor_de_botones">
                <section class="boton_bd">
                    <div class="primera_parte_bd">
                        <h3>
                            Exportar Base de Datos (.SQL)
                        </h3>
                        <p>
                            Con esta función puede guardar una copia de seguridad completa de toda la información registrada en la aplicación y cómo está organizada. Es recomendable que se hagan copias periodicamente.
                        </p>
                    </div>
                    <div class="bd_boton_negro">
                        <img src="img/svg/base_de_datos_blanco.svg" alt="icono" class="icono">
                        <img src="img/svg/subida_blanco.svg" alt="icono" class="icono">
                    </div>
                </section>
                <section class="boton_bd">
                    <div class="primera_parte_bd">
                        <h3>
                            Importar Base de Datos (.SQL)
                        </h3>
                        <p>
                            Esta opción permite cargar un archivo SQL para restaurar o actualizar toda la información, siempre y cuando esta contenga una estructura adecuada para la aplicación.
                        </p>
                    </div>
                    <div class="bd_boton_negro">
                        <img src="img/svg/base_de_datos_blanco.svg" alt="icono" class="icono">
                        <img src="img/svg/bajada_blanco.svg" alt="icono" class="icono">
                    </div>
                </section>
            </div>
        </section>
    </main>
    <?php include "includes/_bar.php"; 
    include 'includes/_messages.php'; ?>
</body>
</html>