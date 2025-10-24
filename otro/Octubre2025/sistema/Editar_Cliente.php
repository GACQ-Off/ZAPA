<?php 
	session_start();
	
	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['Nombres']) || empty($_POST['Apellidos']) || empty($_POST['Telefono']) || empty($_POST['Direccion']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}
		else
		{//#2980B9 azul

			$CedulaCliente    = $_POST['Cedula'];
			$NombresCliente   = ucwords(utf8_decode($_POST['Nombres']));
			$ApellidosCliente = ucwords(utf8_decode($_POST['Apellidos']));
			$TelefonoCliente  = $_POST['Telefono'];
			$CorreoCliente    = $_POST['Correo'];
			$DireccionCliente = $_POST['Direccion'];
			
			$sql_Actualizar=mysqli_query($conexion, "UPDATE Cliente
															 SET Nombres_Cliente='$NombresCliente',Apellidos_Cliente='$ApellidosCliente', Telefono_Cliente='$TelefonoCliente', Correo_Cliente='$CorreoCliente', Direccion_Cliente='$DireccionCliente'
															 WHERE Cedula_Cliente=$CedulaCliente");	
	

				if($sql_Actualizar)
				{
					$alert='<p class="msg_save">Cliente Actualizado correctamente.</p>';
				}
				else
				{
					$alert='<p class="msg_error">Error al Actualizar el Cliente.</p>';
				}

		}
	}
	// Mostrar Datos
	if(empty($_REQUEST["Cedula"]))
	{
		header('Location: Lista_Clientes.php');
		mysqli_close($conexion);
	}
	$CedulaCliente=$_REQUEST['Cedula'];
	$sql=mysqli_query($conexion, "SELECT *FROM Cliente WHERE Cedula_Cliente='$CedulaCliente'");
	mysqli_close($conexion);
	$result_sql=mysqli_num_rows($sql);
	if ($result_sql==0)
	{
		header('location: Lista_Clientes.php');
	}
	else
	{	
		while($Datos = mysqli_fetch_array($sql))
		{
			$CedulaCliente=$Datos['Cedula_Cliente'];
			$NombresCliente=$Datos['Nombres_Cliente'];
			$ApellidosCliente=$Datos['Apellidos_Cliente'];
			$TelefonoCliente=$Datos['Telefono_Cliente'];
			$CorreoCliente=$Datos['Correo_Cliente'];
			$DireccionCliente=$Datos['Direccion_Cliente'];

		}
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Cliente</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<input type="hidden" name="Cedula" value="<?php echo $CedulaCliente;?>">
				<label for="Nombres">Nombres</label>
				<input type="text" name="Nombres" id="Nombres" placeholder="Nombres completos" value="<?php echo utf8_encode($NombresCliente);?>">
				<label for="Apellidos">Apellidos</label>
				<input type="text" name="Apellidos" id="Apellidos" placeholder="Apellidos completos" value="<?php echo utf8_encode($ApellidosCliente);?>">
				<label for="Telefono">Teléfono</label>
				<input type="text" name="Telefono" id="Telefono" placeholder="Teléfono" value="<?php echo $TelefonoCliente;?>">
				<label for="Correo">Correo Electrónico</label>
				<input type="email" name="Correo" id="Correo" placeholder="Correo Electrónico" value="<?php echo $CorreoCliente;?>">
				<label for="Direccion">Dirección</label>
				<input type="text" name="Direccion" id="Direccion" placeholder="Dirección Completa" value="<?php echo $DireccionCliente;?>">
				<input type="submit" value="Actualizar Cliente" class="btn_save">

			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>