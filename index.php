<?php 



	
	$alert = '';
	session_start();

	if(!empty($_session['activa']))
	{
		header('location: sistema/');
	}else{

		if (!empty($_POST))
		{
			$correo = $_POST['correo'];
			$clave  = $_POST['clave'];
				
			//echo "hola mundo ya tengo los datos! ".$correo." ".$clave;
					
			include('coneccion.php');

			$query_login =  mysqli_query($conn,"CALL login('$correo','$clave');");
			mysqli_close($conn);				

			if ($query_login)
			{
				echo "el usuario es el correcto";				
				$datos = mysqli_fetch_array($query_login);		
							
				// echo $datos['id_usuario'];
				// echo $datos['nombre'];
				// echo $datos['descripcion'];
				
				$_SESSION['activa'] = true;
				$_SESSION['idUsuario'] = $datos['idusid_usuariouario'];
				$_SESSION['nombre']    = $datos['nombre'];
				$_SESSION['nombre']    = $datos['apellido'];
				$_SESSION['nombre']    = $datos['tipo_user'];
					
				header('location: sistema/'); 

				}else{

					$alert = "Correo o Clave Invalidos! ";
					session_destroy();

				} 

			}
	}
	

?>



<!DOCTYPE html>
<html>
<head>
	<title>Sistema de Ventas</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<section id= "container">
		
		<form action="" method="post">
			<h3>Iniciar Sesion</h3>
			<img src="img/silueta.png" alt="Login">
			<div class="alert"> <?php echo isset($alert)? $alert : ''; ?> </div>
			<input type="email" name="correo" placeholder="Ingrese Correo Electronico" required>
			<input type="password" name="clave" placeholder="Ingrese Clave" required>			
			<input type="submit" name="ingresar" value="Ingresar">
		</form>
	
	</section>

</body>
</html>