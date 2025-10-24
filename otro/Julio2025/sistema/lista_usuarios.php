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
	<title><i class="fa-solid fa-users"></i> Lista de Usuarios</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<h1><i class="fa-solid fa-users"></i> Lista de Usuarios</h1>
		<a href="registro_usuario.php" class="btn_new">Crear Usuario</a>
		
		<form action="buscar_usuario.php" method="GET" class="form_search">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
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
			//este query es para determinar el total de registrosc|
			$sql_registro = mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM Usuario WHERE Estatus_Usuario = 1 ");
			$result_registro = mysqli_fetch_array($sql_registro); // se almacena el número de registros 
			$total_registro = $result_registro['total_registro']; // se guarda el total de registros

			$por_pagina = 10;// esta variable permitirá mostrar el número de registro por página

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina); /* permite mostrar la cantidad de números en la parte de abajo y la función ceil lo que hace es redondear para que no quede un número decimal y asi determinar el numero de páginas a mostrar */
			$query = mysqli_query($conexion,"SELECT u.Cedula_Usuario, u.Nombres_Usuario, u.Apellidos_Usuario, u.Telefono_Usuario, u.Correo_Usuario, u.Usuario, t.Descripcion_Tipo_Usuario FROM Usuario u INNER JOIN Tipo_Usuario t ON u.Codigo_Tipo_Usuario = t.Id_Tipo_Usuario WHERE Estatus_Usuario=1 ORDER BY Descripcion_Tipo_Usuario LIMIT $desde, $por_pagina ");
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
					<td><?php echo $data['Descripcion_Tipo_Usuario'] ?></td>
					<td>
				<a class="link_edit" href="Editar_Usuario.php?Cedula=<?php echo $data["Cedula_Usuario"];?>"><i class="fa-regular fa-pen-to-square"></i> Editar</a>

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
		<div class="paginador">
			<ul>
			<?php 
				if($pagina != 1)
				{
			 ?>
				<li><a href="?pagina=<?php echo 1; ?>">|<</a></li>
				<li><a href="?pagina=<?php echo $pagina-1; ?>"><<</a></li>
			<?php 
				}
				for ($i=1; $i <= $total_paginas; $i++) { 
					# code...
					if($i == $pagina)
					{
						echo '<li class="pageSelected">'.$i.'</li>';
					}else{
						echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
					}
				}

				if($pagina != $total_paginas)
				{
			 ?>
				<li><a href="?pagina=<?php echo $pagina + 1; ?>">>></a></li>
				<li><a href="?pagina=<?php echo $total_paginas; ?> ">>|</a></li>
			<?php } ?>
			</ul>
		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>