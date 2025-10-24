<?php 
if ($modo === 'editar') { ?>
    <input type="hidden" name="id_registro_a_modificar" value="<?php echo htmlspecialchars($datos['ci_autor'] ?? ''); ?>">
<?php } ?>

<section id="cuerpo_formulario">
    <table class="formulario_tabla">
        <tbody>
            <tr>
                <td colspan="2">
                    <div>
                        <label for="ci_formulario" class="labels">
                            Cédula de Identidad<?php if ($modo === 'editar') { ?> (*) <?php } ?>:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="text" id="ci_formulario" 
                            name="ci_formulario" 
                            placeholder="C.I" 
                            autocomplete="off"
                            maxlength="9" readonly
                            value="<?php echo htmlspecialchars($datos['ci_autor'] ?? ''); ?>">
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['ci_autor'] ?? 'N/A'); ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <label for="nom_formulario" class="labels">
                            Nombres<?php if ($modo === 'editar') { ?> (*) <?php } ?>:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="text" 
                            name="nom_formulario" 
                            id="nom_formulario" 
                            placeholder="Primer y Segundo Nombre" 
                            autocomplete="off" 
                            maxlength="32" minlength="3"
                            value="<?php echo htmlspecialchars($datos['nombres_autor'] ?? ''); ?>"> 
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['nombres_autor'] ?? 'N/A'); ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <div>
                        <label for="ape_formulario" class="labels">
                            Apellidos<?php if ($modo === 'editar') { ?> (*) <?php } ?>:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="text" name="ape_formulario" 
                            id="ape_formulario" 
                            placeholder="Primer y Segundo Apellido" 
                            autocomplete="off" 
                            maxlength="32" minlength="3"
                            value="<?php echo htmlspecialchars($datos['apellidos_autor'] ?? ''); ?>">
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['apellidos_autor'] ?? 'N/A'); ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div>
                        <label for="pseu_formulario" class="labels">
                            Seudónimos Conocidos:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="text" 
                            name="pseu_formulario" id="pseu_formulario" 
                            placeholder="Seudonimos..." 
                            autocomplete="off" 
                            maxlength="65" minlength="3"
                            value="<?php echo htmlspecialchars($datos['seudonimos_autor'] ?? ''); ?>">
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['seudonimos_autor'] ?? 'N/A'); ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <label for="f_nac_formulario" class="labels">
                            Fecha de Nacimiento:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="date" 
                            name="f_nac_formulario" 
                            id="f_nac_formulario"
                            value="<?php 
                                $f_nac = $datos['f_nacimiento_autor'] ?? '';
                                if (!empty($f_nac) && $f_nac !== '0000-00-00') {
                                    echo htmlspecialchars(date('Y-m-d', strtotime($f_nac)));
                                }
                            ?>">
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php 
                                $f_nac = $datos['f_nacimiento_autor'] ?? 'N/A';
                                if ($f_nac !== 'N/A' && $f_nac !== '0000-00-00') {
                                    echo htmlspecialchars(date('d/m/Y', strtotime($f_nac)));
                                } else {
                                    echo 'N/A';
                                }
                            ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <div>
                        <label for="f_fall_formulario" class="labels">
                            Fecha de Fallecimiento:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="date" name="f_fall_formulario" id="f_fall_formulario"
                            value="<?php 
                                $f_fall = $datos['f_fallecimiento_autor'] ?? '';
                                if (!empty($f_fall) && $f_fall !== '0000-00-00') {
                                    echo htmlspecialchars(date('Y-m-d', strtotime($f_fall)));
                                }
                            ?>">
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php 
                                $f_fall = $datos['f_fallecimiento_autor'] ?? 'N/A';
                                if ($f_fall !== 'N/A' && $f_fall !== '0000-00-00') {
                                    echo htmlspecialchars(date('d/m/Y', strtotime($f_fall)));
                                } else {
                                    echo 'N/A';
                            }?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <label for="tel_formulario" class="labels">
                            Teléfono de Contacto:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="text" name="tel_formulario" 
                            id="tel_formulario" 
                            placeholder="Número Teléfonico" 
                            autocomplete="off" 
                            maxlength="13" minlength="7"
                            value="<?php echo htmlspecialchars($datos['telefono_autor'] ?? ''); ?>">
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['telefono_autor'] ?? 'N/A'); ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <div>
                        <label for="mail_formulario" class="labels">
                            Correo Electrónico:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <input type="email" 
                            name="mail_formulario" id="mail_formulario" 
                            placeholder="Correo Electrónico" 
                            autocomplete="off" 
                            maxlength="85" minlength="10"
                            value="<?php echo htmlspecialchars($datos['mail_autor'] ?? ''); ?>">
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['mail_autor'] ?? 'N/A'); ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div>
                        <label for="dom_formulario" class="labels">
                            Domicilio:
                        </label>
                        <?php if ($modo === 'editar') { ?>
                        <textarea name="dom_formulario" 
                            id="dom_formulario" placeholder="Dirección" 
                            autocomplete="off" 
                            maxlength="85" minlength="6"><?php echo htmlspecialchars($datos['domicilio_autor'] ?? ''); ?></textarea>
                        <?php } else { ?>
                        <p class="valor_visualizacion">
                            <?php echo htmlspecialchars($datos['domicilio_autor'] ?? 'N/A'); ?>
                        </p>
                        <?php } ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</section>