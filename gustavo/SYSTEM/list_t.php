<?php
session_start();
$_tabs_atr = 4;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/list_t.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/_disabler.php';
require_once 'includes/show/show_t_.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
$tecnicas = $datos['registros'];
?>

<! DOCTYPE html>
<html lang="es">

<head>
    <?php include "includes/_head.php" ?>
</head>

<body>
    <div id="fondo"></div>
    <main>
        <?php include "includes/_header.php";
        include "includes/list_tabs.php"; 
        include 'includes/_actions.php'; ?>
        <section id="listados_">
            <div id="contenedor_de_tabla">
                <table>
                    <thead>
                        <tr>
                            <th>
                                Nombre
                            </th>
                            <th class="columna_media">
                                ...
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                <?php if ($tecnicas && count($tecnicas) > 0) {foreach ($tecnicas as $tecnica) { 
                    $id = $tecnica['id_tecnica']; $tipo_registro = 'Técnica'; $nombre_registro = $tecnica['nombre_tecnica']; $iu = $_tabs_atr;    ?>
                        <tr>
                            <td>
                                <p><?php echo htmlspecialchars($tecnica['nombre_tecnica']); ?></p>
                            </td>
                            <td>
                                <?php include 'includes/_submenu.php' ?>
                            </td>
                        </tr>
                        <?php }} else {echo '<tr><td colspan="7">No se encontraron '.$_c_dos.'.</td></tr>'; } ?>
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