<?php 
	
  session_start();
  
	if ($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2) {
		header("location: ./");
	} 

  include '../coneccion.php';


	$idUsuario = $_SESSION['idUsuario'];
	
	// extraer datos del producto a travez del 'id' y mostrarlos en el formulario para editar
	if(!empty($_REQUEST)){ 
    
		$alert = '';

		// asigo el valor del GET a una variable
		$idProducto = $_REQUEST['id'];


		// realizo un query para buscar los datos del producto a editar
		$query_recovery = mysqli_query($conn,"SELECT p.codproducto as idProducto, p.descripcion, p.unidad_medida, um.descripcion as unidadTxt, tp.descripcion as tipoProductotxt, p.tipo_producto, p.precio, p.foto
											  FROM producto as p INNER JOIN unidades_medida as um on p.unidad_medida = um.id
											  INNER JOIN tipo_producto as tp ON p.tipo_producto = tp.id
											  WHERE p.codproducto = '$idProducto' AND p.tipo_producto != 5 AND p.estado = 1");
		$result = mysqli_num_rows($query_recovery);
		
		// si el resultado del query es mayor a 0, es decir, el producto existe y esta dado de alta en la base de datos
		// asigno el resultado del query a variables para pooder manerjarlas, sino, redirecciono a la lista de productos.

		if($result > 0){

			// verificada la existencia, paso a guardar los valores de la consulta en variables de php para poder usarlas
			
			$datos = mysqli_fetch_assoc($query_recovery);
			
			// print_r($datos);
			
			$idProducto 	= $datos['idProducto'];
			$nombreProducto = $datos['descripcion'];
			$unidadMedida   = $datos['unidad_medida'];
			$unidadMedidaTxt = $datos['unidadTxt'];
			$tipoProducto = $datos['tipo_producto'];
			$tipoProductoTxt = $datos['tipoProductotxt'];
			$precioProducto = $datos['precio'];
			$foto 			= $datos['foto'];

			// almaceno la primera opcion del producto cuando se va a editar

			$opcionMedidaUso ='<option value="'.$unidadMedida.'" selected >'.$unidadMedidaTxt.'</option>';
			$opcionTipoProducto ='<option value="'.$tipoProducto.'" selected >'.$tipoProductoTxt .'</option>';

			// manejo de la foto
			$classRemove = 'notBlock';
			$fotoImg = '';

			if($foto != 'img_producto.jpg')
			{
				$classRemove = '';
				$fotoImg = '<img id="img" src="img/uploads/'.$foto.'" alt="Imagen producto">';

			}

			// a partir de aca queda asignar los valores a los campos en el formulario y luego actualizar los datos 

		}else{
			header("location: lista_productos.php");
		}
		

        mysqli_close($conn);


	}

	// queda pendiente el post para actualizar los datos 

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">


    <?php include "includes/scripts.php"; ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

	<title>Sistema Ventas</title>
</head>
<body style="background-color:#B4B4B4;" >

	<?php include "includes/header.php"; ?>

	<section id="container">
		<h1>Nuevo Producto</h1>
	</section>

    <div style="width: 50%;" class="container container-fluid form_nuevo_prod" >

        <form action="" method="post" enctype="multipart/form-data">
        	<h1 class="h3 text-center">Editar de Producto </h1> 
       		<div class="alert" id="msjUsuario"><?php echo isset($alert) ? $alert : '';?></div>
			
			<input type="hidden"  id="idProductoEditar" value="<?php echo $idProducto;?>" name="idProductoEditar" style="Display:none;" required>
		  	<div class="form-row">

				<div class="form-group col-md-6">
					<label for="nombreProductoEditar">Nombre del Producto</label>
					<input type="text" class="form-control" value="<?php echo $nombreProducto;?>" id="nombreProductoEditar" name="nombreProductoEditar" placeholder="Nombre del Producto" required> 
				</div>

         	    <?php // barra dee opciones para seleccionar TIPO PRODUCTO 
              		include '../coneccion.php';
				      $query_tipoProd = mysqli_query($conn,"SELECT id as idtipo_prod, descripcion FROM tipo_producto WHERE id != 5 ORDER BY descripcion ASC");
				      $resultado = mysqli_num_rows($query_tipoProd);  ?>				
				
				<div class="form-group col-md-6">
					<label for="tipoProductoEditar">Tipo Producto</label>
					<select name="tipoProductoEditar" id="tipoProductoEditar" class="form-control">
					<?php echo $opcionTipoProducto; ?>
					
					<?php   					
						if ($resultado > 0) {
						# code...
						while ($tipo_prod = mysqli_fetch_array($query_tipoProd)) {
						# code...
					?>
					<option value="<?php echo $tipo_prod['idtipo_prod'];?>" ><?php echo $tipo_prod['descripcion'];?></option>   
						
					<?php 	}	} //CIERRE DEL WHILE ?> 
					</select>
				</div>

    		</div>

    <div class="form-row">

        <div class="form-group col-md-6">
          <label for="unidadUsoProdEditar">Unidad de Uso</label>
          <select name="unidadUsoProdEditar" id="unidadUsoProdEditar" class="form-control">
		  	<?php echo $opcionMedidaUso; ?>	
            <option value="1" >Gramo (Gr)</option>
            <option value="4">Mililitro (Ml)</option>
            <option value="5">Unidad (Un)</option>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label for="precioProductoEditar">Precio</label>
          <input type="number" class="form-control" name="precioProductoEditar" value="<?php echo $precioProducto;?>" id="precioProductoEditar" placeholder="Precio del Producto" required> 
        </div>
    </div>

    <div class="form-row" id="contenedorFoto">

        <div class="photo display-block form-group col-md-6">
					
          <label for="foto align-center">Ingresar Foto</label>
   				
           <div class="prevPhoto">
    				  <span class="delPhoto <?php echo $classRemove; ?>">X</span>
    				  <label for="foto"></label>
						<?php echo $fotoImg; ?>
    				</div>

    				<div class="upimg">
        			<input type="file" name="foto" id="foto">
        		</div>

        		<div id="form_alert"></div>
				</div>

        
        <div class="form-group col-md-4">
            <button type="submit" id="guardarNuevoProd" class="btn btn-primary m-5 p-5 font-bold"><i class="fa fa-sync-alt"></i> Actualizar Producto</button>
        </div>

    </div>

    </div>
 
    </form>
    </div>


    <?php include "includes/footer.php"; ?>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
</body>
</html>