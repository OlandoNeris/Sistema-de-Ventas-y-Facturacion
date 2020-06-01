<?php 
	session_start();
	
	include '../coneccion.php';

	if(!empty($_POST))
	{
		$alert = '';

		if (empty($_POST['nombrecli']) || empty($_POST['apellidocli']) || empty($_POST['telefono']) || empty($_POST['correo']) || empty($_POST['direccion']))
		{
			$alert = ' <p class="msg_error">Todos los Campos son Obligatorios</p>';			
		}else{			

			$nombrecli    = $_POST['nombrecli'];
			$apellidocli  = $_POST['apellidocli'];
			$dnicuit      = $_POST['dnicuit'];
			$telefono     = $_POST['telefono'];
			$correo       = $_POST['correo'];
			$direccion    = $_POST['direccion'];


			$query = mysqli_query($conn, "SELECT * FROM cliente WHERE dni_cliente = '$dnicuit' OR correo = '$correo'");
			
			$result = mysqli_num_rows($query);

			if($result > 0)
			{
				$alert = '<p class="msg_error">El correo o CUIT/DNI del Cliente Ya Existe! </p>';
				

			}else{
				$query_insert = mysqli_query($conn,"INSERT INTO cliente(nombre,apellido, telefono, correo, direccion,dni_cliente)
														VALUES('$nombrecli','$apellidocli','$telefono','$correo','$direccion' , '$dnicuit')");
				if($query_insert){
					$alert =  '<p class="msg_save">Cliente Creado Con Exito!  </p>';
				}else{
					$alert =  '<p class="msg_error">No se Pudo Crear el Cliente..  </p>';

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

	<title>Registro Clientes</title>
</head>



<body style="background: #0C4951">

	<?php include "includes/header.php"; ?>

	<section id="container">

		<div class="form_registro">

			<h1><i class="fas fa-user-plus"></i>  Registro Clientes</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

			<form action="" method="post">
				
				<label for="nombrecli" >Nombre</label>
				<input type="text" name="nombrecli" id="nombrecli" placeholder="Ingrese Nombre Nombre " required >

				<label for="apellidocli" >Apellido</label>
				<input type="text" name="apellidocli" id="apellidocli" placeholder="Ingrese Nombre del Apellido " required>

				<label for="dnicuit" >DNI / Cuit</label>
				<input type="text" name="dnicuit" id="dnicuit" placeholder="Ingrese Nombre del DNI / Cuit " required>

				<label for="telefono" >Telefono</label>
				<input type="text" name="telefono" id="telefono" placeholder="Ingrese Telefono " required>

				<label for="correo" >Correo</label>
				<input type="email" name="correo" id="correo" placeholder="Ingrese Correo electronico " required>

				<label for="direccion" >Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Ingrese direccion " required>
				
				<button type="submit" class="btn_enviar"><i class="fas fa-save"></i>  Registrar</button>

			</form>

		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>