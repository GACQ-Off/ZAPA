<?php 
    if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
        if (empty($_POST['nom_formulario'])) {
            header('Location: list_c.php?e=1');
            exit();} 
        else {
            $nom_cat = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
            $resu_cat = !empty($_POST['resu_formulario']) ? trim(filter_var($_POST['resu_formulario'], FILTER_SANITIZE_STRING)) : NULL;
            if (strlen($nom_cat) > 32 || strlen($nom_cat) < 4) {
                header('Location: list_c.php?e=6'); 
                exit();}
            elseif ($bd->existeEnTabla('Categoria', 'nombre_categoria', $nom_cat)) {
                header('Location: list_c.php?e=3');
                exit();} 
            else {
                $sql = 'INSERT INTO Categoria (nombre_categoria, descripcion_categoria, status_categoria, usuario_ci) VALUES (?, ?, ?, ?)';
                $tipos = 'ssii';
                $parametros = [$nom_cat, $resu_cat, $status_comun, $ci_usuario_sesion];
                if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                    header('Location: list_c.php?s=1');
                    exit();} 
                else {
                header('Location: list_c.php?e=2');
                exit();}}}}?>
<section id="cuerpo_formulario">
    <div>
        <label for="nom_formulario" class="labels">
            Nombre (*):
        </label>
        <input type="text" name="nom_formulario" id="nom_formulario" placeholder="Nombre de la Categoría" maxlength="32" minlength="4" autocomplete="off">
    </div>
    <div>
        <label for="resu_formulario" class="labels">
            Texto Descripción:
        </label>
        <textarea name="resu_formulario" id="resu_formulario" placeholder="Esta categoría se trata de..." maxlength="255" autocomplete="off"></textarea>
    </div>
</section>