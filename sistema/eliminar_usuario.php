<?php 

	session_start();
	if ($_SESSION['idrol'] != 1) {
		# code...
		header('location: ./');
	}

	include '../coneccion.php';

	if (!empty($_POST)){
		# code...

		if ($_POST['idusuario'] == 1) {
			header('location: lista_usuarios.php');	
			mysqli_close($conn);
			exit;		
		}

		$idusuario = $_POST['idusuario'];

		//$query_borrar = mysqli_query($conn, "DELETE FROM usuario WHERE idusuario = $idusuario");  query para eliminar usuario
		$query_borrar = mysqli_query($conn, "UPDATE usuario SET estado = 0 WHERE idusuario = $idusuario"); // query para baja logica del usuario
		mysqli_close($conn);

		if ($query_borrar) {
			# code...
			header('location: lista_usuarios.php');
		}else{
			echo 'No se pudo Elimiar el Usuario';
		}

	}

	if (empty($_REQUEST['id']) || $_REQUEST['id'] == 1) {
		# code...
		header('location: lista_usuarios.php');
		mysqli_close($conn);
	}else{

		$idusuario = $_REQUEST['id'];

		$query = mysqli_query($conn, "SELECT u.nombre, u.apellido, u.usuario,  r.roldesc FROM usuario u INNER JOIN rol r ON u.rol = r.idrol WHERE u.idusuario = '$idusuario'");
		mysqli_close($conn);
		$result = mysqli_num_rows($query);

		if ($result > 0) {
			while ($datos = mysqli_fetch_array($query)) {
				
				$nombre   = $datos['nombre'];
				$apellido = $datos['apellido'];
				$rol      = $datos['roldesc'];
				$usuario  = $datos['usuario'];
			}
		
		}else{
			header('location: lista_usuarios.php');
			mysqli_close($conn);
		}

	}


 ?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php "; ?>

	<title>Eliminar Usuario</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	
	<section id="container">
		<div class="eliminar_datos">
			<i class="fas fa-user-times fa-7x" style="color:darkred"></i>
			<h2>¿Esta seguro de eliminar el siguiente Usuario?</h2>
			<p>Nombre: <span><?php echo $nombre ?></span></p>
			<p>Apellido: <span><?php echo $apellido ?></span></p>
			<p>Usuario: <span><?php echo $usuario ?></span></p>
			<p>Rol: <span><?php echo $rol ?></span></p>

			<form method="post" action="">
				<input type="hidden" name="idusuario" value="<?php echo $idusuario; ?>"> 
				<a href="lista_usuarios.php" class="btn_cancel"><i class="fas fa-ban" style="color:white"></i>  Cancelar</a>
				<button type="submit" class="btn_eliminar"><i class="fas fa-user-times" style="color: white"></i> Aceptar</button>
			</form>

	</section>
</div>


	<?php include "includes/footer.php"; ?>
</body>
</html>