<?php
session_start();
$_tabs_atr = 0;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/list_s.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/_disabler.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
$solicitudes = $datos['registros'];
?>

<! DOCTYPE html>
    <html lang="es">

    <head>
        <?php include "includes/_head.php"; ?>
    <script type="module" src="JS/_multipler.js"></script>
    </head>

    <body>
        <div id="fondo"></div>
        <main>

            <?php include "includes/_header.php"; ?>
            <section id="pestanas_">
                <ul>
                    <li>
                        <a class="pestanas_accion" id="acento_pestanas">
                            Solicitudes
                        </a>
                    </li>
                </ul>
            </section>
            <?php include "includes/_actions.php" ?>
            <section id="listados_">
                <div id="contenedor_de_tabla">
                    <table>
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Estado</th>
                                <th>F.Escritura</th>
                                <th>F.Modificación</th>
                                <th>Escrito por</th>
                                <th class="columna_media">...</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($solicitudes)) {
                            foreach ($solicitudes as $solicitud) {
                            $id = $solicitud['id_mantenimiento'];
                            $tipo_registro = 'Solicitud';
                            $nombre_registro = $solicitud['resumen_mantenimiento'];
                            $fecha_e = date('d/m/Y h:i a', strtotime($solicitud['f_escritura_mantenimiento']));
                            $fecha_m = date('d/m/Y h:i a', strtotime($solicitud['f_actualizacion_mantenimiento']));
                            $iu = $_tabs_atr; ?>
                            <tr>
                                <td><p>
                                    <?php echo htmlspecialchars($solicitud['resumen_mantenimiento']) ?>
                                </p></td>
                                <td><p>
                                    <?php echo htmlspecialchars($solicitud['estado_mantenimiento']) ?>
                                </td></p>
                                <td><p>
                                    <?php echo htmlspecialchars($fecha_e) ?>
                                </td></p>
                                <td><p>
                                    <?php echo htmlspecialchars($fecha_m) ?>
                                </td></p>
                                <td><p>
                                </td></p>
                                <td>
                                    <?php include 'includes/_submenu.php' ?>
                                </td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr><td colspan="5">No se encontraron solicitudes</td></tr>';
                    } ?>
                    </tbody>
                </table>
                </div>
                <?php include 'includes/_pager.php'; ?>
            </section>
        </main>
        <?php include 'includes/_bar.php';
        include 'includes/_messages.php'; ?>
    </body>

    </html>