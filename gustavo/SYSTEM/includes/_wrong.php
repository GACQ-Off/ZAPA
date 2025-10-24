<?php 
if (!isset($_SESSION['status_usuario']) || $_SESSION['status_usuario'] === 0 || empty($_SESSION['active']) || $_SESSION['active'] === false) {
    header("location: ../index.php");
    exit();
} elseif (($_tabs_atr >= 10 && $_tabs_atr <= 13) && $_SESSION['rol_id'] != 2) {
    header("location: ../index.php");
    exit();}
?>