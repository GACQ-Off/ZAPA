<?php

$usuario = ControladorUsuarios::ctrMostrarUsuarios("cedula",$_SESSION["cedula"]);
$esAdmin = boolval($usuario["perfil"] == "Administrador");
?>


<aside class="main-sidebar"data-color="purple">

	 <section class="sidebar"data-color="purple">

		<ul class="sidebar-menu"data-color="purple">

			<?php if($esAdmin):?>
			<li class="active">
		    	
				<a href="inicio">

					<i class="fa fa-home"></i>
					<span>Inicio</span>

				</a>

			</li>
			<?php endif?>
			<?php if($esAdmin):?>
		    <li class="active">
		    	
				<a href="configuracion">

					<i class="fa fa-cogs"></i>
					<span>Configuración</span>

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

					<li>

						<a href="eventos">
							
							<i class="fa fa-circle-o"></i>
							<span>Registro de Eventos</span>

						</a>

					</li>
					
				

				</ul>

			</li>
			<?php endif?>
			<?php if($esAdmin):?>
			<li class="treeview">

				<a href="#">

					<i class="fa fa-th"></i>
					
					<span>Almacén</span>
					
					<span class="pull-right-container">
					
						<i class="fa fa-angle-left pull-right"></i>

					</span>

				</a>

				<ul class="treeview-menu">
					
					<li>

						<a href="categorias">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Categoría</span>

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

						<a href="productos">
							
							<i class="fa fa-circle-o"></i>
							<span>Administrar Productos</span>

						</a>

					</li>
					<li>

						<a href="compras">
							
							<i class="fa fa-circle-o"></i>
							<span>Entradas de Mercancia</span>

						</a>

					</li>
					
					<li>

						<a href="extensiones/fpdf/plantilla2.php">
							
							<i class="fa fa-circle-o"></i>
							<span><i class="fa fa-file-pdf-o"></i> Reporte de Inventario</span>

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
					<?php endif?>
					<?php if($esAdmin):?>
					<li>

						<a href="crear-ventav2">
							
							<i class="fa fa-circle-o"></i>
							<span>Crear venta V2</span>

						</a>

					</li>

					<li>

						<a href="extensiones/fpdf/reported.php">
							
							<i class="fa fa-circle-o"></i>
							<span><i class="fa fa-file-pdf-o"></i> Reporte Diario</span>

						</a>

					</li>
					<li>

						<a href="extensiones/fpdf/plantillac.php">
							
							<i class="fa fa-circle-o"></i>
							<span><i class="fa fa-file-pdf-o"></i> Reporte Mensual</span>

						</a>

					</li>
					<li>

						<a href="extensiones/fpdf/plantillac2.php">
							
							<i class="fa fa-circle-o"></i>
							<span><i class="fa fa-file-pdf-o"></i> Reporte Total de Ventas</span>

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
			
					
				

				</ul>

			</li>
			<?php endif?>
		</ul>

	 </section>

</aside>
<script>
  const menuItems = document.querySelectorAll('.treeview-menu li');

  menuItems.forEach(item => {
    item.addEventListener('mouseover', () => {
      item.style.backgroundColor = '#3c8dbc'; // Change background color on hover
      item.style.color = '#fff'; // Change text color on hover
    });

    item.addEventListener('mouseout', () => {
      item.style.backgroundColor = ''; // Reset background color on mouseout
      item.style.color = ''; // Reset text color on mouseout
    });
  });
</script>
<script>
document.querySelectorAll('.sidebar-menu li a').forEach(link => {
  link.addEventListener('mouseover', () => {
    link.style.backgroundColor = '#3c8dbc';
    link.style.color = '#fff';
  });

  link.addEventListener('mouseout', () => {
    link.style.backgroundColor = '';
    link.style.color = '';
  });
});
</script>