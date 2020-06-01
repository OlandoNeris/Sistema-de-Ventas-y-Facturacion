<?php 
	
  session_start();
  
	if ($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2) {
		header("location: ./");
	} 

  include '../coneccion.php';


	$idUsuario = $_SESSION['idUsuario'];

	if(!empty($_POST)){
    
		$alert = '';

      // asignacion de los campos de POST a variables 
			$nombre_prod   	= $_POST['nombre_prod'];
			$precio_prod   	= $_POST['precio_prod']; 
      $unidad_uso     = $_POST['unidad_uso']; 
      $tipo_producto  = $_POST['tipo_producto'];
 

			// MANEJO DE LA FOTO

        $foto = $_FILES['foto'];
        $nombre_foto = $foto['name'];
        $type        = $foto['type'];
        $url_temp    = $foto['tmp_name'];
        
        $img_producto = 'img_producto.jpg';

        if ($nombre_foto != '') {
          # code...
          $destino = 'img/uploads/';
          $img_nombre = 'img_'.md5(date('d-m-y H:m:s'));
          $img_producto = $img_nombre.'.jpg';
          $src = $destino.$img_producto;
        }
         
        
        $query_insert = mysqli_query($conn,"CALL guardarNuevoProd('$nombre_prod','$precio_prod','$idUsuario','$img_producto','$tipo_producto','$unidad_uso')");

        if($query_insert)
        {
          $alert =  '<p class="msg_save">Producto Creado Con Exito!  </p>';
        }else{
          $alert =  '<p class="msg_error">No se Pudo Crear el Producto..  </p>';

        }
        mysqli_close($conn);


		    }

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
        <h1 class="h3 text-center">Alta de Producto </h1> 
        <div class="alert" id="msjUsuario"><?php echo isset($alert) ? $alert : '';?></div>
          <div class="form-row">

            <div class="form-group col-md-6">
            <label for="nombre_prod">Nombre del Producto</label>
            <input type="text" class="form-control" name="nombre_prod" id="nombre_prod" placeholder="Nombre del Producto" required> 
            </div>


          <?php // barra dee opciones para seleccionar TIPO PRODUCTO 
              include '../coneccion.php';
				      $query_tipoProd = mysqli_query($conn,"SELECT id as idtipo_prod, descripcion FROM tipo_producto WHERE id != 5 ORDER BY descripcion ASC");
				      $resultado = mysqli_num_rows($query_tipoProd);
				
				  ?>
          <div class="form-group col-md-6">
            <label for="tipo_producto">Tipo Producto</label>
            <select name="tipo_producto" id="tipo_producto" class="form-control">

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
          <label for="unidad_uso">Unidad de Uso</label>
          <select name="unidad_uso" id="unidad_uso" class="form-control">
            <option value="1" selected>Gramo (Gr)</option>
            <option value="4">Mililitro (Ml)</option>
            <option value="5">Unidad (Un)</option>
          </select>
        </div>

        <div class="form-group col-md-6">
          <label for="precio_prod">Precio</label>
          <input type="number" class="form-control" name="precio_prod" id="precio_prod" placeholder="Precio del Producto" required> 
        </div>
    </div>

    <div class="form-row" id="contenedorFoto">

        <div class="photo display-block form-group col-md-6">
					
          <label for="foto align-center">Ingresar Foto</label>
   				
           <div class="prevPhoto">
    				  <span class="delPhoto notBlock">X</span>
    				  <label for="foto"></label>
    				</div>

    				<div class="upimg">
        			<input type="file" name="foto" id="foto">
        		</div>

        		<div id="form_alert"></div>
				</div>

        
        <div class="form-group col-md-4">
            <button type="submit" id="guardarNuevoProd" class="btn btn-primary m-5 p-5 font-bold"><i class="fa fa-save"></i>   Guardar Producto</button>
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