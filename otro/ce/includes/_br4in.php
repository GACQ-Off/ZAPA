<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();}
define('DB_SERVER', 'localhost');
define('DB_USUARIO', 'root');
define('DB_CONTRASENIA', '');
define('DB_NOMBRE', 'educacion_estudio');
$db = new mysqli(DB_SERVER, DB_USUARIO, DB_CONTRASENIA, DB_NOMBRE);
if($db->connect_error){
        die("ERROR de Conexión a DB: " . $db->connect_error);}
$db->set_charset("utf8mb4");
$header = '';
$boton = '';
$place = '';
$form = '';
$back = '';
$form_h = '';
$desc = '';
switch ($iu) {
    case 1:
        $header = 'Listado de Profesores';
        $boton = 'Registrar Profesor';
        $desc = 'En esta pantalla se encuentran los datos personales de los distintos docentes registrados.';
        $place = 'Buscar por Nombres, Apellidos, Profesión ...';
        $form = 'form_t.php';
        $back = 'index.php';
        $form_h = 'Registro de Profesor';
        break;
    case 2:
        $header = 'Listado de Profesiones';
        $boton = 'Registrar Profesión';
        $desc = 'En esta pantalla se muestran los distintos datos asociados a las profesiones registrados.';
        $place = 'Buscar por Nombre, Descripción ...';
        $form = 'form_r.php';
        $back = 'list_r.php';
        $form_h = 'Registro de Profesión';
        break;
    default:
        break;}
?>