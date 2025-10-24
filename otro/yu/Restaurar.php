<html>

<head>
</head>

<body>

        <?php 
        session_start();
            include "conexion/conexion.php";
            if(!empty($_POST))
            {
                require ("Importar_BD.php");
            }
            

         ?>
            <form action="" method="post" class="formulario_1">
                <h2 class="title">Restaurar Base Datos</h2>
                        <label for="Nombre" class="text">Archivo:</label>
                        <input type="file" required="" id="bd" name="bd" class="input">
                        <a href="basededatos.php" class="btn btn--cancel">Regresar</a>
                        <input type="submit" value="Importar" class="input">
            </form>
</body>

</html>
