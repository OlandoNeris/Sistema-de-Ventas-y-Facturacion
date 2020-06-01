<?php 
	session_start();
	
	include '../coneccion.php';
//  ACTUALIZAR DATOS EN DB  <------------
	if(!empty($_POST))
	{
		$alert = '';

		if (empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['correo']) || empty($_POST['direccion']) || empty($_POST['telefono']) )
		{
			$alert = ' <p class="msg_error">Todos los Campos son Obligatorios</p>';			
		}else{	


			$codproveedor = $_POST['id'];
			$proveedor    = $_POST['proveedor'];
			$contacto  = $_POST['contacto'];
			$correo    = $_POST['correo'];
			$direccion = $_POST['direccion'];
			$telefono  = $_POST['telefono'];
			


			$query = mysqli_query($conn, "SELECT * FROM proveedor
												   WHERE (proveedor = '$proveedor' AND codproveedor != $codproveedor) 
												   OR (correo = '$correo' AND codproveedor != $codproveedor)");
			$result = mysqli_fetch_array($query);
			$cantidad = mysqli_num_rows($query);
		

			if($cantidad > 0)
			{
				$alert = '<p class="msg_error">El Correo o Proveedor Ya Existe! </p>'; 
			}else{

					$sql_update = mysqli_query($conn, "UPDATE proveedor
													   SET proveedor ='$proveedor', contacto = '$contacto', correo = '$correo', telefono = '$telefono', direccion = '$direccion'
													   WHERE codproveedor = '$codproveedor' ");
				

				if($sql_update){
					$alert =  '<p class="msg_save">Registro Actualizado Con Exito!  </p>';
				}else{
					$alert =  '<p class="msg_error">No se Pudo Actualizar el Registro..  </p>';

				}

				}

				


			}

			}
		

 // MOSTRAR DATOS DE USUARIOS <------------

	if (empty($_REQUEST['id'])) {
		header("location: lista_proveedores.php");
		mysqli_close($conn);
	}

	$idproveedor = $_REQUEST['id'];

	$sql = mysqli_query($conn, "SELECT * FROM proveedor 
								WHERE codproveedor = $idproveedor");
	mysqli_close($conn);

	$result_sql = mysqli_num_rows($sql);

	if ($result_sql == 0) {
		header("location: lista_proveedores.php");
	}else{
		
		while ($data = mysqli_fetch_array($sql)) {
			# code...
			$idproveedor = $data['codproveedor'];
			$proveedor    = $data['proveedor'] ;
			$contacto  = $data['contacto'];
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

	<title>Actualizar Proveedor</title>
</head>
<body style="background: #0C4951">

	<?php include "includes/header.php"; ?>

	<section id="container">

		<div class="form_registro">

			<h1><i class="fas fa-user-edit"></i>  Editar Proveedor</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : '';?></div> 

			<form action="" method="post">

				<input type="hidden" name="id" value="<?php echo $idproveedor; ?>">
				
				<label for="proveedor" >Proveedor</label>
				<input type="text" name="proveedor" id="proveedor" placeholder="Ingrese Nombre " value="<?php echo $proveedor; ?>" required>

				<label for="contacto" >Contacto</label>
				<input type="text" name="contacto" id="contacto" placeholder="Ingrese Contacto " value="<?php echo $contacto; ?>" required>

				<label for="correo" >Correo</label>
				<input type="text" name="correo" id="correo" placeholder="Ingrese Correo " value="<?php echo $correo; ?>" required>

				<label for="telefono" >Telefono</label>
				<input type="text" name="telefono" id="telefono" placeholder="Ingrese Telefono " value="<?php echo $telefono; ?>" required>

				<label for="direccion" >Direccion</label>
				<input type="text" name="direccion" id="direccion" placeholder="Ingrese direccion " value="<?php echo $direccion; ?>" required>

				<button type="submit" class="btn_enviar"><i class="fas fa-sync-alt"></i>  Actualizar</button>

			</form>


		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>

