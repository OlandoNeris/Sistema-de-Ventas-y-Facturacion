<?php 
	
	session_start();
	

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<?php include "includes/scripts.php"; ?>
	<link rel="stylesheet" href="css/bootstrap.css">

	<title>Sistema Ventas</title>
</head>
<body >
	
	<input type="hidden" name="userId" id="userIdCaja" value="<?php echo $_SESSION['idUsuario']; ?>">


	<?php include "includes/header.php"; ?>

	<section id="container">
		<h1>Bienvenido al sistema</h1>
	</section>
	<div class="container p-0" style="border:solid; background-color:#CCD1D1;">


		<!-- FORM APERTURA / CIERRE DE CAJA   -->

		<div class="container col-8" id="aperturaCierreForm">
			<div class="row">
				<div class="alert alert-primary col-6 col " role="alert">
					<button type="button"  id="aperturaNuevaCaja" class="btn btn-outline-success btn-lg col align-self-center ">Abrir Caja</button>
				</div>

				<div class="alert alert-primary col-6 col align-items-center" role="alert">
					<button type="button" class="btn btn-outline-danger btn-lg col align-self-center" id="btnCierreCaja" disabled>Cerrar Caja</button>
				</div>

			</div>
		</div>

		<!-- ACA EMPIEZA EL FORMULARIO DE CAJA  -->

		<div class="" style="display:none;" id="formDatosCaja">
			<div class="container col-10" id="DatosUsuarioCaja" style="text-color:black;">
				<div class="row">
					<div class="alert alert-primary col-6 " role="alert">
						<label style="text-align:center; color:black;" class="col align-self-center" for="numeroCaja">Numero de Caja</label>
						<label style="text-align:center; color:black;" class="col align-self-center" id="numeroCaja" for="numeroCaja"></label>
					</div>

					<div class="alert alert-primary col-6 col" role="alert">
						<label style="text-align:center; color:black;"  class="col align-self-center">Usuario</label>
						<label style="text-align:center; color:black;" class="col align-self-center" id="datosCajero" ></label>
					</div>

				</div>
			</div>

			<div class="container col-10">

				<div class="container col-12 alert alert-primary" id="separador "></div>

			</div>

			<div class="container col-10" id="DatosTotales">
				<div class="row">
					<div class="card text-white bg-success mb-4 col-4 col align-self-center" >
						<div class="card-header">Total de Ingresos</div>
						<div class="card-body">
						<h5 class="card-title">0</h5>

						</div>
					</div>

					<div class="card text-white bg-danger mb-4 col-4 col align-self-center" >
						<div class="card-header">Total Egresos</div>
						<div class="card-body">
						<h5 class="card-title"> - 0</h5>
						</div>
					</div>

					<div class="card text-white bg-primary mb-4 col-4 col align-self-center">
						<div class="card-header">Total Caja</div>
						<div class="card-body">
						<h5 class="card-title "> 0 </h5>
						</div>
					</div>

				</div>
			</div>
		
			<div class="container col-10" id="listado_operaciones">
				<div class="row">
					<h3 class="alert alert-primary col-12"> Listado de Operaciones</h3>
				</div>

				<div class="row">
					<div class="container col-12" id="tabla_operacioes">
						<table class="table col-12">
							<thead class="thead-dark">
								<tr>
								<th scope="col">#</th>
								<th scope="col">First</th>
								<th scope="col">Last</th>
								<th scope="col">Handle</th>
								</tr>
							</thead>
							<tbody>
								<tr>
								<th scope="row">1</th>
								<td>Mark</td>
								<td>Otto</td>
								<td>@mdo</td>
								</tr>
								<tr>
								<th scope="row">2</th>
								<td>Jacob</td>
								<td>Thornton</td>
								<td>@fat</td>
								</tr>
							</tbody>
							</table>

					</div>
				</div>

			</div>
		
		</div>

	</div>


	<?php include "includes/footer.php"; ?>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>