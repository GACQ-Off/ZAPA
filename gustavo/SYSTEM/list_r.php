<?php 
session_start();
$_tabs_atr = 8;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/list_r.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/_disabler.php';
require_once 'includes/show/show_r_.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
$restauradores = $datos['registros'];
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
                            <th class="columna_corta">Cédula</th>
                            <th class="columna_larga">Nombres</th>
                            <th class="columna_larga">Apellidos</th>
                            <th class="columna_corta">Teléfono</th>
                            <th class="columna_media">Correo</th>
                            <th class="columna_larga">Domicilio</th>
                            <th class="columna_media">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($restauradores)) {
                            foreach ($restauradores as $restaurador) {
                                $id = $restaurador['ci_restaurador']; $tipo_registro = 'Restaurador'; $nombre_registro = $restaurador['nombres_restaurador'].' '.$restaurador['apellidos_restaurador'] ; $iu = $_tabs_atr;   ?>  
                        <tr>
                            <td><p><?php echo htmlspecialchars($restaurador['ci_restaurador']) ?></p></td>
                            <td><p><?php echo htmlspecialchars($restaurador['nombres_restaurador']) ?></p></td>
                            <td><p><?php echo htmlspecialchars($restaurador['apellidos_restaurador']) ?></p></td>
                            <td><p><?php echo htmlspecialchars($restaurador['telefono_restaurador']) ?></p></td>
                            <td><p><?php echo htmlspecialchars($restaurador['mail_restaurador']) ?></p></td>
                            <td><p><?php echo htmlspecialchars($restaurador['domicilio_restaurador']) ?></p></td>
                            <td><p><?php include 'includes/_submenu.php' ?></td>
                        </tr>
                        <?php }} else {echo '<tr><td colspan="7">No se encontraron  '.$_c_dos.'</td></tr>'; } ?>
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