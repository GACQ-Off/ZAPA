<?php 
session_start();
$_tabs_atr = 2;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/list_a.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/_disabler.php';
require_once 'includes/show/show_a_.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
$autores = $datos['registros'];
?>

<! DOCTYPE html>
<html lang="es">

<head>
    <?php include "includes/_head.php"?>
</head>

<body>
    <div id="fondo"></div>
    <main>
        <?php include "includes/_header.php"; 
        include "includes/list_tabs.php";
        include "includes/_actions.php"; ?>
        <section id="listados_">
            <div id="contenedor_de_tabla">
                <table>
                    <thead>
                        <tr>
                            <th class="">
                                Cédula
                            </th>
                            <th class="">
                                Nombres
                            </th>
                            <th class="">
                                Apellidos
                            </th>
                            <th class="">
                                Teléfono
                            </th>
                            <th class="">
                                Correo
                            </th>
                            <th class="">
                                F.Nacimiento
                            </th>
                            <th class="">
                                F.Fallecimiento
                            </th>
                            <th class="columna_media">
                                ...
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($autores)) {
                            foreach ($autores as $autor) { 
                                $id = $autor['ci_autor']; $tipo_registro = 'Autor'; $nombre_registro = htmlspecialchars($autor['nombres_autor']) . ' ' . htmlspecialchars($autor['apellidos_autor']); $iu = $_tabs_atr;    ?>
                                <tr>
                                    <td><p><?php echo htmlspecialchars($autor['ci_autor']); ?></p></td>
                                    <td><p><?php echo htmlspecialchars($autor['nombres_autor']); ?> </p></td>
                                    <td><p><?php echo htmlspecialchars($autor['apellidos_autor']); ?></p></td>
                                    <td><p><?php echo htmlspecialchars($autor['telefono_autor']); ?></p></td>
                                    <td><p><?php echo htmlspecialchars($autor['mail_autor']); ?></p></td>
                                    <td><p><?php if (!empty($autor['f_nacimiento_autor']) && $autor['f_nacimiento_autor'] != '0000-00-00') {
                                    echo htmlspecialchars(date('d/m/Y', strtotime($autor['f_nacimiento_autor'])));} ?></p></td>
                                    <td><p><?php if (!empty($autor['f_fallecimiento_autor']) && $autor['f_fallecimiento_autor'] != '0000-00-00') {
                                    echo htmlspecialchars(date('d/m/Y', strtotime($autor['f_fallecimiento_autor'])));} ?></p></td>
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