<?php 

 
	
  session_start();
  
	if ($_SESSION['idrol'] != 1 and $_SESSION['idrol'] != 2) {
		header("location: ./");
	} 

  include '../../coneccion.php';


  $idUsuario = $_SESSION['idUsuario'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
  
  <?php include "../sistema2/header2.php"; ?>


	<title>Sistema Ventas</title>

</head>
<body style="background-color:#B4B4B4;" >


	<section id="container">
		<h1>Nuevo Producto Elaborado</h1>
	</section>

    <div   class="form_opciones_producto  container pr-5 pl-5">
      <form class="form-inline">        
        <div class="form-group mx-sm-3 mb-2">
          <input type="text" id="buscar_prod_elaborado" class="form-control buscar_prod_elaborado" onclick="mostrarFormEditarReceta();"  placeholder=" Buscar Receta... "> 
        </div>
        <button type="button" class="btn btn-primary mb-2 ml-5" onclick="mostrarFormRecetaNueva();"><i class="fas fa-plus" ></i> Añadir Nueva Receta</button>
      </form>
    </div>
     
    <!-- FORMULARIO PARA CREAR UN NUEVO PRODUCTO ELABORADO -->
    <div id="form_agregar_receta" action="" name="guardarCabeceraReceta"  class="container pr-5 pl-5">

        <form method="post" enctype="multipart/form-data">
        	<h1 class="h3 text-center">Alta de Producto Elaborado </h1> 
			    <div id="msjErrorNuevaReceta" class="alert"><?php echo isset($alert) ? $alert : '';?></div>
          
          <input type="hidden" id="idUsuario" name="idUsuario" value="<?php echo $idUsuario; ?>" > 
          <input type="hidden" id="idReceta"  name="idReceta" value="" >

        	<div class="form-row">
				    <div class="form-group col-md-4">
              <label for="nombreNuevoProdElaborado">Nombre</label>
              <input type="text" class="form-control" name="nombreNuevoProdElaborado" id="nombreNuevoProdElaborado" placeholder="Nombre del Producto" required> 
				    </div>

				    <div class="form-group col-md-4">
              <label for="precioNuevoProdElaborado">Precio</label>
              <input type="number" class="form-control" name="precioNuevoProdElaborado" id="precioNuevoProdElaborado" placeholder="Precio del Producto" required> 
            </div>

            <div class="form-group col-md-4">
              <button type="submit" id="guardarNuevoProductoElaborado" class="btn btn-primary font-bold mt-5 ml-3 "><i class="fa fa-save"></i> Guardar Producto</button>
            </div>

          </div>           

		    </form>

        <div class="alert alert-primary mt-1 mb-1" role="alert">
            Lista de Ingredientes
                <!-- Button trigger modal -->
            <button type="button" id="btnAgregarIngredienteNuevoProdElaborado" onclick="agregarInsumoReceta();" class="btn btn-primary ml-4" data-toggle="modal" data-target="#staticBackdrop"><i class="fas fa-plus"></i>  Agregar Ingrediente</button>
			  </div>
            <table class="table table-striped table-hovertext-center p-4">
                <thead>
                    <tr class="text-center">
                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;" colspan="2">Insumo</font></font></th>
						            <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Cantidad</font></font></th>
                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Medida</font></font></th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="listaIngredientesNuevaReceta">
                <!--  FILAS ARMADAS A TRAVEZ DE AJAX-->
                </tbody>
            </table>
        </div>
    </div>




    <!-- FORMULARIO PARA EDITAR UNA RECETA EXISTENTE -->
    <div id="form_editar_receta"  class="container pr-5 pl-5">

        <form   enctype="multipart/form-data">
        	<h1 class="h3 text-center">Editar Producto Elaborado </h1> 
			    <div class="alert" id="msj_editar_receta"><?php echo isset($alert) ? $alert : '';?></div>
          <input type="hidden" id="idRecetaEditarR"  name="idRecetaEditarR" value="" >
			
        	<div class="form-row">
				    <div class="form-group col-md-4">
              <label for="nombre_receta_editar">Nombre</label>
              <input type="text" class="form-control" name="nombre_receta_editar" id="nombre_receta_editar" placeholder="Nombre del Producto" required disabled> 
				    </div>

				    <div class="form-group col-md-4">
              <label for="precio_receta_editar">Precio</label>
              <input type="number" class="form-control" name="precio_receta_editar" id="precio_receta_editar" placeholder="Precio del Producto" required disabled>  
            </div>

            <div class="form-group col-md-4">
              <button type="button" class="btn btn-primary font-bold mt-5 ml-3 " id="actualizar_receta" disabled><i class="fas fa-sync-alt"></i> Actualizar</button>
            </div>

       		</div> 
		    </form>

        <div class="alert alert-primary mt-1 mb-1" role="alert">
            Lista de Ingredientes
            <button type="button" class="btn btn-info ml-5" id="add_ingrediente_editar_receta" data-toggle="modal" data-target="#staticBackdrop2" disabled><i class="fa fa-plus"></i> Agregar Ingrediente</button>
			  </div>
            <table class="table table-striped table-hovertext-center p-4">
                <thead>
                    <tr class="text-center">
                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;" colspan="2">Insumo</font></font></th>
						            <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Cantidad</font></font></th>
                        <th><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Medida</font></font></th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody id="lista_insumo_editar_receta">
                <!-- FILAS ARMADAS A TRAVEZ DE AJAX -->
                </tbody>
            </table>
        </div>
    </div>

  </body>
</html>