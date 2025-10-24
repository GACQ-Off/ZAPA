<?php
if (!isset($_SESSION['id_usuario']) || $_SESSION['id_usuario'] !== '1') {
    header("Location: ../ingreso.php");
    exit();
}
?>