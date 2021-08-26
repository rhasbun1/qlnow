      
<?php $__env->startSection('contenedorprincipal'); ?>

<div style="padding: 5px">
	<input type="hidden" id="_token" name="_token" value="<?php echo e(csrf_token()); ?>">
    <div class="panel panel-default" id="contenedor3">
        <div class="panel-heading">
        	<h4>Liberar Nº de Guía de Despacho</h4>
        </div> 
        <div class="panel-body" id="panelBody">
			<div class="row">
				<div class="col-md-2">
					Nº Guía (*)
				</div>
				<div class="col-md-2">
					<input id="numeroGuia" class="form-control input-sm" onkeypress="return isNumberKey(event)" maxlength="9">
				</div>
			</div>
			<div class="row" style="padding-top: 5px">
				<div class="col-md-2">
					Motivo (*)
				</div>
				<div class="col-md-6">
					<input id="motivo" class="form-control input-sm" maxlength="100">
				</div>					
			</div>
			<div class="row" style="padding-top: 5px">
				<div class="col-md-1">
					<button id="continuar" class="btn btn-success btn-sm" onclick="continuar();">Continuar</button>
				</div>
			</div>
			<div id="datosGuia" style="padding-top: 20px; padding-left: 10px;display: none">
				<div class="row" style="padding-top: 5px">
					<div class="col-md-2">
						Cliente
					</div>
					<div class="col-md-6">
						<input id="nombreCliente" class="form-control input-sm" maxlength="100" readonly="">
					</div>					
				</div>			
				<div class="row" style="padding-top: 5px">
					<div class="col-md-2">
						Nº Pedido
					</div>
					<div class="col-md-2">
						<input id="numeroPedido" class="form-control input-sm" maxlength="10" readonly="">
					</div>					
				</div>	
				<div class="row" style="padding-top: 5px">
					<div class="col-md-2">
						Fecha Emisión
					</div>
					<div class="col-md-2">
						<input id="fechaEmision" class="form-control input-sm" maxlength="10" readonly="">
					</div>					
				</div>	
				<div class="row" style="padding-top: 15px">
					<div class="col-md-4" style="display: inline;">
						<button class="btn btn-success btn-sm" style="display: inline;" onclick="eliminarGuia();">Liberar Nº de GD</button>
						<button class="btn btn-warning btn-sm" style="display: inline;" onclick="limpiar();">Limpiar</button>
					</div>					
				</div>				
			</div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>

<script src="<?php echo e(asset('/')); ?>js/app/funciones.js?<?php echo e($parametros[0]->version); ?>"></script>
<script src="<?php echo e(asset('/')); ?>js/app/guiaDespacho.js?<?php echo e($parametros[0]->version); ?>"></script>
<script>

	function continuar(){

        if ($("#numeroGuia").val().trim()==''){
            swal(
                {
                    title: "¡Debe ingresar el número de guía!" ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Cerrar',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: true
                }
            );
            return;      
        }

        if ($("#motivo").val().trim()==''){
            swal(
                {
                    title: "¡Debe ingresar el motivo!" ,
                    text: '',
                    type: 'warning',
                    showCancelButton: false,
                    confirmButtonText: 'Cerrar',
                    cancelButtonText: '',
                    closeOnConfirm: true,
                    closeOnCancel: true
                }
            );
            return;      
        }

	    $.ajax({
	        url: urlApp + "datosGuiaDespacho",
	        headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
	        type: 'POST',
	        dataType: 'json',
	        data: { tipoGuia: 1 ,
	                numeroGuia: document.getElementById('numeroGuia').value
	              },
	        success:function(dato){
	            if(dato[0]){
	              	$("#numeroPedido").val( dato[0].idPedido );
	              	$("#nombreCliente").val(dato[0].nombreCliente);
	              	$("#fechaEmision").val(dato[0].fechaGeneracion);
					document.getElementById('continuar').style.display="none";
					document.getElementById('datosGuia').style.display="block";	                     
	            }else{
	                swal(
	                    {
	                        title: "¡Número de guía no encontrado!" ,
	                        text: '',
	                        type: 'warning',
	                        showCancelButton: false,
	                        confirmButtonText: 'Cerrar',
	                        cancelButtonText: '',
	                        closeOnConfirm: true,
	                        closeOnCancel: true
	                    }
	                );	            	
	            }
	          }
	    }) 		
	}

	function limpiar(){
		document.getElementById('continuar').style.display="block";
		document.getElementById('datosGuia').style.display="none";
		document.getElementById('numeroGuia').value='';
		document.getElementById('motivo').value='';
	}

	function eliminarGuia(){

        $.ajax({
            url: urlApp + "eliminarGuiaDespacho",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { 
                    numeroGuia: document.getElementById('numeroGuia').value,
                    motivo: document.getElementById('motivo').value
                  },
            success:function(dato){
                swal(
                    {
                        title: "¡El Nº de Guía de Despacho ha sido liberado!" ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'Cerrar',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: true
                    }
                );
                limpiar();
                return;                    
            }
        });

	}

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('plantilla', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>