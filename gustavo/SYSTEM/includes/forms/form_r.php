<?php 
    if (!empty($_POST) && ((!empty($_POST['accion']) && $_POST['accion'] == '_registrar'))) {
        if (empty($_POST['ci_formulario']) || empty($_POST['nom_formulario']) || empty($_POST['ape_formulario'])) {
                header('Location: list_r.php?e=1');
                exit();} 
            else {
                $ci_re = trim($_POST['ci_formulario']);
                $nom_re = trim(ucwords(filter_var($_POST['nom_formulario'], FILTER_SANITIZE_STRING)));
                $ape_re = trim(ucwords(filter_var($_POST['ape_formulario'], FILTER_SANITIZE_STRING)));
                $tel_re = !empty($_POST['tel_formulario']) ? trim($_POST['tel_formulario']) : NULL;
                $mail_re = !empty($_POST['mail_formulario']) ? filter_var($_POST['mail_formulario'], FILTER_SANITIZE_EMAIL) : NULL;
                $dom_re = !empty($_POST['dom_formulario']) ? filter_var($_POST['dom_formulario'], FILTER_SANITIZE_STRING) : NULL;
                if (!preg_match('/^[0-9]+$/', $ci_re)) {
                    header('Location: list_r.php?e=4');
                    exit();} 
                elseif (!empty($mail_re) && !filter_var($mail_re, FILTER_VALIDATE_EMAIL)) {
                    header('Location: list_r.php?e=5');
                    exit();} 
                elseif ($bd->existeEnTabla('Restaurador', 'ci_restaurador', $ci_re)) {
                    header('Location: list_r.php?e=3');
                    exit();} 
                else {
                    $sql = 'INSERT INTO Restaurador (ci_restaurador, nombres_restaurador, apellidos_restaurador, telefono_restaurador, mail_restaurador, domicilio_restaurador, status_restaurador, usuario_ci) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                    $tipos = 'isssssii';
                    $parametros = [$ci_re, $nom_re, $ape_re, $tel_re, $mail_re, $dom_re, $status_comun, $ci_usuario_sesion];
                    if ($bd->accionRegistro($sql, $tipos, $parametros)) {
                        header('Location: list_r.php?s=1');
                        exit();
                    } else {
                        header('Location: list_r.php?e=2');
                        exit();}}}} ?>
<section id="cuerpo_formulario">
    <table class="formulario_tabla">
        <tbody>
            <tr>
                <td colspan="2"> <div>
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
                        <input type="text" name="nom_formulario" id="nom_formulario" placeholder="Primer y Segundo Nombre" autocomplete="off" minlength="3" maxlength="32">
                    </div>
                </td>
                <td>
                    <div>
                        <label for="ape_formulario" class="labels">
                            Apellidos (*):
                        </label>
                        <input type="text" name="ape_formulario" id="ape_formulario" placeholder="Primer y Segundo Apellido" autocomplete="off" minlength="3" maxlength="32">
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div>
                        <label for="tel_formulario" class="labels">
                            Télefono de Contacto:
                        </label>
                        <input type="text" name="tel_formulario" id="tel_formulario" placeholder="Número Teléfonico" autocomplete="off" minlength="8" maxlength="13">
                    </div>
                </td>
                <td>
                    <div>
                        <label for="mail_formulario" class="labels">
                            Correo Electrónico:
                        </label>
                        <input type="email" name="mail_formulario" id="mail_formulario" placeholder="Correo Electrónico" autocomplete="off" minlength="10" maxlength="65">
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2"> <div>
                        <label for="dom_formulario" class="labels">
                            Domicilio:
                        </label>
                        <textarea name="dom_formulario" id="dom_formulario" placeholder="Dirección" maxlength="85" autocomplete="off" minlength="6"></textarea>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</section>