		<?php 

		if(empty($_SESSION['activa'])){
		header('location: ../');
		}

	?>

	<header>
		<div class="header">
			
			<h1>Sistema Facturación</h1> 
			<div class="optionsBar">
				<p>Argentina, <?php echo fechaC(); ?></p>
				<span>|</span>
				<span class="user"> <?php echo "Hola ".$_SESSION['nombre'].' - '.$_SESSION['rol']; ?></span>
				<img class="photouser" src="img/user.png" alt="Usuario"> 
				<a href="salir.php"><img class="close" src="img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
		<?php include "navbar.php"; ?>

	</header>

	<div class="modal">
		<div class="bodyModal">

		</div>		
	</div>



