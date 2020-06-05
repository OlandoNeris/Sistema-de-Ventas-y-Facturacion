
$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
    	var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
              	alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
    	$('#foto').val('');
    	$(".delPhoto").addClass('notBlock');
    	$("#img").remove();

        if ($("#foto_actual") && $("#foto_remove")) {
            $("#foto_remove").val('img_producto.jpg');
        }

    });

    //  FORMULARIO MODAL AGREGAR PRODUCTo

    $('.add_product').click(function(e){

        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';


        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, producto:producto},

            success: function(response){

                if (response != 'error') {

                    var info = JSON.parse(response);

                   // $('#producto_id').val();
                   // $('.nombreProducto').html();

                    // en esta variable algmaceno el formulario modal de agregar existencia 
                    $('.bodyModal').html('<form action="" method="post" name="form_add_existencia" id="form_add_existencia" style="background: #FFF;" onsubmit="event.preventDefault(); sendDataProducto();">'+
                                         '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i><br>Agregar Producto</h1><br><br>'+
                                                '<h2 class="nombreProducto">'+info.descripcion+'</h2><br>'+
                                                '<input type="number" name="txtCantidad" id="txtCantidad" placeholder="Cantidad del Producto" required><br>'+
                                                '<input type="text" name="txtPrecio" id="txtPrecio" placeholder="Precio del Producto " required>'+
                                                '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'"required>'+
                                                '<input type="hidden" name="action" value="addExistencia" required>'+
                                                '<div class="alert alertAddExistencia"><p></p></div>'+
                                                '<button type="submit" class="btn_nuevo"><i class="fas fa-plus"></i>  Agregar</button>'+
                                                '<a href="#" class="btn_eliminar closeModal" onclick="closeModal();"><i class="fas fa-ban"></i>  Cerrar</a>'+

                
                                         '</form>');  
                }
            },


            error: function(error){
                console.log(error);
            }

        });        


        $('.modal').fadeIn();

    });

    // FORMULARIO MODAL ELIMINAR PRODUCTO

    $('.eliminar_producto').click(function(e){

        e.preventDefault();
        var producto = $(this).attr('product');
        var action = 'infoProducto';


        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, producto:producto},

            success: function(response){

                if (response != 'error') {

                    var info = JSON.parse(response);

                   // $('#producto_id').val();
                   // $('.nombreProducto').html();2

                    // en esta variable algmaceno el formulario modal de agregar existencia 
                    $('.bodyModal').html('<form action="" method="post" name="form_elim_producto" id="form_elim_producto" style="background: #FFF;" onsubmit="event.preventDefault(); eliminarProducto();">'+
                                         '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i><br>Eliminar Producto</h1><br><br>'+
                                                '<p class="nombreProducto">¿Esta Seguro de Eliminar el Siguiente Producto? </p><br>'+
                                                '<h2 class="nombreProducto" style="font-size:30px;">'+info.descripcion+'</h2><br>'+
                                                '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'"required>'+
                                                '<input type="hidden" name="action" value="eliminarProd" required>'+
                                                '<div class="alert alertAddExistencia"><p></p></div>'+
                                                '<a href="#" onclick="closeModal();" class="btn_cancel"><i class="fas fa-ban" style="color:white"></i>  Cerrar</a>'+
                                                '<button type="submit" class="btn_eliminar"><i class="fas fa-user-times" style="color: white"></i> Aceptar</button>'+
                
                                         '</form>');  
                }
            },


            error: function(error){
                console.log(error);
            }

        });        


        $('.modal').fadeIn();

    });


    $('#buscar_proveedor').change(function(event) {
        /* Act on the event */
        event.preventDefault();
        var sistema = getUrl();
        location.href = sistema+'buscar_productos.php?proveedor='+$(this).val();
        
    });

    // ACTIVAR CAMPOS PARA REGISTRAR CLIENTE    

    $('.btn_new_cliente').click(function(event) {
        /* quitamos el atributo disabled de los campos para registrar al cliente */
        event.preventDefault();
        $('#nom_cliente').removeAttr('disabled');
        $('#tel_cliente').removeAttr('disabled');
        $('#dir_cliente').removeAttr('disabled');

        $('#div_registro_cliente').slideDown();

    });

    // BUSCAR DATOS DE CLIENTE

    $('#dni_cliente').keyup(function(e) {
        /* Act on the event */
        e.preventDefault();

        var cliente = $(this).val();
        var action = 'buscarCliente';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, cliente:cliente},


         // EVALUO LA RESPUESTA DEL QUERY DE AJAX.PHP   
        success: function(response){
            
            // SI LA RESPUESTA ES IGUAL A CERO, EL CLIENTE NO ESTA REGISTRADO
            if (response == 0) 
            {
                $('#idcliente').val('');
                $('#nom_cliente').val('');
                $('#tel_cliente').val('');
                $('#dir_cliente').val('');
               
               //MOSTRAR BOTON AGREGAR                 
               $('.btn_new_cliente').slideDown();

            }else{

            // SI LA RESPUESTA ES DISTINTA DE CERO, MOSTRAMOS LOS DATOS Y OCULTAMOS EL BOTON AGREGAR
                var datos = $.parseJSON(response);
                $('#idcliente').val(datos.idcliente);
                $('#nom_cliente').val(datos.nombre);
                $('#tel_cliente').val(datos.telefono);
                $('#dir_cliente').val(datos.direccion);
               
               // OCULTAR BOTON AGREGAR                 
               $('.btn_new_cliente').slideUp();


               //BLOQUEAR DE CAMPOS 
                $('#nom_cliente').attr('disabled','disabled');
                $('#tel_cliente').attr('disabled','disabled');
                $('#dir_cliente').attr('disabled','disabled');

                // OCULTAR BOTON GUARDAR 

                $('#div_registro_cliente').slideUp();

            }
        },

        error: function(response){
            console.log(response);
        }
        

        });
        

    });

    // CREAR CLIENTE DESDE EL MODULO DE VENTAS 
    $('#form_new_cliente_venta').submit(function(e) {
        /* Act on the event */
        e.preventDefault();

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#form_new_cliente_venta').serialize(),

            success: function(response){
                
                if (response != 'error') 
                {
                 // SI LA RESPUESTA ES DIFERENTE DE ERROR, EL CLIENTE SE REGISTRO CORRECTAMENTE
                 
                 // Agregar el id al input hidden 
                $('#idcliente').val(response);

                // bloquear campos 

                $('#nom_cliente').attr('disabled','disabled');
                $('#tel_cliente').attr('disabled','disabled');
                $('#dir_cliente').attr('disabled','disabled');

                // ocultar boton de agregar cliente y guardar registro 

                $('.btn_new_cliente').slideUp();
                $('#div_registro_cliente').slideUp();   

                }
            },

            error: function(response){
                console.log(response);
            }

        });
        

    });

    // BUSCAR PRODUCTO CON EL CODIGO 
    $('#txt_cod_producto').keyup(function(e) {
        /* Act on the event */
        e.preventDefault();


        var producto = $(this).val();
        var action  = 'infoProducto';


        if (producto != '') 
        {
            $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, producto:producto},

            success: function(response){

                if (response != 'error') 
                {
                    var info = JSON.parse(response);
                    $('#txt_descripcion').html(info.descripcion);
                    $('#txt_existencia').html(info.existencia);
                    $('#txt_cant_producto').val('1');
                    $('#txt_precio').html(info.precio);
                    $('#txt_precio_total').html(info.precio);
                    
                    // ACTIVAR CAMPO CANTIDAD 
                    $('#txt_cant_producto').removeAttr('disabled');

                    // MOSTRAR BOTON AGREGAR 
                    $('#add_producto_venta').slideDown();

                }else{

                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');    

                    //BLOQUEAR CANTIDAD
                    $('#txt_cant_producto').attr('disabled','disabled');

                    // OCULTAR BOTON DE AGREGAR 
                     $('#add_producto_venta').slideUp();
                }                
            },


           }); 
        }        
        

    });


    // ACTUALIZAR CANTIDAD DEL PRODUCTO ANTES DE AGREGAR 

    $('#txt_cant_producto').keyup(function(e) {
        /* Act on the event */
        e.preventDefault();

        var precio_Total = $(this).val() * $('#txt_precio').html();
        var existencia = parseInt($('#txt_existencia').html());
        $('#txt_precio_total').html(precio_Total);

        // ocultar el boton agregar si la cantidad es menor a 1 

        if (($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia)) 
        {
            $('#add_producto_venta').slideUp();
        }else{
            $('#add_producto_venta').slideDown();
        }

    });

    // AGREGAR PRODUCTO AL DETALLE TEMPORAL

    $('#add_producto_venta').click(function(e) {
        /* Act on the event */
        e.preventDefault();

        if ($('#txt_cant_producto').val() > 0) 
        {
            var codproducto = $('#txt_cod_producto').val();
            var cantidad    = $('#txt_cant_producto').val();
            var action      = 'addProductoDetalle';

            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action, producto:codproducto, cantidad:cantidad},

                success: function(response){
                    
                    if (response != 'error') 
                    {
                        var info = JSON.parse(response);

                        // asignar el detalle a las clases en el formulario de la factura

                        $('#detalle_venta').html(info.detalle);
                        $('#detalle_totales').html(info.totales);

                        // vaciar los campos para ingresar un nuevo producto a la lista 

                        $('#txt_cod_producto').val(''); 
                        $('#txt_descripcion').html('-');
                        $('#txt_existencia').html('-');
                        $('#txt_cant_producto').val('0');
                        $('#txt_precio').html('0.00');
                        $('#txt_precio_total').html('0.00');

                        // bloquear cantidad
                        $('#txt_cant_producto').attr('disabled','disabled');

                        // ocultar boton agregar
                        $('#add_producto_venta').slideUp();
 

                    }else{
                        console.log('sin datos');
                    }
                    viewEmitir();
                },

                error: function(response){
                    
                }

            });
            
        }
    });


    // ANULAR VENTA - RESETEAR DETALLE TEMP 

     $('#btn_anular_venta').click(function(e){

        e.preventDefault();

        var registros = $('#detalle_venta tr').length;

        if (registros > 0) 
        {
            var action = 'anularVenta';

            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action},
                
                success: function(response){
                    
                    if (response != 'error') 
                    {
                        location.reload();
                    }
                },

                error: function(error){

                }

            });
            
        }

    });


    // EMITIR VENTA - RESETEAR DETALLE TEMP 

     $('#btn_facturar_venta').click(function(e){

        e.preventDefault();

        var registros = $('#detalle_venta tr').length;

        if (registros > 0) // si hay registros en la factura, precesa la  venta
        {
            var action = 'emitirVenta';
            var codcliente = $('#idcliente').val();

            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: {action:action, codcliente:codcliente},
                
                success: function(response){
                    
                    if (response != 'error') 
                    {
                        //si entra aca, generó la venta y recargara la pagina
                        var info = JSON.parse(response);
                        //console.log(info);

                        generarPDF(info.codcliente, info.nofactura);

                        location.reload();
                    }else{
                        console.log('sin datos');
                    }
                },

                error: function(error){

                }

            });
            
        }

    });


     // MODAL ANULAR VENTA 

        $('.anular_factura').click(function(e){

        e.preventDefault();
        var nofactura = $(this).attr('factura');
        var action = 'infoFactura';


        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, nofactura:nofactura},

            success: function(response){

                if (response != 'error') 
                {

                    var info = JSON.parse(response);
                    

                    // en esta variable algmaceno el formulario modal anular factura 
                    $('.bodyModal').html('<form action="" method="post" name="form_anular_factura" id="form_anular_factura" style="background: #FFF;" onsubmit="event.preventDefault(); anularFactura();">'+
                                         '<h1><i class="fas fa-cubes" style="font-size: 45pt;"></i><br><br>Anular Factura</h1><br><br>'+
                                                '<p class="nombreProducto">¿Esta Seguro de Eliminar la siguiente Factura? </p><br>'+
                                                '<p><strong>Numero:  '+info.nofactura+'</strong></p>'+
                                                '<p><strong>Monto:     $ '+info.totalfactura+'</strong></p>'+
                                                '<p><strong>Fecha:     '+info.fecha+'</strong></p>'+
                                                '<input type="hidden" name="action" value="anularFactura">'+
                                                '<input type="hidden" name="no_factura" id="no_factura" value='+info.nofactura+' required>'+
                                                '<div class="alert alertAddExistencia"><p></p></div>'+
                                                '<a href="#" onclick="closeModal();" class="btn_cancel"><i class="fas fa-ban" style="color:white"></i>  Cerrar</a>'+
                                                '<button type="submit" class="btn_eliminar"><i class="fas fa-user-times" style="color: white"></i> Anular Factura</button>'+
                
                                         '</form>');  
                }
            },


            error: function(error){
                console.log(error);
            }

        });        


        $('.modal').fadeIn();

    });

    // VER FACTURA

    $('.ver_factura').click(function(e){
        e.preventDefault();

        var codCliente = $(this).attr('cliente');
        var noFactura = $(this).attr('factura');

        generarPDF(codCliente,noFactura);

    });




    // BUSCAR INSUMO PARA RECETA
    $('#txt_nom_insumo').keyup(function(e) {
        /* Act on the event */
        e.preventDefault();


        var insumo = $(this).val();
        var action  = 'infoInsumo';


        if (insumo != '') 
        {
            $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action, insumo:insumo},

            success: function(response){

                if (response != 'error') 
                {
                    var info = JSON.parse(response);

                    $('#txt_cod_insumo').val(info.id_insumo);
                    $('#txt_nom_insumo').val(info.nom_insumo);
                    $('#unidadUso').val(info.unidad_uso);
                    
                    // ACTIVAR CAMPO CANTIDAD 
                    $('#txt_cant_insumo').removeAttr('disabled');

                    // MOSTRAR BOTON AGREGAR 
                    $('#add_insumo_receta').slideDown();

                }else{

                    $('#txt_cod_insumo').val('');
                    $('#unidadUso').val('');
                    $('#txt_cant_insumo').val('0');
  

                    //BLOQUEAR CANTIDAD
                    $('#txt_cant_insumo').attr('disabled','disabled');

                    // OCULTAR BOTON DE AGREGAR 
                     $('#add_insumo_receta').slideUp();
                }                
            },


           }); 
        }        
        

    });


    // AGREGAR PRODUCTO AL DETALLE de RECETA


    // -----------------------------------------------------------------------------------

    // MANEJO DE LOS FORMULARIOS DE ALTA Y EDICION DE RECETAS 

    // OCULTAR FORMULARIO NUEVA RECETA ( YA QUE AL CLICKAR EN LA OPCION SE DESPLEARA EL FORMULARIO CORRESPONDIENTE)
    $('#form_agregar_receta').hide();
    $('#form_editar_receta').hide();
    $('#guardarProductoElaborado').attr('disabled', 'disabled');
    $("#btnAgregarIngredienteNuevoProdElaborado").attr('disabled', 'disabled');



    // FIN MANEJO DE LOS FORMULARIOS DE ALTA Y EDICION DE RECETAS 

 

    // VALIDAR BUSCAR PRODUCTO ELABORADO valida que el producto elaborado no exista en la base de datos

    $('#nombreNuevoProdElaborado').keyup(function (e) {
        /* Act on the event */
        e.preventDefault();
        

        var prodElaborado = $(this).val();
        var action = 'buscarProdElaborado';
        
        if(prodElaborado !== '')
        {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: { action: action, prodElaborado: prodElaborado },

                success: function (response) {

                    if (response != 0){                        
                        
                        var info = JSON.parse(response);
                        
                        $("#msjErrorNuevaReceta").html('El Producto que desea Agregar Ya Existe!');
                        $("#guardarNuevoProductoElaborado").attr('disabled',true);
                        $("#btnAgregarIngredienteNuevoProdElaborado").attr('disabled', true);
                        $("#precioNuevoProdElaborado").val(info.precio);  
                        $("#precioNuevoProdElaborado").attr('disabled', true);
                        $("#descNuevoProdElaborado").val(info.comentarios);
                        $("#descNuevoProdElaborado").attr('disabled', true);    
                                           
                    }else{
                        $("#msjErrorNuevaReceta").html('');
                        $("#guardarNuevoProductoElaborado").attr('disabled', false);
                        $("#precioNuevoProdElaborado").val('');
                        $("#precioNuevoProdElaborado").attr('disabled', false);
                        $("#descNuevoProdElaborado").val('');
                        $("#descNuevoProdElaborado").attr('disabled', false);
                      
                    
                    } 

                }

            });

        };
        

    }); 
  
    // GUARDAR NUEVO PRODUCTO ELABORADO
 
    $("#guardarNuevoProductoElaborado").click(function (e) {

        e.preventDefault();
        var action = "guardarNuevoProdElaborado";
        var nombre = $("#nombreNuevoProdElaborado").val();
        var precio = $("#precioNuevoProdElaborado").val();
        var descripcion = $("#descNuevoProdElaborado").val();
        var idUser =  $("#idUsuario").val();
        
        if(action != '' && nombre != '' && precio != '' && descripcion != '' && idUser != '' ){
            
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: { action: action, nombre:nombre, precio:precio, descripcion:descripcion, idUser:idUser},

                success: function (response) {
                
                    if (response != 'Error') {
                        var info = JSON.parse(response);
                        

                        $('#idReceta').val(info.idReceta);
                        $('#guardarNuevoProductoElaborado').slideUp();
                        $("#btnAgregarIngredienteNuevoProdElaborado").attr('disabled', false);
   
                    } else {

                        alert("Algo a Salido mal...");
                    } 
                },
            });
            

        } else {
            alert("Todos Campos son Obligatorios! ");
        };

      


    });


    // VALIDAR EXISTENCA DEL NUEVO PRODUCTO A GUARDAR 

    $('#nombre_prod').keyup(function (e) {
        /* Act on the event */
        e.preventDefault();

        var nuevoProducto = $(this).val();
        var action = 'ValidarNuevoProducto';

        
        
        if (nuevoProducto !== '') {
            $.ajax({
                url: 'ajax.php',
                type: 'POST',
                async: true,
                data: { action: action, nuevoProducto: nuevoProducto },

                success: function (response) {
                    

                    var resultado = JSON.parse(response);

                    if (resultado != "0") {
                        
                        $("#tipo_producto").attr('disabled', true);
                        $("#unidad_uso").attr('disabled', true);
                        $("#contenedorFoto").attr('disabled', true);
                        $("#guardarNuevoProd").attr('disabled', true);
                        $("#precio_prod").attr('disabled', true);
                        $("#msjUsuario").html('El Producto Ya se encuentra Registrado! ');

                    } else {
                        $("#tipo_producto").attr('disabled', false);
                        $("#unidad_uso").attr('disabled', false);
                        $("#contenedorFoto").attr('disabled', false);
                        $("#guardarNuevoProd").attr('disabled', false);
                        $("#precio_prod").attr('disabled', false);
                        $("#msjUsuario").html('');

                    } 

                }

            }); 

        } else {
            console.log("no action");
        } 


    }); 

    // VALIDAR PRECIO POSITIVO form nuevo producto
    $('#precio_prod').keyup(function (e) {

       if($(this).val() <= 0)
       {
           $("#precio_prod").html('');
           $("#msjUsuario").html('El Precio Debe ser Positivo');
           $("#guardarNuevoProd").attr('disabled', true);
          
       }else{
           
           $("#msjUsuario").html('');
           $("#guardarNuevoProd").attr('disabled', false);
          
       }
    });

    // validar precio positivo formulario nueva recet
    
    $('#precioNuevoProdElaborado').keyup(function (e) {

        if($(this).val() <= 0)
        {
            $("#precioNuevoProdElaborado").html('');
            $("#msjErrorNuevaReceta").html('El Precio Debe ser Positivo');
            $("#guardarNuevoProductoElaborado").attr('disabled', true);
            $("#descNuevoProdElaborado").attr('disabled', true);
        }else{
            
            $("#msjErrorNuevaReceta").html('');
            $("#guardarNuevoProductoElaborado").attr('disabled', false);
            $("#descNuevoProdElaborado").attr('disabled', false);
        }      
 
     });

    // RESETEAR MODAL DESPUES DE GUARDAR EL INSUMO EN LA RECETA

    $('#btnAgregarIngredienteNuevoProdElaborado').click(function (e){
        
        $('#staticBackdropLabel').html('Agregar Insumo a la Receta ');
        $('#agregarInsumoLista').slideDown();
        $('#cantidadInsumoModal').attr('disabled',false);
        $('#cantidadInsumoModal').val();
        $('#SelectAddInsumo').attr('disabled',false);
        
        
    });

     

     // AGREGAR FUNCIONALIDAD AL BOTON DEL MODAL 
     $('#agregarInsumoLista').click(function (e){
        e.preventDefault;


        var codigoInsumo = $('#SelectAddInsumo').val();
        var cantidadInsumo = $('#cantidadInsumoModal').val();
        var idReceta= $('#idReceta').val();

        var action = 'agregarInsumoReceta';
     
    
        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: { action: action, idReceta:idReceta, idInsumo:codigoInsumo, cantidad:cantidadInsumo },    
            
            success: function (response) {
            
                if (response != 'Error') {
     
                    var info = JSON.parse(response);
                   
                    $('#listaIngredientesNuevaReceta').html(info);

                    $('#agregarInsumoLista').slideUp();
                    $('#cantidadInsumoModal').attr('disabled',true);
                    $('#SelectAddInsumo').attr('disabled',true);
                    
                    $('#staticBackdropLabel').html('Ingrediente Agregado con Exito! ');
    
                } else {
    
                    console.log("Algo a Salido mal...");
                } 
            },
        });
    });





}); // FIN READY DOCUMENT 


// ---- BLOQUE DE FUNCIONES ---------------


function actualizarMedidaUso(unidadUso){
    $('#UnidadMedidaModal').val(unidadUso);
}

function agregarInsumoReceta(){

    var idReceta = $('#idReceta').val();

 

}


// mostrar formulario de alta del producto elaborado
function mostrarFormRecetaNueva() {

    $('#form_editar_receta').hide();
    $('#form_agregar_receta').show();

}
// mostrar formulario de edicion del producto elaborado

function mostrarFormEditarReceta() {

    $('#form_agregar_receta').hide();
    $('#form_editar_receta').show();

}


// Anular Factura de Venta

function anularFactura(){

    var noFactura = $('#no_factura').val();
    var action = 'anularFactura';

    $.ajax({
        url: 'ajax.php',
        type: "POST",
        async: true,
        data: {action:action,noFactura:noFactura},


        success: function(response){
            
            if (response == 'error') 
            {
                $('.alertAddExistencia').html('<p style="color:red;">Error al Anular la Factura.</p>');
            }else{
                $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
                $('#form_anular_factura .btn_eliminar').remove();
                $('.alertAddExistencia').html('<p>Factura Anulada.</p>');
                $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
            }
        },

        error: function(error){
            console.log(error);
        }

    });

}


// mostrar/ ocultar boton EMITIR 
function viewEmitir(){
    if ($('#detalle_venta tr').length > 0) 
    {
        $('#btn_facturar_venta').show();
    }else{
        $('#btn_facturar_venta').hide();
    }
}

// ELIMINAR PRODUCTO DEL DETALLE TEMPORAL
function del_producto_detalle(correlativo){

    var action = 'del_producto_detalle';
    var id_detalle = correlativo;


    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, id_detalle:id_detalle},

        success: function(response){
            
            if (response != 'error') 
            {

                var info = JSON.parse(response);

                // asignar el detalle a las clases en el formulario de la factura

                $('#detalle_venta').html(info.detalle);
                $('#detalle_totales').html(info.totales);

                // vaciar los campos para ingresar un nuevo producto a la lista 

                $('#txt_cod_producto').val(''); 
                $('#txt_descripcion').html('-');
                $('#txt_existencia').html('-');
                $('#txt_cant_producto').val('0');
                $('#txt_precio').html('0.00');
                $('#txt_precio_total').html('0.00');

                // bloquear cantidad
                $('#txt_cant_producto').attr('disabled','disabled');

                // ocultar boton agregar
                $('#add_producto_venta').slideUp();

            }else{
                $('#detalle_venta').html('');
                $('#detalle_totales').html('');
            }
            viewEmitir();
        },

        error: function(error){
            
        }
    });
    
}

// RECUPERAR DETALLE DE VENTA
function serchForDetalle(id){
    var action = 'serchForDetalle';
    var user = id;

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, user:user},

        success: function(response){
            if (response != 'error') 
                {
                    var info = JSON.parse(response);

                    // asignar el detalle a las clases en el formulario de la factura

                    $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);

                    // vaciar los campos para ingresar un nuevo producto a la lista 

                    $('#txt_cod_producto').val(''); 
                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');

                    // bloquear cantidad
                    $('#txt_cant_producto').attr('disabled','disabled');

                    // ocultar boton agregar
                    $('#add_producto_venta').slideUp();


                }else{
                    console.log('sin datos');
                }
                viewEmitir();
        },

        error: function(response){
            console.log(response);                
        }

    });
    
}


// RECUPERAR DETALLE DE VENTA
function serchForDetalle(id){
    var action = 'serchForDetalle';
    var user = id;

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action, user:user},

        success: function(response){
            if (response != 'error') 
                {
                    var info = JSON.parse(response);

                    // asignar el detalle a las clases en el formulario de la factura

                    $('#detalle_venta').html(info.detalle);
                    $('#detalle_totales').html(info.totales);

                    // vaciar los campos para ingresar un nuevo producto a la lista 

                    $('#txt_cod_producto').val(''); 
                    $('#txt_descripcion').html('-');
                    $('#txt_existencia').html('-');
                    $('#txt_cant_producto').val('0');
                    $('#txt_precio').html('0.00');
                    $('#txt_precio_total').html('0.00');

                    // bloquear cantidad
                    $('#txt_cant_producto').attr('disabled','disabled');

                    // ocultar boton agregar
                    $('#add_producto_venta').slideUp();


                }else{
                    console.log('sin datos');
                }
                viewEmitir();
        },

        error: function(response){
            console.log(response);                
        }

    });
    
}

// RETORNA LA URL DE DONDE ESTA EL SISTEMA 
function getUrl() {
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

// ACTUALIZAR EXISTENCIA 
function sendDataProducto(){

    $('.alertAddExistencia').html('');

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_add_existencia').serialize(),


        success: function(response){
            if (response == 'error') 
            {
                $('.alertAddExistencia').html('<p style="color:red;">Error al Agregar la Existencia</p>');
            }else{

                var info = JSON.parse(response);
                console.log(response);
                $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
                $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
                $('#txtPrecio').val('');
                $('#txtCantidad').val('');
                $('.alertAddExistencia').html('<p style="color:green;">Productos Ingresados Correctamente</p>');
            }
        },

        error: function(error){
            console.log(error);
        }

    });
    
}


// ELIMINAR PRODUCTO
function eliminarProducto(){

    $('.alertAddExistencia').html('');
    var pr = $('#producto_id').val();

    $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_elim_producto').serialize(),


        success: function(response){

            console.log(response);

            
            if (response == 'error') 
            {
                $('.alertAddExistencia').html('<p style="color:red;">Error al Eliminar el Producto</p>');
            }else{

                $('.row'+pr).remove();
                $('#form_elim_producto .btn_eliminar').remove();

                $('.alertAddExistencia').html('<p style="color:green;">Productos Eliminado Correctamente</p>');
            }
            
        },

        error: function(error){
            console.log(error);
        }

    });
    
}

// CERRAR MODAL 
function closeModal() {
    $('#txtPrecio').val('');
    $('#txtCantidad').val('');
    $('.alertAddExistencia').html('');
    $('.modal').fadeOut();
}

function generarPDF(cliente, factura){

    var ancho = 1000;
    var alto = 800;

    // calculo posiciones para centrar la pagina 

    var x = parseInt((window.screen.width /2) - (ancho / 2));
    var y = parseInt((window.screen.height /2) - (alto / 2));

    $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
    window.open($url,"Factura","left="+x+",top="+y+", height="+alto+", width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");

}

