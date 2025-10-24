<?php
session_start();
$_tabs_atr = 1;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/list_o.php';
require_once 'includes/_general.php';
require_once 'includes/logic/logic_e.php';
require_once 'includes/forms/form_base.php';
require_once 'includes/show/show_base.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';
//
$obras = $datos['registros'];
?>

<! DOCTYPE html>
<html lang="es">

<head>
    <?php include "includes/_head.php"; ?>
    <script type="module" src="JS/_multipler.js"></script>
    <script type="module" src="JS/_disabler.js"></script>
</head>

<body>
    <div id="fondo"></div>
    <main>
        <?php include "includes/_header.php"; 
        include "includes/list_tabs.php"; 
        include "includes/_actions.php"; ?>
        </section>
        <section id="listados_">
            <div id="contenedor_de_tabla">
                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Título</th>
                            <th>Autor(es)</th>
                            <th>Estado de Convervación</th>
                            <th class="columna_media">...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($obras)) {
                            foreach ($obras as $obra) { 
                        $id = $obra['cod_obra']; $tipo_registro = 'Obra'; $nombre_registro = $obra['cod_obra'].' '.$obra['titulo_obra']; $iu = $_tabs_atr;   ?>
                <tr>
                    <td><p>
                        <?php echo htmlspecialchars($obra['cod_obra']) ?>
                    </p></td>
                    <td><p>
                        <?php echo htmlspecialchars($obra['titulo_obra']) ?>
                    </p></td>
                    <td><p>
                        <?php echo htmlspecialchars($obra['nombres_autores']) ?>
                    </p></td>
                    <td><p>
                        <?php echo htmlspecialchars($obra['estado_obra']) ?>
                    </p></td>
                    <td><p>
                        <?php include 'includes/_submenu.php' ?>
                    </p></td>
                </tr>
                <?php }} else {
                    echo '<tr><td colspan="6">No se encontraron Obras de Arte</td></tr>';} ?>
                    </tbody>
                </table>
            </div>
            <?php include 'includes/_pager.php'; ?>
        </section>
        <div id="modal_cuatro">
    <div class="modal_contenido">
        <section id="cabezal_modal_f">
            <p></p>
            <div id="boton_cerrar_modal">
                <img src="img/svg/equis_negro.svg" alt="icono de cerrar" id="cerrar">
            </div>
        </section>
        <h2 id="titulo_modal_eliminar"></h2>
        <p id="mensaje_principal">¿Está seguro que desea deshabilitar el registro con el siguiente identificador?: <strong><text id="display_eliminar"></text></strong></p>
        <p id="mensaje_secundario">Esta acción <strong>deshabilitará</strong> el registro y no podrá ser utilizado en la creación de otros registros. Esta acción puede ser revertida con la intervención de un técnico en la base de datos.  </p>
        <div class="modal_botones">
            <button id="btn_confirmar_eliminar" class="confirmar_eliminar">Sí, Deshabilitar</button>
            <button class="cancelar_eliminar">Cancelar</button>
        </div>
        <form id="eliminar_form" method="POST" action="">
            <input type="hidden" name="accion" value="_deshabilitar"> 
            <input type="hidden" name="id_registro" id="id_registro_eliminar">
            <input type="hidden" name="entidad" id="entidad_eliminar">
        </form>
    </div>
</div>
    </main>
    <?php include 'includes/_bar.php';
    include 'includes/_messages.php'; ?>

</body>
</html>