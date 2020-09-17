<?php 

include('coneccion.php');

  // RECOPILACION DE DATOS 

  $id_receta = 37;
  $cantidad_pedido = 1;


  // buscar la receta con el id 

  $query = mysqli_query($conn,"SELECT r.cod_insumo as idInsumo, r.cantidad, p.stock
                                FROM detalle_receta as r INNER JOIN producto as p ON r.cod_insumo = p.codproducto  
                              WHERE r.id_receta = $id_receta");
  
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
for($i=0; $i <= (count($datos)-1); $i++){

  $aux_cant_pedido = $datos[$i]['stock'] * $cantidad_pedido;
  if($datos[$i]['stock'] < $aux_cant_pedido)
  {
    echo "stock insuficuente"; 
  }

}


  // recorrer la receta con indices y demas 

  // revisar ingrediente por ingrediente que el stock sea suficiente 


	echo "hola mundo";


?>

<!DOCTYPE html>

<html>

<head>

	<title>provando</title>

</head>

<body>





</body>

</html>