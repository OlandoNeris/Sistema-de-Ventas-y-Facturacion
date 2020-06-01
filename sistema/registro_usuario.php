<?php 
	session_start();
	if ($_SESSION['idrol'] != 1) {
		# code...
		header('location: ./');
	}
	include '../coneccion.php';

	if(!empty($_POST))
	{
		$alert = '';

		if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['usuario']) || empty($_POST['correo']) || empty($_POST['clave']) || empty($_POST['re_clave']) || empty($_POST['direccion']) || empty($_POST['rol']) )
		{
			$alert = ' <p class="msg_error">Todos los Campos son Obligatorios</p>';			
		}else{

			if ($_POST['re_clave'] != $_POST['clave']) {
					$alert = ' <p class="msg_error">Las Claves no Cohinciden</p>';
			}else{

						

			$nombre    = $_POST['nombre'];
			$apellido  = $_POST['apellido'];
			$usuario   = $_POST['usuario'];
			$correo    = $_POST['correo'];
			$clave     = $_POST['clave'];
			$re_clave  = $_POST['re_clave'];
			$direccion = $_POST['direccion'];
			$rol       = $_POST['rol'];


			$query = mysqli_query($conn, "SELECT * FROM usuario WHERE usuario = '$usuario' OR correo = '$correo'");
			
			$result = mysqli_fetch_array($query);

			if($result > 0)
			{
				$alert = '<p class="msg_error">El correo o Usuario Ya Existe! </p>'; 
			}else{
				$query_insert = mysqli_query($conn,"INSERT INTO usuario(nombre,apellido,direccion,correo,usuario,clave,rol)
																VALUES('$nombre','$apellido','$direccion','$correo','$usuario','$clave','$rol')");
				if($query_insert){
					$alert =  '<p class="msg_save">Usuario Creado Con Exito!  </p>';
				}else{
					$alert =  '<p class="msg_error">No se Pudo Crear el Usuario..  </p>';

				}

			}
			}
			}


			mysqli_close($conn);
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"; ?>

	<title>Registro Usuario</title>
</head>



<body>

	<?php include "includes/header.php"; ?>

	<section id="container">

		<div class="form_registro">

			<h1><i class="fas fa-user-plus"></i>  Registro Usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

			<form action="" method="post">
				
				<label for="nombre" >Nombres</label>
				<input type="text" name="nombre" id="nombre" placeholder="Ingrese Nombres " required>

				<label for="apellido" >Apellido</label>
				<input type="text" name="apellido" id="apellido" placeholder="Ingrese Apellido " required>

				<label for="usuario" >Usuario</label>
				<input type="text" name="usuario" id="usuario" placeholder="Ingrese Usuario " required>

				<label for="correo" >Correo</label>
				<input type="email" name="correo" id="correo" placeholder="Ingrese Correo electronico " required>

				<label for="clave" >clave</label>
				<input type="password" name="clave" id="clave" placeholder="Ingrese clave " required>

				<label for="re_clave" >Repita Clave</label>
				<input type="password" name="re_clave" id="re_clave" placeholder="Reingrese Clave " required>

				<label for="direccion" >Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Ingrese direccion " required>

				<label for="rol">Tipo Usuario</label>
				<?php 
					include '../coneccion.php';
					$query_rol = mysqli_query($conn,"SELECT * FROM rol");
					mysqli_close($conn);
					$result_rol = mysqli_num_rows($query_rol);

				?>

				<select name="rol" id="rol">
					<?php 


						if($result_rol > 0)
						{
							while ($rol = mysqli_fetch_array($query_rol))
							{
					?>
						<option value="<?php echo $rol['idrol']; ?>"><?php echo $rol["roldesc"] ?></option>
					<?php

							}
						}

					?>
				</select>
				
				<button type="submit" class="btn_enviar"><i class="fas fa-save"></i>  Registro</button>

			</form>

		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>