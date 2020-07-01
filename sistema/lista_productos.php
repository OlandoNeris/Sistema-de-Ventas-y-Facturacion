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

	<title>Lista de Productos</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	<section id="container_listado">
		<h1><i class="fas fa-address-card"></i>  Listado de Productos</h1>
		<a href="registro_producto.php" class="btn_nuevo"><i class="fas fa-plus"></i>  Nuevo Producto</a>
		
		<form action="buscar_productos.php" method="get" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar">
			<button type="submit" class="btn_buscar"><i class="fas fa-search fa-lg"></i></button>
		</form>

		<div class="recuadro_listado">
		<table>
			<tr>
				<th>CODIGO</th>
				<th>DESCRIPCION</th>
				<th>PRECIO</th>
				<th>EXISTENCIA</th>
				<th> TIPO

				<?php /*
				
				$query_proveedor = mysqli_query($conn,"SELECT codproveedor, proveedor FROM proveedor WHERE estado = 1 ORDER BY proveedor ASC");
				$resultado = mysqli_num_rows($query_proveedor);

				 ?>

				<select name="proveedor" id="buscar_proveedor">
					<option value="" selected>PROVEEDOR </option>
					<?php 

					if ($resultado > 0) {
						# code...
						while ($proveedor = mysqli_fetch_array($query_proveedor)) {
							# code...
					?>
					<option value="<?php echo $proveedor['codproveedor'];?>"><?php echo $proveedor['proveedor'];?></option>
					<?php 
						}
					}


					 
									
				</select>
				*/
				?>
				</th>
				<th>FOTO</th>
				<th>ACCIONES</th>
			</tr>

			<?php 
					// PAGINADOR     

				$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registros FROM producto WHERE estado = 1");
				
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

				$query = mysqli_query($conn,"SELECT p.codproducto , 
													p.descripcion, 
													p.precio,
													p.stock,
													tp.descripcion as tipo,
													p.foto 
											 FROM producto as p INNER JOIN tipo_producto as tp ON p.tipo_producto = tp.id WHERE p.estado = 1 
											 ORDER BY descripcion ASC LIMIT $desde,$porPagina");
				mysqli_close($conn);

				$result = mysqli_num_rows($query);

				if($result > 0)
				{
					while($datos = mysqli_fetch_array($query))
					{
						$foto = 'img/uploads/'.$datos['foto'];

			 ?>
			<tr class="row<?php echo $datos['codproducto']; ?>">
				<td><?php echo $datos['codproducto']; ?></td>
				<td><?php echo $datos['descripcion']; ?></td>
				<td class="celPrecio"><?php echo $datos['precio']; ?></td>
				<td class="celExistencia"><?php echo $datos['stock']; ?></td> 
				<td><?php echo $datos['tipo']; ?></td>
				<td><img src="<?php echo $foto; ?>" alt="<?php echo $datos['descripcion']; ?>" style="width: 60px;"></td>
				
					<?php if ($_SESSION['idrol'] == 1) { ?>
				<td>		 
					<a class="link_edit add_product" product="<?php echo $datos['codproducto']; ?>" href="#" style="color:#0B8AB5;"><i class="fas fa-plus"></i> Agregar</a> 
					 | 
					<a class="link_edit" href="editar_producto.php?id=<?php echo $datos['codproducto']; ?>"><i class="fas fa-user-edit"></i> Editar</a> 
					

					|  <a class="link_delete eliminar_producto" product="<?php echo $datos['codproducto']; ?>" href="#"><i class="fas fa-user-times"></i>  Eliminar</a>

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