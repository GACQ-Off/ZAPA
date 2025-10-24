<?php
session_start();
$_tabs_atr = 12;

require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/_general.php';
require_once 'includes/scripts/sett_t.php';
require_once 'reports/reports_.php';
require_once 'includes/_messages.php';

$rif_tr = ''; 
$nom_tr = '';
$ci_dir_tr = '';
$nom_dir_tr = '';
$ape_dir_tr = '';
$ubi_tr = '';
$tel_tr = '';
$mail_tr = '';
$f_fu_tr = date('Y-m-d'); 
$logo_tr = 'img/default.png'; 
$trapicheExiste = false;
$datos = []; 
$sql_select_unico = "SELECT * FROM trapiche LIMIT 1";
$resultado = $bd->obtenerRegistro($sql_select_unico, '', []);

if (!empty($resultado)) {
    $datos = $resultado[0];
    $trapicheExiste = true;
    $rif_tr = $datos['rif_trapiche']; 
    $nom_tr = $datos['nombre_trapiche'];
    $ci_dir_tr = $datos['ci_director_a_trapiche'];
    $nom_dir_tr = $datos['nombres_director_a_trapiche'];
    $ape_dir_tr = $datos['apellidos_director_a_trapiche'];
    $ubi_tr = $datos['ubicacion_trapiche'];
    $tel_tr = $datos['telefono_trapiche'];
    $mail_tr = $datos['mail_trapiche'];
    $f_fu_tr = $datos['f_fundacion_trapiche'];
    if (!empty($datos['logo_trapiche'])) {
        $logo_tr = $datos['logo_trapiche'];
    }
}

$logoTrapiche = $logo_tr; 
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include "includes/_head.php"; ?>
</head>

<body>
    <div id="fondo"></div>
    <main>
        <?php include "includes/_header.php";
        include "includes/sett_tabs.php"; ?>
        <section id="listados_">
            <form method="post" action="sett_t.php" id="contenedor_de_botones" enctype="multipart/form-data">
                
                <?php if ($trapicheExiste): ?>
                    <input type="hidden" name="accion" value="_modificar">
                    <input type="hidden" name="original_rif" value="<?php echo htmlspecialchars($rif_tr); ?>">
                <?php else: ?>
                    <input type="hidden" name="accion" value="_registrar">
                <?php endif; ?>

                <div class="contenedor_mitad">
                    <p class="label_artificial">Logotipo</p>
                    <div class="imagen_contenedor_uno">
                        <img id="imagen_previa"
                            src="<?php echo htmlspecialchars(($logoTrapiche == 'img/default.png' ? $logoTrapiche : $ruta . $logoTrapiche)); ?>"                            
                            alt="Previsualización del logotipo">
                    </div>
                    <div id="foto_input_contenedor">
                        <label for="foto_formulario" class="label_de_imagen_o_archivo">
                            <img src="img/svg/foto_blanco.svg" class="icono">
                            Cambiar Logotipo
                        </label>
                        <input type="file" name="foto_formulario" id="foto_formulario" accept="image/*"
                            class="label_de_imagen_o_archivo">
                    </div>
                </div>
                <div id="segundoparte">
                    <fieldset id="informacion_general">
                        <legend class="label_artificial">
                            Información Principal (Todos los campos son obligatorios)
                        </legend>
                        <table class="formulario_tabla">
                            <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <label for="ci_formulario" class="labels">
                                            Registro Único de Información Fiscal (RIF)
                                            (<?php echo $trapicheExiste ? 'No Modificable' : 'Obligatorio'; ?>):
                                        </label>
                                        <input type="text"
                                            id="ci_formulario" name="ci_formulario"                                
                                            autocomplete="off"                                    
                                            value="<?php echo htmlspecialchars($rif_tr); ?>"
                                            <?php if($trapicheExiste) echo 'readonly'; ?>> 
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="nombre_trapiche_formulario" class="labels">
                                            Nombre:
                                        </label>
                                        <input type="text" name="nombre_trapiche_formulario" id="nombre_trapiche_formulario"
                                            placeholder="Razón Social o Nombre Comercial" minlength="3" maxlength="65"
                                            autocomplete="off" value="<?php echo htmlspecialchars($nom_tr); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="ci_formulario_dir" class="labels">
                                            C.I. Director(a):
                                        </label>
                                        <input type="text"
                                            name="ci_formulario_dir" id="ci_formulario"                                    
                                            placeholder="C.I. Director(a)"  maxlength="9"              
                                            autocomplete="off"                                    
                                            value="<?php echo htmlspecialchars($ci_dir_tr); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="nom_formulario" class="labels">
                                            Nombres Director(a):
                                        </label>
                                        <input type="text"
                                            name="nom_formulario" id="nom_formulario"                                    
                                            placeholder="Nombres del Director(a)" minlength="3" maxlength="32"              
                                            autocomplete="off"                                    
                                            value="<?php echo htmlspecialchars($nom_dir_tr); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="ape_formulario" class="labels">
                                            Apellidos Director(a):
                                        </label>
                                        <input type="text"
                                            name="ape_formulario" id="ape_formulario"                                    
                                            placeholder="Apellidos del Director(a)" minlength="3" maxlength="32"              
                                            autocomplete="off"                                    
                                            value="<?php echo htmlspecialchars($ape_dir_tr); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="dom_formulario" class="labels">
                                            Ubicación:
                                        </label>
                                        <textarea
                                            name="dom_formulario" id="dom_formulario" placeholder="Dirección"              
                                            minlength="6" autocomplete="off"                          
                                            maxlength="75"><?php echo htmlspecialchars($ubi_tr); ?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="tel_formulario" class="labels">
                                            Teléfono(s) de Contacto:
                                        </label>
                                        <input type="text"
                                            name="tel_formulario" id="tel_formulario"                                    
                                            placeholder="Número Teléfonico" minlength="7" maxlength="25" autocomplete="off"
                                            value="<?php echo htmlspecialchars($tel_tr); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="mail_formulario" class="labels">
                                            Correo Electrónico:
                                        </label>
                                        <input type="email"
                                            name="mail_formulario" id="mail_formulario"                                    
                                            placeholder="Correo Electrónico" minlength="10" maxlength="65"                  
                                            autocomplete="off"                                    
                                            value="<?php echo htmlspecialchars($mail_tr); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <label for="f_nac_formulario" class="labels">
                                            Fecha de Fundación:
                                        </label>
                                        <input type="date"
                                            name="f_nac_formulario" id="f_nac_formulario"                                  
                                            value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($f_fu_tr))); ?>">
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <button type="submit" id="boton_guardar">
                        <img src="img/svg/disket_blanco.svg" alt="Icono de Guardar"
                            class="icono">
                        <p>Guardar</p>
                        </button>
                    <div id="modal_tercero" class="modal_confirmacion">
                        <div class="modal_contenido">
                            <p>¿Está seguro de que los datos ingresados son los correctos?</p>
                            <div class="modal_botones">
                                <button id="confirmar_guardar">Sí, Guardar</button>
                                <button id="cancelar_guardar">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    <?php include "includes/_bar.php";
    include 'includes/_messages.php'; ?>
</body>
</html>