@extends('plantilla')      

@section('contenedorprincipal')

<div style="padding: 10px">
    <div class="panel panel-default table-responsive">
        <div class="panel-heading">
            <b>Nota de Venta Nº {{ $notaventa[0]->idNotaVenta }}</b>
        </div>
        <div class="padding-md clearfix"> 

                <input type="hidden" id="idNotaVenta" value="{{ $notaventa[0]->idNotaVenta }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="row">        
                    <div class="col-lg-1 col-md-1 col-sm-2" >
                        Cliente
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <input class="form-control input-sm" id="txtNombreCliente" readonly value="{{ $notaventa[0]->emp_nombre }}">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        Rut
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <input class="form-control input-sm" id="txtRutCliente" readonly value="{{ $notaventa[0]->emp_rut }}" title="Rut del Cliente">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        Cód.Softland
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        @if($notaventa[0]->SolicitaCodigoSoftland=='1')
                            <input class="form-control input-sm" id="txtCodigoSoftland" value="{{ $notaventa[0]->codigoSoftland }}" title="Código Softland" maxlength="9" onkeypress="return isNumberKey(event)" @if(Session::get('grupoUsuario')!='C') readonly @endif>
                        @else
                            <input class="form-control input-sm" id="txtCodigoSoftland" value="{{ $notaventa[0]->codigoSoftland }}" title="Código Softland" readonly>
                        @endif    
                    </div>                                     
                    <div class="col-lg-1 col-md-1 col-sm-2" style="text-align: right;">
                        Fecha
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <input class="form-control input-sm" id="txtFechaCotizacion" readonly value="{{ $notaventa[0]->fechahora_creacion }}">
                    </div>                  
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        Crea
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <input class="form-control input-sm" id="txtUsuarioCrea" readonly value="{{ $notaventa[0]->usuario_creacion }}">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-2" >
                        Aprueba
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <input class="form-control input-sm" id="txtUsuarioValida" readonly value="{{ $notaventa[0]->usuario_validacion }}">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-2">
                       Ejecutivo&nbspQL
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <select id="idUsuarioEncargado" name="idUsuarioEncargado" class="selectpicker" data-live-search="true" title="Seleccione..." data-width="100%" @if(Session::get('grupoUsuario')!='C' or Session::get('idPerfil')=='11' ) disabled @endif>
                            @foreach($usuarios as $item)
                               @if($notaventa[0]->usuario_encargado==$item->usu_nombre.' '.$item->usu_apellido )
                                    <option value="{{ $item->usu_codigo }}" selected>{{ $item->usu_nombre }} {{ $item->usu_apellido }}</option>
                               @else
                                    <option value="{{ $item->usu_codigo }}">{{ $item->usu_nombre }} {{ $item->usu_apellido }}</option>
                               @endif 
                            @endforeach                             
                        </select>                        
                    </div>                     
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-sm-2 col-md-1">
                        Obra/Planta
                    </div>
                    <div class="col-lg-3 col-sm-4 col-md-3">
                            <input id="idObra" class="form-control input-sm" readonly value="{{ $notaventa[0]->Obra }}">
                    </div>
                    <div class="col-lg-1 col-sm-2 col-md-1">
                        O.Compra
                    </div>
                    <div class="col-lg-2 col-sm-4 col-md-3">
                        <div class="input-group">                           
                            <input id="txtOrdenCompra" class="form-control input-sm" value="{{ $notaventa[0]->ordenCompraCliente }}" data-ocarchivo="{{ $notaventa[0]->nombreArchivoOC }}" @if(Session::get('grupoUsuario')!='C' or Session::get('idPerfil')=='11') readonly @endif>
                            @if (Session::get('grupoUsuario')=='C' or Session::get('idPerfil')=='11')
                                <span class="input-group-addon glyphicon glyphicon-cloud-download" title="Bajar Orden de Compra" onclick="bajarOC();"></span>
                            @endif
                            @if (Session::get('grupoUsuario')=='C' or Session::get('idPerfil')=='11')
                                <span class="input-group-addon glyphicon glyphicon-cloud-upload" onclick="subirArchivoOc();" title="Subir Orden de Compra"></span>
                            @endif
                        </div>                            
                    </div>
                    @if ( Session::get('grupoUsuario')=='C')
                        <div class="col-lg-1 col-sm-2 col-md-1">
                            Cond.Pago
                        </div>
                        <div class="col-lg-3 col-sm-2 col-md-3">                    
                            <select id="idCondicionPago" name="idCondicionPago" class="selectpicker" data-live-search="true" title="Seleccione..." data-width="100%" @if(Session::get('grupoUsuario')!='C' or Session::get('idPerfil')=='11') disabled @endif>
                                @foreach($condicionesPago as $item)
                                   @if( $notaventa[0]->idCondiciondePago==$item->idCondiciondePago  )
                                        <option value="{{ $item->idCondiciondePago }}" selected>{{ $item->nombre }} </option>
                                   @else
                                        <option value="{{ $item->idCondiciondePago }}">{{ $item->nombre }} </option>
                                   @endif 
                                @endforeach                             
                            </select>
                        </div>
                    @endif                      
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-sm-2 col-md-1">
                        Contacto
                    </div>
                    <div class="col-lg-3 col-sm-4 col-md-3">                        
                        <input id="txtNombreContacto" class="form-control input-sm" value="{{ $notaventa[0]->nombreContacto }}" @if(Session::get('grupoUsuario')!='C' or Session::get('idPerfil')=='11') readonly @endif>
                    </div> 
                    <div class="col-lg-1 col-sm-2 col-md-1">
                        Teléfono
                    </div>
                    <div class="col-lg-3 col-sm-4 col-md-5">                        
                        <input id="txtTelefonoContacto" class="form-control input-sm" maxlength="12" value="{{ $notaventa[0]->telefonoContacto }}" @if(Session::get('grupoUsuario')!='C' or Session::get('idPerfil')=='11') readonly @endif>
                    </div>                    
                    <div class="col-lg-1 col-sm-2 col-md-1">
                        Email
                    </div>
                    <div class="col-lg-3 col-sm-4 col-md-3">                        
                        <input id="txtCorreoContacto" class="form-control input-sm" value="{{ $notaventa[0]->correoContacto }}" @if(Session::get('grupoUsuario')!='C' or Session::get('idPerfil')=='11') readonly @endif>
                    </div>         
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-md-2 col-sm-2">
                        Observaciones <font size="1px">(máx.255 carac)</font>
                    </div>
                    <div class="col-lg-7 col-md-10 col-sm-10">                            
                        <textarea id="txtObservaciones" style="resize: none;" maxlength="255" rows="3" class="form-control input-sm" @if(Session::get('grupoUsuario')!='C' or Session::get('idPerfil')=='11') readonly @endif>{{ $notaventa[0]->observaciones }}</textarea>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        <b>Cotización</b>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4">
                        <input class="form-control input-sm" id="txtAno" maxlength="4" value="{{ $notaventa[0]->cot_numero }} / {{ $notaventa[0]->cot_ano }}" readonly>
                    </div>                     
                </div>
             
            </div>            
            <div style="padding-left: 20px;padding-right: 20px">
                <table id="tablaDetalle" class="table table-hover table-condensed" style="width:100%">
                    <thead>
                        <th style="display:none">Código</th>
                        <th style="width: 150px">Producto</th>
                        <th style="width: 80px">Diseño</th>
                        <th style="width: 50px;text-align:right;">Cantidad</th>
                        <th style="width: 50px">Unidad</th>
                        @if( Session::get('grupoUsuario')=='C' or Session::get('grupoUsuario')=='CL')
                            <th style="width: 50px;text-align:right;">Precio Base ($)</th>
                            <th style="width: 50px;text-align:right;">Precio Reajustado *($)</th>
                            <th style="width: 50px;text-align:right;">% var</th>
                        @endif
                        <th style="width: 50px;text-align:right;">Por entregar</th>
                        <th style="width: 50px;text-align:right;">Total retirado</th>
                        <th style="width: 50px;text-align:right;">Saldo</th>
                        @if( Session::get('grupoUsuario')=='C' or Session::get('grupoUsuario')=='CL')
                            <th style="width: 250px">Glosa de Reajuste</th>
                        @endif
                        <th style="width: 120px">Planta</th>
                        <th style="width: 120px">Entrega</th>
                    </thead>
                
                    <tbody>
                        @foreach($notaventadetalle as $item)
                        <tr>
                            <td style="display:none"> {{ $item->idNotaVentaDetalle }} </td>
                            <td style="width: 150px"> {{ $item->prod_nombre }} </td>
                            <td style="width: 80px">
                                @if( $item->requiere_diseno==1 )
                                    @if( Session::get('grupoUsuario')=='C' and Session::get('idPerfil')!='11')
                                        <input class="form-control input-sm" value="{{ $item->formula }}" maxlength="20"> 
                                    @else
                                        {{ $item->formula }}
                                    @endif    
                                @endif    
                            </td>
                            <td style="width: 50px;text-align:right;"> {{ number_format( $item->cantidad, 0, ',', '.' ) }} </td>
                            <td style="width: 50px"> {{ $item->u_nombre }} </td>
                            @if( Session::get('grupoUsuario')=='C' or Session::get('grupoUsuario')=='CL')
                                <td style="width: 50px;text-align:right;">{{ number_format( $item->precio, 0, ',', '.' ) }}</td>
                                <td style="text-align: right;">{{ number_format( $item->precioActual, 0, ',', '.' ) }}</td>
                                <td style="text-align: center;">{{ number_format( $item->porcentajeVariacion, 1, ',', '.' ) }}</td>
                            @endif
                            <td style="text-align: right;">{{ number_format( $item->porEntregar, 0, ',', '.' ) }}</td>
                            <td style="text-align: right;">{{ number_format( $item->totalRetirado, 0, ',', '.' ) }}</td>
                            <td style="width: 50px;text-align:right;"> {{ number_format( $item->saldo, 0, ',', '.' ) }} </td>
                            @if( Session::get('grupoUsuario')=='C' or Session::get('grupoUsuario')=='CL')
                                <td style="width: 250px"> {{ $item->cp_glosa_reajuste }} </td>
                            @endif
                            <td style="width: 120px"> {{ $item->nombrePlanta }} </td>
                            <td style="width: 120px"> {{ $item->nombreFormaEntrega }} </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 

            <div style="padding-top: 20px; padding-left: 20px">
                @if (count($pedidos)>0)
                    <div class="row" style="padding: 5px">
                        <b>Pedidos Asociados a esta Nota de Venta. ( * = pendiente de aprobación )</b>
                    </div>
                    <div class="row" style="padding-top: 5px; padding-left: 5px"">
                        @foreach($pedidos as $item)

                            @if( Session::get('grupoUsuario')=='CL')
                            
                                @if( $item->idEstadoPedido==1 )
                                    <a href="{{ asset('/') }}clienteVerPedido/{{ $item->idPedido }}/4/" class="btn btn-xs btn-info"> {{$item->idPedido}} *</a>
                                @elseif( $item->idEstadoPedido==0 )
                                    <a href="{{ asset('/') }}clienteVerPedido/{{ $item->idPedido }}/4/" class="btn btn-xs btn-danger" title="Pedido Suspendido"> {{$item->idPedido}}</a>                              
                                @else
                                    <a href="{{ asset('/') }}clienteVerPedido/{{ $item->idPedido }}/4/" class="btn btn-xs btn-primary"> {{$item->idPedido}} </a>
                                @endif


                            @else

                                @if( $item->idEstadoPedido==1 )
                                    <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/4-{{ $accion }}/" class="btn btn-xs btn-info"> {{$item->idPedido}} *</a>
                                @elseif( $item->idEstadoPedido==0 )
                                    <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/4-{{ $accion }}" class="btn btn-xs btn-danger" title="Pedido Suspendido"> {{$item->idPedido}}</a>                              
                                @else
                                    <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/4-{{ $accion }}/" class="btn btn-xs btn-primary"> {{$item->idPedido}} </a>
                                @endif

                            @endif


                        @endforeach 
                    </div>                      
                @else
                    <b>Esta Nota de Venta no tiene pedidos asociados.</b>    
                @endif

            </div>

            <div style="padding-top:18px; padding-bottom: 20px;padding-left: 15px">
                @if( ( Session::get('idPerfil')=='2' or Session::get('idPerfil')=='4' ) and $notaventa[0]->aprobada==0 and $accion!='3' )
                   <input type="button" class="btn btn-sm btn-primary" onclick="aprobar()" style="width:80px" value="Aprobar">
                @endif
                @if( Session::get('grupoUsuario')=='C' and $notaventa[0]->aprobada==1 and $notaventa[0]->TienePedidos==0 and $accion!='3' ) 
                    <input type="button"  class="btn btn-sm btn-primary" onclick="desaprobar()" style="width:90px" value="Desaprobar">
                @endif
                @if( Session::get('grupoUsuario')=='C' and (Session::get('idPerfil')!='11' and Session::get("idPerfil")!=19 ) )
                    <button class="btn btn-sm btn-success" onclick="guardarCambiosNV();">Guardar Cambios</button>
                    @if( $notaventa[0]->cerrada==0 and $notaventa[0]->aprobada==1 and Session::get('grupoUsuario')=='C' and (Session::get("idPerfil")!=11 and Session::get("idPerfil")!=19 ) )
                        <a href="{{ asset('/') }}gestionarpedido/{{ $notaventa[0]->idNotaVenta }}/" class="btn btn-sm btn-primary">Crear Pedido</a>
                    @endif
                @elseif ( Session::get('idPerfil')=='14')
                    @if( $notaventa[0]->cerrada==0 and $notaventa[0]->aprobada==1 )
                        <a href="{{ asset('/') }}gestionarpedido/{{ $notaventa[0]->idNotaVenta }}/" class="btn btn-sm btn-primary">Crear Pedido</a>
                    @endif                    
                @endif

                @if (   $notaventa[0]->cerrada==0 and
                        (Session::get('idPerfil')=='2' or 
                        Session::get('idPerfil')=='3' or 
                        Session::get('idPerfil')=='4') )
                    <button class="btn btn-sm btn-danger" onclick="cerrarNotaVenta('{{ $notaventa[0]->idNotaVenta }}')">Pasar a Histórico</button> 
                    @if ($accion == 1)                   
                        <a onclick="history.go(-1)" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                    @elseif ($accion == 2)
                        <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/1-2/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                    @elseif ($accion == 4)
                        <a href="{{ asset('/') }}verpedidoNuevaVentana/{{ $numPedido }}/1/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>  <!--MATIAS -->
                    @else
                        <a href="{{ URL::previous() }}" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                    @endif
                @elseif( Session::get('idPerfil')=='14' or Session::get('idPerfil')=='15')
                    <a href="{{ asset('/') }}clienteNotasdeVenta/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                @else
                    @if ($accion == 1)
                        <a onclick="history.go(-1)" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                    @elseif ($accion == 2)
                        <a href="{{ URL::previous() }}" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>  
                    @elseif ($accion == 3)
                        <a href="{{ asset('/') }}historicoNotasdeVenta/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a> 
                    @elseif ($accion == 4)
                        <a href="{{ asset('/') }}verpedidoNuevaVentana/{{ $numPedido }}/1/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>  <!--MATIAS -->
                    @else
                        <a href="{{ URL::previous() }}" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>  
                    @endif                      
                @endif
                <a target="_blank" href="{{ asset('/') }}imprimirNotaVenta/{{ $notaventa[0]->idNotaVenta }}/" class="btn btn-warning btn-sm">Imprimir</a>
            </div>              
        </body>
    </div>
    <div class="tab-pane active" id="tabLogAcciones" style="padding-top: 5px">
        <table id="tablaLog" class="table table-hover table-condensed table-responsive" style="width: 850px">
            <thead>
                <th style="width:200px">Fecha</th>
                <th style="width:200px">Usuario</th>
                <th style="width:100px">Acción</th>
                <th style="width:350px">Motivo</th>
            </thead>
            <tbody>
                @if ($notaventa[0]->fechaCierreSistema!='')
              
                <tr>
                    <td style="width:200px"> {{ $notaventa[0]->fechaCierreSistema }} </td>
                    <td style="width:200px">QL Now</td>
                    <td style="width:100px">Cierre</td>
                    <td style="width:350px">Cerrada automáticamente por no haber movimientos.</td>
                </tr>                
                @endif
                @foreach($log as $item)
                <?php
                    $fecha = $item->fechaHora;
                    $newDate = date("d/m/Y H:i:s", strtotime($fecha));
                    $fecha1 = $newDate
                    
                ?>
                <tr>
                    <td style="width:200px"> {{ $fecha1 }} </td>
                    <td style="width:200px"> {{ $item->nombreUsuario }} </td>
                    <td style="width:100px"> {{ $item->accion }} </td>
                    <td style="width:350px"> {{ $item->motivo }} </td>
                </tr>
                @endforeach  
            </tbody>
        </table>
    </div>
</div>


<div id="modSubirArchivo" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="filaTabla" name="filaTabla">
                <h5><b>Subir Archivo Orden de Compra</b></h5>
            </div>
            <div id="bodyProducto" class="modal-body">
                <form id="datos" name="datos" enctype="multipart/form-data">                             
                    <div class="row" style="padding: 15px">
                        Imagen o Pdf de la Orden de Compra
                        <div class="upload-file">
                            <input type="file" id="upload-demo" name="upload-demo" class="upload-demo" accept="image/jpeg,image/gif,image/png,application/pdf,image/x-eps">
                            <label data-title="Buscar" for="upload-demo">
                                <span id="mensajeUpload" data-title="No ha seleccionado un archivo..."></span>
                            </label>
                        </div>                        
                    </div>
                    <div class="col-md-offset-8" style="padding-top: 20px; padding-bottom: 20px">
                       <button type="submit" class="btn btn-success btn-sm" style="width: 80px">Subir</button>
                       <button type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModalSubirArchivo()" style="width: 80px">Salir</button>
                    </div>                   
                </form>     
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>  
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>
    <script src="{{ asset('/') }}js/app/funciones.js"></script>
    <!-- bootstrap-select -->
    <link rel="stylesheet" href="{{ asset('/') }}css/bootstrap-select/bootstrap-select.min.css">
    <script src="{{ asset('/') }}js/bootstrap-select/bootstrap-select.min.js"></script>
    <script src="{{ asset('/') }}js/bootstrap-select/i18n/defaults-es_ES.min.js"></script>    

    <script>

        $('#datos').on('submit', function(e) {
            // evito que propague el submit
            e.preventDefault();
            // agrego la data del form a formData
            var formData = new FormData( $("#datos")[0] );

            formData.append("idNotaVenta", $("#idNotaVenta").val());

            var ruta= urlApp + "subirOCnotaventa";
            $.ajax({
                url: ruta,
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: formData,
                cache:false,
                contentType: false,
                processData: false,            
                success:function(dato){
                        document.getElementById('txtOrdenCompra').dataset.ocarchivo=dato.nombreArchivo;
                        swal(
                            {
                                title: 'Se ha subido la Orden de Compra' ,
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                cancelButtonText: '',
                                closeOnConfirm: true,
                                closeOnCancel: false
                            },
                            function(isConfirm)
                            {
                                if(isConfirm){
                                    return;                            
                                }
                            }
                        )                   
                }
            })
        });
        function aprobar(){
            swal(
                {
                    title: 'Nota De Venta Aprobada',
                    text: '',
                    type: 'warning',
                    confirmButtonText: 'ok',
                    closeOnConfirm: true,
                    
                },
                function(isConfirm)
                {
                    location.href = "{{ asset('/') }}aprobarnota/{{  $notaventa[0]->idNotaVenta }}/";
                }
            
            )
        }
        function desaprobar(){
            swal(
                {
                    title: 'Nota De Venta Desaprobada',
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ok',
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    location.href ="{{ asset('/') }}Desaprobarnota/{{ $notaventa[0]->idNotaVenta }}/";
                }
            )
        }


        


        function actualizarValores(){
            var tabla= document.getElementById('tablaDetalle');
            var cadena='[';
            for (var i = 1; i < tabla.rows.length; i++){
                cadena+='{';
                cadena+='"idNotaVenta":"'+  $("#idNotaVenta").val()  + '", ';
                cadena+='"prod_codigo":"'+  tabla.rows[i].cells[0].innerHTML  + '", ';
                cadena+='"formula":"'+  tabla.rows[i].cells[2].getElementsByTagName('input')[0].value.replace('.','')  + '"';
                cadena+='}, ';
            }
            cadena=cadena.slice(0,-2);
            cadena+=']';
            var ruta= urlApp + "actualizarValoresNotaVenta";
            $.ajax({
                url: ruta,
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        detalle: cadena
                      },
                success:function(dato){
                        swal(
                            {
                                title: 'Se han actualizado correctamente los valores' ,
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                cancelButtonText: '',
                                closeOnConfirm: true,
                                closeOnCancel: false
                            },
                            function(isConfirm)
                            {
                                if(isConfirm){
                                    location.href="{{ asset('/') }}listarNotasdeVenta";                               
                                }
                            }
                        )                   
                }
            })

        }

        function subirArchivoOc(){
            $("#modSubirArchivo").modal('show'); 
        }

        function cerrarModalSubirArchivo(){
            $("#modSubirArchivo").modal('hide'); 
        }
        function cerrarNotaVenta(idNotaVenta){
            swal(
                {
                    title: 'Pasar la Nota ' + idNotaVenta + ' a historico' ,
                    text: 'Debe ingresar un motivo:',
                    type: 'input',
                    showCancelButton: true,
                    closeOnConfirm: false,
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar',
                },
                function (inputValue) {
                    if (inputValue === false) return false;
                    if (inputValue.length > 255) {
                        swal.showInputError("No puede contener más de 200 caracteres.");
                        return false
                    }
                    if (inputValue === "") {
                        swal.showInputError("El motivo no puede estar vacío.");
                        return false
                    }else{
                        swal(
                            {
                                title: 'Nota de Venta Pasada a Historico' ,
                                text: '',
                                type: 'warning',
                                showCancelButton: false,
                                confirmButtonText: 'OK',
                                cancelButtonText: '',
                                closeOnConfirm: true,
                                closeOnCancel: false
                            },
                            function(isConfirm)
                            {
                                if(isConfirm){
                                    location.href="{{ asset('/') }}cerrarNotaVenta/{{ $notaventa[0]->idNotaVenta }}/" + inputValue +"/";                  
                                }
                            })
                    }

                }
            ); 
        }

        function bajarOC(){
            $.ajax({
                url: urlApp + "existeArchivoNotaDeVenta",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        carpeta: 'ocnventa',
                        nombreArchivo: document.getElementById('txtOrdenCompra').dataset.ocarchivo
                      },
                success:function(dato){
                       if(!dato.existe){
                            swal(
                                {
                                    title: 'Archivo no encontrado!!' ,
                                    text: 'Nombre de archivo: ' + document.getElementById('txtOrdenCompra').dataset.ocarchivo,
                                    type: 'warning',
                                    showCancelButton: false,
                                    closeOnConfirm: true,
                                    confirmButtonText: 'Cerrar',
                                    cancelButtonText: '',
                                })                        
                       }else{
                            var url = urlApp + "bajarOCnventa/"+  document.getElementById('txtOrdenCompra').dataset.ocarchivo +"/";
                            window.open(url, "Ver PDF")
                       };
                }
            })            
        }

        function guardarCambiosNV(){
            var tabla= document.getElementById('tablaDetalle');
            var cadena='[';
            for (var i = 1; i < tabla.rows.length; i++){
                cadena+='{';
                cadena+='"idNotaVentaDetalle":"'+  tabla.rows[i].cells[0].innerHTML  + '", ';
                if(tabla.rows[i].cells[2].getElementsByTagName('input')[0]){
                    cadena+='"formula":"'+  tabla.rows[i].cells[2].getElementsByTagName('input')[0].value.replace('.','')  + '"';
                }else{
                    cadena+='"formula":""';
                }
                cadena+='}, ';
            }
            cadena=cadena.slice(0,-2);
            cadena+=']';

            $.ajax({
                url: urlApp + "actualizarDatosNV",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        idNotaVenta: $("#idNotaVenta").val(),
                        contacto: $("#txtNombreContacto").val(),
                        correo: $("#txtCorreoContacto").val(),
                        telefono: $("#txtTelefonoContacto").val(),
                        observaciones: $("#txtObservaciones").val(),
                        ordenCompraCliente: $("#txtOrdenCompra").val(),
                        idCondiciondePago: $("#idCondicionPago").val(),
                        codigoSoftland: $("#txtCodigoSoftland").val(),
                        idUsuarioEncargado: $("#idUsuarioEncargado").val(),
                        detalle: cadena
                      },
                success:function(dato){
                    swal(
                        {
                            title: '¡¡Los datos han sido guardados!!' ,
                            text: '',
                            type: 'warning',
                            showCancelButton: false,
                            closeOnConfirm: true,
                            confirmButtonText: 'Cerrar',
                            cancelButtonText: '',
                        },
                            function(isConfirm)
                            {
                                if(isConfirm){
                                    location.href="{{ asset('/') }}listarNotasdeVenta";                               
                                }
                            }                        
                        )                        
                }
            }) 
        }
    </script>

    
@endsection