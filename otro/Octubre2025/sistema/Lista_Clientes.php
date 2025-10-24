<?php 
	session_start();
	include "../conexion.php";	

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de Clientes</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<h1><i class="fa-solid fa-users"></i> Lista de Clientes</h1>
		<a href="Registro_Cliente.php" class="btn_new">Crear Cliente</a>
		
		<form action="buscar_cliente.php" method="GET" class="form_search">
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
				<th>Dirección</th>
				<th>Acciones</th>
			</tr>
		<?php 
			//Paginador
			//este query es para determinar el total de registrosc|
			$sql_registro = mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM Cliente");
			$result_registro = mysqli_fetch_array($sql_registro); // se almacena el número de registros 
			$total_registro = $result_registro['total_registro']; // se guarda el total de registros

			$por_pagina = 5;// esta variable permitirá mostrar el número de registro por página

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina); /* permite mostrar la cantidad de números en la parte de abajo y la función ceil lo que hace es redondear para que no quede un número decimal y asi determinar el numero de páginas a mostrar */
			$query = mysqli_query($conexion,"SELECT* FROM Cliente ORDER BY Cedula_Cliente ASC LIMIT $desde, $por_pagina ");
			mysqli_close($conexion);
			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					
			?>
				<tr>
					<td><?php echo $data["Cedula_Cliente"]; ?></td>
					<td><?php echo utf8_encode($data["Nombres_Cliente"]); ?></td>
					<td><?php echo utf8_encode($data["Apellidos_Cliente"]); ?></td>
					<td><?php echo $data["Telefono_Cliente"]; ?></td>
					<td><?php echo $data["Correo_Cliente"]; ?></td>
					<td><?php echo $data["Direccion_Cliente"]; ?></td>
					<td>
				<a class="link_edit" href="Editar_Cliente.php?Cedula=<?php echo $data["Cedula_Cliente"];?>"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
				<?php if ($_SESSION['Tipo_Usuario'] == 1 || $_SESSION['Tipo_Usuario'] == 2){?>
				|
				<a class="link_delete" href="eliminar_confirmar_cliente.php?Cedula=<?php echo $data["Cedula_Cliente"]; ?>"><i class="fa-solid fa-trash-can"></i> Eliminar</a>
				<?php }?>	
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