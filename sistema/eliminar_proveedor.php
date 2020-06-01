<?php 

	session_start();

	include '../coneccion.php';

	if (!empty($_POST)){
		# code...

		$codproveedor = $_POST['codproveedor'];

		//$query_borrar = mysqli_query($conn, "DELETE FROM usuario WHERE idusuario = $idusuario");  query para eliminar usuario
		$query_borrar = mysqli_query($conn, "UPDATE proveedor SET estado = 0 WHERE codproveedor = $codproveedor"); // query para baja logica del usuario
		mysqli_close($conn);

		if ($query_borrar) {
			# code...
			header('location: lista_proveedores.php');
		}else{
			echo 'No se pudo Elimiar el Proveedor';
		}

	}

	if (empty($_REQUEST['id'])) {
		# code...
		header('location: lista_proveedores.php');
		mysqli_close($conn);
	}else{

		

		$codproveedor = $_REQUEST['id'];

		$query = mysqli_query($conn, "SELECT * FROM proveedor WHERE codproveedor = '$codproveedor'");
		mysqli_close($conn);
		$result = mysqli_num_rows($query);

		if ($result > 0) {
			while ($datos = mysqli_fetch_array($query)) {
				
				$proveedor    = $datos['proveedor'];
				$contacto  = $datos['contacto'];
				$telefono   = $datos['telefono'];
				$direccion = $datos['direccion'];
				$correo    = $datos['correo'];
			
			}
		
		}else{
			header('location: lista_proveedores.php');
			mysqli_close($conn);
		}
	}

 ?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"; ?>

	<title>Eliminar Proveedor</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	<section id="container">
		<div class="eliminar_datos">
			<i class="fas fa-user-times fa-7x" style="color:darkred"></i>
			<h2>¿Esta seguro de eliminar el siguiente Proveedor?</h2>
			<div class="contenedorp">
				
				<p>Proveedor: <span><?php echo $proveedor; ?></span></p>
				<p>Contacto: <span><?php echo $contacto; ?></span></p>
				<p>Telefono: <span><?php echo $telefono; ?></span></p>
				<p>Correo: <span><?php echo $correo; ?></span></p>
				<p>Direccion: <span><?php echo $direccion; ?></span></p>

			</div>
			
			<form method="post" action="">
				<input type="hidden" name="codproveedor" value="<?php echo $codproveedor; ?>"> 
				<a href="lista_proveedores.php" class="btn_cancel"><i class="fas fa-ban" style="color:white"></i>  Cancelar</a>
				<button type="submit" class="btn_eliminar"><i class="fas fa-user-times" style="color: white"></i> Eliminar</button>
			</form>
			
		</div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>