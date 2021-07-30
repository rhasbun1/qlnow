@extends('plantilla')      

@section('contenedorprincipal')

<div style="padding: 5px">
    <div class="panel panel-default" id="contenedor3">
        <div class="panel-heading">
            <div class="panel-tab clearfix">
                <ul class="tab-bar">
                    <li class="active"><a href="#tabAprobados" data-toggle="tab"><b>Pedidos Aprobados</b></a></li>
                    @if(Session::get('idPerfil')!='6') 
                        <li><a href="#tabPendientes" data-toggle="tab"><b>Pedidos Pendientes de Aprobación</b></a></li>
                    @endif     
                </ul>
            </div>
        </div> 
        <div class="panel-body" id="panelBody" style="display: none">            
            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="idPerfil" value="{{ Session::get('idPerfil') }}">            
            <div class="tab-content clearfix">

                @if(Session::get('idPerfil')=='10' and Session::get('idPerfil')=='9') 
                    <div class="tab-pane active" id="tabAprobados" style="padding-top: 5px;max-width: 1720px">
                @else
                    <div class="tab-pane active" id="tabAprobados" style="padding-top: 5px;max-width: 1820px">
                @endif
                    <div style="padding-bottom: 15px">  
                        <div class="row">
                            <div class="col-md-1">
                                Fecha Entrega
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date" id="divFechaMin">
                                    <input type="text" class="form-control input-sm" id="min">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date" id="divFechaMax">
                                    <input type="text" class="form-control input-sm" id="max">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="filtroPorFecha('entrega');">Filtrar</button>
                            </div>
                            <div class="col-md-1" style="text-align: right;">
                                Fecha Carga
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date" id="divFechaCargaMin">
                                    <input type="text" class="form-control input-sm" id="minFechaCarga">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group date" id="divFechaCargaMax">
                                    <input type="text" class="form-control input-sm" id="maxFechaCarga">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="filtroPorFecha('carga');">Filtrar</button>
                            </div>
                        </div>
                    </div>                
                    <table id="tablaAprobados" class="table table-condensed">
                        <thead>
                            <th style="width:80px">Pedido</th>
                            <th style="width:120px"></th>
                            <th style="width:80px">Nº Auxiliar</th>
                            <th style="width:50px">Estado</th>
                            <th style="width:200px">Cliente</th>
                            <th style="width:200px">Obra/Planta</th>

                            @if( Session::get('idPerfil')=='10' )
                                <th style="width:120px">Fecha Carga</th>
                                <th style="width:150px">Transporte</th>
                                <th style="width:50px">Planta Origen</th>
                                <th style="width:70px">Fecha Entrega</th>
                                <th style="width:100px">Producto</th>
                                <th style="width:50px">Cantidad</th>
                                <th style="width:50px">Unidad</th>
                            @else
                                <th style="width:100px">Producto</th>
                                <th style="width:50px">Cantidad</th>
                                <th style="width:50px">Unidad</th>
                                <th style="width:50px">Planta Origen</th>
                                <th style="width:70px">Fecha Entrega</th>
                                <th style="width:50px">Forma Entrega</th>
                                <th style="width:120px">Fecha prog. Carga</th>
                                <th style="width:150px">Transporte</th>
                            @endif
                            <th style="width:100px">Fecha Creación</th>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $item)
                                @if (   ($item->idEstadoPedido!='1' and $item->idEstadoPedido!='6') and 
                                        ( ( Session::get('idPerfil')=='10') or Session::get('idPerfil')!='10' ) 
                                    )
                                    @if ( $item->idEstadoPedido=='0' )
                                        <tr style="background-color: #A93226; color: #FDFEFE">
                                    @else
                                        @if ( $item->modificado>0)
                                            <tr style="background-color: yellow">
                                        @else
                                            <tr>
                                        @endif
                                    @endif  
                                        <td style="width:80px" data-idpedido="{{ $item->idPedido }}" >
                                            @if ( $item->idEstadoPedido=='0' or Session::get('idPerfil')=='9' )
                                                <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/3-2/" class="btn btn-xs btn-success">{{ $item->idPedido }}</a>
                                            @else
                                                <a href="{{ asset('/') }}programarpedido/{{ $item->idPedido }}/3/" class="btn btn-xs btn-success">{{ $item->idPedido }}</a>
                                            @endif
                                        </td>
                                        <th style="width:120px">
                                            @if ($item->modificado>0)
                                                <span class="badge badge-primary" title="Nº de modificaciones">{{$item->modificado}}</span>
                                            @endif                                            
                                            @if ($item->tipoTransporte==2)
                                                <span class="badge badge-danger" title="Pedido Mixto">M</span>
                                            @endif
                                            @if ( $item->formula!='' )
                                                <span><img src="{{ asset('/') }}img/iconos/matraz.png" border="0" title="{{ $item->formula }}" width="15px" height="15pxs"></span>
                                            @endif                   
                                            @if ( $item->horaCarga!='' )
                                                <span><img src="{{ asset('/') }}img/iconos/time.png" border="0" title="{{$item->fechaCarga_dma}} {{$item->horaCarga}}"></span>
                                            @endif
                                            @if ( $item->empresaTransporte!='' )
                                                <span><img src="{{ asset('/') }}img/iconos/user.png" border="0" title="{{$item->empresaTransporte}} / {{$item->apellidoConductor}}"></span>
                                            @endif                                            
                                            @if ( $item->cantidadReal>0 )
                                                <span><img src="{{ asset('/') }}img/iconos/cargacompleta.png" border="0"></span>
                                            @endif
                                            @if ( $item->numeroGuia>0 )
                                                <span onclick="abrirGuia(1, {{ $item->numeroGuia}}, this.parentNode.parentNode)" style="cursor:pointer; cursor: hand"><img src="{{ asset('/') }}img/iconos/guiaDespacho2.png" border="0"></span>
                                            @endif                                            
                                            @if ( $item->certificado!='' )  
                                                <a target="_blank" href="{{ asset('/') }}bajarCertificado/{{ $item->certificado }}">
                                                    <img src="{{ asset('/') }}img/iconos/certificado.png" border="0">
                                                </a>
                                            @endif
                                            @if ( $item->salida==1 )
                                            <span><img src="{{ asset('/') }}img/iconos/enTransporte.png" border="0" onclick="verUbicacionGmaps('{{ $item->Patente }}');" style="cursor:pointer; cursor: hand"></span>                                      
                                            @endif    
                                        </td> 
                                        <td style="width:70px; text-align: center">
                                            @if( Session::get('idPerfil')>='5' and Session::get('idPerfil')<='7' )
                                                <a class="btn btn-xs btn-default" style="height: 25px;width:80px" onclick="ingresarNumeroAuxiliar(this.parentNode.parentNode );">{{ $item->numeroAuxiliar }}</a>
                                            @else
                                                {{ $item->numeroAuxiliar }}
                                            @endif
                                        </td>     
                                        <td style="width:50px">{{ $item->estadoPedido }}</td>
                                        <td style="width:200px">{{ $item->nombreCliente }}</td>
                                        <td style="width:200px">
                                            {{ $item->nombreObra }}
                                        </td>
                                        
                                       {{-- se cae aqui en transporte --}}
                                      {{--@if( Session::get('idPerfil')=='10' )--}}
                                        @if( Session::get('idPerfil')=='10' )
                                            <td style="width:120px">{{ $item->fechaCarga }} {{ $item->horaCarga }} </td>
                                            <td style="width:150px">{{ $item->apellidoConductor }} / {{ $item->empresaTransporte }}</td>
                                            <td style="width:50px">{{ $item->nombrePlanta }}</td>
                                            <td style="width:70px">{{ $item->fechaEntrega }} {{ $item->horarioEntrega }}</td>
                                            <td style="width:100px">
                                                {{$item->prod_nombre}}  
                                            </td>                                            
                                            <td style="width:50px">{{ $item->cantidad }}</td>
                                            <td style="width:50px">{{ $item->u_abre }}</td>
                                        @else
                                            <td style="width:100px">
                                                {{$item->prod_nombre}} 
                                            </td>
                                            <td style="width:50px;text-align: right;">{{ number_format( $item->cantidad, 0, ',', ',' ) }}</td>
                                            <td style="width:50px">{{ $item->u_abre }}</td>
                                            <td style="width:50px">{{ $item->nombrePlanta }}</td>
                                            
                                            <td style="width:70px">{{ $item->fechaEntrega }} {{ $item->horarioEntrega }}</td>
                                            <td style="width:50px">{{ $item->formaEntrega }}</td>
                                            <td style="width:120px">{{ $item->fechaCarga }} {{ $item->horaCarga }} </td>
                                            <td style="width:150px">{{ $item->apellidoConductor }} / {{ $item->empresaTransporte }}</td>
                                        @endif
                                        <td style="width:100px">{{ date('d/m/Y', strtotime($item->fecha))}} {{ $item->hora}}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>            
                    </table>
                </div>
                @if(Session::get('idPerfil')!='6') 
                    <div class="tab-pane" id="tabPendientes" style="padding-top: 5px">
                        <table id="tablaPendientes" class="pedidos table table-hover table-condensed">
                            <thead>
                                <th>Pedido</th>
                                <th>Estado</th>
                                <th>Fecha Creación</th>
                                <th>Cliente</th>
                                <th>Obra/Planta</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Planta Origen</th>
                                <th>Forma Entrega</th>
                                <th>Fecha Entrega</th>
                                <th>Horario</th>
                                <th style="display: none;" >Cantidad</th>

                            </thead>
                            <tbody>
                                @foreach($pedidos as $item)

                                    <?php

                                    ?>
                                    @if( $item->idEstadoPedido == '1' )
                                        <tr>
                                            <td><a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/3-2/" class="btn btn-xs btn-success">{{ $item->idPedido }}</a></td>
                                            <td></td>
                                            <td>{{ $item->fechahora_creacion }}</td>
                                            <td>{{ $item->nombreCliente }}</td>
                                            <td>{{ $item->nombreObra }}</td>
                                            <td>
                                                {{ $item->prod_nombre }}
                                                @if ($item->tipoTransporte==2)
                                                    <span class="badge badge-danger">M</span>
                                                @endif                                             
                                            </td>
                                            <td style="text-align: right;">{{ number_format( $item->cantidad, 0, ',', ',' ) }}</td>
                                            <td>{{ $item->nombrePlanta }}</td>
                                            <td>{{ $item->formaEntrega }}</td>
                                            <td>{{ $item->fechaEntrega }}</td>
                                            <td>{{ $item->horarioEntrega }}</td>
                                            <td style="display: none;">{{ number_format( $item->cantidad, 0, ',', ',' ) }}</td>

                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>              
                        </table>      
                    </div>
                @endif
            </div>
        </div>
    </div>
   
</div>

<div id="modNumeroAuxiliar" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-sm">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Ingrese número auxiliar</b></h5>
            </div>
            <div id="bodyProducto" class="modal-body">
                <input id="numeroAuxiliar" class="form-control input-sm" maxlength="10" data-fila="0">
            </div>
            <div class="col-md-offset-4" style="padding-top: 20px; padding-bottom: 20px">
               <button id="btnNumAuxiliar" type="button" class="btn btn-warning btn-sm" style="width: 100px" onclick="guardarNumeroAuxiliar();">Guardar</button>
               <button id="btnCerrarMumAuxiliar" type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModalNumeroAuxliar()" style="width: 80px">Salir</button>
            </div>            
        </div>
    </div>
</div>

<div id="modUbicacion" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Datos de Ubicación</b></h5>
            </div>
            <div id="bodyProducto" class="modal-body">
                <input class="hidden" id="fila">
                <div class="row" style="padding-top: 5px">
                    <div class="col-md-2">
                        Patente 
                    </div>
                    <div class="col-md-2">
                        <input class="form-control input-sm" id="nombre" value="CFHK83" readonly>
                    </div>
                    <div class="col-md-2">
                        Fecha/Hora Reporte:
                    </div>
                    <div class="col-md-3">
                        <input class="form-control input-sm" value="2018-08-03T12:20:06-04:00" readonly>
                    </div>                
                </div>            
                <div class="row" style="padding-top: 5px">
                    <div class="col-md-2">
                        Localización
                    </div>
                    <div class="col-md-10">
                        <input class="form-control input-sm" id="descripcion" value="A 198 mts (SE) La Montana y 5.81 km Chicureo, Colina,Chacabuco,Chile." readonly>
                    </div>
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-md-2">
                        Latitud
                    </div>
                    <div class="col-md-2">
                        <input class="form-control input-sm" value="-33.313198" readonly>
                    </div>
                    <div class="col-md-2">
                        Longitud
                    </div>
                    <div class="col-md-2">
                        <input class="form-control input-sm" value="-70.718941" readonly>
                    </div>
                    <div class="col-md-2">
                        Velocidad
                    </div>
                    <div class="col-md-2">
                        <input class="form-control input-sm" value="0.0" readonly>
                    </div>
                </div>
                   
            </div>
            <div id="map" style="height: 400px">
                 <img src="{{ asset('/') }}img/mapa.jpg" border="0" style="width:100%; height:100%">
            </div>
            <div class="col-md-offset-8" style="padding-top: 20px; padding-bottom: 20px">
               <button type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModal()" style="width: 80px">Salir</button>
            </div>        
        </div>
    </div>
</div>

<div id="modalGuia" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Datos de la Guía</b></h5>
            </div>
            <div id="bodyModal" class="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                        Número
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="txtNumeroGuia" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-sm-3">
                        Sellos
                    </div>
                    <div class="col-sm-3">
                        <input type="text" id="txtSellos" class="form-control input-sm" readonly>
                    </div>
                    <div class="col-sm-3">
                        Temperatura
                    </div>
                    <div class="col-sm-3">
                        <input type="text" id="txtTemperatura" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-sm-3">
                        Observaciones
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="txtObservaciones" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-sm-3">
                        Fecha/Hora Salida
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="txtFechaHoraSalida" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-sm-3">
                        Retirado Por
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="txtRetira" class="form-control input-sm" readonly>
                    </div>
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-sm-3">
                        Patente
                    </div>
                    <div class="col-sm-9">
                        <input type="text" id="txtPatente" class="form-control input-sm" readonly>
                    </div>
                </div>                                                         
            </div>        
            <div style="padding-left: 15px; padding-top: 5px">
                <h5><b> (*) Dato Obligatorio</b></h5>
            </div>
            <div class="col-md-offset-8" style="padding-top: 20px; padding-bottom: 20px">
               <button id="btnVerGuia" type="button" class="btn btn-warning btn-sm" style="width: 100px" disabled>Ver Guia Elec.</button>
               <button id="btnCerrar" type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModalGuia()" style="width: 80px">Salir</button>
            </div>

        </div>
    </div>
</div>

@include('guiaDespacho')

@endsection

@section('javascript')

    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script> 
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>
    <script src="{{ asset('/') }}js/app/funciones.js?{{$parametros[0]->version}}"></script>
    <script src="{{ asset('/') }}js/app/guiaDespacho.js?{{$parametros[0]->version}}"></script>

    <script>

        function ingresarNumeroAuxiliar(row){
            var tabla=$("#tablaAprobados").DataTable();
            var fila=tabla.row(row).index();
            var celda=tabla.cell(fila,2).node();
            $("#numeroAuxiliar").val(celda.getElementsByTagName('a')[0].innerHTML);            
            document.getElementById("numeroAuxiliar").dataset.fila=tabla.row( row ).index();;
            $("#modNumeroAuxiliar").modal('show');
        } 

        function cerrarModalNumeroAuxliar(){
            $("#modNumeroAuxiliar").modal('hide');
        }
        
        function guardarNumeroAuxiliar(){
            var fila=document.getElementById('numeroAuxiliar').dataset.fila;
            var tabla=$("#tablaAprobados").DataTable();
            var celda=tabla.row(document.getElementById("numeroAuxiliar").dataset.fila,0).node();
            var pedido=celda.getElementsByTagName('a')[0].innerHTML;

            for(var x=0;x<tabla.rows().count();x++){
                celda=tabla.cell(x,0).node();
                if(celda.getElementsByTagName('a')[0].innerHTML==pedido){
                    celda=tabla.cell(x,2).node();
                    celda.getElementsByTagName('a')[0].innerHTML=$("#numeroAuxiliar").val();
                    tabla.cell(x,2).draw();
                }
            }

            //guardar Numero auxiliar en el pedido
            $.ajax({
                url: urlApp + "actualizarNumeroAuxiliar",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        idPedido: pedido,
                        numeroAuxiliar: $("#numeroAuxiliar").val()
                      },
                success:function(dato){
                }
            });
            cerrarModalNumeroAuxliar();      
        }

        function abrirModal(patente){
            $("#modUbicacion").modal("show");
        }

        function cerrarModal(){
            $("#modUbicacion").modal("hide");
        }  

        function filtroPorFecha(tipoFecha){
            if(tipoFecha=='entrega'){
                $("#minFechaCarga").val('');
                $("#maxFechaCarga").val('');
                var fechaDesde=$("#min").val().trim();
                var fechaHasta=$("#max").val().trim();
                var urlApi=urlApp +'productosconPedidoPendientePorFechaEntrega';
            }else{
                $("#min").val('');
                $("#max").val('');
                var fechaDesde=$("#minFechaCarga").val().trim();
                var fechaHasta=$("#maxFechaCarga").val().trim();
                var urlApi=urlApp +'productosconPedidoPendientePorFechaCarga';                 
            }

            var tabla=$("#tablaAprobados").DataTable();
            tabla.rows().remove().draw();


            if(fechaDesde==''){
                fechaDesde='01/01/1900';
            }
            
            if(fechaHasta==''){   
                var hoy = new Date();
                var dd = hoy.getDate();
                var mm = hoy.getMonth()+1;
                var yyyy = hoy.getFullYear();

                if (dd < 10) {dd = '0' + dd; }
                if (mm < 10) {mm = '0' + mm; }
            
                fechaHasta=dd + '/' + mm + '/' + yyyy;
            }

            fechaDesde=fechaAtexto(fechaDesde);
            fechaHasta=fechaAtexto(fechaHasta);

            $.ajax({
                url: urlApi,
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        fechaInicio: fechaDesde,
                        fechaTermino: fechaHasta
                      },
                success:function(dato){
                    for(var x=0;x<dato.length;x++){
                        if( ( dato[x].idEstadoPedido!='1' && dato[x].idEstadoPedido!='6') && 
                                        ( $('#idPerfil').val()=='10' || $('#idPerfil').val()!='10' ) ) {
                            cadena="";
                            if(dato[x].modificado>0){
                                cadena+='<span class="badge badge-primary" title="Nº de modificaciones">' + dato[x].modificado + '</span>';
                            }    
                            if(dato[x].tipoTransporte==2){
                                cadena+='<span class="badge badge-danger" title="Pedido mixto">M</span>';
                            }
                            if( dato[x].formula!='' ){
                                cadena+='<span><img src="' + urlApp +'img/iconos/matraz.png" border="0" title="' + dato[x].formula +'" width="15px" height="15pxs"></span>';
                            }
                            if( dato[x].horaCarga!='' ){
                                cadena+='<span><img src="' + urlApp + 'img/iconos/time.png" border="0" title="'+dato[x].fechaCarga_dma + ' ' + 
                                dato[x].horaCarga + '"></span>';
                            }
                            if( dato[x].empresaTransporte!='' ){
                                cadena+='<span><img src="' + urlApp + 'img/iconos/user.png" border="0" title="' + dato[x].empresaTransporte + '/' + 
                                dato[x].apellidoConductor + '"></span>';
                            }
                            if( dato[x].cantidadReal>0 ){
                                cadena+='<span><img src="' + urlApp + 'img/iconos/cargacompleta.png" border="0"></span>';
                            }

                            if( dato[x].numeroGuia>0 ){
                                cadena+='<span onclick="abrirGuia(1, ' + dato[x].numeroGuia + ', this.parentNode.parentNode);" style="cursor:pointer; cursor: hand"><img src="'+ urlApp + 'img/iconos/guiaDespacho2.png" border="0"></span>';
                            }

                            if( dato[x].certificado!='' ){
                                if(dato[x].certificado == 'S/C'){
                                      
                                 cadena+='<a target="_blank" href="'+ urlApp + 'bajarCertificado/"' + dato[x].certificado + '">';
                                cadena+='<img src="'+ urlApp + 'img/iconos/certificado.png" border="0"></a>';
                                }  
                                
                                
                            }

                            if( dato[x].salida==1 ){
                                cadena+='<span><img src="' + urlApp + 'img/iconos/enTransporte.png" border="0" onclick="verUbicacionGmaps(' + dato[x].Patente + ');" style="cursor:pointer; cursor: hand"></span>';    
                            }

                            if ( dato[x].idEstadoPedido=='0' || $('#idPerfil').val()=='9' ){
                                celdaPedido='<a href="'+urlApp+'verpedido/'+ dato[x].idPedido + '/3/" class="btn btn-xs btn-success">' + dato[x].idPedido +'</a>';
                            }else{
                                celdaPedido='<a href="'+urlApp+'programarpedido/'+ dato[x].idPedido + '/3/" class="btn btn-xs btn-success">' + dato[x].idPedido +'</a>';
                            }

                        celdaNumAux="";

                        if(document.getElementById('idPerfilSession').value>=5 && document.getElementById('idPerfilSession').value<=7 ){
                            celdaNumAux='<a class="btn btn-xs btn-default" style="height: 25px;width:80px" onclick="ingresarNumeroAuxiliar(this.parentNode.parentNode );">' + dato[x].numeroAuxiliar + '</a>';                                   
                        }else{
                           celdaNumAux='<td style="width: 80px; text-align: center;">' + dato[x].numeroAuxiliar + '</td>';
                        }   

            
                        
                            var ind=tabla.row.add( [
                                    celdaPedido,
                                    cadena,
                                    celdaNumAux,
                                    dato[x].estadoPedido,
                                    dato[x].nombreCliente,
                                    dato[x].nombreObra,
                                    dato[x].prod_nombre,
                                    dato[x].cantidad,
                                    dato[x].u_abre,
                                    dato[x].nombrePlanta,
                                    dato[x].fechaEntrega +' '+ dato[x].horarioEntrega,
                                    dato[x].formaEntrega,
                                    dato[x].fechaCarga+' '+ dato[x].horaCarga,
                                    dato[x].apellidoConductor+' / '+dato[x].empresaTransporte,
                                    dato[x].fechahora_creacion
                                ] ).index();

                            if(dato[x].idEstadoPedido=='0'){
                                var nodo=tabla.row(ind).node();
                                nodo.style.backgroundColor='#A93226';
                                nodo.style.color='#FDFEFE';
                            }else{
                                if( dato[x].modificado>0){
                                    var nodo=tabla.row(ind).node();
                                    nodo.style.backgroundColor='yellow';
                                }
                            }

                        }

                    }
                    tabla.draw();
                   // $("#mdProcesando").modal('hide');
                },
                error: function(jqXHR, text, error){
                    $("#mdProcesando").modal('hide');
                    alert('Ha ocurrido un error!, vuelva a presionar el botón Buscar Selección');
                }                
            });         
        }
   

        $('#datosGuia').on('submit', function(e) {
            // evito que propague el submit
            e.preventDefault();
              // agrego la data del form a formData

            if( $("#nuevoFolioDTE").val().trim()=='' ){
                swal(
                    {
                        title: '¡¡Debe ingresar el numero Folio DTE!!' ,
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
                );
                return;
            }

            if( $("#upload-demo").val().trim()=='' ){
                swal(
                    {
                        title: 'No ha seleccionado un archivo para subir!!' ,
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
                );
                return;
            }

            var tabla=document.getElementById('tablaDetalleGuia');
            for (var i = 1; i < tabla.rows.length; i++){
                if(tabla.rows[i].cells[4].getElementsByTagName('input')[0]){
                  cantidad=tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "").replace(",",".");
                  if(cantidad=='' || parseFloat(cantidad)<=0){
                    swal(
                        {
                            title: '¡Debe completar las cantidades y actualizar antes de subir el archivo!' ,
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
                    );
                    return;            
                  }
                }
                /*if(tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "")>parseInt(tabla.rows[i].cells[3].innerHTML.trim().replace(".", ""))){
                        swal(
                        {
                            title: 'La cantidad pedida no puede ser mayor a la solicitada',
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
                            return;
                        }
                        )
                        return; 
                }*/
            }

            // Se recorre el DataTable para modificar la funcion abrirGuia con el nuevo número ingresado por el usuario

            var numeroGuiaOrigen="abrirGuia(1, " + document.getElementById('folioDTE').dataset.numeroguia + ", this.parentNode.parentNode)";
            var nuevoNumeroGuia ="abrirGuia(1, " + $('#nuevoFolioDTE').val() + ", this.parentNode.parentNode)";
            var table = $('#tablaAprobados').DataTable();
            var cadena = "";
            var filas=table.rows().count();

            for (var i = 0; i < filas; i++){
                cadena=table.cell(i,1).data();
                table.cell(i,1).data( cadena.replace(numeroGuiaOrigen, nuevoNumeroGuia) );
            }

          var existe=true;  
          $.ajax({
                async: false, 
                url: urlApp + "validarNumeroGuia",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                    numeroGuia: nuevoFolioDTE.value
                },
                success:function(data){
                    if(data.identificador==0){
                        existe=false;
                        swal(
                            {
                                title: '¡El Número de guía ingresado no existe en contabilidad!' ,
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
                        );                    
                    }
                },
                error: function(jqXHR, text, error){
                    alert('Error!, No se pudo Añadir los datos');
                }
          });
          if(!existe){
            return;
          }
              
            // Aqui se actualizan los cantidades ingresadas en la guía de despacho   

            actualizarDatosGuiaDespacho(false);

            // a continuación se envñia el formulario con el nuevo número de guía y el archivo pdf correspondiente a la guía

            var formData = new FormData( $("#datosGuia")[0]);
            $.ajax({
                  url: urlApp + "subirGuiaDespachoPdf",
                  headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                  type:'POST',
                  data:formData,
                  cache:false,
                  contentType: false,
                  processData: false,
                  success:function(data){
                    if(data=="0"){
                        swal(
                            {
                                title: 'El número de guía ya existe!!' ,
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
                        );
                        return;
                    }                 

                    $("#folioDTE").val( $("#nuevoFolioDTE").val() );
                    if(document.getElementById('btnAnularGuiaTemporal')){
                      document.getElementById('btnAnularGuiaTemporal').style.display='none';
                    }
                    if(document.getElementById('btnGuardarDatosGuia')){
                      document.getElementById('btnGuardarDatosGuia').style.display='none';
                    }

                    if( document.getElementById('btnEmitirGuia').dataset.idperfil=='5' || 
                        document.getElementById('btnEmitirGuia').dataset.idperfil=='6' || 
                        document.getElementById('btnEmitirGuia').dataset.idperfil=='7'){
                        document.getElementById('btnRegistrarSalida').style.display='inline';
                    }

                    document.getElementById('btnRegistrarSalida').style.display='none';
                    document.getElementById('btnGuardarDatosGuia').style.display='none';

                    if(document.getElementById('btnEmitirGuia').dataset.idperfil=='5' || 
                      document.getElementById('btnEmitirGuia').dataset.idperfil=='6' || 
                      document.getElementById('btnEmitirGuia').dataset.idperfil=='7'){
                    }
                    document.getElementById('btnEmitirGuia').style.display='none';
                    document.getElementById('btnBajar').style.display='inline';
                    $("#observacionDespacho").attr('readonly', true);
                    $("#guiaPatente").attr('readonly', true);
                    $("#guiaNombreConductor").attr('readonly', true);
                    $("#sellos").attr('readonly', true);
                    $("#nombreEmpresaTransportes").attr('readonly', true);
                    $("#temperatura").attr('readonly', true);
                    document.getElementById('btnEmitirGuia').onclick = function() { emitirGuiaDespacho(false); }      

                    cerrarModalSubirGuiaPdf();

                  },
                  error: function(jqXHR, text, error){
                      alert('Error!, No se pudo Añadir los datos');
                  }
            });
        });
        
        $(document).ready(function() {
            
            var idPerfil=$("#idPerfil").val();
            var tablaDetalle="#tablaAprobados";
            // Setup - add a text input to each footer cell

            // DataTable

            // Setup - add a text input to each footer cell
            $('#tablaAprobados thead tr').clone(true).appendTo( '#tablaAprobados thead' );
            $('#tablaAprobados thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();

                if(title.trim()!='' && title.trim()=='Obra/Planta' ){
                    $(this).html( '<select id="selObra" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Cliente' ){
                    $(this).html( '<select id="selCliente" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Estado' ){
                    $(this).html( '<select id="selEstado" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Forma Entrega' ){
                    $(this).html( '<select id="selFormaEntrega" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Planta Origen' ){
                    $(this).html( '<select id="selPlanta" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Unidad' ){
                    $(this).html( '<select id="selFormato" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Fecha prog. Carga' ){
                    $(this).html( '<select id="selFechaCarga" class="form-control input-sm"></select>')
                }else if(title.trim()!='' && title.trim()=='Producto' ){
                    $(this).html( '<select id="selProducto" class="form-control input-sm"></select>');                         
                }else{
                    $(this).html( '<input type="text" class="form-control input-sm" placeholder="Buscar..." />' );
                    $( 'input', this ).on( 'keyup change', function () {
                        if ( table.column(i).search() !== this.value ) {
                            table
                                .column(i)
                                .search( this.value )
                                .draw();
                        }
                    } );                    
                }
             
            } );
     
            var titulo="Pedidos en Proceso";
            var table=$('#tablaAprobados').DataTable({
                 orderCellsTop: true,
                 fixedHeader: true,         
                "lengthMenu": [[-1, 6, 12, 20], ["Todos", "6", "12", "20"]],
                dom: 'Bfrtip',
                "scrollX": true,
                buttons: [
                    {
                        text: 'Atrás',
                        action: function ( e, dt, node, config ) {
                            location.href=("{{ asset('/') }}dashboard");
                        }
                    },
                    {
                        text: 'Actualizar',
                        action: function ( e, dt, node, config ) {
                            this.disable();    
                            location.reload(true);                        
                        }
                    },
                    'pageLength',
                    {
                            extend: 'excelHtml5',
                            title: titulo,
                            text: '<i class="fa fa-file-excel-o"></i>',
                            titleAttr: 'Excel',                           
                            exportOptions: {
                                columns: [ 0, 2, 3, 4, 5, 6,7,8,9,10,11,12,13,14 ]
                            }
                        },
                    {
                        text: 'Próximos pedidos Granel',
                        action: function ( e, dt, node, config ) {

                            window.open(urlApp + 'verResumenGranel', "QL Now", 'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,fullscreen=1')                    
                        }
                    },
                    {
                        text: 'Pedidos despachados (granel)',
                        action: function ( e, dt, node, config ) {
                            window.open(urlApp + 'verPedidosDespachados', "QL Now")                    
                        }
                    }
                ],                  
                "order": [[ 0, "asc" ]],                        
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                preDrawCallback: function( settings ) {
                    document.getElementById('panelBody').style.display="block";
                  },                
                initComplete: function () {
                    this.api().columns(3).every( function () {
                        var column = this;
                        var select = $("#selEstado" ).empty().append( '<option value=""></option>' )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
         
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
         
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );

                    this.api().columns(4).every( function () {
                        var column = this;
                        var select = $("#selCliente" ).empty().append( '<option value=""></option>' )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
         
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
         
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );

                    this.api().columns(5).every( function () {
                        var column = this;
                        var select = $("#selObra" ).empty().append( '<option value=""></option>' )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
         
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
         
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );                    

                    if( $("#idPerfil").val()=='10' ){
                        columna=12;
                    }else{
                        columna=8;
                    }                    

                    this.api().columns(columna).every( function () {
                        var column = this;
                        var select = $("#selFormato" ).empty().append( '<option value=""></option>' )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
             
                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );
             
                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                    } );

                    if( $("#idPerfil").val()=='10' ){
                        columna=6;
                    }else{
                        columna=12;
                    }                    

                    this.api().columns(columna).every( function () {
                        var column = this;
                        var select = $("#selFechaCarga" ).empty().append( '<option value=""></option>' )
                            .on( 'change', function () {
                                column
                                    .search( $(this).val().substring(0, 10) )
                                    .draw();
                            } );
                        var arrFechas=new Array();
                        column.data().unique().sort().each( function ( d, j ) {
                            if(!arrFechas.includes( d.substring(0,10)  ) ){
                                select.append( '<option value="'+ d.substring(0,10)  +'">'+ d.substring(0,10)  +'</option>' );
                                arrFechas.push(d.substring(0,10));
                            }

                        } );
                    } );

                    if( $("#idPerfil").val()=='10' ){
                        columna=10;
                    }else{
                        columna=6;
                    }                    

                    this.api().columns(columna).every( function () {
                        var column = this;
                        var select = $("#selProducto" ).empty().append( '<option value=""></option>' )
                            .on( 'change', function () {
                                column
                                    .search( $(this).val().substring(0, 10) )
                                    .draw();
                            } );
                        var arrFechas=new Array();
                        column.data().unique().sort().each( function ( d, j ) {
                            if(!arrFechas.includes( d.substring(0,10)  ) ){
                                select.append( '<option value="'+ d.substring(0,10)  +'">'+ d.substring(0,10)  +'</option>' );
                                arrFechas.push(d.substring(0,10));
                            }

                        } );
                    } );   
                    if( $("#idPerfil").val()!='10' ){
                    
                        this.api().columns(11).every( function () {
                            var column = this;
                            var select = $("#selFormaEntrega" ).empty().append( '<option value=""></option>' )
                                .on( 'change', function () {
                                    var val = $.fn.dataTable.util.escapeRegex(
                                        $(this).val()
                                    );
             
                                    column
                                        .search( val ? '^'+val+'$' : '', true, false )
                                        .draw();
                                } );
             
                            column.data().unique().sort().each( function ( d, j ) {
                                select.append( '<option value="'+d+'">'+d+'</option>' )
                            } );
                        } ); 
                     }   

                    if( $("#idPerfil").val()=='10' ){
                        columna=8;
                    }else{
                        columna=9;
                    }
                    this.api().columns(columna).every( function () {
                        var column = this;
                        var select = $("#selPlanta" ).empty().append( '<option value=""></option>' )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
         
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
         
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );                                               
                }

            });
        
            $('.date').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true
            }) 

            $('#wrapper').toggleClass('sidebar-hide');
            $('.main-menu').find('.openable').removeClass('open');
            $('.main-menu').find('.submenu').removeAttr('style');             
        } );
        function formato(texto){
			return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
		}
        
    </script>

@endsection