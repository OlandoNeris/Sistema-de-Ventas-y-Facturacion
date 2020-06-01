<?php 
	session_start();
	

	if ($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2) {
		header("location: ./");
	} 

	include '../coneccion.php';

	$idUsuario = $_SESSION['idUsuario'];


// SI SE DA CLICK EN "ACTUALIZAR PRODUCTO" 	
	if(!empty($_POST))
	{
		$alert = '';

		if ( empty($_POST['nombre']) || empty($_POST['precio']) || $_POST['precio'] <= 0 || empty($_POST['id']) || empty($_POST['foto_actual']) || empty($_POST['foto_remove']))
		{
			$alert = ' <p class="msg_error">Todos los Campos son Obligatorios y Los Numero Positivos </p>';			
		}else{			

			$codigo_producto = $_POST['id'];
			$nombre  	  = $_POST['nombre'];
			$precio       = $_POST['precio'];
			$img_producto  = $_POST['foto_actual'];
			$imgRemove    = $_POST['foto_remove'];

			// MANEJO DE LA FOTO

			$foto = $_FILES['foto'];
			$nombre_foto = $foto['name'];
			$type        = $foto['type'];
			$url_temp    = $foto['tmp_name'];
			
			$update = '';

			if ($nombre_foto != '') {
				# code...
				$destino = 'img/uploads/';
				$img_nombre = 'img_'.md5(date('d-m-y H:m:s'));
				$img_producto = $img_nombre.'.jpg';
				$src = $destino.$img_producto;
			}else{
				if ($img_producto != $imgRemove) {
					
					$img_producto = 'img_producto.jpg';
				}

			}

			$query_update = mysqli_query($conn,"UPDATE producto
												SET descripcion = '$nombre',
													precio      = $precio,
													foto        = '$img_producto'
												WHERE codproducto = $codigo_producto");
			if($query_update){

				if (($nombre_foto != '' && ($_POST['foto_actual'] != 'img_producto.jpg')) || ($_POST['foto_actual'] != $_POST['foto_remove'])) {
					# code...
					unlink('img/uploads/'.$_POST['foto_actual']);
				}

				if ($nombre_foto != ''){
					move_uploaded_file($url_temp, $src);
				}
				$alert =  '<p class="msg_save">Producto Actualizado Con Exito!  </p>';
			}else{
				$alert =  '<p class="msg_error">No se Pudo Actualizar el Producto..  </p>';

			}

		}

		}




// 	TRAER E INFORMAR LOS DATOS DEL PRODUCTO  <----------------------

	// 	validar producto

	if (empty($_REQUEST['id'])) 
	{
		header("location: lista_productos.php");
	}else{

		$id_producto = $_REQUEST['id'];
		if (!is_numeric($id_producto)) {
			header("location: lista_productos.php");
		}
		// SE TRAEN LOS DATOS DEL PRODUCTO CON EL ID QUE VIENE EN EL REQUEST
		$query_producto = mysqli_query($conn,"SELECT p.codproducto,descripcion, p.proveedor as idproveedor, pr.proveedor as nom_proveedor,precio,foto
											  FROM producto as p INNER JOIN proveedor as pr ON p.proveedor = pr.codproveedor
											  WHERE codproducto = $id_producto AND p.estado = 1");
		$resultado = mysqli_num_rows($query_producto);


		// VARIABLES PARA TRABAJAR EL ALMACENAMIENTO DE LA FOTO 
		$foto = '';
		$classRemove =  'notBlock';


		// SI EL QUERY DEVUELVE RESULTADOS, HAGO LO SIGUIENTE 
		if ($resultado > 0) {
			
			$datos_producto = mysqli_fetch_assoc($query_producto);

			if ($datos_producto['foto'] != 'img_producto.jpg') {
				$classRemove = '';
				$foto = '<img id="img" src="img/uploads/'.$datos_producto['foto'].'" alt="Producto">';
			}

		}else{ // SINO, SE REDIRECCIONA A LA LISTA DE PRODUCTOS 
			header("location: lista_productos.php");
		}

	}


 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	
	<?php include "includes/scripts.php"; ?>

	<title>Editar Producto</title>
</head>



<body>

	<?php include "includes/header.php"; ?>

	<section id="container">

		<div class="form_registro">

			<h1><i class="fas fa-cubes"></i> Editar Producto</h1>
			<hr>
			<div class="alert"><?php echo isset($alert) ? $alert : '';?></div>

			<form action="" method="post" enctype="multipart/form-data">
				<?php // CAMPOS PARA REEMPLAZAR LA FOTO NUEVA  ?>
				<input type="hidden" name="id" value="<?php echo $id_producto;?>">
				<input type="hidden" id="foto_actual" name="foto_actual" value="<?php echo $datos_producto['foto'];?>">
				<input type="hidden" id="foto_remove" name="foto_remove" value="<?php echo $datos_producto['foto'];?>">


				<label for="nombre" >Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Ingrese Nombre del Producto " value="<?php echo $datos_producto['descripcion'];?>">

				<label for="precio" >Precio</label>
				<input type="number" name="precio" id="precio" min=0.01 step="any" placeholder="Ingrese precio del Producto" value="<?php echo $datos_producto['precio'];?>">

				<div class="photo">
					<label for="foto">Foto</label>
   				    <div class="prevPhoto">
    				<span class="delPhoto <?php echo $classRemove;?>">X</span>
    				<label for="foto"></label>
    				<?php echo $foto; ?>
    				</div>
    				<div class="upimg">
        			<input type="file" name="foto" id="foto">
        			</div>
        			<div id="form_alert"></div>
				</div>

				<button type="submit" class="btn_enviar"><i class="fas fa-save"></i>  Actualizar Producto</button>

			</form>

		</div>

	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>