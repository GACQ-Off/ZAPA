<?php 
	session_start();
	if($_SESSION['Tipo_Usuario'] != 1)
	{
		header("location: ./");
	}

	include "../conexion.php";	

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de Usuarios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		<?php 

			$busqueda = strtolower($_REQUEST['busqueda']); // se guarda el dato que viene de la URL
			            //la función strtolower convierte en minúscula el dato que se ha enviado por busqueda
			if(empty($busqueda))
			{
				header("location: lista_usuarios.php");
				mysqli_close($conexion);
			}


		 ?>
		
		<h1><i class="fa-solid fa-users"></i> Lista de Usuarios</h1>
		<a href="registro_usuario.php" class="btn_new">Crear Usuario</a>
		
		<form action="buscar_usuario.php" method="get" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_search">
		</form>

		<table>
			<tr>
				<th>Cédula</th>
				<th>Nombres</th>
				<th>Apellidos</th>
				<th>Teléfono</th>
				<th>Correo</th>
				<th>Usuario</th>
				<th>Tipo Usuario</th>
				<th>Acciones</th>
			</tr>
		<?php 
			//Paginador
			$Tipo_Usuario = '';
			if($busqueda == 'administrador')
			{
				$Tipo_Usuario = " OR Codigo_Tipo_Usuario LIKE '%1%' ";

			}else if($busqueda == 'supervisor'){

				$Tipo_Usuario = " OR Codigo_Tipo_Usuario LIKE '%2%' ";

			}else if($busqueda == 'vendedor'){

				$Tipo_Usuario = " OR Codigo_Tipo_Usuario LIKE '%3%' ";
			}


			$sql_registro = mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM Usuario 
				WHERE ( Cedula_Usuario LIKE '%$busqueda%' OR Nombres_Usuario LIKE '%$busqueda%' OR 	Apellidos_Usuario LIKE '%$busqueda%' OR Telefono_Usuario LIKE '%$busqueda%' OR 
					Correo_Usuario LIKE '%$busqueda%' OR 
					Usuario LIKE '%$busqueda%'
					$Tipo_Usuario  ) 		AND Estatus_Usuario = 1  ");

			$result_registro = mysqli_fetch_array($sql_registro);
			$total_registro = $result_registro['total_registro'];

			$por_pagina = 5;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conexion,"SELECT u.Cedula_Usuario, u.Nombres_Usuario, u.Apellidos_Usuario, u.Telefono_Usuario, u.Correo_Usuario, u.Usuario, t.Descripcion_Tipo_Usuario FROM Usuario u INNER JOIN Tipo_Usuario t ON u.Codigo_Tipo_Usuario = t.Id_Tipo_Usuario 
										WHERE 
										( u.Cedula_Usuario LIKE '%$busqueda%' OR 
										u.Nombres_Usuario LIKE '%$busqueda%' OR 
										u.Apellidos_Usuario LIKE '%$busqueda%' OR
										u.Telefono_Usuario LIKE '%$busqueda%' OR 
										u.Correo_Usuario LIKE '%$busqueda%' OR 
										u.Usuario LIKE '%$busqueda%' OR
										t.Descripcion_Tipo_Usuario LIKE '%$busqueda%')
										AND 
										Estatus_Usuario=1 ORDER BY Cedula_Usuario ASC LIMIT $desde, $por_pagina");
			mysqli_close($conexion);
			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					
			?>
				<tr>
					<td><?php echo $data["Cedula_Usuario"]; ?></td>
					<td><?php echo $data["Nombres_Usuario"]; ?></td>
					<td><?php echo $data["Apellidos_Usuario"]; ?></td>
					<td><?php echo $data["Telefono_Usuario"]; ?></td>
					<td><?php echo $data["Correo_Usuario"]; ?></td>
					<td><?php echo $data["Usuario"]; ?></td>
					<td><?php echo $data['Descripcion_Tipo_Usuario']; ?></td>
					<td>
						<a class="link_edit" href="editar_usuario.php?id=<?php echo $data["Cedula_Usuario"]; ?>"><i class="fa-regular fa-pen-to-square"></i> Editar</a>

					<?php if($data["Cedula_Usuario"] != 16418498){ ?>
						|
						<a class="link_delete" href="eliminar_confirmar_usuario.php?Cedula=<?php echo $data["Cedula_Usuario"]; ?>"><i class="fa-solid fa-trash-can"></i> Eliminar</a>
					<?php } ?>
						
					</td>
				</tr>
			
		<?php 
				}

			}
		 ?>


		</table>
<?php 
	
	if($total_registro != 0)
	{
 ?>
		<div class="paginador">
			<ul>
			<?php 
				if($pagina != 1)
				{
			 ?>
				<li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
			<?php 
				}
				for ($i=1; $i <= $total_paginas; $i++) { 
					# code...
					if($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';
					}else{
						echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
					}
				}

				if($pagina != $total_paginas)
				{
			 ?>
				<li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?> ">>|</a></li>
			<?php } ?>
			</ul>
		</div>
<?php } ?>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>