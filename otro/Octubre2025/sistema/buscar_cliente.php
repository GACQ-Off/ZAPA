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
		<?php 

			$busqueda = strtolower($_REQUEST['busqueda']); // se guarda el dato que viene de la URL
			            //la función strtolower convierte en minúscula el dato que se ha enviado por busqueda
			if(empty($busqueda))
			{
				header("location: Lista_Clientes.php");
				mysqli_close($conexion);
			}


		 ?>
		
		<h1><i class="fa-solid fa-users"></i> Lista de Clientes</h1>
		<a href="Registro_Cliente.php" class="btn_new">Crear Cliente</a>
		
		<form action="buscar_cliente.php" method="get" class="form_search">
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
				<th>Direccion</th>
				<th>Acciones</th>
			</tr>
		<?php 
			//Paginador
			
			$sql_registro = mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM Cliente 
																WHERE ( Cedula_Cliente LIKE '%$busqueda%' OR 
																		Nombres_Cliente LIKE '%$busqueda%' OR 
																		Apellidos_Cliente LIKE '%$busqueda%' OR
																		Telefono_Cliente LIKE '%$busqueda%' OR 
																		Correo_Cliente LIKE '%$busqueda%' OR 
																		Direccion_Cliente LIKE '%$busqueda%')");

			$result_registro = mysqli_fetch_array($sql_registro);
			$total_registro = $result_registro['total_registro'];

			$por_pagina = 2;

			if(empty($_GET['pagina']))
			{
				$pagina = 1;
			}else{
				$pagina = $_GET['pagina'];
			}

			$desde = ($pagina-1) * $por_pagina;
			$total_paginas = ceil($total_registro / $por_pagina);

			$query = mysqli_query($conexion,"SELECT *FROM Cliente 
										WHERE 
										( Cedula_Cliente LIKE '%$busqueda%' OR 
										Nombres_Cliente LIKE '%$busqueda%' OR 
										Apellidos_Cliente LIKE '%$busqueda%' OR
										Telefono_Cliente LIKE '%$busqueda%' OR 
										Correo_Cliente LIKE '%$busqueda%' OR 
										Direccion_Cliente LIKE '%$busqueda%')
										ORDER BY Cedula_Cliente ASC LIMIT $desde, $por_pagina");
			mysqli_close($conexion);
			$result = mysqli_num_rows($query);
			if($result > 0){

				while ($data = mysqli_fetch_array($query)) {
					
			?>
				<tr>
					<td><?php echo $data["Cedula_Cliente"]; ?></td>
					<td><?php echo $data["Nombres_Cliente"]; ?></td>
					<td><?php echo $data["Apellidos_Cliente"]; ?></td>
					<td><?php echo $data["Telefono_Cliente"]; ?></td>
					<td><?php echo $data["Correo_Cliente"]; ?></td>
					<td><?php echo $data["Direccion_Cliente"]; ?></td>
					<td>
						<a class="link_edit" href="Editar_Cliente.php?Cedula=<?php echo $data["Cedula_Cliente"]; ?>"><i class="fa-regular fa-pen-to-square"></i> Editar</a>
                        <?php if ($_SESSION['tipo_usuario'] == 1 || $_SESSION['tipo_usuario'] == 2){?>
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