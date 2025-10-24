		<nav>
			<ul>
				<li><a href="index.php"><i class="fa-solid fa-house"></i> Inicio</a></li>
			<?php 
				
				if($_SESSION['Tipo_Usuario'] == 1){
			 ?>
				<li class="principal">

					<a href="#"><i class="fa-solid fa-users"></i> Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fa-solid fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fa-solid fa-users"></i> Lista de Usuarios</a></li>
					</ul>
				</li>
			<?php }?>
				<li class="principal">

					<a href="#"><i class="fa-solid fa-users"></i> Clientes</a>
					<ul>
						<li><a href="Registro_Cliente.php"><i class="fa-solid fa-user-plus"></i> Nuevo Cliente</a></li>
						<li><a href="Lista_Clientes.php"><i class="fa-solid fa-users"></i> Lista de Clientes</a></li>
					</ul>
				</li>
				
				<li class="principal">

					<a href="#"><i class="fa-solid fa-print"></i> Reportes</a>
					<ul>
						<li><a href="Reporte_Clientes.php"><i class="fa-solid fa-table-list"></i> Clientes</a></li>
						<li><a href="Usuarios.php"><i class="fa-solid fa-table-list"></i> Usuarios</a></li>
					</ul>
				</li>
				<?php if($_SESSION['Tipo_Usuario'] == 1){
			 ?>
				<li class="principal">

					<a href="#"><i class="fa-solid fa-gear"></i> Mantenimiento</a>
					<ul>
						<li><a href="Respaldar.php"><i class="fa-solid fa-download"></i> Respaldar Base de Datos</a></li>
						<li><a href="Restaurar.php"><i class="fa-solid fa-upload"></i> Restaurar Base de Datos</a></li>
					</ul>
				</li>
				<?php }?>
			</ul>
		</nav>