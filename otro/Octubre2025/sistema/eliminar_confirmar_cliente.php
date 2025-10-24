<?php 
	//session_start();
	session_start();
	if($_SESSION['Tipo_Usuario'] != 1 and $_SESSION['Tipo_Usuario'] != 2)
	{
		header("location: ./");
	}
	include "../conexion.php";
	//Eliminar Registro
	if(!empty($_POST))
	{
		if(empty($_POST['CedulaCliente']))
		{
			header("Location: Lista_Clientes.php");
			mysqli_close($conexion);
		}
		$CedulaCliente=$_POST['CedulaCliente'];
		$query_eliminar=mysqli_query($conexion,"DELETE FROM Cliente WHERE Cedula_Cliente=$CedulaCliente");
		//$query_eliminar=mysqli_query($conexion, "UPDATE Cliente SET Estatus_Cliente=0 WHERE Cedula_Cliente=$CedulaCliente");
		mysqli_close($conexion);
		if($query_eliminar)
		{
			header("Location: Lista_Clientes.php");
		}
		else
		{
			echo "Error al Eliminar";
		}
	}	
	//Mostrar Datos del Registro
	if(empty($_REQUEST['Cedula']))
	{
		header("Location: Lista_Clientes.php");
		mysqli_close($conexion);
	}
	else
	{
		$CedulaCliente=$_REQUEST['Cedula'];
		$query=mysqli_query($conexion, "SELECT *FROM Cliente WHERE Cedula_Cliente=$CedulaCliente");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if($result>0)
		{
			while ($Datos=mysqli_fetch_array($query)) 
			{
				$NombresCliente=$Datos['Nombres_Cliente'];
				$ApellidosCliente=$Datos['Apellidos_Cliente'];
				$TelefonoCliente=$Datos['Telefono_Cliente'];
				# code...
			}
		}
		else
		{
			header("Location: Lista_Clientes.php");
		}
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar Cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Nombres:<span><?php echo $NombresCliente;?></span></p>
			<p>Apellidos:<span><?php echo $ApellidosCliente;?></span></p>
			<p>Teléfono:<span><?php echo $TelefonoCliente;?></span></p>
			<form method="post" action="">
				<input type="hidden" name="CedulaCliente" value="<?php echo $CedulaCliente;?>">
				<a href="Lista_Clientes.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Eliminar" class="btn_ok">
			</form>
			
		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>