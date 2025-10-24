<?php 
	session_start();
	include "../conexion.php";
	if(!empty($_POST))
	{
		require ("Exportar_BD.php");
	}
	

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Exportar</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1>Exportar</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				
				<button type="submit" id="Respaldar" name="Respaldar">Respaldar Base de Datos</button> 
			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>