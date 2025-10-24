<?php 
	//session_start();
	session_start();
	if($_SESSION['Tipo_Usuario'] != 1)
	{
		header("location: ./");
	}
	include "../conexion.php";
	//Eliminar Registro
	if(!empty($_POST))
	{
		if($_POST['CedulaUsuario']==16418498)
		{
			header("Location: lista_usuarios.php");	
			mysqli_close($conexion);
			exit;
		}
		$CedulaUsuario=$_POST['CedulaUsuario'];
		//$query_eliminar=mysqli_query($conexion,"DELETE FROM Usuario WHERE Cedula_Usuario=$CedulaUsuario");
		$query_eliminar=mysqli_query($conexion, "UPDATE Usuario SET Estatus_Usuario=0 WHERE Cedula_Usuario=$CedulaUsuario");
		mysqli_close($conexion);
		if($query_eliminar)
		{
			header("Location: lista_usuarios.php");
		}
		else
		{
			echo "Error al Eliminar";
		}
	}	
	//Mostrar Datos del Registro
	if(empty($_REQUEST['Cedula'])||$_REQUEST['Cedula']==16418498)
	{
		header("Location: lista_usuarios.php");
		mysqli_close($conexion);
	}
	else
	{
		$CedulaUsuario=$_REQUEST['Cedula'];
		$query=mysqli_query($conexion, "SELECT u.Nombres_Usuario,u.Apellidos_Usuario,u.Usuario,t.Descripcion_Tipo_Usuario FROM Usuario u INNER JOIN Tipo_Usuario t ON u.Codigo_Tipo_Usuario=t.Id_Tipo_Usuario WHERE u.Cedula_Usuario=$CedulaUsuario");
		mysqli_close($conexion);
		$result=mysqli_num_rows($query);
		if($result>0)
		{
			while ($Datos=mysqli_fetch_array($query)) 
			{
				$NombresUsuario=$Datos['Nombres_Usuario'];
				$ApellidosUsuario=$Datos['Apellidos_Usuario'];
				$Usuario=$Datos['Usuario'];
				$TipoUsuario=$Datos['Descripcion_Tipo_Usuario'];
				# code...
			}
		}
		else
		{
			header("Location: lista_usuarios.php");
		}
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar Usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<div class="data_delete">
			<h2>¿Está seguro de eliminar el siguiente registro?</h2>
			<p>Nombres:<span><?php echo $NombresUsuario;?></span></p>
			<p>Apellidos:<span><?php echo $ApellidosUsuario;?></span></p>
			<p>Usuario:<span><?php echo $Usuario;?></span></p>
			<p>Tipo Usuario:<span><?php echo $TipoUsuario;?></span></p>
			<form method="post" action="">
				<input type="hidden" name="CedulaUsuario" value="<?php echo $CedulaUsuario?>">
				<a href="lista_usuarios.php" class="btn_cancel">Cancelar</a>
				<input type="submit" value="Aceptar" class="btn_ok">
			</form>
			
		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>