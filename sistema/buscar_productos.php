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
		
		<?php 


		$busqueda = '';
		$buscar_proveedor = '';

		if (empty($_REQUEST['busqueda']) && empty($_REQUEST['proveedor'])) {
			
			header("location: lista_productos.php");
		}

		if (!empty($_REQUEST['busqueda'])) {
			
			$busqueda = strtolower($_REQUEST['busqueda']);
			$condicion="(codproducto LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%') AND prod.estado = 1";
			$buscar = 'busqueda='.$busqueda;
						
		}

		if (!empty($_REQUEST['proveedor'])) {
			
			$buscar_proveedor = strtolower($_REQUEST['proveedor']);
			$condicion = "prod.proveedor LIKE $buscar_proveedor AND prod.estado = 1";
			$buscar = 'proveedor='.$buscar_proveedor;
		}


		?>

		<h1><i class="fas fa-address-card"></i>  Listado de Productos</h1>
		<a href="registro_producto.php" class="btn_nuevo"><i class="fas fa-plus"></i>  Nuevo Producto</a>
		
		<form action="buscar_productos.php" method="get" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<button type="submit" class="btn_buscar"><i class="fas fa-search fa-lg"></i></button>
		</form>

		<div class="recuadro_listado">
		<table>
			<tr>
				<th>CODIGO</th>
				<th>DESCRIPCION</th>
				<th>PRECIO</th>
				<th>EXISTENCIA</th>
				<th>
				<?php 

				$pro = 0;
				if (!empty($_REQUEST['proveedor'])) {
					$pro = $_REQUEST['proveedor'];

				}

				$query_proveedor = mysqli_query($conn,"SELECT codproveedor, proveedor FROM proveedor WHERE estado = 1 ORDER BY proveedor ASC");
				$resultado = mysqli_num_rows($query_proveedor);

				 ?>

				<select name="proveedor" id="buscar_proveedor">
					<option value="" selected>PROVEEDOR </option>
					<?php 

						if ($resultado > 0) 
						{
							# code...
							while ($proveedor = mysqli_fetch_array($query_proveedor)) 
							{
								if ($pro == $proveedor['codproveedor']) 
								{
					?>
					<option value="<?php echo $proveedor['codproveedor'];?>" selected><?php echo $proveedor['proveedor'];?></option>
					<?php 
								}else{
					?>
					<option value="<?php echo $proveedor['codproveedor'];?>" ><?php echo $proveedor['proveedor'];?></option>
					<?php 


								}	
							}
						}

					?>
									
				</select>
				</th>
				<th>FOTO</th>
				<th>ACCIONES</th>
			</tr>

			<?php 
					// PAGINADOR     

				$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registros 
													 FROM producto as prod
													 WHERE $condicion");
				
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

				$query = mysqli_query($conn,"SELECT codproducto, 
													descripcion, 
													prov.proveedor as proveedor, 
													precio, 
													existencia, 
													foto 
											FROM producto as prod 
											INNER JOIN proveedor as prov ON prod.proveedor = prov.codproveedor 
											WHERE $condicion
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
				<td class="celExistencia"><?php echo $datos['existencia']; ?></td> 
				<td><?php echo $datos['proveedor']; ?></td>
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
		<?php // este if muestra los registros si la cantidad de paginas es distinta de 0 
			if ($total_paginas != 0) {
				# code...
			
		 ?>

		<div class="paginador">
			<ul>
				<?php 	

				if ($pagina != 1)
				 {				
			
				 ?>
				<li><a href="?pagina=<?php 	echo 1; ?>&<?php echo $buscar;?>"><i class="fas fa-fast-backward"></i></a></li>				
				<li><a href="?pagina=<?php 	echo $pagina - 1; ?>&<?php echo $buscar;?>"><i class="fas fa-backward"></i></a></li>

				<?php
					} 	
					for ($i=1; $i <= $total_paginas; $i++) { 
						# code...

						if ($i == $pagina) {
							# code...
							echo '<li class="pageSelected">'.$i.'</li>';
						}else{

							echo '<li><a href="?pagina='.$i.'&'.$buscar.'">'.$i.'</a></li>';
						}						
					}

					if ($pagina != $total_paginas)
					{
				?>				

				<li><a href="?pagina=<?php 	echo $pagina + 1 ?>&<?php echo $buscar;?>"><i class="fas fa-forward"></i></a></li>
				<li><a href="?pagina=<?php 	echo $total_paginas;?>&<?php echo $buscar;?>"><i class="fas fa-fast-forward"></i></a></li>
				<?php 	} ?>
			</ul>
			
		</div>

		<?php } ?>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>