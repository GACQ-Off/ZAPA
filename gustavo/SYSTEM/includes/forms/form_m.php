<?php 
    if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
            if (empty($_POST['nom_formulario'])) {
                header('Location: list_m.php?e=1');
                exit();} 
            else {
                $nom_mat = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
                if (strlen($nom_mat) > 32 || strlen($nom_mat) < 3) {
                    header('Location: list_m.php?e=7'); 
                    exit();}
                elseif ($bd->existeEnTabla('Material', 'nombre_material', $nom_mat)) {
                    header('Location: list_m.php?e=3');
                    exit();} 
                else {
                    $sql = 'INSERT INTO Material (nombre_material, status_material, usuario_ci) VALUES (?, ?, ?)';
                    $tipos = 'sii';
                    $parametros = [$nom_mat, $status_comun, $ci_usuario_sesion];
                    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                        header('Location: list_m.php?s=1');
                        exit();} 
                    else {
                    header('Location: list_m.php?e=2');
                    exit();}}}}?>
<section id="cuerpo_formulario">
    <div>
        <label for="nom_formulario" class="labels">
            Nombre (*):
        </label>
        <input type="text" name="nom_formulario" id="nom_formulario" placeholder="Nombre del Material" minlength="3" maxlength="32">
    </div>
</section>