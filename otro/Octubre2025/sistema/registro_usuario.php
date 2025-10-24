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
		if(empty($_POST['Cedula']) || empty($_POST['Nombres']) || empty($_POST['Apellidos']) || empty($_POST['Telefono']) ||empty($_POST['Correo']) || empty($_POST['Usuario']) || empty($_POST['Clave']) || empty($_POST['Tipo_Usuario']))
		{
			$alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
		}
		else
		{
			$Cedula = $_POST['Cedula'];
			$Nombres = ucwords($_POST['Nombres']);
			$Apellidos = $_POST['Apellidos'];
			$Telefono = $_POST['Telefono'];
			$Correo  = $_POST['Correo'];
			$Usuario   = $_POST['Usuario'];
			$Clave  = $_POST['Clave'];
			$Tipo_Usuario    = $_POST['Tipo_Usuario'];


			$query = mysqli_query($conexion,"SELECT * FROM Usuario WHERE Usuario = '$Usuario' OR Correo_Usuario = '$Correo' ");
			//mysqli_close($conexion);
			$result = mysqli_fetch_array($query);

			if($result > 0){
				$alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
			}else{

				$query_insert = mysqli_query($conexion,"INSERT INTO Usuario(Cedula_Usuario,Nombres_Usuario,Apellidos_Usuario,Telefono_Usuario,Correo_Usuario,Usuario,Clave_Usuario,Codigo_Tipo_Usuario)
																	VALUES('$Cedula','$Nombres','$Apellidos','$Telefono','$Correo','$Usuario','$Clave','$Tipo_Usuario')");
				if($query_insert){
					$alert='<p class="msg_save">Usuario creado correctamente.</p>';
				}else{
					$alert='<p class="msg_error">Error al crear el usuario.</p>';
				}

			}


		}

	}



 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Usuario</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fa-solid fa-user-plus"></i> Registro Usuario</h1>
			
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

			<form action="" method="post">
				<label for="Cedula">Cédula</label>
				<input type="text" name="Cedula" id="Cedula" placeholder="Cédula">
				<label for="Nombres">Nombres</label>
				<input type="text" name="Nombres" id="Nombres" placeholder="Nombres completos">
				<label for="Apellidos">Apellidos</label>
				<input type="text" name="Apellidos" id="Apellidos" placeholder="Apellidos completos">
				<label for="Telefono">Teléfono</label>
				<input type="text" name="Telefono" id="Telefono" placeholder="Teléfono">
				<label for="Correo">Correo Electrónico</label>
				<input type="email" name="Correo" id="Correo" placeholder="Correo Electrónico">
				<label for="Usuario">Usuario</label>
				<input type="text" name="Usuario" id="Usuario" placeholder="Usuario">
				<label for="Clave">Clave</label>
				<input type="password" name="Clave" id="Clave" placeholder="Clave de Acceso">
				<label for="Tipo_Usuario">Tipo Usuario</label>

				<?php 

					$query_rol = mysqli_query($conexion,"SELECT * FROM Tipo_Usuario");
					//mysqli_close($conexion);
					$result_rol = mysqli_num_rows($query_rol);

				 ?>

				<select name="Tipo_Usuario" id="Tipo_Usuario">
					<?php 
						if($result_rol > 0)
						{
							while ($Tipo_Usuario = mysqli_fetch_array($query_rol)) 
							{
					?>
							<option value="<?php echo $Tipo_Usuario["Id_Tipo_Usuario"]; ?>"><?php echo $Tipo_Usuario["Descripcion_Tipo_Usuario"] ?></option>
					<?php 
								# code...
							}
							
						}
					 ?>
				</select>
			<?	//<input type="submit" value="Crear usuario" class="btn_save">?>
				<button type="submit" class="btn_save"><i class="fa-regular fa-floppy-disk"></i> Crear Usuario</button>
			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>