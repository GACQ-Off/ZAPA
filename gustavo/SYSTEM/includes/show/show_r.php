<?php if ($modo === 'editar'): ?>
    <input type="hidden" name="id_registro_a_modificar_m" 
        value="<?php echo htmlspecialchars($datos['ci_restaurador'] ?? ''); ?>">
<?php endif; ?>
<section id="cuerpo_formulario">
    <table class="formulario_tabla">
        <tbody>
            <tr>
                <td colspan="2">
                    <div>
                        <label for="ci_formulario" class="labels">
                            Cédula de Identidad (*):
                        </label>
                        <?php 
                        $is_readonly = ($modo === 'editar') ? 'readonly' : '';
                        ?>
                        <?php if ($modo === 'editar'): ?>
                            <input type="text" id="ci_formulario" name="ci_formulario_m"
                                placeholder="C.I" autocomplete="off" maxlength="9" required <?php echo $is_readonly; ?>
                                value="<?php echo htmlspecialchars($datos['ci_restaurador'] ?? ''); ?>">
                        <?php else: ?>
                            <p class="valor_visualizacion">
                                <?php echo htmlspecialchars($datos['ci_restaurador'] ?? 'N/A'); ?>
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
                                placeholder="Primer y Segundo Nombre" autocomplete="off" minlength="3" maxlength="32" required
                                value="<?php echo htmlspecialchars($datos['nombres_restaurador'] ?? ''); ?>">
                        <?php else: ?>
                            <p class="valor_visualizacion">
                                <?php echo htmlspecialchars($datos['nombres_restaurador'] ?? 'N/A'); ?>
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
                                placeholder="Primer y Segundo Apellido" autocomplete="off" minlength="3" maxlength="32" required
                                value="<?php echo htmlspecialchars($datos['apellidos_restaurador'] ?? ''); ?>">
                        <?php else: ?>
                            <p class="valor_visualizacion">
                                <?php echo htmlspecialchars($datos['apellidos_restaurador'] ?? 'N/A'); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <label for="tel_formulario" class="labels">
                            Télefono de Contacto:
                        </label>
                        <?php if ($modo === 'editar'): ?>
                            <input type="text" name="tel_formulario_m" id="tel_formulario"
                                placeholder="Número Teléfonico" autocomplete="off" minlength="8" maxlength="13"
                                value="<?php echo htmlspecialchars($datos['telefono_restaurador'] ?? ''); ?>">
                        <?php else: ?>
                            <p class="valor_visualizacion">
                                <?php echo htmlspecialchars($datos['telefono_restaurador'] ?? 'N/A'); ?>
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
                                placeholder="Correo Electrónico" autocomplete="off" minlength="10" maxlength="65"
                                value="<?php echo htmlspecialchars($datos['correo_restaurador'] ?? ''); ?>">
                        <?php else: ?>
                            <p class="valor_visualizacion">
                                <?php echo htmlspecialchars($datos['correo_restaurador'] ?? 'N/A'); ?>
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
                            <textarea name="dom_formulario_m" id="dom_formulario"
                                placeholder="Dirección" maxlength="85" autocomplete="off" minlength="6"><?php echo htmlspecialchars($datos['domicilio_restaurador'] ?? ''); ?></textarea>
                        <?php else: ?>
                            <p class="valor_visualizacion">
                                <?php 
                                echo nl2br(htmlspecialchars($datos['domicilio_restaurador'] ?? 'N/A')); 
                                ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</section>