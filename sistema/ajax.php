<?php 

	include '../coneccion.php';
	session_start();

// nunca jamas pongas un puto exit; cerca del encabezado 
// porque va a dar error en todo el puto codigo


if (!empty($_POST)) {




	if ($_POST['action'] == 'aperturaCaja') 
	{
		$idUser = $_POST['idUser'];
		// primero verifico que el usuario no tenga ninguna caja abierta para poder abrir una 

		$query_apertura = mysqli_query($conn, "SELECT * FROM cajas WHERE cajas.id_usuario = '$idUser' AND cajas.estado = 1");
		$resultado = mysqli_num_rows($query_apertura);

		if($resultado == 0) // si no tiene cajas abiertas, se llamara al procedimiento almacenado 
		{
			$query_procedimiento_apertura = mysqli_query($conn,"CALL aperturaCaja('$idUser')");
			$datos = $datos = mysqli_fetch_assoc($query_procedimiento_apertura);
            echo json_encode($datos, JSON_UNESCAPED_UNICODE);	
			
		}else{
			echo 0;
		}

	

		exit;		
	}

		// VALIDAR NUEVO PRODUCTO A GUARDAR
		if ($_POST['action'] == 'ValidarNuevoProducto') 
		{	
			

			if (!empty($_POST['nuevoProducto']))
				{

					$nuevoProducto = $_POST['nuevoProducto'];

					$query = mysqli_query($conn,"SELECT p.descripcion FROM producto as p WHERE p.descripcion LIKE '%$nuevoProducto' AND p.estado = 1");
					mysqli_close($conn);

					$resultado = mysqli_num_rows($query);

					$datos = '';
					if ($resultado > 0) 
					{
						$datos = '1';
					}else{

						$datos = '0';
					}

				echo json_encode($datos, JSON_UNESCAPED_UNICODE);
				}	

			exit;
		}


	// EXTRAER DATOS DEL PRODUCTO  	

	if ($_POST['action'] == 'infoProducto') {
		# code...
		// echo "consulta producto"; exit;

		$producto_id = $_POST['producto'];

		$query = mysqli_query($conn,"SELECT codproducto, descripcion,precio FROM producto 
									 WHERE (descripcion LIKE '%$producto_id') AND tipo_producto != 4 AND estado = 1");

		mysqli_close($conn);

		$resultado = mysqli_num_rows($query);
		if ($resultado > 0) {
			# code...
			$datos = mysqli_fetch_assoc($query);
			echo json_encode($datos, JSON_UNESCAPED_UNICODE);
			exit();
		}
		echo "error";
		
		
		exit();

	}


	// AGREGAR EXISTENCIA A LOS PRODUCTOS (tabla entradas)
	if ($_POST['action'] == 'addExistencia') 
	{

		if (!empty($_POST['producto_id']) and  !empty($_POST['txtPrecio']) and !empty($_POST['txtCantidad'])) 
		{
			# code...
			
			$cantidad = $_POST['txtCantidad'];
			$precio   = $_POST['txtPrecio'];
			$producto_id = $_POST['producto_id'];
			$usuario_id = $_SESSION['idUsuario'];

			$query_insert = mysqli_query($conn,"INSERT INTO entradas(codproducto,cantidad,precio,usuario_id)
				VALUES($producto_id,$cantidad,$precio,$usuario_id)");

			// SI EL QUERY SE EJECUTO CORRECTAMENTE, SE EJECUTA EL PRODECIMIENTO ALMACENADO
			if ($query_insert) 
			{

				$query_update = mysqli_query($conn,"CALL actualizar_precio_producto($cantidad,$precio,$producto_id)");

				$resultado_proc = mysqli_num_rows($query_update);

				if($resultado_proc > 0)
				{

					$datos = mysqli_fetch_assoc($query_update);

					$datos['producto_id'] = $producto_id;

					echo json_encode($datos, JSON_UNESCAPED_UNICODE);
					exit();
				}
				
			}else{
				echo 'error';
			}

			mysqli_close($conn);

		}

	}

	// ELIMINAR PRODUCTO 	

	if ($_POST['action'] == 'eliminarProd') 
	{
		# code...
		if (empty($_POST['producto_id']) || !is_numeric($_POST['producto_id'])) {
			echo 'error';
		}else{
			$idproducto = $_POST['producto_id'];
			$query_delete = mysqli_query($conn,"UPDATE producto SET estado = 0 WHERE codproducto = $idproducto");
			mysql_close($conn);

			if ($query_delete) {
				echo 'ok';
			}else{
				echo 'error';

			}
		}
	echo 'error';

	exit;		
	}

	// BUSCAR CLIENTE 

	if ($_POST['action'] == 'buscarCliente') 
	{	

		if (!empty($_POST['cliente']))
		{
			$dni = $_POST['cliente'];

			$query = mysqli_query($conn,"SELECT * FROM cliente WHERE dni_cliente LIKE '$dni' AND estado = 1");
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


	// REGISTRAR CLIENTE DESDE MODULO DE VENTAS 

	if ($_POST['action'] == 'addCliente') 
	{	
		// GUARDO LOS VALORES QUE VIENEN POR POST A TRAVEZ DE AJAX

		$dni 		= $_POST['dni_cliente'];
		$nombre 	= $_POST['nom_cliente'];
		$telefono 	= $_POST['tel_cliente'];
		$direccion 	= $_POST['dir_cliente'];
		$usuario_id = $_SESSION['idUsuario'];

		$query_insert = mysqli_query($conn,"INSERT INTO cliente(dni_cliente,nombre,telefono,direccion,usuario_id)
											VALUES('$dni','$nombre','$telefono','$direccion',$usuario_id)");

		if ($query_insert) 
		{
			# code...
			$codCliente = mysqli_insert_id($conn);
			$msg = $codCliente;
		}else{
			$msg='error';
		}
		mysqli_close($conn);
		echo $msg;

		exit;
	}


	// AGREGAR PRODUCTO AL DETALLE TEMPORAL 
	if ($_POST['action'] == 'addProductoDetalle')
	{

		if (empty($_POST['producto']) || empty($_POST['cantidad'])) {
			echo 'error';
		}else{


			$codproducto = $_POST['producto'];
			$cantidad  	 = $_POST['cantidad'];
			$token		 = md5($_SESSION['idUsuario']);

			// trabajare a partir de aca para cambiar la funcionalidad del boton
			// primero busco el tipo del producto que es para saber por donde ir 

			$query_tipo_prod = mysqli_query($conn, "SELECT tipo_producto as tipo FROM producto as p WHERE p.codproducto = '$codproducto'");
			$resultado_tipo_prod = mysqli_fetch_assoc($query_tipo_prod);

			if( $resultado_tipo_prod['tipo'] == 5 ) // si es un producto elaborado, entrara por aca
			{
				
				// buscar la receta con el id 

				$query = mysqli_query($conn,"SELECT r.cod_insumo as idInsumo, r.cantidad, p.stock
				FROM detalle_receta as r INNER JOIN producto as p ON r.cod_insumo = p.codproducto  
				WHERE r.id_receta = $codproducto");

				$datos = array(); //  contenedor para las filas del query

				// guardado del query en el array "$datos"
				while($row = mysqli_fetch_array($query))
				{
					$datos[] = array(
					'idInsumo' => $row['idInsumo'],
					'cantidad' => $row['cantidad'],
					'stock' => $row['stock'],
					);
				} 

				// recorrida y evaluacion de si el stock sera suficiene para afrontar el pedido 
				for($i=0; $i <= (count($datos)-1); $i++)
				{
					$aux_cant_pedido = $datos[$i]['stock'] * $cantidad;
					if($datos[$i]['stock'] < $aux_cant_pedido)
					{
						echo "sin stock";
						exit; // si no tiene stock cualquiera de los insumos de la receta para afrontar el pedido,
					}		  // directamente lo sacara de la operacion y no seguira. 
				}				

			}else{ // esto hara si es un producto que no es elaborado

				
				$query_stock = mysqli_query($conn,"SELECT stock FROM producto WHERE codproducto = '$codproducto'");
				$resultado_stock_prod = mysqli_fetch_assoc($query_stock);
				$resultado_stock_prod['stock'];

				if(($resultado_stock_prod['stock'] - $cantidad) < 0 )
				{
					echo "sin stock"; // si no tiene stock cualquiera de los insumos de la receta para afrontar el pedido,
					exit;			  // directamente lo sacara de la operacion y no seguira.
				}
				
			}

			// ----------------------------------------------------------------


			// la tabla configuracion se crea para hacer las facturas y demas...

			$query_iva = mysqli_query($conn,"SELECT iva FROM configuracion");
			$resultado_iva = mysqli_num_rows($query_iva);

			// llamo al procedimiento almacenado, pasandole los parametros 
			$query_detalle_temp = mysqli_query($conn,"CALL add_detalle_temp($codproducto,$cantidad,'$token')");
			$resultado_detalle = mysqli_num_rows($query_detalle_temp);

			$detalleTabla = '';
			$sub_total	  = 0;
			$monto_iva 	  = 0;
			$total 		  = 0;
			$arrayDatos   = array();   // para guardar los datos del detalle

			if ($resultado_detalle > 0) 
			{
				if ($resultado_iva > 0) // si hay resultado del query de iva, se guarda ese valor en la variable
				{
					$info_iva = mysqli_fetch_assoc($query_iva);
					$monto_iva = $info_iva['iva'];
				}

				// armado de las filas del detalle de la compra

				while ($datos = mysqli_fetch_assoc($query_detalle_temp)) 
				{
					$precio_total = round($datos['cantidad'] * $datos['precio_venta'],2);
					$sub_total	  = round($sub_total + $precio_total,2);
					$total 		  = round($total + $precio_total,2);

					$detalleTabla .= '<tr>
										<td>'.$datos['codproducto'].'</td>
										<td colspan="2">'.$datos['descripcion'].'</td>
										<td>'.$datos['cantidad'].'</td>
										<td class="textright">'.$datos['precio_venta'].'</td>
										<td class="textright">'.$precio_total.'</td>
										<td class="">
											<a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle('.$datos['correlativo'].');"><i class="fas fa-trash-alt"></i></a>
										</td>
									</tr>';
				// fin while	
				}

				//calculo de totales 

				$impuesto = round($sub_total * ($monto_iva / 100),2);
				$total_sin_iva = round($sub_total - $impuesto,2);
				$total  = round($total_sin_iva + $impuesto, 2);

				$detalle_totales = '<tr>
										<td colspan="5" class="textright">SUBTOTAL $</td>
										<td class="textright">'.$total_sin_iva.'</td>
									</tr>
									<tr>
										<td colspan="5" class="textright">IVA ('.$monto_iva.'%)</td>
										<td class="textright">'.$impuesto.'</td>
									</tr>
									<tr>
										<td colspan="5" class="textright">TOTAL $</td>
										<td class="textright">'.$total.'</td>
									</tr>';


				$arrayDatos['detalle'] = $detalleTabla;
				$arrayDatos['totales'] = $detalle_totales;

				echo json_encode($arrayDatos,JSON_UNESCAPED_UNICODE);

			}else{
				echo 'error';
			}

		


			mysqli_close($conn);
		}
		exit;
	}


	// RECUPERAR DATOS DEL DETALLE TEMPORAL 
	if ($_POST['action'] == 'serchForDetalle')
	{
		if (empty($_POST['user'])) 
		{
			echo 'error';
		}else{

			$token		 = md5($_SESSION['idUsuario']);


			$query_iva = mysqli_query($conn,"SELECT iva FROM configuracion");
			$resultado_iva = mysqli_num_rows($query_iva);

			$query_detalle_temp = mysqli_query($conn,"SELECT tmp.correlativo,
															 tmp.token_user,
															 tmp.cantidad,
															 tmp.precio_venta,
															 p.codproducto,
															 p.descripcion
													  FROM detalle_temp as tmp
													  INNER JOIN producto as p
													  ON tmp.codproducto = p.codproducto
													  WHERE token_user = '$token'");

			$resultado_detalle = mysqli_num_rows($query_detalle_temp);

			$detalleTabla = '';
			$sub_total	  = 0;
			$monto_iva 	  = 0;
			$total 		  = 0;
			$arrayDatos   = array();

			if ($resultado_detalle > 0) 
			{
				if ($resultado_iva > 0) 
				{
					$info_iva = mysqli_fetch_assoc($query_iva);
					$monto_iva = $info_iva['iva'];
				}

				while ($datos = mysqli_fetch_assoc($query_detalle_temp)) 
				{
					$precio_total = round($datos['cantidad'] * $datos['precio_venta'],2);
					$sub_total	  = round($sub_total + $precio_total,2);
					$total 		  = round($total + $precio_total,2);

					$detalleTabla .= '<tr>
										<td>'.$datos['codproducto'].'</td>
										<td colspan="2">'.$datos['descripcion'].'</td>
										<td>'.$datos['cantidad'].'</td>
										<td class="textright">'.$datos['precio_venta'].'</td>
										<td class="textright">'.$precio_total.'</td>
										<td class="">
											<a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle('.$datos['correlativo'].');"><i class="fas fa-trash-alt"></i></a>
										</td>
									</tr>';
				// fin while	
				}

				//calculo de totales 

				$impuesto = round($sub_total * ($monto_iva / 100),2);
				$total_sin_iva = round($sub_total - $impuesto,2);
				$total  = round($total_sin_iva + $impuesto, 2);

				$detalle_totales = '<tr>
										<td colspan="5" class="textright">SUBTOTAL $</td>
										<td class="textright">'.$total_sin_iva.'</td>
									</tr>
									<tr>
										<td colspan="5" class="textright">IVA ('.$monto_iva.'%)</td>
										<td class="textright">'.$impuesto.'</td>
									</tr>
									<tr>
										<td colspan="5" class="textright">TOTAL $</td>
										<td class="textright">'.$total.'</td>
									</tr>';


				$arrayDatos['detalle'] = $detalleTabla;
				$arrayDatos['totales'] = $detalle_totales;

				echo json_encode($arrayDatos,JSON_UNESCAPED_UNICODE);

			}else{
				echo 'error';
			}
			mysqli_close($conn);
		}
		exit;
	}

	// QUITAR PRODUCTO DEL DETALLE DE FACTURA
	if ($_POST['action'] == 'del_producto_detalle')
	{
		if (empty($_POST['id_detalle'])) 
		{
			echo 'error';
		}else{

			$id_detalle  = $_POST['id_detalle'];
			$token		 = md5($_SESSION['idUsuario']);

			$query_iva = mysqli_query($conn,"SELECT iva FROM configuracion");
			$resultado_iva = mysqli_num_rows($query_iva);



			$query_detalle_temp = mysqli_query($conn,"CALL del_detalle_temp($id_detalle,'$token')");

			$resultado_detalle = mysqli_num_rows($query_detalle_temp);

			$detalleTabla = '';
			$sub_total	  = 0;
			$monto_iva 	  = 0;
			$total 		  = 0;
			$arrayDatos   = array();

			if ($resultado_detalle > 0) 
			{
				if ($resultado_iva > 0) 
				{
					$info_iva = mysqli_fetch_assoc($query_iva);
					$monto_iva = $info_iva['iva'];
				}

				while ($datos = mysqli_fetch_assoc($query_detalle_temp)) 
				{
					$precio_total = round($datos['cantidad'] * $datos['precio_venta'],2);
					$sub_total	  = round($sub_total + $precio_total,2);
					$total 		  = round($total + $precio_total,2);

					$detalleTabla .= '<tr>
										<td>'.$datos['codproducto'].'</td>
										<td colspan="2">'.$datos['descripcion'].'</td>
										<td>'.$datos['cantidad'].'</td>
										<td class="textright">'.$datos['precio_venta'].'</td>
										<td class="textright">'.$precio_total.'</td>
										<td class="">
											<a href="#" class="link_delete" onclick="event.preventDefault(); del_producto_detalle('.$datos['correlativo'].');"><i class="fas fa-trash-alt"></i></a>
										</td>
									</tr>';
				// fin while	
				}

				//calculo de totales 

				$impuesto = round($sub_total * ($monto_iva / 100),2);
				$total_sin_iva = round($sub_total - $impuesto,2);
				$total  = round($total_sin_iva + $impuesto, 2);

				$detalle_totales = '<tr>
										<td colspan="5" class="textright">SUBTOTAL $</td>
										<td class="textright">'.$total_sin_iva.'</td>
									</tr>
									<tr>
										<td colspan="5" class="textright">IVA ('.$monto_iva.'%)</td>
										<td class="textright">'.$impuesto.'</td>
									</tr>
									<tr>
										<td colspan="5" class="textright">TOTAL $</td>
										<td class="textright">'.$total.'</td>
									</tr>';


				$arrayDatos['detalle'] = $detalleTabla;
				$arrayDatos['totales'] = $detalle_totales;

				echo json_encode($arrayDatos,JSON_UNESCAPED_UNICODE);

			}else{
				echo 'error';
			}
			mysqli_close($conn);
		}
		exit;
	}

	// ANULAR FACTURA ANTES DE EMITIR 
	if ($_POST['action'] == 'anularVenta')
	{

		$token		 = md5($_SESSION['idUsuario']);
		$query_delete = mysqli_query($conn,"DELETE FROM detalle_temp WHERE token_user = '$token'");
		mysqli_close($conn);
		if ($query_delete) 
		{
		 	echo 'ok';
		}else{
			echo 'error';
		}
		exit;
	}

	// EMITIR VENTA 
	if ($_POST['action'] == 'emitirVenta')
	{

		if (empty($_POST['codcliente'])) 
		{
			$codcliente = 1;
		}else{
			$codcliente = $_POST['codcliente'];
		}
		
		$token		 = md5($_SESSION['idUsuario']);
		$idusuario   = $_SESSION['idUsuario'];

		$query = mysqli_query($conn,"SELECT * FROM detalle_temp WHERE token_user = '$token'");
		$result = mysqli_num_rows($query);

		if ($result > 0) 
		{
			$query_emitir = mysqli_query($conn, "CALL procesar_venta($idusuario,$codcliente,'$token')");
			$resultado_venta = mysqli_num_rows($query_emitir);

			if ($resultado_venta > 0) 
			{
				$datos = mysqli_fetch_assoc($query_emitir);
				echo json_encode($datos,JSON_UNESCAPED_UNICODE);
			}else{
				echo 'error';
			}
		}else{
			echo 'error';
		}
		mysqli_close($conn);
		exit;
	}


	// ANULAR VENTA

	if ($_POST['action'] == 'infoFactura')
	{
		if(!empty($_POST['nofactura']))
		{
			$nofactura = $_POST['nofactura'];
			$query = mysqli_query($conn, "SELECT * FROM factura WHERE nofactura = $nofactura AND estado = 1");
			mysqli_close($conn);

			$resultado = mysqli_num_rows($query);
			if ($resultado > 0) 
			{
				$datos = mysqli_fetch_assoc($query);
				echo json_encode($datos,JSON_UNESCAPED_UNICODE);
				
				exit;
			}


		}
		echo 'error';

		exit;
	}


    // ANULAR FACTURA (es llamado desde el modal para anular facturas)

	if ($_POST['action'] == 'anularFactura')
	{
		if(!empty($_POST['noFactura']))
		{

			$noFactura = $_POST['noFactura'];

			$query_anular =  mysqli_query($conn,"CALL anular_factura($noFactura)");
			$resultado = mysqli_num_rows($query_anular);
			mysqli_close($conn);

			if($resultado > 0)
			{

				$datos = mysqli_fetch_assoc($query_anular);
				echo json_encode($datos, JSON_UNESCAPED_UNICODE);
				exit;
			}

		}
		
		echo "error";
		exit;
	}



	// BUSCAR INSUMOS PARA RECETA

	if ($_POST['action'] == 'infoInsumo') {
		# code...
		// echo "consulta producto"; exit;


		$insumo_desc = $_POST['insumo'];

		$query = mysqli_query($conn,"SELECT p.codproducto as id_insumo, p.descripcion as nom_insumo, um.descripcion as unidad_uso 
								     FROM producto as p INNER JOIN unidades_medida as um ON p.unidad_uso = um.id
									 WHERE p.descripcion LIKE '%$insumo_desc' AND estado = 1 AND tipo_producto = 4");

		mysqli_close($conn);

		$resultado = mysqli_num_rows($query);
		if ($resultado > 0) {
			# code...
			$datos = mysqli_fetch_assoc($query);
			echo json_encode($datos, JSON_UNESCAPED_UNICODE);
			exit();
		}
		echo "error";
		exit();
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

