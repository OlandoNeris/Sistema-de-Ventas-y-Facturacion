<?php 

	include '../../coneccion.php';
	session_start();

// nunca jamas pongas un puto exit; cerca del encabezado 
// porque va a dar error en todo el puto codigo


if (!empty($_POST)) {

		// 	ACTUALIZAR DATOS DE LA RECETA DESDE EL FORMULARIO DE EDICION DE RECETAS

		if ($_POST['action'] == 'actualizarDatosReceta')
		{
	
			if (empty($_POST['idReceta'])) {
				echo 'error';
			}else{
	
				$idReceta = $_POST['idReceta'];
				$nombreReceta = $_POST['nombreReceta'];
				$precioReceta = $_POST['precioReceta'];
				
				$query_actualizar_datosR = mysqli_query($conn,"UPDATE producto as p 
																SET p.descripcion = '$nombreReceta', p.precio = '$precioReceta' where p.codproducto = $idReceta");
				
				if ($query_actualizar_datosR) 
				{
					echo 'ok';
	
				}else{
					echo 'error';
				}
				mysqli_close($conn);
			}
			exit;
		}

		// ELIMINAR INGREDIENTE DE LA RECETA DEL FORMULARIO DE EDICION DE PRODUCTOS ELABORADOS 
		if($_POST['action'] == 'eliminarInsumoEditarR'){
		
			$idReceta = $_POST['idReceta'];
			$idInsumo = $_POST['idInsumo'];
	
			$queryDelete = mysqli_query($conn,"CALL eliminarInsumoNuevaReceta('$idReceta','$idInsumo')");
	
			$resultado = mysqli_num_rows($queryDelete);
	
			$detalleTabla = '';
	
			if($resultado > 0){
		
				while($datos = mysqli_fetch_assoc($queryDelete)){
	

					$detalleTabla .= '
					<tr>
						<td type="hidden" style="display:none;" name="idInsumo" value="'.$datos['cod_insumo'].'"></td>
						<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;" colspan="2">'.$datos['descripcion'].'</font></font></td>
						<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['cantidad'].'</font></font></td>
						<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['unidad_medida'].'</font></font></td>
						<td>
							<div class="container d-inline d-center">
								<button type="button" class="btn btn-outline-danger ml-6 " onclick="eliminarInsumoEditarR('.$datos['cod_insumo'].','.$datos['id_receta'].');" > <i class="fas fa-trash"></i>    Eliminar</button>															
							</div>
						</td>
					</tr>' ;
				}
	
				echo json_encode($detalleTabla,JSON_UNESCAPED_UNICODE); 
	
			}else{
				echo 'Sin Datos';
			}
		}

		// 	BUSCAR RECETAR PARA EDITAR EN FORMULARIO DE EDICION DE RECETAS

		if ($_POST['action'] == 'buscarReceta') 
		{	
	
			if (!empty($_POST['nombreReceta']))
			{
	
				$nombreReceta = $_POST['nombreReceta'];
	
				$query = mysqli_query($conn,"SELECT p.codproducto as id_receta, p.descripcion as nombre, p.precio as precio_venta 
											FROM producto as p
											WHERE p.estado = 1 AND tipo_producto = 5 AND p.descripcion LIKE '$nombreReceta%'"
				);
				mysqli_close($conn);
	
				$resultado = mysqli_num_rows($query);
			
				$datos = '';
				
				if ($resultado > 0) 
				{
					$datos = mysqli_fetch_assoc($query);
				}else{
	
					$datos = 0;
				}
	
				echo json_encode($datos, JSON_UNESCAPED_UNICODE);
			}
	
			exit;
		} 


		// AGREGAR PRODUCTO AL DETALLE  
		if ($_POST['action'] == 'buscarDetalleRecetaEditar')
		{
	
			if (empty($_POST['idReceta'])) {
				echo 'error';
			}else{
	
				$idReceta = $_POST['idReceta'];

				$query_listaIngredientes = mysqli_query($conn,"SELECT p.codproducto as idInsumo, p.descripcion as nombreInsumno, dr.cantidad, um.descripcion as medida 
																FROM detalle_receta dr INNER JOIN producto as p ON dr.cod_insumo = p.codproducto 
																INNER JOIN unidades_medida as um on p.unidad_medida = um.id 
																WHERE dr.id_receta = '$idReceta'");
				$result = mysqli_num_rows($query_listaIngredientes);
			
				$detalleTabla = '';
				$arrayDatos   = array();   // para guardar los datos del detalle
	
				if ($result > 0) 
				{
	
					// armado de las filas del detalle de la receta
	
					while ($datos = mysqli_fetch_assoc($query_listaIngredientes)) 
					{
	
						$detalleTabla .= '<tr>
											<td style="display:none;">'.$datos['idInsumo'].'</td>
											<td colspan="1">'.$datos['nombreInsumno'].'</td>
											<td class="textcenter">'.$datos['cantidad'].'</td>
											<td class="textcenter">'.$datos['medida'].'</td>
											<td class="">
												<div class="container d-inline d-center">
													<button type="button" class="btn btn-outline-danger ml-6 " onclick="event.preventDefault(); eliminarInsumoEditarR('.$datos['idInsumo'].','.$idReceta.');"> <i class="fas fa-trash"></i>    Eliminar</button>															
												</div>
											</td>
										</tr>';
					// fin while	
					}
	
	
					$arrayDatos['detalle'] = $detalleTabla;
	
	
					echo json_encode($arrayDatos,JSON_UNESCAPED_UNICODE);
	
				}else{
					echo 'error';
				}
				mysqli_close($conn);
			}
			exit;
		}

	// ELIMINAR INSUMO DE LA LISTA DE INSUMOS DEL FOMULARIO NUEVO PRODUCTO ELABORADO
	if($_POST['action'] == 'eliminarInsumoNuevaReceta'){
		
		$idReceta = $_POST['idReceta'];
		$idInsumo = $_POST['idInsumo'];

		$queryDelete = mysqli_query($conn,"CALL eliminarInsumoNuevaReceta('$idReceta','$idInsumo')");

		$resultado = mysqli_num_rows($queryDelete);

		$detalleTabla = '';

		if($resultado > 0){
	
			while($datos = mysqli_fetch_assoc($queryDelete)){

				$detalleTabla .= '
								<tr>
									<td type="hidden" style="display:none;" name="idInsumo" value="'.$datos['cod_insumo'].'"></td>
									<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;" colspan="2">'.$datos['descripcion'].'</font></font></td>
									<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['cantidad'].'</font></font></td>
									<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['unidad_medida'].'</font></font></td>
									<td>
										<div class="container d-inline d-center">
											<button type="button" class="btn btn-outline-danger ml-6 " onclick="eliminarInsumoNuevaReceta('.$datos['cod_insumo'].','.$datos['id_receta'].');" > <i class="fas fa-trash"></i>    Eliminar</button>															
										</div>
									</td>
								</tr>' ;
			}

			echo json_encode($detalleTabla,JSON_UNESCAPED_UNICODE); 

		}else{
			echo 'Sin Datos';
		}
	}

	// AGREGAR INSUMO A LA RECETA 
	if($_POST['action'] == 'agregarInsumoReceta'){

		$idReceta = $_POST['idReceta'];
		$idInsumo = $_POST['idInsumo'];
		$cantidad = $_POST['cantidad'];

	
		
		$detalleTabla = '';
		$query_agregarInsumo = mysqli_query($conn,"CALL add_detalle_receta($idReceta,$idInsumo,$cantidad)");
		$resultado = mysqli_num_rows($query_agregarInsumo);


		if($resultado > 0){
			
			while($datos = mysqli_fetch_assoc($query_agregarInsumo)){

				$detalleTabla .= '
								<tr>
									<td type="hidden" style="display:none;" name="idInsumo" value="'.$datos['cod_insumo'].'"></td>
									<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;" colspan="2">'.$datos['descripcion'].'</font></font></td>
									<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['cantidad'].'</font></font></td>
									<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['unidad_medida'].'</font></font></td>
									<td>
										<div class="container d-inline d-center">
											<button type="button" class="btn btn-outline-danger ml-6 " onclick="eliminarInsumoNuevaReceta('.$datos['cod_insumo'].','.$datos['id_receta'].');" > <i class="fas fa-trash"></i>    Eliminar</button>															
										</div>
									</td>
								</tr>' ;
			}

			echo json_encode($detalleTabla,JSON_UNESCAPED_UNICODE); 

		}else{
			echo "Error al Ejecutar el Precedimiento almacenado! ";
		}
		
		exit;

	}

	// AGREGAR INSUMO A LA RECETA DEL FORMULARIO DE EDICION DE RECETA 
		if($_POST['action'] == 'agregarInsumoEditarR'){

			$idReceta = $_POST['idReceta'];
			$idInsumo = $_POST['idInsumo'];
			$cantidad = $_POST['cantidad'];
	
			$detalleTabla = '';
			$query_agregarInsumo = mysqli_query($conn,"CALL add_detalle_receta('$idReceta','$idInsumo','$cantidad')");
			$resultado = mysqli_num_rows($query_agregarInsumo);
	
			if($resultado > 0){
				
				while($datos = mysqli_fetch_assoc($query_agregarInsumo)){
	

					$detalleTabla .= '
					<tr>
						<td type="hidden" style="display:none;" name="idInsumo" value="'.$datos['cod_insumo'].'"></td>
						<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;" colspan="2">'.$datos['descripcion'].'</font></font></td>
						<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['cantidad'].'</font></font></td>
						<td><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">'.$datos['unidad_medida'].'</font></font></td>
						<td>
							<div class="container d-inline d-center">
								<button type="button" class="btn btn-outline-danger ml-6 " onclick="eliminarInsumoEditarR('.$datos['cod_insumo'].','.$datos['id_receta'].');" > <i class="fas fa-trash"></i>    Eliminar</button>															
							</div>
						</td>
					</tr>' ;

				}
	
				echo json_encode($detalleTabla,JSON_UNESCAPED_UNICODE); 
	
			}else{
				echo "Error al Ejecutar el Precedimiento almacenado! ";
			}
			
			exit;
	
		}
	
	// GUARDAR CABECERA DEL PRODUCTO ELABORADO

	if($_POST['action'] == 'guardarNuevoProdElaborado'){
		
		
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
		$idUser =  $_POST['idUser'];
		
		$sql = mysqli_query($conn,"CALL guardarCabeceraReceta('$nombre','$precio','$idUser')");
		if($sql){
			$datos = mysqli_fetch_assoc($sql);
			echo json_encode($datos, JSON_UNESCAPED_UNICODE);
		} else{
			echo 'Error';
		}
	} 

	// BUSCAR PRODUCTO ELABORADO PARA VALIDAR EXISTENCIA 

	if ($_POST['action'] == 'buscarProdElaborado') 
	{	

		if (!empty($_POST['prodElaborado']))
		{

			$prodElaborado = $_POST['prodElaborado'];

			$query = mysqli_query($conn,"SELECT p.descripcion, p.precio FROM producto as p 									     
										WHERE p.tipo_producto = 5 AND p.estado = 1 AND p.descripcion LIKE '%$prodElaborado%'");
			mysqli_close($conn);

			$resultado = mysqli_num_rows($query);
		
			$datos = '';
			
			if ($resultado > 0) 
			{
				$datos = mysqli_fetch_assoc($query);
			}else{

				$datos = 0;
			}

			echo json_encode($datos, JSON_UNESCAPED_UNICODE);
		}

		exit;
	} 



	// AGREGAR PRODUCTO AL DETALLE TEMPORAL 
	if ($_POST['action'] == 'addInsumoReceta')
	{

		if (empty($_POST['insumo']) || empty($_POST['cantidad'])) {
			echo 'error';
		}else{
			


			$codInsumo = $_POST['insumo'];
			$cantidad  	 = $_POST['cantidad'];
			$unidadUsoTxt   = $_POST['unidadUso'];
			$idReceta  	 = $_POST['receta'];

			$query_idUnidadUso = mysqli_query($conn,"SELECT id FROM unidades_medida WHERE descripcion = '$unidadUsoTxt'");
			$idUnidadUso_array = mysqli_fetch_assoc($query_idUnidadUso);
			$idUnidadUso  = $idUnidadUso_array['id'];


			echo $idReceta.$codInsumo.$cantidad.$idUnidadUso;
			exit;
			// llamo al procedimiento almacenado, pasandole los parametros 

			$query_detalle_receta = mysqli_query($conn,"CALL add_detalle_receta($idReceta,$codInsumo,$cantidad,$idUnidadUso)");
			$resultado_detalle = mysqli_num_rows($query_detalle_receta);


			$detalleTabla = '';
			$arrayDatos   = array();   // para guardar los datos del detalle

			if ($resultado_detalle > 0) 
			{

				// armado de las filas del detalle de la receta

				while ($datos = mysqli_fetch_assoc($query_detalle_receta)) 
				{
					if ($datos['unidad_medida'] == 1) {
						$unidadMedidaDesc =  'Gramo';
					};


					$detalleTabla .= '<tr>
										<td>'.$datos['idtoken'].'</td>
										<td colspan="1">'.$datos['descripcion'].'</td>
										<td class="textcenter">'.$datos['cantidad'].'</td>
										<td class="textcenter">'.$unidadMedidaDesc.'</td>
										<td class="">
											<a href="#" class="link_delete" onclick="event.preventDefault(); del_insumo_receta('.$datos['idtoken'].');"><i class="fas fa-trash-alt"></i></a>
										</td>
									</tr>';
				// fin while	
				}


				$arrayDatos['detalle'] = $detalleTabla;


				echo json_encode($arrayDatos,JSON_UNESCAPED_UNICODE);

			}else{
				echo 'error';
			}
			mysqli_close($conn);
		}
		exit;
	}

}

exit;

 ?>

