
<?php 
	session_start();
	include '../coneccion.php';


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php";?>
	<title>Nueva Venta</title>
</head>
<body>
	<?php include "includes/header.php" /*El header Tiene la validacion de usuario*/ ;?>
	<section id="container_venta">
		<div class="title_page">
			<h1><i class="fas fa-cube"></i>Nueva Venta</h1>
		</div>

		<div class="datos_cliente">
			<div class="action_cliente">
				<h2>Datos del Cliente</h2>
				<a href="#" class="btn_nuevo btn_new_cliente"><i class="fas fa-plus"></i> Nuevo Cliente</a>		
			</div>
		</div>

		<form action="" name="form_new_cliente_venta" id="form_new_cliente_venta" class="datos">

			<input type="hidden" name="action" value="addCliente">

			<input type="hidden" name="idcliente" id="idcliente" value="" required>

			<div class="wd30">
				<label for="dni_cliente" >DNI / CUIT</label>
				<input type="text" name="dni_cliente" id="dni_cliente">				
			</div>

			<div class="wd30">
				<label for="nom_cliente">Nombre</label>
				<input type="text" name="nom_cliente" id="nom_cliente" disabled required>
			</div>

			<div class="wd30">
				<label for="tel_cliente">Telefono</label>
				<input type="text" name="tel_cliente" id="tel_cliente" disabled required>
			</div>

			<div class="wd100">
				<label for="dir_cliente">Direccion</label>
				<input type="text" name="dir_cliente" id="dir_cliente" disabled required>
			</div>

			<div id="div_registro_cliente" class="wd100">
				<button type="submit" class="btn_nuevo"><i class="far fa-save fa-lg"></i> Guardar</button>
				
			</div>			
		</form>
		<br>

		<div class="datos_venta">
			<h2>Datos de Venta</h2>
			<div class="datos">
				<div class="wd50">
					<label>Vendedor</label>
					<p style="text-transform: uppercase;"><?php echo $_SESSION['nombre']."  ".$_SESSION['apellido']; ?></p>					
				</div>
				<div class="wd50">
					<label>Acciones</label>
					<div class="acciones_venta">
						<a href="#" class="btn_eliminar textcenter" id="btn_anular_venta"><i class="fas fa-ban"></i> Anular</a>
						<a href="#" class="btn_nuevo textcenter" id="btn_facturar_venta" style="display: none;"><i class="fas fa-edit"></i> Procesar</a>
					</div>
				</div>
			</div>
		</div>
		
		<table class="tbl_venta">
			<thead>
				<tr>
					<th width="100px">Codigo</th>
					<th coldspan="2">Nombre Producto</th>
					<th width="100px">Cantidad</th>
					<th class="textright">Precio</th>
					<th class="textright">Precio Total</th>
					<th colspan="2"> Accion</th>
				</tr>
				<tr>
					<td id="txt_cod_producto" id="txt_cod_producto">-</td>
					<td><input type="text" name="txt_nombre_producto" id="txt_nombre_producto"></td>
					<td><input type="text" name="txt_cant_producto" id="txt_cant_producto" value="0" min="1" disabled></td>
					<td id="txt_precio" class="textright">0.00</td>
					<td id="txt_precio_total" class="textright">0.00</td>
					<td><a href="#" id="add_producto_venta" class="link_add" ><i class="fas fa-plus"> Agregar</i></a></td>
				</tr>
				<tr>
					<th>Codigo</th>
					<th colspan="2">Descripcion</th>
					<th>Cantidad</th>
					<th class="textright">Precio</th>
					<th class="textright">Precio Total</th>
					<th>Accion</th>
				</tr>
			</thead>
			<tbody id="detalle_venta">
				<!-- FILAS ARMADAS A TRAVEZ DE AJAX -->
			</tbody>
			<tfoot id="detalle_totales">
				<!-- FILAS ARMADAS A TRAVEZ DE AJAX -->
			</tfoot>
			
		</table>
	</section>
	

	<?php include "includes/footer.php";?>
	
	<script type="text/javascript">
		$(document).ready(function(){
			var usuarioid = '<?php echo $_SESSION['idUsuario']; ?>';
			serchForDetalle(usuarioid);
			
		});
	</script>


</body>
</html>