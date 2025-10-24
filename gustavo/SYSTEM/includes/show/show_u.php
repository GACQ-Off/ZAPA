<?php
$sql_roles = "SELECT id_rol, descripcion_rol FROM rol";
$ruta = 'uploads/profiles/';
$roles_db = $bd->obtenerRegistro($sql_roles, '', []);
$roles_map = []; 
if (!empty($roles_db)) {
    foreach ($roles_db as $rol) {
        $roles_map[$rol['id_rol']] = $rol['descripcion_rol'];}}
$foto_actual = !empty($datos['img_usuario']) ? $ruta . $datos['img_usuario'] : 'img/default_usuario_negro.png';
$ci_usuario = htmlspecialchars($datos['ci_usuario'] ?? '');
$nombres_usuario = htmlspecialchars($datos['nombres_usuario'] ?? '');
$apellidos_usuario = htmlspecialchars($datos['apellidos_usuario'] ?? '');
$telefono_usuario = htmlspecialchars($datos['telefono_usuario'] ?? '');
$mail_usuario = htmlspecialchars($datos['mail_usuario'] ?? '');
$domicilio_usuario = htmlspecialchars($datos['domicilio_usuario'] ?? '');
$nombre_usuario = htmlspecialchars($datos['nombre_usuario'] ?? '');
$pass_usuario = htmlspecialchars($datos['pass_usuario'] ?? '');
$id_rol = htmlspecialchars($datos['rol_id'] ?? ''); 
$f_nacimiento_db = $datos['f_nacimiento_usuario'] ?? NULL;
$f_nacimiento_visual = ($f_nacimiento_db && $f_nacimiento_db !== '0000-00-00') ? date('d/m/Y', strtotime($f_nacimiento_db)) : '';
$descripcion_rol = $roles_map[$id_rol] ?? 'N/A';
if ($modo === 'editar') { ?>
    <input type="hidden" 
        name="id_registro_a_modificar_m" 
        value="<?php echo $ci_usuario; ?>">
    <input type="hidden" name="foto_anterior_m" value="<?php echo htmlspecialchars($datos['img_usuario'] ?? ''); ?>">
    <input type="hidden" name="accion" value="_modificar">
<?php } ?>

<section id="cuerpo_formulario">
    <div class="contenedor_mitad">
        <p class="label_artificial">
            Foto de Perfil
        </p>
        <div class="imagen_contenedor_uno">
            <img id="imagen_previa_m" src="<?php echo htmlspecialchars($foto_actual); ?>" alt="Previsualización de la imagen">
        </div>
        
        <?php if ($modo === 'editar'): ?>
            <div id="foto_input_contenedor">
                <label for="foto_formulario_m" class="label_de_imagen_o_archivo">
                    <img src="img/svg/foto_blanco.svg" class="icono">
                    Seleccionar Foto
                </label>
                <input type="file" name="foto_formulario" id="foto_formulario_m" accept="image/*" class="label_de_imagen_o_archivo">
            </div>
        <?php endif; ?>
    </div>

    <div id="segundoparte">
        <fieldset id="informacion_general">
            <legend class="label_artificial">
                Información General
            </legend>
            <table class="formulario_tabla">
                <tbody>
                    <tr>
                        <td colspan="2">
                            <div>
                                <label for="ci_formulario" class="labels">
                                    Cédula de Identidad (*):
                                </label>
                                <?php if ($modo === 'editar'): ?>
                                    <input type="text" id="ci_formulario" name="ci_formulario" readonly
                                        placeholder="C.I" maxlength="9" autocomplete="off" 
                                        value="<?php echo $ci_usuario; ?>">
                                <?php else: ?>
                                    <p class="valor_visualizacion">
                                        <?php echo $ci_usuario ?: 'N/A'; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label for="nom_formulario" class="labels">
                                    Nombres (*):
                                </label>
                                <?php if ($modo === 'editar'): ?>
                                    <input type="text" name="nom_formulario_m" id="nom_formulario" 
                                        placeholder="Primer y Segundo Nombre" minlength="3" maxlength="32" autocomplete="off" 
                                        value="<?php echo $nombres_usuario; ?>">
                                <?php else: ?>
                                    <p class="valor_visualizacion">
                                        <?php echo $nombres_usuario ?: 'N/A'; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label for="ape_formulario" class="labels">
                                    Apellidos (*):
                                </label>
                                <?php if ($modo === 'editar'): ?>
                                    <input type="text" name="ape_formulario_m" id="ape_formulario" 
                                        placeholder="Primer y Segundo Apellido" minlength="3" maxlength="32" autocomplete="off" 
                                        value="<?php echo $apellidos_usuario; ?>">
                                <?php else: ?>
                                    <p class="valor_visualizacion">
                                        <?php echo $apellidos_usuario ?: 'N/A'; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div>
                                <label for="f_nac_formulario" class="labels">
                                    Fecha de Nacimiento:
                                </label>
                                <?php if ($modo === 'editar'): ?>
                                    <input type="date" name="f_nac_formulario_m" id="f_nac_formulario"
                                        value="<?php echo htmlspecialchars($f_nacimiento_db); ?>">
                                <?php else: ?>
                                    <p class="valor_visualizacion">
                                        <?php echo $f_nacimiento_visual ?: 'N/A'; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <label for="tel_formulario" class="labels">
                                    Teléfono de Contacto:
                                </label>
                                <?php if ($modo === 'editar'): ?>
                                    <input type="text" name="tel_formulario_m" id="tel_formulario" 
                                        placeholder="Número Teléfonico" minlength="8" maxlength="13" autocomplete="off"
                                        value="<?php echo $telefono_usuario; ?>">
                                <?php else: ?>
                                    <p class="valor_visualizacion">
                                        <?php echo $telefono_usuario ?: 'N/A'; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <label for="mail_formulario" class="labels">
                                    Correo Electrónico:
                                </label>
                                <?php if ($modo === 'editar'): ?>
                                    <input type="email" name="mail_formulario_m" id="mail_formulario" 
                                        placeholder="Correo Electrónico" minlength="10" maxlength="65" autocomplete="off"
                                        value="<?php echo $mail_usuario; ?>">
                                <?php else: ?>
                                    <p class="valor_visualizacion">
                                        <?php echo $mail_usuario ?: 'N/A'; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div>
                                <label for="dom_formulario" class="labels">
                                    Domicilio:
                                </label>
                                <?php if ($modo === 'editar'): ?>
                                    <textarea name="dom_formulario_m" id="dom_formulario" placeholder="Dirección" 
                                        minlength="6" maxlength="65" autocomplete="off"><?php echo $domicilio_usuario; ?></textarea>
                                <?php else: ?>
                                    <p class="valor_visualizacion">
                                        <?php echo nl2br($domicilio_usuario ?: 'N/A'); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </fieldset>

        <fieldset id="informacion_usuario">
            <legend class="label_artificial">
                Información de Sistema
            </legend>
            <table class="formulario_tabla">
                <tr>
                    <td>
                        <div>
                            <label for="nick_formulario">
                                Nombre de Usuario (*):
                            </label>
                            <br>
                            <?php if ($modo === 'editar'): ?>
                                <input type="text" id="nick_formulario" name="nick_formulario_m" 
                                    placeholder="Nombre de Usuario" minlength="4" maxlength="32" autocomplete="off"
                                    value="<?php echo $nombre_usuario; ?>">
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    <?php echo $nombre_usuario ?: 'N/A'; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label for="pass_formulario">
                                Contraseña (*):
                            </label>
                            <br>
                            <?php if ($modo === 'editar'): ?>
                                <input type="text" name="pass_formulario_m" id="pass_formulario" 
                                    placeholder="Contraseña" minlength="8" maxlength="32" autocomplete="off"
                                    value="<?php echo $pass_usuario; ?>"> 
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    ************ </p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div>
                            <label for="rol_formulario">
                                Rol (*):
                            </label>
                            <?php if ($modo === 'editar'): ?>
                                <input type="text" name="rol_formulario_m" id="rol_formulario" list="roles_" 
                                    placeholder='Indique Rol del Usuario' autocomplete="off"
                                    value="<?php echo $id_rol; ?>">
                                <datalist id="roles_">
                                    <?php 
                                    if (!empty($roles_db)) { 
                                        foreach ($roles_db as $rol) { 
                                            echo '<option value="' . htmlspecialchars($rol["id_rol"]) . '">' . htmlspecialchars($rol["descripcion_rol"]) . '</option>'; 
                                        } 
                                    } 
                                    ?>
                                </datalist>
                            <?php else: ?>
                                <p class="valor_visualizacion">
                                    <?php echo $descripcion_rol; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</section>