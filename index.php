<?php 
	
	$alert = '';
	session_start();

	if(!empty($_session['activa'])){
		header('location: sistema/');
		}else{
	
			if (!empty($_POST)){
		
				if (empty($_POST['correo']) || empty($_POST['clave'])){
			
					$alert = "Ingrese su Correo y Clave";
				}else{

					include('coneccion.php');

					$correo = $_POST['correo'];
					$clave  = $_POST['clave'];

					$query =  mysqli_query($conn,"SELECT * FROM usuario as u inner join rol as r 
												  on u.rol = r.idrol
												  WHERE u.correo = '$correo' AND u.clave = '$clave'");
					mysqli_close($conn);
					$resultado = mysqli_num_rows($query);

					if ($resultado > 0){
				
						$datos = mysqli_fetch_array($query);				

						$_SESSION['activa'] = true;
						$_SESSION['idUsuario'] = $datos['idusuario'];
						$_SESSION['nombre']    = $datos['nombre'];
						$_SESSION['apellido']  = $datos['apellido'];
						$_SESSION['correo']    = $datos['correo'];
						$_SESSION['usuario']   = $datos['usuario'];
						$_SESSION['rol']       = $datos['roldesc'];
						$_SESSION['idrol']     = $datos['idrol'];

						header('location: sistema/');

					}else{

						$alert = "Correo o Clave Invalidos! ";
						session_destroy();

					}

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
			<input type="text" name="correo" placeholder="Ingrese Correo Electronico">
			<input type="password" name="clave" placeholder="Ingrese Clave">
			<div class="alert"> <?php echo isset($alert)? $alert : ''; ?> </div>
			<input type="submit" name="ingresar" value="Ingresar">
		</form>
	
	</section>

</body>
</html>