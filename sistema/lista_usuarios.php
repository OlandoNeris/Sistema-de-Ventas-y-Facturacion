<?php 
	session_start();
	if ($_SESSION['idrol'] != 1) {
		# code...
		header('location: ./');
	}

	include '../coneccion.php';

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"; ?>

	<title>Lista de Usuarios</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	<section id="container_listado">
		<h1><i class="fas fa-users"></i>  Listado de Usuarios</h1>
		<a href="registro_usuario.php" class="btn_nuevo"><i class="fas fa-user-plus"></i> Crear Usuario</a>
		
		<form action="buscar_usuario.php" method="get" class="form_buscar">
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
				<th>ROL</th>
				<th>ACCIONES</th>
			</tr>

			<?php 
					// PAGINADOR     

				$sql_registro = mysqli_query($conn, "SELECT COUNT(*) as total_registros FROM usuario WHERE estado = 1");
				
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

				$query = mysqli_query($conn,"SELECT u.idusuario, u.nombre, u.apellido, u.usuario, u.correo, r.roldesc  FROM usuario u INNER JOIN rol r on u.rol = r.idrol WHERE estado = 1 ORDER BY idusuario ASC LIMIT $desde,$porPagina ");
				mysqli_close($conn);

				$result = mysqli_num_rows($query);

				if($result >0)
				{
					while($datos = mysqli_fetch_array($query))
					{
			 ?>
			<tr>
				<td><?php echo $datos['idusuario']; ?></td>
				<td><?php echo $datos['nombre']; ?></td>
				<td><?php echo $datos['apellido']; ?></td>
				<td><?php echo $datos['usuario']; ?></td>
				<td><?php echo $datos['correo']; ?></td>
				<td><?php echo $datos['roldesc']; ?></td>
				<td>
					<a class="link_edit" href="editar_usuario.php?id=<?php echo $datos['idusuario']; ?>"><i class="fas fa-user-edit"></i> Editar</a> 
					<?php 

					if ($datos['roldesc'] != 'Admin') {

					 ?>
					 | <a class="link_delete" href="eliminar_usuario.php?id=<?php echo $datos['idusuario']; ?>"><i class="fas fa-user-times"></i> Eliminar</a>
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