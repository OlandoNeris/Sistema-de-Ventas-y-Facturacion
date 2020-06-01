<?php 
	session_start();
	
	include '../coneccion.php';
//  ACTUALIZAR DATOS EN DB  <------------
	if(!empty($_POST))
	{
		$alert = '';

		if (empty($_POST['nombre']) || empty($_POST['apellido']) ||  empty($_POST['correo']) || empty($_POST['direccion']) || empty($_POST['telefono']) )
		{
			$alert = ' <p class="msg_error">Todos los Campos son Obligatorios</p>';			
		}else{	


			$idcliente = $_POST['id'];
			$nombre    = $_POST['nombre'];
			$apellido  = $_POST['apellido'];
			$correo    = $_POST['correo'];
			$direccion = $_POST['direccion'];
			$telefono  = $_POST['telefono'];
			


			$query = mysqli_query($conn, "SELECT * FROM cliente
												   WHERE (correo = '$correo' AND idcliente != $idcliente)");
			$result = mysqli_fetch_array($query);
			$cantidad = mysqli_num_rows($query);
		

			if($cantidad > 0)
			{
				$alert = '<p class="msg_error">El correo  Ya Existe! </p>'; 
			}else{

				if (empty($_POST['clave']))
				{
					$sql_update = mysqli_query($conn, "UPDATE cliente
													   SET nombre ='$nombre', apellido ='$apellido', correo = '$correo',
													       telefono = '$telefono', direccion = '$direccion'
													   WHERE idcliente = '$idcliente' ");
				}else{
					$sql_update = mysqli_query($conn, "UPDATE cliente
													   SET nombre ='$nombre', apellido = '$apellido', correo = '$correo', telefono = '$telefono', direccion = '$direccion'
													   WHERE idcliente = '$idcliente' ");
				}

				

				if($sql_update){
					$alert =  '<p class="msg_save">Cliente Actualizado Con Exito!  </p>';
				}else{
					$alert =  '<p class="msg_error">No se Pudo Actualizar el Cliente..  </p>';

				}

			}

			}
		}
		

 // MOSTRAR DATOS DE USUARIOS <------------

	if (empty($_REQUEST['id'])) {
		header("location: lista_clientes.php");
		mysqli_close($conn);
	}

	$idcliente = $_REQUEST['id'];

	$sql = mysqli_query($conn, "SELECT * FROM cliente 
								WHERE idcliente = '$idcliente'");
	mysqli_close($conn);

	$result_sql = mysqli_num_rows($sql);

	if ($result_sql == 0) {
		header("location: lista_clientes.php");
	}else{
		
		while ($data = mysqli_fetch_array($sql)) {
			# code...
			$idcliente = $data['idcliente'];
			$nombre    = $data['nombre'] ;
			$apellido  = $data['apellido'];
			$direccion = $data['direccion'];
			$correo    = $data['correo'];			
			$telefono  = $data['telefono'];

		}
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php "; ?>

	<title>Actualizar Cliente</title>
</head>
<body style="background: #0C4951">

	<?php include "includes/header.php"; ?>

	<section id="container">

		<div class="form_registro">

			<h1><i class="fas fa-user-edit"></i>  Editar Cliente</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : '';?></div> 

			<form action="" method="post">

				<input type="hidden" name="id" value="<?php echo $idcliente; ?>">
				
				<label for="nombre" >Nombres</label>
				<input type="text" name="nombre" id="nombre" placeholder="Ingrese Nombres " value="<?php echo $nombre; ?>" required>

				<label for="apellido" >Apellido</label>
				<input type="text" name="apellido" id="apellido" placeholder="Ingrese Apellido " value="<?php echo $apellido; ?>" required>

				<label for="correo" >Correo</label>
				<input type="email" name="correo" id="correo" placeholder="Ingrese Correo electronico " value="<?php echo $correo; ?>" required>

				<label for="telefono" >Telefono</label>
				<input type="text" name="telefono" id="telefono" placeholder="Ingrese Telefono " value="<?php echo $telefono; ?>" required>

				<label for="direccion" >Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Ingrese direccion " value="<?php echo $direccion; ?>" required>

				<button type="submit" class="btn_enviar"><i class="fas fa-sync-alt"></i>  Actualizar Cliente</button>

			</form>


		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>

