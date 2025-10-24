<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vertex";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

?>