@extends('plantilla')      

@section('contenedorprincipal')
	<div class="padding-md">
		<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
		<div style="padding:20px">
			<h3>{{$datosUsuario[0]->nombreUsuario}}</h3><br>
			<div class="row" style="padding:3px">
				<div class="col-md-2">
					Correo
				</div>
				<div class="col-md-3 col-lg-2">
					<input class="form-control input-sm" id="correlativoGuias" value="{{$datosUsuario[0]->usu_correo}}">
				</div>
			</div>
			<br>
			<b>Indique que notificaciones desea recibir por correo:</b>
			<br>
			<div style="padding-top: 20px">
				<label style="width:120px">Aviso pedidos agrupados</label>
				@if($datosUsuario[0]->avisoDespacho==1)
					<input type="checkbox" class="chk" id="despacho" checked>
				@else
					<input type="checkbox" class="chk" id="despacho" >
				@endif
				<span class="custom-checkbox"></span> 
			</div>
			<div style="padding-top: 10px">
				<label style="width:120px">Pedidos Urgentes</label>
				@if($datosUsuario[0]->novedades==1)
					<input type="checkbox" class="chk" id="novedades" checked>
				@else
					<input type="checkbox" class="chk" id="novedades">
				@endif
				<span class="custom-checkbox"></span> 
			</div>
			<br>
			<button id="grabar" class="btn btn-sm btn-success" onclick="grabarCambios();">Grabar Cambios</button>
		</div>
	</div><!-- /.padding-md -->
@endsection


@section('javascript')

<script src="{{ asset('/') }}js/app/funciones.js"></script>
<script>

	function grabarCambios(){
			var avisoDespacho=0;
			var avisoNovedades=0;

			if(despacho.checked){
				avisoDespacho=1;
			}
			if(novedades.checked){
				avisoNovedades=1;
			}

            $.ajax({
                url: urlApp + "usuarioAvisosCorreo",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        despacho: avisoDespacho,
                        novedades: avisoNovedades
                      },
                success:function(dato){
                    swal(
                        {
                            title: 'Los cambios han sido grabados!',
                            text: '',
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonText: 'OK',
                            cancelButtonText: '',
                            closeOnConfirm: true,
                            closeOnCancel: false
                        }
                    )
                }
            })  		
	}
	
</script>

@endsection