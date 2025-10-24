<?php 
	
$alert = '';
	if(!empty($_POST))
	{
		if(empty($_POST['Usuario']) || empty($_POST['Clave']))
		{
			$alert = 'Ingrese su usuario y su clave';
		}
		else
		{
			require_once "conexion.php";
			$usuario = $_POST['Usuario'];
			$pass = $_POST['Clave'];

			$query = mysqli_query($conexion,"SELECT * FROM Usuario WHERE Usuario= '$usuario' AND Clave_Usuario = '$pass'");
			//mysqli_close($conexion);
			$result = mysqli_num_rows($query);

			if($result > 0)
			{
				$data = mysqli_fetch_array($query);
				session_start();
				$_SESSION['active'] = true;
				$_SESSION['Cedula'] = $data['Cedula_Usuario'];
				$_SESSION['Nombres'] = $data['Nombres_Usuario'];
				$_SESSION['Apellidos'] = $data['Apellidos_Usuario'];
				$_SESSION['Telefono'] = $data['Telefono_Usuario'];
				$_SESSION['Correo']  = $data['Correo_Usuario'];
				$_SESSION['Usuario']   = $data['Usuario'];
				$_SESSION['Tipo_Usuario']    = $data['Codigo_Tipo_Usuario'];

				header('location: sistema/');
			}
			else
			{
				$alert = 'El usuario o la clave son incorrectos';
			}


		}

	}
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login | Sistema Ventas</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<section id="container">
		
		<form action="" method="post">
			
			<h3>Iniciar Sesión</h3>
			<img src="img/login.png" alt="Login">

			<input type="text" name="Usuario" placeholder="Usuario">
			<input type="password" name="Clave" placeholder="Contraseña">
			<div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>
			<input type="submit" value="INGRESAR">

		</form>

	</section>
</body>
</html>