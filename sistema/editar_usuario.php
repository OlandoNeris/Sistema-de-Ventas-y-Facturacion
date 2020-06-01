<?php 
	session_start();


	if ($_SESSION['idrol'] != 1) {
		# code...
		header('location: ./');
	}

	include '../coneccion.php';
//  ACTUALIZAR DATOS EN DB  <------------

	if(!empty($_POST))
	{
		$alert = '';

		if (empty($_POST['nombre']) || empty($_POST['apellido']) ||  empty($_POST['usuario']) ||  empty($_POST['correo']) 
			 || empty($_POST['direccion']) || empty($_POST['rol']) )
		{
			$alert = ' <p class="msg_error">Todos los Campos son Obligatorios</p>';			
		}else{	


			$idusuario = $_POST['id'];
			$nombre    = $_POST['nombre'];
			$apellido  = $_POST['apellido'];
			$usuario   = $_POST['usuario'];
			$correo    = $_POST['correo'];
			$direccion = $_POST['direccion'];
			$clave     = $_POST['clave'];
			$rol       = $_POST['rol'];
			


			$query = mysqli_query($conn, "SELECT * FROM usuario
												   WHERE (correo = '$correo' AND idusuario != $idusuario)");
			$result = mysqli_fetch_array($query);
			$cantidad = mysqli_num_rows($query);
		

			if($cantidad > 0)
			{
				$alert = '<p class="msg_error">El correo  Ya Existe! </p>'; 
			}else{				

				if (empty($_POST['clave']))
				{
					$sql_update = mysqli_query($conn, "UPDATE usuario
													   SET nombre ='$nombre', apellido ='$apellido', correo = '$correo',
													       usuario = '$usuario', rol = '$rol', direccion = '$direccion'
													   WHERE idusuario = '$idusuario' ");
				}else{

					$sql_update = mysqli_query($conn, "UPDATE usuario
												   		SET nombre ='$nombre', apellido = '$apellido', correo = '$correo', 
												   	   		direccion = '$direccion', usuario = '$usuario', rol = '$rol', clave = '$clave'
												   		WHERE idusuario = '$idusuario' ");						
				}			


				if($sql_update){
					$alert =  '<p class="msg_save">Usuario Actualizado Con Exito!  </p>';
				}else{
					$alert =  '<p class="msg_error">No se Pudo Actualizar el Usuario..  </p>';

				}

			}

			}
		}
		
	

 // MOSTRAR DATOS DE USUARIOS <------------

	if (empty($_REQUEST['id'])) {
		header("location: lista_usuarios.php");
		mysqli_close($conn);
	}

	$iduser = $_REQUEST['id'];

	$sql = mysqli_query($conn, "SELECT  u.nombre, u.apellido, u.correo, u.usuario, u.direccion, (u.rol) as idrol, (r.roldesc) as rol
								FROM usuario u 
								INNER JOIN rol r 
								on u.rol = r.idrol 
								WHERE idusuario = $iduser");
	mysqli_close($conn);

	$result_sql = mysqli_num_rows($sql);

	if ($result_sql == 0) {
		header("location: lista_usuarios.php");
	}else{
		$option = '';
		while ($data = mysqli_fetch_array($sql)) {
			# code...
			$nombre    = $data['nombre'] ;
			$apellido  = $data['apellido'];
			$direccion = $data['direccion'];
			$correo    = $data['correo'];
			$usuario   = $data['usuario'];			
			$idrol     = $data['idrol'];
			$rol       = $data['rol'] ;
				
			if ($idrol == 1) {
				$option = '<option value= "'.$idrol.'"select>'.$rol.'</option>';
			}else if ($idrol == 2) {
				$option = '<option value= "'.$idrol.'"select>'.$rol.'</option>';
			}else if ($idrol ==3) {
				$option = '<option value= "'.$idrol.'"select>'.$rol.'</option>';
			}


		}
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php "; ?>

	<title>Actualizar Usuario</title>
</head>
<body>

	<?php include "includes/header.php"; ?>

	<section id="container">

		<div class="form_registro">

			<h1><i class="fas fa-user-edit fa-2x"></i>  Editar Usuario</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : '';?></div> 

			<form action="" method="post">

				<input type="hidden" name="id" value= "<?php echo $iduser; ?>" >
				
				<label for="nombre" >Nombres</label>
				<input type="text" name="nombre" id="nombre" placeholder="Ingrese Nombres " value="<?php echo $nombre ?>" required>

				<label for="apellido" >Apellido</label>
				<input type="text" name="apellido" id="apellido" placeholder="Ingrese Apellido " value="<?php echo $apellido ?>" required>

				<label for="usuario" >Usuario</label>
				<input type="text" name="usuario" id="usuario" placeholder="Ingrese Usuario " value="<?php echo $usuario ?>" required>

				<label for="correo" >Correo</label>
				<input type="email" name="correo" id="correo" placeholder="Ingrese Correo electronico " value="<?php echo $correo ?>" required>

				<label for="clave" >clave</label>
				<input type="password" name="clave" id="clave" placeholder="Ingrese clave ">

				<label for="re_clave" >Repita Clave</label>
				<input type="password" name="re_clave" id="re_clave" placeholder="Reingrese Clave ">

				<label for="direccion" >Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Ingrese direccion " value="<?php echo $direccion ?>" required>

				<label for="rol">Tipo Usuario</label>
				<?php 

					include '../coneccion.php';

					$query_rol = mysqli_query($conn,"SELECT * FROM rol");
					mysqli_close($conn);
					$result_rol = mysqli_num_rows($query_rol);

				?>

				<select name="rol" id="rol" class="notItem">
					<?php 

						echo $option;

						if($result_rol > 0)
						{
							while ($rol = mysqli_fetch_array($query_rol))
							{
					?>
						<option value="<?php echo $rol["idrol"]; ?>"><?php echo $rol["roldesc"] ?></option>
					<?php

							}
						}

					?>
				</select>

				<button type="submit" class="btn_enviar"><i class="fas fa-sync-alt"></i>  Actualizar Usuario</button>

			</form>

		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>

