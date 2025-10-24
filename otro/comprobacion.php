<?php

$con = mysqli_connect('localhost', 'root', '', 'sistema');

$cedula = $_POST['Cedula'];
$nombre = $_POST['Nombre'];
$apellido = $_POST['Apellido'];
$correo = $_POST['Correo'];
$contrasena = $_POST['Contrasena'];

$sql = "INSERT INTO usuarios (Cedula_us, Nombres_us, Apellidos_us, Correo_us, Contrasena_us) VALUES ('$cedula', '$nombre', '$apellido', '$correo', '$contrasena')";

$rs = mysqli_query($con, $sql);

if ($rs) {
    echo "....................................Enviado correctamente";
} else {
    echo "....................................error";
};

?>