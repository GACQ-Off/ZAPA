<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "vertex"; 
$conn_notificaciones = new mysqli($servername, $username, $password, $dbname);
if ($conn_notificaciones->connect_error) {
    die("Conexión fallida: " . $conn_notificaciones->connect_error);
}
?>