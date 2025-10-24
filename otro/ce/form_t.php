<?php 
$iu = 1;
require_once 'includes/_br4in.php'; 
$ci_profesor = '';
$nombres = '';
$apellidos = '';
$telefono = '';
$email = '';
$domicilio = '';
$id_profesion_fk = '';
$error_mensaje = '';
$exito_mensaje = '';
$ci_usuario_logueado = $_SESSION['ci_usuario'] ?? NULL;
$profesiones_list = [];
$sql_profesiones = "SELECT id_profesion, nombre_profesion FROM profesion WHERE status_profesion = 1 ORDER BY nombre_profesion ASC";
$resultado_profesiones = $db->query($sql_profesiones); 
if ($resultado_profesiones) {
    $profesiones_list = $resultado_profesiones->fetch_all(MYSQLI_ASSOC);
    $resultado_profesiones->free();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ci_profesor = trim($_POST['ci_profesor']);
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = trim($_POST['telefono'] ?? NULL);
    $email = trim($_POST['email'] ?? NULL);
    $domicilio = trim($_POST['domicilio'] ?? NULL);
    $nombre_profesion_input = trim($_POST['profesion_nombre']);
    foreach ($profesiones_list as $p) {
        if ($p['nombre_profesion'] === $nombre_profesion_input) {
            $id_profesion_fk = $p['id_profesion'];
            break;}}
    if (empty($ci_profesor) || empty($nombres) || empty($apellidos) || empty($id_profesion_fk)) {
        $error_mensaje = "Por favor, complete todos los campos obligatorios (*).";}
    if (empty($error_mensaje)) {
        $sql_check = "SELECT ci_profesor FROM profesor WHERE ci_profesor = ? AND status_profesor = 1";
        if ($sent_verificar = $db->prepare($sql_check)) {
            $sent_verificar->bind_param("s", $ci_profesor);
            $sent_verificar->execute();
            $sent_verificar->store_result();            
            if ($sent_verificar->num_rows > 0) {
                $error_mensaje = "Error: El profesor con la Cédula {$ci_profesor} ya está registrado.";}
            $sent_verificar->close();}}
    if (empty($error_mensaje)) {
        $sql_insertar = "INSERT INTO profesor (ci_profesor, nombres_profesor, apellidos_profesor, telefono_profesor, email_profesor, domicilio_profesor, profesion_id, status_profesor, usuario_ci) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?)";
        if ($sent_insertar = $db->prepare($sql_insertar)) {
            $sent_insertar->bind_param("ssssssis", 
                $ci_profesor, $nombres, $apellidos, $telefono, $email, $domicilio, $id_profesion_fk, $ci_usuario_logueado);
            if ($sent_insertar->execute()) {
                $exito_mensaje = "Profesor registrado exitosamente. Redirigiendo...";
                header("list_t.php"); 
                exit; } 
            else {
                $error_mensaje = "Error al ejecutar la inserción: " . $sent_insertar->error;}
            $sent_insertar->close();} 
        else {
            $error_mensaje = "Error al preparar la consulta de inserción: " . $db->error;}}}
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Profesor | Registro</title>
        <link rel="stylesheet" href="CSS/_interface.css">
        <link rel="stylesheet" href="CSS/_bar.css">
        <link rel="stylesheet" href="CSS/_form.css">
        <link rel="stylesheet" href="CSS/list_t.css">
        <script src="" async defer></script>
    </head>

    <body>
        <?php include 'includes/_bar.php' ?>
        <main id="contenedor_principal">
            <form action="" method="post" id='formulario'>
<?php include 'includes/h_form.php' ?>

<?php if (!empty($error_mensaje)): ?>
    <div class="alerta error">
        <?php echo htmlspecialchars($error_mensaje); ?>
    </div>
<?php endif; ?>

<?php if (!empty($exito_mensaje)): ?>
    <div class="alerta exito">
        <?php echo htmlspecialchars($exito_mensaje); ?>
    </div>
<?php endif; ?>

<div id="formulario_cuerpo">
          <div>
            <label for="Cedula">Cédula de Identidad (*):</label>
            <input type="text" id="Cedula" name="ci_profesor" 
                               value="<?php echo htmlspecialchars($ci_profesor); ?>" 
                               autocomplete="off" maxlength="11" placeholder="C.I" required>
            <label for="Nombres">Nombres (*):</label>
            <input type="text" id="Nombres" name="nombres" 
                               value="<?php echo htmlspecialchars($nombres); ?>" 
                               autocomplete="off" maxlength="32" placeholder="Primer y Segundo Nombre" required>
            <label for="Apellidos">Apellidos (*):</label>
            <input type="text" id="Apellidos" name="apellidos" 
                               value="<?php echo htmlspecialchars($apellidos); ?>" 
                               autocomplete="off" maxlength="32" placeholder="Primer y Segundo Apellido" required>
            <label for="Telefono">Telefono:</label>
            <input type="text" id="Telefono" name="telefono" 
                               value="<?php echo htmlspecialchars($telefono); ?>" 
                               autocomplete="off" maxlength="11" placeholder="Número Teléfonico">
          </div>
          <div>
            <label for="Email">Correo Electrónico:</label>
            <input type="email" id="Email" name="email" 
                               value="<?php echo htmlspecialchars($email); ?>" 
                               autocomplete="off" maxlength="32" placeholder="E-mail">
            <label for="Domicilio">Domicilio:</label>
            <input type="text" id="Domicilio" name="domicilio" 
                               value="<?php echo htmlspecialchars($domicilio); ?>" 
                               autocomplete="off" maxlength="62" placeholder="Dirección">
            
                        <label for="Profesion">Profesión (*):</label>
            <input type="text" list="Profesiones" name="profesion_nombre" id="Profesion" 
                               value="<?php echo htmlspecialchars($nombre_profesion_input ?? ''); ?>"
                               autocomplete="off" placeholder="Seleccione o escriba la Profesión" required>
            
                                    <datalist id="Profesiones">
                            <?php foreach ($profesiones_list as $profesion): ?>
                  <option value="<?php echo htmlspecialchars($profesion['nombre_profesion']); ?>">
                            <?php endforeach; ?>
            </datalist>
          </div>
        </div>
                <?php include 'includes/b_form.php' ?>
            </form>
        </main>
    </body>
</html>