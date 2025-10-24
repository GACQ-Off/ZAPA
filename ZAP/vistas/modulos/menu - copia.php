<?php

$usuario = ControladorUsuarios::ctrMostrarUsuarios("cedula",$_SESSION["cedula"]);
$esAdmin = boolval($usuario["perfil"] == "Administrador");
?>


<aside class="main-sidebar"data-color="purple">

	 <section class="sidebar"data-color="purple">

		<ul class="sidebar-menu"data-color="purple">

			<?php if($esAdmin):?>
			<li class="active">
		    	
				<a href="configuracion">

					<i class="fa fa-cogs"></i>
					<span>Configuracion</span>

				</a>

			</li>
			<?php endif?>
			<?php if($esAdmin):?>
		    <li class="active">
		    	
				<a href="inicio">

					<i class="fa fa-home"></i>
					<span>Inicio</span>

				</a>

			</li>
			<?php endif?>
			<?php if($esAdmin):?>

			<li>
					<li class="treeview">

				<a href="#">

					<i class="fa fa-user"></i>
					
					<span>Personal</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">
					
					<li>

						<a href="usuarios">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Usuarios</span>

						</a>

					</li>

					<li>

						<a href="personal">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Empleados</span>

						</a>

					</li>
					
				

				</ul>

			</li>
			<?php endif?>
			<?php if($esAdmin):?>
			<li class="treeview">

				<a href="#">

					<i class="fa fa-th"></i>
					
					<span>Almacen</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">
					
					<li>

						<a href="categorias">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Categoria</span>

						</a>

					</li>

					<li>

						<a href="tipos">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Tipos</span>

						</a>

					</li>
					<li>

						<a href="marcas">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Marcas</span>

						</a>
						<li>

						<a href="colores">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Colores</span>

						</a>

					</li>
					<li>

						<a href="productos">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Productos</span>

						</a>

					</li>
					<li>

						<a href="extensiones/fpdf/73.php">
							
							<i class="fa fa-circle-o"></i>
							<span>Reporte de Inventario</span>

						</a>

					</li>
				

				</ul>

			</li>
		    <?php endif?>
			<?php if(true):?>

		   <li>

				<a href="clientes">

					<i class="fa fa-users"></i>
					<span>Clientes</span>

				</a>

			</li>
			<?php endif?>
			<?php if(true):?>
			<li class="treeview">

				<a href="#">

					<i class="fa fa-list-ul"></i>
					
					<span>Ventas</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>
				
				<ul class="treeview-menu">
					
					<li>

						<a href="ventas">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar ventas</span>

						</a>

					</li>

					<li>

						<a href="crear-venta">
							
							<i class="fa fa-circle-o"></i>
							<span>Crear venta</span>

						</a>

					</li>
					<li>

						<a href="extensiones/fpdf/index.php">
							
							<i class="fa fa-circle-o"></i>
							<span>Reporte de ventas</span>

						</a>

					</li>
				

				</ul>

			</li>
			<?php endif?>
			<?php if($esAdmin):?>
			<li>

				<a href="proveedor">

					<i class="fa fa-user"></i>
					<span>Proveedores</span>

				</a>

			</li>
			<?php endif?>
			<?php if($esAdmin):?>
		    <li>
		    <li class="active">
		    	
				<a href="mantenimiento">

					<i class="fa fa-database"></i>
					<span>Mantenimiento</span>

				</a>

			</li>
			<li class="treeview">

				<a href="#">

					<i class="fa fa-list-ul"></i>
					
					<span>Mantenimiento</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">
					
					<li>

						<a href="apartado">
							
							<i class="fa fa-circle-o"></i>
							<span>Exportar Base de Datos</span>

						</a>

					</li>

					<li>

						<a href="crear-apartado">
							
							<i class="fa fa-circle-o"></i>
							<span>Restaurar Base de Datos</span>

						</a>

					</li>
					
				

				</ul>

			</li>
			<?php endif?>
		</ul>

	 </section>

</aside>