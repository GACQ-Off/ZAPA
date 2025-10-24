<?php
session_start();
$_tabs_atr = 3;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/list_c.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/_disabler.php';
require_once 'includes/show/show_c_.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
$categorias = $datos['registros'];
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
                    <?php if (!empty($categorias)) {
                        foreach ($categorias as $categoria) { 
                            $id = $categoria['id_categoria']; $tipo_registro = 'Categoría'; $nombre_registro = $categoria['nombre_categoria']; $iu = $_tabs_atr;    ?>
                <tr>
                    <td><p>
                        <?php echo htmlspecialchars($categoria['nombre_categoria']) ?>
                    </p></td>
                    <td>
                        <?php include 'includes/_submenu.php' ?>
                    </td>
                    </tr>
                    <?php }} else {echo '<tr><td colspan="2">No se encontraron '.$_c_dos.'.</td></tr>';} ?>
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