<?php 
	session_start();
	if($_SESSION['Tipo_Usuario'] != 1)
	{
		header("location: ./");
	}
	
	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['Cedula']) || empty($_POST['Nombres']) || empty($_POST['Apellidos']) || empty($_POST['Telefono']) ||empty($_POST['Correo']) || empty($_POST['Usuario']) || empty($_POST['Tipo_Usuario']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}
		else
		{//#2980B9 azul

			$Cedula = $_POST['Cedula'];
			$Nombres = $_POST['Nombres'];
			$Apellidos = $_POST['Apellidos'];
			$Telefono = $_POST['Telefono'];
			$Correo  = $_POST['Correo'];
			$Usuario   = $_POST['Usuario'];
			$Clave  = $_POST['Clave'];
			$Tipo_Usuario    = $_POST['Tipo_Usuario'];


			$query = mysqli_query($conexion,"SELECT * FROM Usuario 
													  WHERE (Usuario = '$Usuario' AND Cedula_Usuario !='$Cedula')
													  OR (Correo_Usuario = '$Correo' AND Cedula_Usuario !='$Cedula') ");
			$result = mysqli_fetch_array($query);
			$result = count($result);
			if($result > 0)
			{
				$alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
			}else
			{	if(empty($_POST['Clave']))
				{
					$sql_Actualizar=mysqli_query($conexion, "UPDATE Usuario
															 SET Nombres_Usuario='$Nombres',Apellidos_Usuario='$Apellidos', Telefono_Usuario='$Telefono', Correo_Usuario='$Correo', Usuario='$Usuario',Codigo_Tipo_Usuario='$Tipo_Usuario'
															 WHERE Cedula_Usuario=$Cedula");
				}
				else
				{
					$sql_Actualizar=mysqli_query($conexion, "UPDATE Usuario
															 SET Nombres_Usuario='$Nombres',Apellidos_Usuario='$Apellidos', Telefono_Usuario='$Telefono', Correo_Usuario='$Correo', Usuario='$Usuario',Clave_Usuario='$Clave',Codigo_Tipo_Usuario='$Tipo_Usuario'
															 WHERE Cedula_Usuario=$Cedula");	
				}

				if($sql_Actualizar)
				{
					$alert='<p class="msg_save">Usuario Actualizado correctamente.</p>';
				}
				else
				{
					$alert='<p class="msg_error">Error al actualizar el usuario.</p>';
				}

			}
		}
	}
	// Mostrar Datos
	if(empty($_REQUEST["Cedula"]))
	{
		header('Location: lista_usuarios.php');
		mysqli_close($conexion);
	}
	$CedulaUsuario=$_REQUEST['Cedula'];
	$sql=mysqli_query($conexion, "SELECT u.Cedula_Usuario,u.Nombres_Usuario,u.Apellidos_Usuario,u.Telefono_Usuario,u.Correo_Usuario,u.Usuario,(u.Codigo_Tipo_Usuario) as Id_Tipo_Usuario,(t.Descripcion_Tipo_Usuario) as Descripcion_Tipo_Usuario
									FROM Usuario u
									INNER JOIN Tipo_Usuario t
									on u.Codigo_Tipo_Usuario=t.Id_Tipo_Usuario
									WHERE u.Cedula_Usuario='$CedulaUsuario'");
	mysqli_close($conexion);
	$result_sql=mysqli_num_rows($sql);
	if ($result_sql==0)
	{
		header('location: lista_usuarios.php');
	}
	else
	{	$opcion='';
		while($Datos = mysqli_fetch_array($sql))
		{
			$CedulaUsuario=$Datos['Cedula_Usuario'];
			$NombresUsuario=$Datos['Nombres_Usuario'];
			$ApellidosUsuario=$Datos['Apellidos_Usuario'];
			$TelefonoUsuario=$Datos['Telefono_Usuario'];
			$CorreoUsuario=$Datos['Correo_Usuario'];
			$Usuario=$Datos['Usuario'];
			$IdTipoUsuario=$Datos['Id_Tipo_Usuario'];
			$TipoUsuario=$Datos['Descripcion_Tipo_Usuario'];
			if($IdTipoUsuario==1){
				$opcion='<option value="'.$IdTipoUsuario.'" select>'.$TipoUsuario.'</option>';
			}else if($IdTipoUsuario==2){
				$opcion='<option value="'.$IdTipoUsuario.'" select>'.$TipoUsuario.'</option>';
			}else if ($IdTipoUsuario==3){
				$opcion='<option value="'.$IdTipoUsuario.'" select>'.$TipoUsuario.'</option>';
			}

		}
	}

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1>Actualizar Usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="Cedula">Cédula</label>
				<input type="text" name="Cedula" id="Cedula" placeholder="Cédula" value="<?php echo $CedulaUsuario?>">
				<label for="Nombres">Nombres</label>
				<input type="text" name="Nombres" id="Nombres" placeholder="Nombres completos" value="<?php echo $NombresUsuario?>">
				<label for="Apellidos">Apellidos</label>
				<input type="text" name="Apellidos" id="Apellidos" placeholder="Apellidos completos" value="<?php echo $ApellidosUsuario?>">
				<label for="Telefono">Teléfono</label>
				<input type="text" name="Telefono" id="Telefono" placeholder="Teléfono" value="<?php echo $TelefonoUsuario?>">
				<label for="Correo">Correo Electrónico</label>
				<input type="email" name="Correo" id="Correo" placeholder="Correo Electrónico" value="<?php echo $CorreoUsuario?>">
				<label for="Usuario">Usuario</label>
				<input type="text" name="Usuario" id="Usuario" placeholder="Usuario" value="<?php echo $Usuario?>">
				<label for="Clave">Clave</label>
				<input type="password" name="Clave" id="Clave" placeholder="Clave de Acceso">
				<label for="Tipo_Usuario">Tipo Usuario</label>

				<?php 
					include "../conexion.php";
					$query_rol = mysqli_query($conexion,"SELECT * FROM Tipo_Usuario");
					mysqli_close($conexion);
					$result_rol = mysqli_num_rows($query_rol);

				 ?>

				<select name="Tipo_Usuario" id="Tipo_Usuario" class="notItemOne">
					<?php 
						echo $opcion;
						if($result_rol > 0)
						{
							while ($Tipo_Usuario = mysqli_fetch_array($query_rol)) {
					?>
							<option value="<?php echo $Tipo_Usuario["Id_Tipo_Usuario"]; ?>"><?php echo $Tipo_Usuario["Descripcion_Tipo_Usuario"] ?></option>
					<?php 
								# code...
							}
							
						}
					 ?>
				</select>
				<input type="submit" value="Actualizar Usuario" class="btn_save">

			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>