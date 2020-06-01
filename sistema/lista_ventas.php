<?php 
	session_start();
	include '../coneccion.php';

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"; ?>

	<title>Lista de Ventas</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	<section id="container_listado">
		<h1><i class="far fa-newspaper"></i>  Listado de Ventas</h1>
		<a href="nueva_venta.php" class="btn_nuevo"><i class="fas fa-plus"></i>  Nueva Venta</a>
		
		<form action="buscar_venta.php" method="get" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="N° Factura">
			<button type="submit" class="btn_buscar"><i class="fas fa-search fa-lg"></i></button>
		</form>

		<div>
			<form action="buscar_venta.php" method="get" class="form_search_fecha">
				<label>Desde</label>
				<input type="date" name="fecha_desde" id="fecha_desde" required>
				<label> Hasta </label>
				<input type="date" name="fecha_hasta" id="fecha_hasta" required>
				<button type="submit" class="btn_view"><i class="fas fa-search"></i></button>	
			</form>	
		</div>

		<div class="recuadro_listado">
		<table>
			<tr>
				<th>NUMERO</th>
				<th>FECHA / HORA</th>
				<th>CLIENTE</th>
				<th>VENDEDOR</th>
				<th>ESTADO</th>
				<th class="textright">TOTAL FACTURA</th>
				<th class="textright">ACCIONES</th>
			</tr>

			<?php 
					// PAGINADOR     

				$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registros FROM factura WHERE estado != 10");
				
				$sql_resultado = mysqli_fetch_array($sql_registro);
				$totalRegistros = $sql_resultado['total_registros'];

					
				// cantidad de registros por pagina
				$porPagina = 10;
				// ----------------------------------


				if (empty($_GET['pagina']))
				{
					$pagina=1;						
				}else{
					$pagina = $_GET['pagina'];
				}


				$desde = ($pagina - 1) * $porPagina;
				$total_paginas = ceil($totalRegistros / $porPagina);


				$query = mysqli_query($conn,"SELECT f.nofactura,
													f.fecha,
													f.totalfactura,
													f.codcliente,
													f.estado,
													u.nombre as vendedor,
													cl.nombre as cliente
											 FROM factura as f
											 INNER JOIN usuario as u ON f.usuario = u.idusuario
											 INNER JOIN cliente as cl ON f.codcliente = cl.idcliente
											 WHERE f.estado != 10
											 ORDER BY f.fecha DESC LIMIT $desde,$porPagina");
				mysqli_close($conn);

				$result = mysqli_num_rows($query);

				if($result > 0)
				{
					while($datos = mysqli_fetch_array($query))
					{
						if($datos['estado'] == 1)
						{
							$estado = '<span class="pagada">Pagada</span>';
						}else{
							$estado = '<span class="anulada">Anulada</span>';
						}

						
			 ?>
			<tr id="row_<?php echo $datos['nofactura']; ?>">

				<td><?php echo $datos['nofactura']; ?></td>
				<td><?php echo $datos['fecha']; ?></td>
				<td><?php echo $datos['cliente']; ?></td>
				<td><?php echo $datos['vendedor']; ?></td>
				<td class="estado"><?php echo $estado; ?></td>
				<td class="textright total_factura"><span>$.</span><?php echo $datos['totalfactura']; ?></td>
				<td>

					<div class="div_acciones">
						<div>
							<button class="btn_view ver_factura" type="button" cliente="<?php echo $datos['codcliente']; ?>" factura="<?php echo $datos['nofactura']; ?>" name="Ver Factura"><i class="fas fa-eye"></i>	
							</button>
						</div>						


							<?php 
								if ($_SESSION['idrol'] == 1) 
								{
									if($datos['estado'] == 1) 
									{
							?> 
									<div class="div_factura">
										<button class="btn_anular anular_factura" type="button" factura="<?php echo $datos['nofactura']; ?>"><i class="fas fa-ban"></i>	
									</button>
									</div>
									

							<?php	}else{  ?>

									<div class="div_factura">
										<button class="btn_anular inactive" type="button" factura="<?php echo $datos['nofactura']; ?>" ><i class="fas fa-ban"></i>	
									</button>
									</div>
							<?php 	
									}
								}
							 ?>
					</div>		

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