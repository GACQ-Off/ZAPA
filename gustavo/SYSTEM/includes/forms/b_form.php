                        <?php
                        if (isset($_tabs_atr)) {
                            if ($_tabs_atr == 0) {
                                $_tex_b_f = 'Enviar';
                                $_src_b_f = 'img/svg/flecha_derecha_blanco.svg';
                                $_alt_b_f = 'Icono de Enviar';
                            } else {
                                $_tex_b_f = 'Guardar';
                                $_src_b_f = 'img/svg/disket_blanco.svg';
                                $_alt_b_f = 'Icono de Guardar';
                            }
                            $_src_r_f = 'img/svg/borrador_blanco.svg';
                        } else {
                            $_src_b_f = 'SYSTEM/img/svg/entrar_blanco.svg';
                            $_alt_b_f = 'Icono de Entrar';
                            $_tex_b_f = 'Entrar';
                            $_src_r_f = 'SYSTEM/img/svg/borrador_blanco.svg';
                        } ?>
                        <button type="submit" id="boton_guardar">
                            <img src="<?php echo htmlspecialchars($_src_b_f); ?>" alt="<?php echo htmlspecialchars($_alt_b_f); ?>"  class="icono">
                            <p><?php echo htmlspecialchars($_tex_b_f); ?></p>
                        </button>
                        <button type="reset" id="boton_limpiar">
                            <img src="<?php echo htmlspecialchars($_src_r_f); ?>" alt="icono de limpiar campos" class="icono">
                            <p>Limpiar Campos</p>
                        </button>