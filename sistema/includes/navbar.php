		<nav>
			<ul>
				<li><a href="#"><i class="fas fa-home"></i> Inicio</a></li>
				<?php 

					if ($_SESSION['idrol'] == 1) 
					{
						
				?>

				<li class="principal">
					<a href="#"><i class="fas fa-users"></i>  Usuarios</a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fas fa-user-plus"></i>  Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fas fa-users"></i>  Lista de Usuarios</a></li>
					</ul>
				</li>
				<?php } ?>
				
				<li class="principal">
					<a href="#"><i class="fas fa-address-card"></i>  Clientes</a>
					<ul>
						<li><a href="registro_clientes.php"><i class="fas fa-plus"></i>   Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php"><i class="far fa-list-alt"></i>  Lista de Clientes</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Proveedores</a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fas fa-plus"></i>   Nuevo Proveedor</a></li>
						<li><a href="lista_proveedores.php"><i class="far fa-list-alt"></i>   Lista de Proveedores</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Productos</a>
					<ul>
						<li><a href="nuevo_producto.php">Nuevo Producto</a></li>
						<li><a href="sistema2/registro_producto_elaborado.php">Productos elaborados</a></li>
						<li><a href="lista_productos.php">Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#">Operaciones de Caja </a>
					<ul>
						<li><a href="nueva_venta.php">Nueva Venta</a></li>
						<li><a href="">Factura Compra</a></li>
						<li><a href="">Retiro Efectivo</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="caja.php">Caja </a>
				</li>
			</ul>
		</nav>