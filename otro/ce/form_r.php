<?php 
$iu = 2;
require_once 'includes/_br4in.php'; 
$nombre_profesion = '';
$descripcion_profesion = '';
$error_mensaje = '';
$exito_mensaje = '';
$ci_usuario_logueado = $_SESSION['ci_usuario'] ?? NULL;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_profesion = trim($_POST['nombre_profesion'] ?? '');
    $descripcion_profesion = trim($_POST['descripcion_profesion'] ?? '');
    if (empty($nombre_profesion) || empty($descripcion_profesion)) {
        $error_mensaje = "Por favor, complete todos los campos obligatorios (*).";}
    if (empty($error_mensaje)) {
        $sql_check = "SELECT id_profesion FROM profesion WHERE nombre_profesion = ? AND status_profesion = 1";
        if ($sent_verificar = $db->prepare($sql_check)) {
            $sent_verificar->bind_param("s", $nombre_profesion);
            $sent_verificar->execute();
            $sent_verificar->store_result();
            if ($sent_verificar->num_rows > 0) {
                $error_mensaje = "Error: La profesión '{$nombre_profesion}' ya existe en el sistema.";}
            $sent_verificar->close();} 
        else {
            $error_mensaje = "Error al preparar la verificación de unicidad.";}}
    if (empty($error_mensaje)) {
        $sql_insertar = "INSERT INTO profesion (nombre_profesion, descripcion_profesion, status_profesion, usuario_ci) VALUES (?, ?, 1, ?)";
        if ($sent_insertar = $db->prepare($sql_insertar)) {
            $sent_insertar->bind_param("sss", $nombre_profesion, $descripcion_profesion, $ci_usuario);
            if ($sent_insertar->execute()) {
                $exito_mensaje = "Profesión registrada exitosamente.";
                header("list_r.php");
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
        <title>Profesión | Registro</title>
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
                                    <label for="Nombre">Nombre (*):</label>
            <input type="text" id="Nombre" name="nombre_profesion" 
                               value="<?php echo htmlspecialchars($nombre_profesion ?? ''); ?>" 
                               autocomplete="off" maxlength="32" placeholder="Nombre de la Profesión" required>

                        <label for="Descripcion">Descripción (*):</label>
            <textarea id="Descripcion" name="descripcion_profesion" 
                                  autocomplete="off" maxlength="64" placeholder="Descripción de la Profesión" required><?php echo htmlspecialchars($descripcion_profesion ?? ''); ?></textarea>
          </div>
        </div>
                <?php include 'includes/b_form.php' ?>

            </form>
        </main>
    </body>
</html>