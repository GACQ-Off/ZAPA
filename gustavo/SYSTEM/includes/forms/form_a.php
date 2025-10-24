<?php 
    if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
        if (empty($_POST['ci_formulario']) || empty($_POST['nom_formulario']) || empty($_POST['ape_formulario'])) {
                header('Location: list_a.php?e=1');
                exit();
            } else {
                $ci_au = trim($_POST['ci_formulario']);
                $nom_au = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
                $ape_au = trim(ucwords(filter_var($_POST['ape_formulario'], FILTER_SANITIZE_STRING)));
                $pseu_au = !empty($_POST['pseu_formulario']) ? ucwords(filter_var($_POST['pseu_formulario'], FILTER_SANITIZE_STRING)) : NULL;
                $f_nac_au = !empty($_POST['f_nac_formulario']) ? $_POST['f_nac_formulario'] : NULL;
                $f_fall_au = !empty($_POST['f_fall_formulario']) ? $_POST['f_fall_formulario'] : NULL;
                $tel_au = !empty($_POST['tel_formulario']) ? $_POST['tel_formulario'] : NULL;
                $mail_au = !empty($_POST['mail_formulario']) ? filter_var($_POST['mail_formulario'], FILTER_SANITIZE_EMAIL) : NULL;
                $dom_au = !empty($_POST['dom_formulario']) ? filter_var($_POST['dom_formulario'], FILTER_SANITIZE_STRING) : NULL;
                if (!preg_match('/^[0-9]+$/', $ci_au) || strlen($ci_au) > 9) {
                    header('Location: list_a.php?e=4');
                    exit();}
                elseif (strlen($nom_au) < 3 || strlen($nom_au) > 32) {
                    header('Location: list_a.php?e=7'); 
                    exit();}  
                elseif (strlen($ape_au) < 3 || strlen($ape_au) > 32) {
                    header('Location: list_a.php?e=8'); 
                    exit();}  
                elseif (!empty($tel_au) && (strlen($tel_au) < 7 || strlen($tel_au) > 13) || is_numeric(value: $tel_au)) {
                    header('Location: list_a.php?e=9');
                    exit();}
                elseif (!empty($mail_au) && !filter_var($mail_au, FILTER_VALIDATE_EMAIL)) {
                    header('Location: list_a.php?e=5');
                    exit();} 
                elseif ($bd->existeEnTabla('Autor', 'ci_autor', $ci_au)) {
                    header('Location: list_a.php?e=3');
                    exit();
                } else {
                    $sql = 'INSERT INTO Autor (ci_autor, nombres_autor, apellidos_autor, seudonimos_autor, f_nacimiento_autor, f_fallecimiento_autor, telefono_autor, mail_autor, domicilio_autor, status_autor, usuario_ci) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    $tipos = 'issssssssii';
                    $parametros = [$ci_au, $nom_au, $ape_au, $pseu_au, $f_nac_au, $f_fall_au, $tel_au, $mail_au, $dom_au, $status_comun, $ci_usuario_sesion];
                    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                        header('Location: list_a.php?s=1');
                        exit();} 
                    else {header('Location: list_a.php?e=2');
                    exit();}}}}
?>
<section id="cuerpo_formulario">
    <table class="formulario_tabla">
        <tbody>
            <tr>
                <td colspan="2">
                    <div>
                        <label for="ci_formulario" class="labels">
                            Cédula de Identidad (*):
                        </label>
                        <input type="text" id="ci_formulario" name="ci_formulario" placeholder="C.I" autocomplete="off" maxlength="9">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div>
                        <label for="nom_formulario" class="labels">
                            Nombres (*):
                        </label>
                        <input type="text" name="nom_formulario" id="nom_formulario" placeholder="Primer y Segundo Nombre" autocomplete="off" maxlength="32" minlength="3">
                    </div>
                </td>
                <td>
                    <div>
                        <label for="ape_formulario" class="labels">
                            Apellidos (*):
                        </label>
                        <input type="text" name="ape_formulario" id="ape_formulario" placeholder="Primer y Segundo Apellido" autocomplete="off" maxlength="32" minlength="3">
                    </div>
                </td>
            </tr>
            
            <tr>
                <td colspan="2">
                    <div>
                        <label for="pseu_formulario" class="labels">
                            Seudónimos Conocidos:
                        </label>
                        <input type="text" name="pseu_formulario" id="pseu_formulario" placeholder="Seudonimos..." autocomplete="off" maxlength="65" minlength="3">
                    </div>
                </td>
            </tr>
            
            <tr>
                <td>
                    <div>
                        <label for="f_nac_formulario" class="labels">
                            Fecha de Nacimiento:
                        </label>
                        <input type="date" name="f_nac_formulario" id="f_nac_formulario">
                    </div>
                </td>
                <td>
                    <div>
                        <label for="f_fall_formulario" class="labels">
                            Fecha de Fallecimiento:
                        </label>
                        <input type="date" name="f_fall_formulario" id="f_fall_formulario">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div>
                        <label for="tel_formulario" class="labels">
                            Télefono de Contacto:
                        </label>
                        <input type="text" name="tel_formulario" id="tel_formulario" placeholder="Número Teléfonico" autocomplete="off" maxlength="13" minlength="7">
                    </div>
                </td>
                <td>
                    <div>
                        <label for="mail_formulario" class="labels">
                            Correo Electrónico:
                        </label>
                        <input type="email" name="mail_formulario" id="mail_formulario" placeholder="Correo Electrónico" autocomplete="off" maxlength="85" minlength="10">
                    </div>
                </td>
            </tr>


            <tr>
                <td colspan="2">
                    <div>
                        <label for="dom_formulario" class="labels">
                            Domicilio:
                        </label>
                        <textarea name="dom_formulario" id="dom_formulario" placeholder="Dirección" autocomplete="off" maxlength="85" minlength="6"></textarea>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</section>