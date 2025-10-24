<?php
$autores = $bd->obtenerRegistro("SELECT ci_autor, nombres_autor, apellidos_autor, status_autor FROM autor WHERE status_autor = 1", '', []);
$categorias = $bd->obtenerRegistro("SELECT id_categoria, nombre_categoria FROM categoria WHERE status_categoria = 1", '', []);
$tecnicas = $bd->obtenerRegistro("SELECT id_tecnica, nombre_tecnica FROM tecnica WHERE status_tecnica = 1", '', []);
$materiales = $bd->obtenerRegistro("SELECT id_material, nombre_material FROM material WHERE status_material = 1", '', []);
$areas = $bd->obtenerRegistro("SELECT id_area, nombre_area FROM area WHERE status_area = 1", '', []);
$colecciones = $bd->obtenerRegistro("SELECT cod_coleccion, titulo_coleccion FROM coleccion WHERE status_coleccion = 1", '', []); 
$archivo_guardado = NULL; 
require_once 'includes/logic/logic_e.php'; 
if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
    if (empty($_POST['id_formulario']) || empty($_POST['ti_formulario']) || empty($_POST['es_con_formulario']) || empty($_POST['cat_formulario']) || empty($_POST['tec_formulario']) || empty($_POST['mat_formulario']) || empty($_POST['col_formulario']) || empty($_POST['are_formulario']) || empty($_POST['pro_formulario']) || empty($_POST['aut_formulario'])) {
        header('Location: list_o.php?e=1');
        exit();}  
    $cod_obr = trim(filter_var($_POST['id_formulario'], FILTER_SANITIZE_STRING));
    $tit_obr = trim(ucwords(filter_var($_POST['ti_formulario'], FILTER_SANITIZE_STRING)));
    $f_e_obr = !empty($_POST['f_el_formulario']) ? $_POST['f_el_formulario'] : NULL;
    $est_obr = trim(ucwords(filter_var($_POST['es_con_formulario'], FILTER_SANITIZE_STRING)));
    $val_obr = !empty($_POST['va_formulario']) ? filter_var(trim($_POST['va_formulario']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : NULL;
    $f_v_obr = !empty($_POST['f_va_formulario']) ? $_POST['f_va_formulario'] : NULL;
    $f_i_obr = !empty($_POST['f_in_formulario']) ? $_POST['f_in_formulario'] : NULL;
    $cat_obr = intval($_POST['cat_formulario']); 
    $tec_obr = intval($_POST['tec_formulario']); 
    $mat_obr = intval($_POST['mat_formulario']); 
    $col_obr = trim(filter_var($_POST['col_formulario'], FILTER_SANITIZE_STRING)); 
    $are_obr = intval($_POST['are_formulario']); 
    $pro_obr = trim(filter_var($_POST['pro_formulario'], FILTER_SANITIZE_STRING));
    $aut_obr = trim($_POST['aut_formulario']);
    $aut_array = explode(',', $aut_obr);
    $img_obr = NULL;
    $alt_obr = !empty($_POST['alt_formulario']) ? filter_var(trim($_POST['alt_formulario'], FILTER_SANITIZE_NUMBER_INT)) : NULL;
    $anc_obr = !empty($_POST['anc_formulario']) ? filter_var(trim($_POST['anc_formulario'], FILTER_SANITIZE_NUMBER_INT)) : NULL;
    $prof_obr = !empty($_POST['profun_formulario']) ? filter_var(trim($_POST['profun_formulario'], FILTER_SANITIZE_NUMBER_INT)) : NULL;
    $des_obr = !empty($_POST['resu_formulario']) ? trim(filter_var($_POST['resu_formulario'], FILTER_SANITIZE_STRING)) : NULL;

    if (strlen($cod_obr) > 25 || strlen($cod_obr) < 1) {
        header('Location: list_o.php?e=10');
        exit();} 
    elseif (strlen($tit_obr) > 65 || strlen($tit_obr) < 2) {
        header('Location: list_o.php?e=7');
        exit();} 
        elseif (strlen($est_obr) > 12 || strlen($est_obr) < 4) {
        header('Location: list_o.php?e=12');
        exit();} 
    elseif (strlen($pro_obr) > 13 || strlen($pro_obr) < 8) {
        header('Location: list_o.php?e=11');
        exit();} 
        elseif (!empty($des_obr) && (strlen($des_obr) > 255 || strlen($des_obr) < 20)) {
        header('Location: list_o.php?e=11');
        exit();} 
        elseif ((!empty($alt_obr) && (!is_numeric($alt_obr) || $alt_obr < 0)) ||
        (!empty($anc_obr) && (!is_numeric($anc_obr) || $anc_obr < 0)) ||
        (!empty($prof_obr) && (!is_numeric($prof_obr) || $prof_obr < 0)) ||
        (!empty($val_obr) && (!is_numeric($val_obr) || $val_obr < 0))) {
        header('Location: list_o.php?e=10'); 
        exit();} 
    if ($bd->existeEnTabla('obra', 'cod_obra', $cod_obr)) {
        header('Location: list_o.php?e=3');
        exit();} 
        if (!$bd->existeEnTabla('categoria', 'id_categoria', $cat_obr) ||
        !$bd->existeEnTabla('tecnica', 'id_tecnica', $tec_obr) ||
        !$bd->existeEnTabla('material', 'id_material', $mat_obr) ||
        !$bd->existeEnTabla('coleccion', 'cod_coleccion', $col_obr) ||
        !$bd->existeEnTabla('area', 'id_area', $are_obr)) {
        header('Location: list_o.php?e=13'); 
        exit();}
        if (!empty($_FILES['foto_formulario']) && $_FILES['foto_formulario']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                $manejador = new logica_files($ruta);
                $archivo_guardado = $manejador->procesarYGuardarImagen($_FILES['foto_formulario']);
                if (!empty($archivo_guardado)) {
                    $img_obr = $archivo_guardado;}
        } catch (Exception $e) {
            header("Location: list_o.php?e=99"); 
            exit();}}
    $bd->iniciarTransaccion();
    $estado_registro = true;
    $sql_obra = 'INSERT INTO obra (cod_obra, titulo_obra, alto_obra, ancho_obra, profundidad_obra, procedencia_obra, f_ingreso_obra, valor_obra, f_tasacion_obra, estado_obra, descripcion_obra, img_obra, status_obra, coleccion_cod, categoria_id, material_id, tecnica_id, area_id, usuario_ci) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $tipos_obra = 'ssiiisdsisssisiiiis'; 
    $parametros_obra = [
        $cod_obr, $tit_obr, $alt_obr, $anc_obr, $prof_obr, $pro_obr, $f_i_obr, $val_obr, $f_v_obr, 
        $est_obr, $des_obr, $img_obr, $status_comun, $col_obr, $cat_obr, $mat_obr, $tec_obr, $are_obr, $ci_usuario_sesion
    ];
    if (!$bd->accionRegistro($sql_obra, $tipos_obra, $parametros_obra)['success']) {
        $estado_registro = false;}
        $sql_relacion = 'INSERT INTO autor_obra (obra_cod, autor_ci, f_elaboracion_obra) VALUES (?, ?, ?)';
        $tipos_relacion = 'sss';
    if ($estado_registro) {
        foreach ($aut_array as $aut_ci) {
            $aut_ci = trim($aut_ci);
            if (!empty($aut_ci) && $bd->existeEnTabla('autor', 'ci_autor', $aut_ci)) {
                $parametros_relacion = [$cod_obr, $aut_ci, $f_e_obr];
                if (!$bd->accionRegistro($sql_relacion, $tipos_relacion, $parametros_relacion)['success']) {
                    $estado_registro = false;
                    break;}} 
            elseif (!empty($aut_ci)) {
                $estado_registro = false;
                break;}}}
            if ($estado_registro) {
                $bd->confirmarTransaccion();
                header('Location: list_o.php?s=1'); 
                exit();} 
            else {
                $bd->revertirTransaccion();
                header('Location: list_o.php?e=2'); 
                exit();}}
?>

<section id="cuerpo_formulario">
    <fieldset id="f_contenedor_uno" class="formulario_parte">
        <legend class="label_artificial">
            Fase 1 de 4 - Información General
        </legend><table class="formulario_tabla">
    <tbody>
        <tr>
            <td colspan="2">
                <div>
                    <label for="id_formulario" class="labels">
                        Código de Identificación (*):
                    </label>
                    <input type="text" autocomplete="off" id="id_formulario" name="id_formulario"
                        placeholder="Código de Identificación" maxlength="25" minlength="1">
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <label for="ti_formulario" class="labels">
                        Título (*):
                    </label>
                    <input type="text" autocomplete="off" name="ti_formulario" id="ti_formulario" placeholder="Título"
                        maxlength="65" minlength="2">
                </div>
            </td>
            <td>
                <div>
                    <label for="f_in_formulario">
                        Fecha de Ingreso:
                    </label>
                    <input type="date" name="f_in_formulario" id="f_in_formulario">
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <label for="es_con_formulario" class="labels">
                        Estado de Conservación (*):
                    </label>
                    <select name="es_con_formulario" id="es_con_formulario" required>
                        <option value="">Seleccione...</option>
                        <option value="Bueno">Bueno</option>
                        <option value="Regular">Regular</option>
                        <option value="Malo">Malo</option>
                    </select>
                </div>
            </td>
            <td>
                <div>
                    <label for="f_el_formulario" class="labels">
                        Fecha de Elaboración:
                    </label>
                    <input type="date" name="f_el_formulario" id="f_el_formulario">
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <label for="va_formulario" class="labels">
                        Valor Estimado ($):
                    </label>
                    <input type="number" step="0.01" autocomplete="off" name="va_formulario" id="va_formulario"
                        placeholder="Valor Estimado (en Doláres)" max="99999999999" min="0">
                </div>
            </td>
            <td>
                <div>
                    <label for="f_va_formulario" class="labels">
                        Fecha de Tasación:
                    </label>
                    <input type="date" name="f_va_formulario" id="f_va_formulario">
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <label for="cat_formulario" class="labels">
                        Categoría (*):
                    </label>
                    <select name="cat_formulario" id="cat_formulario" required <?php echo empty($categorias) ? 'disabled' : ''; ?>>
                        <option value="">
                            <?php echo empty($categorias) ? 'No hay Categorías activas' : 'Seleccione Categoría'; ?>
                        </option>
                        <?php if (!empty($categorias)) {
                            foreach ($categorias as $categoria) {
                                echo '<option value="' . htmlspecialchars($categoria["id_categoria"]) . '">' . htmlspecialchars($categoria["nombre_categoria"]) . '</option>';
                            }
                        } ?>
                    </select>
                </div>
            </td>
            <td>
                <div>
                    <label for="tec_formulario" class="labels">
                        Técnica (*):
                    </label>
                    <select name="tec_formulario" id="tec_formulario" required <?php echo empty($tecnicas) ? 'disabled' : ''; ?>>
                        <option value="">
                            <?php echo empty($tecnicas) ? 'No hay Técnicas activas' : 'Seleccione Técnica'; ?>
                        </option>
                        <?php if (!empty($tecnicas)) {
                            foreach ($tecnicas as $tecnica) {
                                echo '<option value="' . htmlspecialchars($tecnica["id_tecnica"]) . '">' . htmlspecialchars($tecnica["nombre_tecnica"]) . '</option>';
                            }
                        } ?>
                    </select>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <label for="mat_formulario" class="labels">
                        Material (*):
                    </label>
                    <select name="mat_formulario" id="mat_formulario" required <?php echo empty($materiales) ? 'disabled' : ''; ?>>
                        <option value="">
                            <?php echo empty($materiales) ? 'No hay Materiales activos' : 'Seleccione Material'; ?>
                        </option>
                        <?php if (!empty($materiales)) {
                            foreach ($materiales as $material) {
                                echo '<option value="' . htmlspecialchars($material["id_material"]) . '">' . htmlspecialchars($material["nombre_material"]) . '</option>';
                            }
                        } ?>
                    </select>
                </div>
            </td>
            <td>
                <div>
                    <label for="col_formulario" class="labels">
                        Coleccion (*):
                    </label>
                    <select name="col_formulario" id="col_formulario" required <?php echo empty($colecciones) ? 'disabled' : ''; ?>>
                        <option value="">
                            <?php echo empty($colecciones) ? 'No hay Colecciones activas' : 'Seleccione Colección'; ?>
                        </option>
                        <?php if (!empty($colecciones)) {
                            foreach ($colecciones as $coleccion) {
                                echo '<option value="' . htmlspecialchars($coleccion["cod_coleccion"]) . '">' . htmlspecialchars($coleccion["titulo_coleccion"]) . '</option>';
                            }
                        } ?>
                    </select>
                </div>
            </td>
        </tr>

        <tr>
            <td>
                <div>
                    <label for="are_formulario" class="labels">
                        Área de Almacenamiento/Exposición (*):
                    </label>
                    <select name="are_formulario" id="are_formulario" required <?php echo empty($areas) ? 'disabled' : ''; ?>>
                        <option value="">
                            <?php echo empty($areas) ? 'No hay Áreas activas' : 'Seleccione Área'; ?>
                        </option>
                        <?php if (!empty($areas)) {
                            foreach ($areas as $area) {
                                echo '<option value="' . htmlspecialchars($area["id_area"]) . '">' . htmlspecialchars($area["nombre_area"]) . '</option>';
                            }
                        } ?>
                    </select>
                </div>
            </td>
            <td>
                <div>
                    <label for="pro_formulario" class="labels">
                        Procedencia (*):
                    </label>
                    <select name="pro_formulario" id="pro_formulario" required>
                        <option value="">Seleccione Procedencia</option>
                        <option value="Donación">Donación</option>
                        <option value="Comodato">Comodato</option>
                        <option value="Premiación">Premiación</option>
                        <option value="Adquisición">Adquisición</option>
                    </select>
                </div>
            </td>
        </tr>
    </tbody>
</table>
    </fieldset>

    <fieldset id="f_contenedor_dos" class="formulario_parte">
        <legend>
            <label for="autores_formulario" class="label_artificial">
                Fase 2 de 4 - Autoría (*)
            </label>
        </legend>
        <div id="contenedor_de_tabla">
            <table>
                <thead>
                    <tr>
                        <th class="columna_corta">
                            ...
                        </th>
                        <th class="columna_larga">
                            Cédula
                        </th>
                        <th>
                            Nombres
                        </th>
                        <th>
                            Apellidos
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($autores)) {
                        foreach ($autores as $autor) {
                            $id_form = $autor['ci_autor']; ?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="autor_seleccionado[]" class="autor_selector"
                                        value='<?php echo htmlspecialchars($id_form) ?>'>
                                </td>
                                <td><?php echo htmlspecialchars($autor['ci_autor']); ?></td>
                                <td><?php echo htmlspecialchars($autor['nombres_autor']); ?> </td>
                                <td><?php echo htmlspecialchars($autor['apellidos_autor']); ?></td>
                            </tr>
                        <?php }
                    } else {
                        echo '<tr><td colspan="4">No se encontraron autores activos para asociar.</td></tr>';
                    } ?>

                </tbody>
            </table>
        </div>
        <input type="hidden" autocomplete="off" name="aut_formulario" id="aut_formulario" value="">
    </fieldset>

    <fieldset id="f_contenedor_tres" class="formulario_parte">
        <legend class="label_artificial">
            Fase 3 de 4 - Material Fotográfico de Referencia:
        </legend>
        <div class="imagen_contenedor_uno">
            <img id="imagen_previa" src="" alt="Previsualización de la imagen">
        </div>
        <div id="foto_input_contenedor">
            <label for="foto_formulario" class="label_de_imagen_o_archivo">
                <img src="img/svg/foto_blanco.svg" class="icono">
                Seleccionar Foto
            </label>
            <input type="file" name="foto_formulario" id="foto_formulario" accept="image/*"
                class="label_de_imagen_o_archivo">
        </div>
    </fieldset>
    <fieldset id="f_contenedor_cuatro" class="formulario_parte">
        <legend class="label_artificial">
            Fase 4 de 4 - Informaciones Adicionales
        </legend>
        <div class="partes_uno">
            <h5 class="label_artificial">
                Dimensiones
            </h5>
            <div>
                <label for="alt_formulario">
                    Alto (cm.):
                </label>
                <input type="number" step="any" autocomplete="off" id="alt_formulario" name="alt_formulario"
                    placeholder="Altura (en Centimetros)" max="99999999999" min="0">
            </div>
            <div>
                <label for="anc_formulario">
                    Ancho (cm.):
                </label>
                <input type="number" step="any" autocomplete="off" name="anc_formulario" id="anc_formulario"
                    placeholder="Anchura (en Centimetros)" max="99999999999" min="0">
            </div>
            <div>
                <label for="profun_formulario">
                    Profundidad (cm.):
                </label>
                <input type="number" step="any" autocomplete="off" name="profun_formulario" id="profun_formulario"
                    placeholder="Profundidad (en Centimetros)" max="99999999999" min="0">
            </div>
        </div>
        <div class="partes_uno">
            <label for="resu_formulario">
                Texto Descripción:
            </label>
            <textarea name="resu_formulario" autocomplete="off" id="resu_formulario" placeholder="Esta obra es..."
                maxlength="255" minlength="20"></textarea>
        </div>
    </fieldset>
</section>
<section id="formulario_navegacion">
    <button id="boton_atras">Atrás</button>
    <button id="boton_siguiente">Siguiente</button>
</section>