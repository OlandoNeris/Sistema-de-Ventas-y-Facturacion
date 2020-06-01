<?php 
	session_start();
	
	include '../coneccion.php';

	if(!empty($_POST))
	{
		$alert = '';

		if (empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['correo']) || empty($_POST['direccion']))
		{
			$alert = ' <p class="msg_error">Todos los Campos son Obligatorios</p>';			
		}else{			

			$proveedor    = $_POST['proveedor'];
			$contacto  = $_POST['contacto'];
			$telefono   = $_POST['telefono'];
			$correo    = $_POST['correo'];
			$direccion = $_POST['direccion'];


			$query = mysqli_query($conn, "SELECT * FROM `proveedor` WHERE proveedor = '$proveedor' OR correo = '$correo'");
			
			$result = mysqli_num_rows($query);

			if($result > 0)
			{
				$alert = '<p class="msg_error">El correo o Proveedor Ya Existe! </p>'; 
			}else{
				$query_insert = mysqli_query($conn,"INSERT INTO proveedor(proveedor,contacto, telefono, correo, direccion)
																VALUES('$proveedor','$contacto','$telefono','$correo','$direccion')");
				if($query_insert){
					$alert =  '<p class="msg_save">Proveedor Creado Con Exito!  </p>';
				}else{
					$alert =  '<p class="msg_error">No se Pudo Crear el Proveedor..  </p>';

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

	<title>Registro Proveedor</title>
</head>



<body style="background: #0C4951">

	<?php include "includes/header.php"; ?>

	<section id="container">

		<div class="form_registro">

			<h1><i class="fas fa-user-plus"></i>  Registro Proveedor</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

			<form action="" method="post">
				
				<label for="proveedor" >Proveedor</label>
				<input type="text" name="proveedor" id="proveedor" placeholder="Ingrese Nombre Proveedor " required>

				<label for="contacto" >Contacto</label>
				<input type="text" name="contacto" id="contacto" placeholder="Ingrese Nombre del Contacto " required>

				<label for="telefono" >Telefono</label>
				<input type="text" name="telefono" id="telefono" placeholder="Ingrese Telefono " required>

				<label for="correo" >Correo</label>
				<input type="email" name="correo" id="correo" placeholder="Ingrese Correo electronico " required>

				<label for="direccion" >Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Ingrese direccion " required>
				
				<button type="submit" class="btn_enviar"><i class="fas fa-save"></i>  Registro</button>

			</form>

		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>