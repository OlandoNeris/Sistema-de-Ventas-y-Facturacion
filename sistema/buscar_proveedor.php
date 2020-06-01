<?php 

	session_start();

	if ($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2) {
		header("location: ./");
	} 

	include '../coneccion.php';

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"; ?>

	<title>Lista de Proveedores</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	<section id="container_listado">
		<?php 

			$busqueda = strtolower($_REQUEST['busqueda']);

			if (empty($busqueda)) 
			{
				header("location: lista_proveedores.php");

				mysqli_close($conn);
			}

		?>



		<h1>Listado de Proveedores</h1>
		<a href="registro_proveedor.php" class="btn_nuevo">Crear Proveedor</a>
		
		<form action="buscar_proveedor.php" method="get" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_buscar"><i class="fas fa-search fa-lg"></i></button>
		</form>

		<div class="recuadro_listado">
		<table>
			<tr>
				<th>ID</th>
				<th>PROVEEDOR</th>
				<th>CONTACTO</th>
				<th>CORREO</th>
				<th>TELEFONO</th>
				<th>DIRECCION</th>
				<th>ACCIONES</th>
			</tr>

			<?php 
					// PAGINADOR     

				$rol = '';

				$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registros FROM proveedor 
													 WHERE ( codproveedor LIKE '%$busqueda%' OR 
													 proveedor LIKE '%$busqueda%' OR 		
													 contacto LIKE '%$busqueda%' OR 
													 correo LIKE '%$busqueda%'  OR 
													 telefono LIKE '%$busqueda%') AND estado = 1");
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




				$query = mysqli_query($conn,"SELECT * FROM proveedor WHERE ( codproveedor LIKE '%$busqueda%' OR 
												 		proveedor LIKE '%$busqueda%' OR 
												 		contacto LIKE '%$busqueda%' OR 
												 		correo LIKE '%$busqueda%' OR 
												 		telefono LIKE '%$busqueda%' ) 
												 AND estado = 1 LIMIT $desde,$porPagina ");
				mysqli_close($conn);
				$result = mysqli_num_rows($query);

				if($result > 0)
				{
					while($datos = mysqli_fetch_array($query))
					{
			 ?>
			<tr>
				<td><?php echo $datos['codproveedor']; ?></td>
				<td><?php echo $datos['proveedor']; ?></td>
				<td><?php echo $datos['contacto']; ?></td>
				<td><?php echo $datos['correo']; ?></td>
				<td><?php echo $datos['telefono']; ?></td>
				<td><?php echo $datos['direccion']; ?></td>
				<td>
					<a class="link_edit" href="editar_proveedor.php?id=<?php echo $datos['codproveedor']; ?>"><i class="fas fa-user-edit"></i> Editar</a> 
					
				
					|  <a class="link_delete" href="eliminar_proveedor.php?id=<?php echo $datos['codproveedor']; ?>"><i class="fas fa-user-times"></i>  Eliminar</a>
				

				</td>
			</tr>
			<?php 
					
					}
				}


			 ?>

		</table>
		</div>
		<?php 

			if ($totalRegistros != 0) 
			{		

		?>
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

		<?php 

			}
		 ?>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>