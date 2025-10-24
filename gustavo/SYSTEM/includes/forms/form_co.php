<?php
if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
    if (empty($_POST['id_formulario']) || empty($_POST['ti_formulario']) || empty($_POST['es_formulario']) || empty($_POST['tip_formulario'])) {
        header('Location: list_co.php?e=1');
        exit();} 
    else {
        $cod_co = trim($_POST['id_formulario']);
        $tit_co = trim(ucwords(filter_var($_POST['ti_formulario'], FILTER_SANITIZE_STRING)));
        $f_c_co = !empty($_POST['f_cr_formulario']) ? $_POST['f_cr_formulario'] : NULL;
        $tip_co = trim(filter_var($_POST['tip_formulario'], FILTER_SANITIZE_STRING));
        $est_co = trim(filter_var($_POST['es_formulario'], FILTER_SANITIZE_STRING));
        $des_co = !empty($_POST['resu_formulario']) ? trim(filter_var($_POST['resu_formulario'], FILTER_SANITIZE_STRING)) : NULL;
        if (strlen($cod_co) < 1 || strlen($cod_co) > 25) {
            header('Location: list_co.php?e=10'); 
            exit();} 
        elseif (strlen($tit_co) < 4 || strlen($tit_co) > 65) {
            header('Location: list_co.php?e=6'); 
            exit();} 
        elseif (!empty($des_co) && (strlen($des_co) < 20 || strlen($des_co) > 255)) {
            header('Location: list_co.php?e=11'); 
            exit();}
        elseif ($bd->existeEnTabla('coleccion', 'cod_coleccion', $cod_co)) {
            header('Location: list_co.php?e=3');
            exit();} 
        else {
            $tip_co = empty($tip_co) ? 'Permanente' : $tip_co;
            $est_co = empty($est_co) ? 'Disponible' : $est_co;
            $sql = 'INSERT INTO Coleccion (cod_coleccion, titulo_coleccion, f_creacion_coleccion, naturaleza_coleccion, estado_coleccion, descripcion_coleccion, status_coleccion, usuario_ci) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $tipos = 'ssssssis'; 
            $parametros = [$cod_co, $tit_co, $f_c_co, $tip_co, $est_co, $des_co, $status_comun, $ci_usuario_sesion];
            if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                header('Location: list_co.php?s=1');
                exit();
            } else {
                header('Location: list_co.php?e=2');
                exit();}}}}
?>
<section id="cuerpo_formulario">
    <table class="formulario_tabla">
        <tbody>
            <tr>
                <td > 
                    <div>
                        <label for="cod_formulario" class="labels">
                            Código de Identificación (*):
                        </label>
                        <input type="text" id="id_formulario" name="id_formulario" placeholder="Código de Identificación"
                            maxlength="25" minlength="1" autocomplete="off">
                    </div>
                </td>
                <td colspan="2">
                    <div>
                        <label for="ti_formulario" class="labels">
                            Título (*):
                        </label>
                        <input type="text" name="ti_formulario" id="ti_formulario" placeholder="Título" maxlength="65" minlength="4"
                            autocomplete="off">
                    </div>
                </td>
            </tr>

            <tr>
            </tr>
            
            <tr>
                <td>
                    <div>
                        <label for="f_cr_formulario" class="labels">
                            Fecha de Creación:
                        </label>
                        <input type="date" name="f_cr_formulario" id="f_cr_formulario">
                    </div>
                </td>
                <td>
                    <div>
                        <label for="tip_formulario" class="labels">Tipo/Naturaleza (*):</label>
                        <select name="tip_formulario" id="tip_formulario" required>
                            <option value="">Seleccione...</option>
                            <option value="Permanente">Permanente</option>
                            <option value="Temporal">Temporal</option>
                        </select>
                    </div>
                </td>
                <td>
                    <div>
                        <label for="es_formulario" class="labels">
                            Estado (*):
                        </label>
                        <select name="es_formulario" id="es_formulario" required>
                            <option value="">Seleccione...</option>
                            <option value="Disponible">Disponible</option>
                            <option value="En prestamo">En prestamo</option>
                        </select>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <div>
                        <label for="resu_formulario">
                            Texto Descripción:
                        </label>
                        <textarea name="resu_formulario" id="resu_formulario" placeholder="Esta colección es..." maxlength="255" max='255'
                        minlength="20" autocomplete="off"></textarea>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</section> 