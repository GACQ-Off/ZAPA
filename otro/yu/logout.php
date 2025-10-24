<?php

session_start();

session_unset();

session_destroy();


echo "<script>
        alert('La sesion a finalizado');
        window.location.href = 'ingreso.php';
      </script>";
?>
