<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die();}
session_start();
require_once '../../../_con.php';
require_once 'logic_g.php'; 
$ruta_incl = '../show/';
$ci_usuario_sesion = $_SESSION['ci_usuario']; 
$registro_id = $_POST['id_registro'] ?? die('Error: Falta identificativo');
$modo = $_POST['modo'] ?? 'ver';
$nombre_registro = $_POST['nombre_registro'] ?? 'Registro';
$tipo_registro = (int)($_POST['tipo_registro']) ?? die('Error: Falta tipo de registro');
$tabla = '';
$campo_id = '';
$archivo_incl = '';
$titulo_m = '';
switch ($tipo_registro) {
    case 0:
        $tabla = 'mantenimiento';
        $campo_id = 'id_mantenimiento';
        $archivo_incl = $ruta_incl . 'show_s.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Solicitud' : 'Detalles de Solicitud';
        break;
    case 1:
        $tabla = 'obra';
        $campo_id = 'cod_obra';
        $archivo_incl = $ruta_incl . 'show_o.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Obra de Arte' : 'Detalles de Obra de Arte';
        $sql_autores = "SELECT ci_autor, nombres_autor, apellidos_autor, status_autor FROM autor WHERE status_autor = 1";
        $autores = $bd->obtenerRegistro($sql_autores, '', []);
        $sql_autores_obra = "SELECT autor_ci FROM autor_obra WHERE obra_cod = ?";
        $resultado_autores_obra = $bd->obtenerRegistro($sql_autores_obra, 's', [$registro_id]);
        $autores_obra = array_column($resultado_autores_obra, 'ci_autor');
        $categorias = $bd->obtenerRegistro("SELECT id_categoria, nombre_categoria FROM categoria WHERE status_categoria = 1", '', []);
        $tecnicas = $bd->obtenerRegistro("SELECT id_tecnica, nombre_tecnica FROM tecnica WHERE status_tecnica = 1", '', []);
        $materiales = $bd->obtenerRegistro("SELECT id_material, nombre_material FROM material WHERE status_material = 1", '', []);
        $areas = $bd->obtenerRegistro("SELECT id_area, nombre_area FROM area WHERE status_area = 1", '', []);
        $colecciones = $bd->obtenerRegistro("SELECT cod_coleccion, titulo_coleccion FROM coleccion WHERE status_coleccion = 1", '', []);
        break;
    case 2:
        $tabla = 'autor';
        $campo_id = 'ci_autor';
        $archivo_incl = $ruta_incl . 'show_a.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Autor' : 'Detalles de Autor';
        break;
    case 3:
        $tabla = 'categoria';
        $campo_id = 'id_categoria';
        $archivo_incl = $ruta_incl . 'show_c.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Categoría' : 'Detalles de Categoría';
        break;
    case 4:
        $tabla = 'tecnica';
        $campo_id = 'id_tecnica';
        $archivo_incl = $ruta_incl . 'show_t.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Técnica' : 'Detalles de Técnica';
        break;
    case 5:
        $tabla = 'material';
        $campo_id = 'id_material';
        $archivo_incl = $ruta_incl . 'show_m.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Material' : 'Detalles de Material';
        break;
    case 6:
        $tabla = 'coleccion';
        $campo_id = 'cod_coleccion';
        $archivo_incl = $ruta_incl . 'show_co.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Colección' : 'Detalles de Colección';
        break;
    case 7:
        $tabla = 'area';
        $campo_id = 'id_area';
        $archivo_incl = $ruta_incl . 'show_ar.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Área' : 'Detalles de Área';
        break;
    case 8:
        $tabla = 'restaurador';
        $campo_id = 'ci_restaurador';
        $archivo_incl = $ruta_incl . 'show_r.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Restaurador' : 'Detalles de Restaurador';
        break;
    case 11: 
        $tabla = 'usuario';
        $campo_id = 'ci_usuario';
        $archivo_incl = $ruta_incl . 'show_u.php';
        $titulo_m = ($modo === 'editar') ? 'Modificar Usuario' : 'Detalles de Usuario';
        break;
    default:
        die('<h1 id="h_modificacion">Error</h1><div id="modal_area_m"><p>Tipo de registro no válido.</p></div>');
}
$sql_select = "SELECT * FROM {$tabla} WHERE {$campo_id} = ?";
$resultado = $bd->obtenerRegistro($sql_select, 's', [$registro_id]); 
if (empty($resultado)) {
    die('<section id="cabezal_modal_m"><h1 id="h_modificacion">Error</h1></section><div id="modal_area_m"><p>Registro no encontrado en la base de datos.</p></div>');}
$datos = $resultado[0]; 
?> 
<section id="cabezal_modal_m">
    <h1 id="h_modificacion"><?php echo htmlspecialchars($titulo_m) ?></h1>
    <div id="boton_cerrar_modal"><img src="img/svg/equis_negro.svg" alt="icono de cerrar" id="boton_cerrar_modal_mo"></div>
</section>
<div id="modal_area_m">
    <form method="post" enctype="multipart/form-data" id="_modificar"> <?php
    if ($modo === 'editar') {?>
        <p id="Obligatorios"><strong> (*): Campo Obligatorios</strong></p><?php }
    if (file_exists($archivo_incl)) {
        include $archivo_incl;} 
        else {
        die('<p>Error interno: Plantilla de vista ' . htmlspecialchars($archivo_incl) . ' no encontrada.</p>');} ?>
        <input type="hidden" name="accion" value="_modificar">
        <section id="bottom_modal_m">
            <?php if ($modo === 'ver' || ($modo === 'ver' && $_SESSION['rol_id'] != 2 && $_tabs_tr != 0)) { ?>
                <button id="btn_cambiar_a_edicion" type="button">
                    <img src="img/svg/palma_mano_white.svg" alt="Icono de Editar" class="icono">
                    <p>Modificar registro</p>
                </button>
            <?php } elseif ($modo === 'editar'  || ($modo === 'editar' && $_SESSION['rol_id'] != 2 && $_tabs_tr != 0)) { ?>
                <button type="submit" id="boton_guardar_m">
                    <img src="img/svg/pulgar_arriba_white.svg" alt="Icono de Guardar" class="icono"><p>Guardar Cambios</p>
                </button>
                <button type="button" id="btn_cambiar_a_visualizacion">
                    <img src="img/svg/no_tocar_blanco.svg" alt="Icono de Visualizar" class="icono"><p>Deshabilitar Edición</p>
                </button>
            <?php } ?>
        </section>
    </form>
</div>