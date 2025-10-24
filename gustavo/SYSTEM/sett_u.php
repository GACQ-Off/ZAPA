<?php
session_start();
$_tabs_atr = 11;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/sett_u.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/_disabler.php';
require_once 'includes/show/show_u_.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
$usuarios = $datos['registros'];
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
        include "includes/sett_tabs.php"; 
        include 'includes/_actions.php'; ?>
        <section id="listados_">
            <div id="contenedor_de_tabla">
                <table>
                    <thead>
                        <tr>
                            <th class="columna_corta">Cédula</th>
                            <th class="columna_larga">Nombres</th>
                            <th class="columna_larga">Apellidos</th>
                            <th class="columna_media">Correo Electrónico</th>
                            <th class="columna_corta">Teléfono</th>
                            <th class="columna_corta">Rol</th>
                            <th class="columna_media">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($usuarios && count($usuarios) > 0) {
                            foreach ($usuarios as $usuario) {
                                $id = $usuario['ci_usuario']; $tipo_registro = 'Usuario'; $nombre_registro = $usuario['nombres_usuario'].' '.$usuario['apellidos_usuario'];
                                $fotoPerfilUsuario = !empty($usuario['img_usuario']) ? htmlspecialchars(string: "uploads/profiles/{$usuario['img_usuario']}") : 'img/default_usuario_negro.png';; $iu = $_tabs_atr;   ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($usuario['ci_usuario']); ?></td>
                                    <td>
                                        <img src="<?php echo $fotoPerfilUsuario; ?>" alt="foto de perfil" class="icono_de_perfil">
                                        <?php echo htmlspecialchars($usuario['nombres_usuario']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($usuario['apellidos_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['mail_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['telefono_usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($usuario['descripcion_rol']); ?></td>
                                    <td><?php include 'includes/_submenu.php' ?></td>
                                </tr>
                        <?php }} else {echo '<tr><td colspan="7">No se encontraron usuarios.</td></tr>'; } ?>
                    </tbody>
                </table>
            </div>
            <?php include 'includes/_pager.php' ?>
        </section>
    </main>
    <?php include 'includes/_bar.php';
    include 'includes/_messages.php'; ?>
</body>
</html>