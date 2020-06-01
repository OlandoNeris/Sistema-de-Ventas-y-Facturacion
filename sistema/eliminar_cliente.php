<?php 

	session_start();
	if ($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2) {
		# code...
		header('location: ./');
	}

	include '../coneccion.php';

	if (!empty($_POST)){
		# code...

		$idcliente = $_POST['idcliente'];

		//$query_borrar = mysqli_query($conn, "DELETE FROM usuario WHERE idusuario = $idusuario");  query para eliminar usuario
		$query_borrar = mysqli_query($conn, "UPDATE cliente SET estado = 0 WHERE idcliente = $idcliente"); // query para baja logica del usuario
		mysqli_close($conn);

		if ($query_borrar) {
			# code...
			header('location: lista_clientes.php');
		}else{
			echo 'No se pudo Elimiar el Cliente';
		}

	}

	if (empty($_REQUEST['id'])) {
		# code...
		header('location: lista_clientes.php');
		mysqli_close($conn);
	}else{

		

		$idcliente = $_REQUEST['id'];

		$query = mysqli_query($conn, "SELECT * FROM cliente WHERE idcliente = '$idcliente'");
		mysqli_close($conn);
		$result = mysqli_num_rows($query);

		if ($result > 0) {
			while ($datos = mysqli_fetch_array($query)) {
				
				$nombre    = $datos['nombre'];
				$apellido  = $datos['apellido'];
				$direccion = $datos['direccion'];
				$correo    = $datos['correo'];
			
			}
		
		}else{
			header('location: lista_clientes.php');
			mysqli_close($conn);
		}
	}

 ?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"; ?>

	<title>Eliminar Cliente</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	<div class="container">
	<section id="container">
		<div class="container eliminar_datos">
			<i class="fas fa-user-times fa-7x" style="color:darkred"></i>
			<h2>¿Esta seguro de eliminar el siguiente Cliente?</h2>
			<div class="contenedorp">
				
				<p>Nombre: <span><?php echo $nombre; ?></span></p>
				<p>Apellido: <span><?php echo $apellido; ?></span></p>
				<p>Correo: <span><?php echo $correo; ?></span></p>
				<p>Direccion: <span><?php echo $direccion; ?></span></p>

			</div>
			
			<form method="post" action="">
				<input type="hidden" name="idcliente" value="<?php echo $idcliente; ?>"> 
				<a href="lista_clientes.php" class="btn_cancel"><i class="fas fa-ban" style="color:white"></i>  Cancelar</a>
				<button type="submit" class="btn_eliminar"><i class="fas fa-user-times" style="color: white"></i> Eliminar</button>
			</form>
			
		</div>
	</section>
	</div>
	


	<?php include "includes/footer.php"; ?>
</body>
</html>