<?php 
    if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
            if (empty($_POST['nom_formulario'])) {
                header('Location: list_t.php?e=1');
                exit();
            } else {
                $nom_tec = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
                if ($bd->existeEnTabla('Tecnica', 'nombre_tecnica', $nom_tec)) {
                    header('Location: list_t.php?e=3');
                    exit();
                } else {
                    $sql = 'INSERT INTO Tecnica (nombre_tecnica, status_tecnica, usuario_ci) VALUES (?, ?, ?)';
                    $tipos = 'sii';
                    $parametros = [$nom_tec, $status_comun, $ci_usuario_sesion];
                    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                        header('Location: list_t.php?s=1');
                        exit();
                    } else {
                    header('Location: list_t.php?e=2');
                    exit();}}}}?>
<section id="cuerpo_formulario">
    <div>
        <label for="nom_formulario" class="labels">
            Nombre (*):
        </label>
        <input type="text" name="nom_formulario" id="nom_formulario" placeholder="Nombre de la Técnica" maxlength="32" minlength="3" autocomplete="off">
    </div>
</section>