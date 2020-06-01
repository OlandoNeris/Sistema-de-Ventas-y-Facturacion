		<?php 

		if(empty($_SESSION['activa'])){
		header('location: ../');
		}

	?>

	<header>
		<?php include "scripts2.php"; ?>
		<div class="header">
			
			<h1>Sistema Facturación</h1> 
			<div class="optionsBar">
				<p>Argentina, <?php echo fechaC(); ?></p>
				<span>|</span>
				<span class="user"> <?php echo "Hola ".$_SESSION['nombre'].' - '.$_SESSION['rol']; ?></span>
				<img class="photouser" src="../img/user.png" alt="Usuario"> 
				<a href="salir.php"><img class="close" src="../img/salir.png" alt="Salir del sistema" title="Salir"></a>
			</div>
		</div>
		<?php include "../includes/navbar.php"; ?>

	</header>

	<div class="modal">
		<div class="bodyModal">

		</div>		
	</div>

	<div class="modalAddInsumo">
	< !--Modal Agregar Insumo a la Receta-- >
        <div class="modal fade w-60 " id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
						<div class="alert alert-secondary" role="alert">
							<h5 class="modal-title " id="staticBackdropLabel">Agregar Insumo a la Receta</h5>
						</div>					
                    </div>
                    <div class="modal-body">
                        <div class="input-group mb-3">
							<?php 

							include '../../coneccion.php';

							$query = mysqli_query($conn,"SELECT p.codproducto as idInsumo, p.descripcion as nombre, p.unidad_medida as unidades_medida 
															 FROM producto as p WHERE p.tipo_producto = 4 AND p.estado = 1");
							mysqli_close($conn);
							$result = mysqli_num_rows($query);

							?>

							<select class="custom-select" id="inputGroupSelect02">
								<option selected>Seleccione el Insumo</option>
							<?php 

								if($result > 0)
								{
									while ($insumo = mysqli_fetch_array($query))
									{
							?>	
								<option value="<?php echo $insumo["idInsumo"]; ?>"><?php echo $insumo["nombre"]; ?></option>
							<?php
									}
								}

							?>							
							</select>

      				</div>
					  <div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-default">Unidad de Medida</span>
						</div>
						<input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" disabled>
						</div>
						<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text" id="inputGroup-sizing-default">Cantidad A Usar</span>
						</div>
						<input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
						</div>
                    <div class="modal-footer ">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-exit"></i>Cerrar</button>
                        <button type="button" class="btn btn-primary"><i class="fas fa-plus"></i>  Agregar</button>
                    </div>
                </div>
            </div>
        </div>

</div>