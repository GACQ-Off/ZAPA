<?php
	session_start();
	include "../conexion.php";
	$sql_query=mysqli_query($conexion, "SELECT * FROM Tipo_Usuario");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<?php include "includes/scripts.php"; ?>
	<title>Reporte</title>
</head>
<body>
<?php include "includes/header.php"; ?>
<section id="container">
	<div class="form_register"
	<h1>Generar Reporte de Usuarios</h1>
	<form action="Reporte_Usuarios.php" method="post" autocomplete="off">
		<label for="Tipo_Usuario">Selecciona Tipo de Usuario</label>
		<select name="Tipo_Usuario" id="Tipo_Usuario">
			<?php while ($fila=$sql_query->fetch_assoc()){?>
				<option value="<?php echo $fila['Id_Tipo_Usuario']; ?>"> <?php echo $fila['Descripcion_Tipo_Usuario']; ?> </option>

					<?php }?>
		</select><br>
		<button type="submit">Generar</button>

	</form>
	</div>
</section>
</body>
</html>