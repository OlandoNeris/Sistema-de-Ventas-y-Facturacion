<?php 
	session_start();
	include 'coneccion.php';

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<title>Lista de Viajes</title>
</head>
<body>

	<section id="container_listado">
		<h1><i class="fas fa-address-card"></i>  Listado de Viajes</h1>
		<a href="registro_clientes.php" class="btn_nuevo"><i class="fas fa-plus"></i>  Nuevo Cliente</a>
		
		<div>
			<h5>Buscar por Fecha</h5>
			<form action="buscar_venta.php" method="get" class="form_search_fecha">
				<label>De:</label>
				<input type="date" name="fecha_desde" id="fecha_desde" required>
				<label> A </label>
				<input type="date" name="fecha_hasta" id="fecha_hasta" required>
				<button type="submit" class="btn_view"><i class="fas fa-search"></i></button>	
			</form>	
		</div>

		<div class="recuadro_listado">
		<table>
			<tr>
				<th>ID</th>
				<th>LINEA</th>
				<th>COCHE</th>
				<th>Loc Salida</th>
				<th>loc Destino</th>
				<th>FECHA</th>
				<th>KM Recorridos</th>
			</tr>

			<?php 
					// PAGINADOR     

				$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registros FROM cliente WHERE estado = 1");
				
				$sql_resultado = mysqli_fetch_array($sql_registro);
				$totalRegistros = $sql_resultado['total_registros'];

					// cantidad de registros por pagina

				$porPagina = 5;

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
				<td><?php echo $datos['usercliente']; ?></td>
				<td><?php echo $datos['correo']; ?></td>
				<td><?php echo $datos['telefono']; ?></td>
				<td><?php echo $datos['direccion']; ?></td>
				<td>
					<a class="link_edit" href="editar_cliente.php?id=<?php echo $datos['idcliente']; ?>"><i class="fas fa-user-edit"></i> Editar</a> 
					
					<?php if ($_SESSION['rol'] == 1) { ?>
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