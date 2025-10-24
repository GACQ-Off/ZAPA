<?php
	session_start();
	include "../conexion.php";
	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['Cedula']) || empty($_POST['Nombres']) || empty($_POST['Apellidos']) || empty($_POST['Telefono']) || empty($_POST['Direccion']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}
		else
		{
			$Cedula = $_POST['Cedula'];
			$Nombres = ucwords(utf8_decode($_POST['Nombres']));
			$Apellidos = ucwords(utf8_decode($_POST['Apellidos']));
			$Telefono = $_POST['Telefono'];
			$Correo  = $_POST['Correo'];
			$Direccion   = ucwords(utf8_decode($_POST['Direccion']));
			$Cedula_Usuario = $_SESSION['Cedula'];

			$query = mysqli_query($conexion,"SELECT * FROM Cliente WHERE Cedula_Cliente = '$Cedula'");
			$result = mysqli_fetch_array($query);

			if($result > 0)
			{
				$alert='<p class="msg_error">La Cédula ya existe.</p>';
			}
			else
			{

				$query_insert = mysqli_query($conexion,"INSERT INTO Cliente(Cedula_Cliente,Nombres_Cliente,Apellidos_Cliente,Telefono_Cliente,Correo_Cliente, Direccion_Cliente,Usuario_Cedula)
																	VALUES('$Cedula','$Nombres','$Apellidos','$Telefono','$Correo','$Direccion','$Cedula_Usuario')");
				if($query_insert)
				{
					$alert='<p class="msg_save">Cliente Guardado correctamente.</p>';
				}
				else
				{
					$alert='<p class="msg_error">Error al Guardar el Cliente.</p>';
				}

			}


		}
		mysqli_close($conexion);
	}

 ?>
 <script>
	function SoloLetras(e)
	{
		key=e.keyCode || e.which;
		tecla=String.fronCharCode(key).toString();
		Letras="ABCDEFGHIJKLMNÑOPQRSTUVWXYZabcdefghijklmnñopqrstuvwyzáéíóúÁÉÍÓÚ";
		especiales=[8,13];
		tecla_especial=false
		for (var i in especiales ) 
		{
			if (key==especiales[i])
			{
				tecla_especial=true;
				break;
			}
		}
		if(Letras.indexOf(tecla)==-1 && !tecla_especial)
		{
			alert("Ingresar solo letras");
			return false;
		}
	}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Cliente</title>
	
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1>Registro Cliente</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="Cedula">Cédula</label>
				<input type="text" name="Cedula" id="Cedula" placeholder="Cédula">
				<label for="Nombres">Nombres</label>
				<input type="text" onkeypress="return SoloLetras(event);" required="" name="Nombres" id="Nombres" placeholder="Nombres completos">
				<label for="Apellidos">Apellidos</label>
				<input type="text" name="Apellidos" id="Apellidos" placeholder="Apellidos completos">
				<label for="Telefono">Teléfono</label>
				<input type="text" name="Telefono" id="Telefono" placeholder="Teléfono">
				<label for="Correo">Correo Electrónico</label>
				<input type="email" name="Correo" id="Correo" placeholder="Correo Electrónico">
				<label for="Direccion">Dirección</label>
				<input type="text" name="Direccion" id="Direccion" placeholder="Dirección Completa">
				<input type="submit" value="Crear Cliente" class="btn_save">

			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>