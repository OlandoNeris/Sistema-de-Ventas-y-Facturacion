<?php 
	session_start();
	include '../coneccion.php';

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

	<section id="container_listado">
		<h1><i class="fas fa-address-card"></i>  Listado de Clientes</h1>
		<a href="registro_clientes.php" class="btn_nuevo"><i class="fas fa-plus"></i>  Nuevo Cliente</a>
		
		<form action="buscar_cliente.php" method="get" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_buscar"><i class="fas fa-search fa-lg"></i></button>
		</form>

		<div class="recuadro_listado">
		<table>
			<tr>
				<th>ID</th>
				<th>NOMBRE</th>
				<th>APELLIDO</th>
				<th>USUARIO</th>
				<th>CORREO</th>
				<th>TELEFONO</th>
				<th>DIRECCION</th>
				<th>ACCIONES</th>
			</tr>

			<?php 
					// PAGINADOR     

				$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registros FROM cliente WHERE estado = 1");
				
				$sql_resultado = mysqli_fetch_array($sql_registro);
				$totalRegistros = $sql_resultado['total_registros'];

					// cantidad de registros por pagina

				$porPagina = 8;

				if (empty($_GET['pagina']))
				{
					$pagina=1;						
				}else{
					$pagina = $_GET['pagina'];
				}


				$desde = ($pagina - 1) * $porPagina;
				$total_paginas = ceil($totalRegistros / $porPagina);

				$query = mysqli_query($conn,"SELECT * FROM cliente WHERE estado = 1 ORDER BY idcliente ASC LIMIT $desde,$porPagina");
				mysqli_close($conn);

				$result = mysqli_num_rows($query);

				if($result > 0)
				{
					while($datos = mysqli_fetch_array($query))
					{
			 ?>
			<tr>
				<td><?php echo $datos['idcliente']; ?></td>
				<td><?php echo $datos['nombre']; ?></td>
				<td><?php echo $datos['apellido']; ?></td>
				<td><?php echo $datos['dni_cliente']; ?></td>
				<td><?php echo $datos['correo']; ?></td>
				<td><?php echo $datos['telefono']; ?></td>
				<td><?php echo $datos['direccion']; ?></td>
				<td>
					<a class="link_edit" href="editar_cliente.php?id=<?php echo $datos['idcliente']; ?>"><i class="fas fa-user-edit"></i> Editar</a> 
					
					<?php if ($_SESSION['idrol'] == 1) { ?>
					|  <a class="link_delete" href="eliminar_cliente.php?id=<?php echo $datos['idcliente']; ?>"><i class="fas fa-user-times"></i>  Eliminar</a>
					<?php } ?>

				</td>
			</tr>
			<?php 
					
					}
				}


			 ?>

		</table>
		</div>

		<div class="paginador">
			<ul>
				<?php 	

				if ($pagina != 1)
				 {				
			
				 ?>
				<li><a href="?pagina=<?php 	echo 1; ?>"><i class="fas fa-fast-backward"></i></a></li>				
				<li><a href="?pagina=<?php 	echo $pagina - 1; ?>"><i class="fas fa-backward"></i></a></li>

				<?php
					} 	
					for ($i=1; $i <= $total_paginas; $i++) { 
						# code...

						if ($i == $pagina) {
							# code...
							echo '<li class="pageSelected">'.$i.'</li>';
						}else{

							echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
						}						
					}

					if ($pagina != $total_paginas)
					{
				?>				

				<li><a href="?pagina=<?php 	echo $pagina + 1 ?>"><i class="fas fa-forward"></i></a></li>
				<li><a href="?pagina=<?php 	echo $total_paginas; ?>"><i class="fas fa-fast-forward"></i></a></li>
				<?php 	} ?>
			</ul>
			
		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>