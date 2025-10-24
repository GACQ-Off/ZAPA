<?php
session_start();
$_tabs_atr = 6;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/list_co.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/show/show_co_.php';
require_once 'includes/_disabler.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
$colecciones = $datos['registros'];
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
        include "includes/list_tabs.php";
        include "includes/_actions.php" ?>
        <section id="listados_">
            <div id="contenedor_de_tabla">
                <table>
                    <thead>
                        <tr>
                            <th>
                                Código
                            </th>
                            <th>
                                Título
                            </th>
                            <th>
                                F.Creación
                            </th>
                            <th>
                                Naturaleza
                            </th>
                            <th>
                                Estado
                            </th>
                            <th class="columna_media">
                                ...
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($colecciones)) {
                            foreach ($colecciones as $coleccion) { 
                                $id = $coleccion['cod_coleccion']; $tipo_registro = 'Colección'; $nombre_registro = $coleccion['cod_coleccion'].' '.$coleccion['titulo_coleccion']; $iu = $_tabs_atr;  ?>  
                        <tr>
                            <td>
                                <p>
                                    <?php echo htmlspecialchars($coleccion['cod_coleccion']) ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?php echo htmlspecialchars($coleccion['titulo_coleccion']) ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?php echo htmlspecialchars(date( 'd/m/Y', strtotime($coleccion['f_creacion_coleccion'])) ?? 'Indeterminada'); ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?php echo htmlspecialchars($coleccion['naturaleza_coleccion']); ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                        <?php echo htmlspecialchars($coleccion['estado_coleccion']); ?>
                                </p>
                            </td>
                            <td>
                                <p>
                                    <?php include 'includes/_submenu.php' ?>
                                </p>
                            </td>
                        </tr>
                    <?php }} else {echo '<tr><td colspan="6">No se encontraron '.$_c_dos.'.</td></tr>';} ?>
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