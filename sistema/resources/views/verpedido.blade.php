

@extends($plantilla)
@section('contenedorprincipal')

<div style="padding: 5px">
    <div class="panel panel-default table-responsive">
<!--        <div class="panel-heading">
            <h5><b>Pedido Nº {{ $pedido[0]->idPedido }}</b></h5>
        </div>-->
        <div class="padding-md clearfix">
        	<div>
                <input type="hidden" id="idPedido" value="{{ $pedido[0]->idPedido }}">
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <font size="3"><b>Pedido Nº {{ $pedido[0]->idPedido }}</b></font>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        Cliente
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-5">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->emp_nombre }}">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        Rut
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->emp_rut }}">
                    </div>  
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        Creación
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->fechahora_creacion }}">
                    </div>                                     
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        Obra
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->Obra }}">
                    </div>                      
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        Datos de Contacto
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->datosContacto }}">
                    </div>                      
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        Estado
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-3">
                       <b><input class="form-control input-sm" readonly value="{{ $pedido[0]->estado }}"></b>
                    </div> 
                    <div class="col-lg-1 col-sm-2 col-md-1">
                        O.Compra
                    </div>
                    <div class="col-lg-3 col-sm-4 col-md-3">
                        <div class="input-group">                           
                            <input id="txtOrdenCompra" class="form-control input-sm" readonly value="{{ $pedido[0]->ordenCompraCliente }}" data-ocarchivo="{{ $pedido[0]->nombreArchivoOC }}" >
                            @if ( Session::get('grupoUsuario')=='C' ) 
                                <span class="input-group-addon glyphicon glyphicon-cloud-download" title="Bajar Orden de Compra" onclick="bajarOCpedido();"></span>
                            @endif    
                        </div>                            
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        N.Venta
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        @if ( Session::get('grupoUsuario')=='P' or Session::get('grupoUsuario')=='C' )
                            <a class="btn btn-success btn-sm" style="width: 100%" href="{{ asset('/') }}vernotaventa/{{ $pedido[0]->idNotaVenta }}-{{ $pedido[0]->idPedido }}/{{ $accionNota }}/">{{ $pedido[0]->idNotaVenta }}</a>
                        @else
                            <input class="form-control input-sm" value="{{ $pedido[0]->idNotaVenta }}" readonly>
                        @endif
                    </div>
                    @if ( Session::get('grupoUsuario')=='C' ) 
                        <div class="col-lg-1 col-md-1 col-sm-1">
                            Cond.Pago
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <textarea class="form-control input-sm" readonly rows="1">{{ $pedido[0]->condiciondePago }}</textarea>
                        </div>
                    @endif                                    
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        Fecha&nbspEntrega
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-4">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->fechaEntrega }} {{ $pedido[0]->horarioEntrega }}">
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-2">
                        Ejecutivo&nbspQL
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->usuario_encargado }}">
                    </div>
                </div>
                <div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-md-2 col-sm-2">
                        Observaciones
                    </div>
                    <div class="col-lg-8 col-md-7 col-sm-6">
                        <textarea style="background-color:white !important; color:red; border:solid 1px orange;" class="form-control input-sm" readonly rows="2">{{ $pedido[0]->observaciones }}</textarea>
                    </div>                                                      
                </div>
                @if ($pedido[0]->noExcederCantidadSolicitada==1)
        		    <div class="row" style="padding-top: 5px">
                        <div class="col-md-12" style="text-align: right;">
                            <h4><span class="label label-danger">NO EXCEDER LA CANTIDAD SOLICITADA</span></h4>
                        </div>          
                    </div>
                @endif                      		  		
        	</div>

        </div>
        <div style="padding: 10px">
            <table id="tablaDetalle" class="table table-hover table-condensed table-responsive">
                <thead>
                    <th style="display: none">Código</th>
                    <th>Producto</th>
                    <th style="width: 80px">Diseño</th>
                    <th style="width: 50px">Cant.</th>
                    <th>Unidad</th>
                    @if( Session::get('grupoUsuario')=='C' )   
                        <th>Precio ($) <b>*</b></th>
                        <th>Total ($) **</th>
                    @endif
                    <th>Planta de Origen</th>
                    <th>Entrega</th>
                    <th>Transporte</th>
                    <th>Camión</th>
                    <th style="text-align: center;">Rampla</th>
                    <th>Conductor</th>
                    <th style="text-align: center;">Fecha Hora Carga<br>Programada</th>
                    <th style="text-align: center;">Fecha Hora Carga<br>Real</th>
                </thead>
            
                <tbody>
                    <?php
                        $despachado=0;
                        $sinDespachar=0;
                        $guiaEmitida=0;
                    ?>
                    @foreach($listaDetallePedido as $item)
                    <?php
                        if($item->salida==1){$despachado++;}
                        if($item->salida==0){$sinDespachar++;}
                        if($item->numeroGuia>0){$guiaEmitida++;}
                    ?>
                    <tr>
                        <td style="display: none"> 
                            {{ $item->prod_codigo }}                         
                        </td>
                        <td>
                            {{ $item->prod_nombre }}
                            @if ( $item->certificado==1 )  
                                <a target="_blank" href="{{ asset('/') }}bajarCertificado/{{ $item->certificado }}">
                                    <img src="{{ asset('/') }}img/iconos/certificado.png" border="0">
                                </a>
                            @endif
                            @if ($item->modificado>0)
                                <span class="badge badge-primary" title="Nº de modificaciones">{{$item->modificado}}</span>
                            @endif                                            
                            @if ($item->tipoTransporte==2)
                                <span class="badge badge-danger" title="Pedido Mixto">M</span>
                            @endif                            
                            @if ( $item->formula!='' )
                                <span><img src="{{ asset('/') }}img/iconos/matraz.png" border="0" title="{{ $item->formula }}" width="15px" height="15pxs"></span>
                            @endif                            
                            @if ( $item->cantidadReal > 0 )
                                <span><img src="{{ asset('/') }}img/iconos/cargacompleta.png" border="0"></span>
                            @endif
                            @if ( $item->horaCarga!='' )
                                <span><img src="{{ asset('/') }}img/iconos/time.png" border="0" title="{{$item->fechaCarga_dma}} {{$item->horaCarga}}"></span>
                            @endif
                            @if ( $item->nombreEmpresaTransporte!='' )
                                <span><img src="{{ asset('/') }}img/iconos/user.png" border="0" title="{{$item->nombreEmpresaTransporte}} / {{$item->apellidoConductor}}"></span>
                            @endif                            
                            @if( $item->numeroGuia>0 )
                                <span onclick='abrirGuia(1, {{ $item->numeroGuia }}, this.parentNode.parentNode);' style="cursor:pointer; cursor: hand"><img src="{{ asset('/') }}img/iconos/guiaDespacho2.png" border="0"></span>                               
                            @endif 
                            @if ( $item->salida==1 )
                                <span><img src="{{ asset('/') }}img/iconos/enTransporte.png" border="0" onclick="verUbicacionGmaps('{{ $item->patente }}');" style="cursor:pointer; cursor: hand"></span>                                      
                            @endif                             
                        </td>
                        <td>{{ $item->formula }}</td>
                        <td style="width:50px">{{number_format( $item->cantidad, 0, ',', '.' ) }}</td>   
                        <td> {{ $item->u_nombre }} </td>
                        @if( Session::get('grupoUsuario')=='C' or Session::get('grupoUsuario')=='CL' )   
                            <td align="right">{{ number_format( $item->precioActual, 0, ',', '.' ) }}</td>
                            <td align="right">{{ number_format( $item->precioActual * $item->cantidad , 0, ',', '.' ) }}</td>
                        @endif    
                        <td> {{ $item->nombrePlanta }} </td>
                        <td> {{ $item->nombreFormaEntrega }} </td>
                        <td> {{ $item->nombreEmpresaTransporte }} </td>
                        <td> {{ $item->patente }} </td>
                        <td style="text-align: center;"> {{ $item->numeroRampla }} </td>
                        <td> {{ $item->nombreConductor }} </td>
                        <td> {{ $item->fechaCarga }} {{ $item->horaCarga }}</td>
                        <td> {{ $item->fechaCargaReal }} </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot> 
                    @if($pedido[0]->cantidadFleteFalso>0)
                        <tr>
                            <td>Flete Falso</td>
                            <td></td>
                            <td>{{$pedido[0]->cantidadFleteFalso}}</td>
                            <td>tonelada</td>
                            <td align="right">{{ number_format($pedido[0]->valorFleteFalso, 0, ',', '.' )}}</td>
                            <td align="right">{{ number_format( $pedido[0]->valorFleteFalso*$pedido[0]->cantidadFleteFalso, 0, ',', '.' ) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>             
                        </tr>
                    @endif                   
                    @if( Session::get('grupoUsuario')=='C' or Session::get('grupoUsuario')=='CL' )   
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right"><b>Neto $</b></td>
                            <td align="right"><b>{{ number_format($pedido[0]->totalNeto, 0, ',', '.' ) }} </b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>  
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right"><b>Iva $</b></td>
                            <td align="right"><b>{{ number_format( $pedido[0]->montoIva, 0, ',', '.' ) }} </b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>                          
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td align="right"><b>Total $</b></td>
                            <td align="right"><b>{{ number_format( $pedido[0]->totalNeto + $pedido[0]->montoIva, 0, ',', '.' ) }} </b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>                       
                        </tr> 
                    @endif                     
                </tfoot>
            </table>
            <br>
            <b>*</b> Precio indicado es el reajustado a la fecha de creación del pedido. Pueden existir diferencias con el precio final de facturación si el despacho se realiza en un mes distinto.<br>
            <b>**</b> El total facturado podrá ser distinto ya que se considerarán las cantidades efectivamente despachadas y los precios reajustados al mes durante el cual se realiza efectivamente el despacho.
        </div> 
        
        <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
            @if ( ( Session::get('idPerfil')=='2' or Session::get('idPerfil')=='11')  and $pedido[0]->idEstadoPedido==1 and $accion!='6' )
                <button class="btn btn-sm btn-primary" style="width:100px" onclick="aprobarPedido({{ $pedido[0]->idPedido }});">Aprobar VB</button>
            @endif    

            @if ( (Session::get('idPerfil')=='2' or Session::get('idPerfil')=='3' or Session::get('idPerfil')=='4') and $pedido[0]->idEstadoPedido>0 and $guiaEmitida==0 )
                <a href="{{ asset('/') }}editarPedido/{{ $pedido[0]->idPedido }}/" class="btn btn-sm btn-success" style="width:100px">Modificar</a>
            @endif
            @if ($accion=='1')
                @if (Session::get('grupoUsuario')=='C' and (Session::get('idPerfil')=='2' or Session::get('idPerfil')=='11') )
                    @if ($pedido[0]->idEstadoPedido==2 and $pedido[0]->idTransporte==0 )
                        <a href="{{ asset('/') }}desaprobarPedido/{{ $pedido[0]->idPedido }}/" class="btn btn-sm btn-primary" style="width:100px">Desaprobar</a>
                    @endif
                @endif    
                @if ( ($pedido[0]->idEstadoPedido>0 and $pedido[0]->idEstadoPedido < 5) and 
                (Session::get('idPerfil')=='2' or Session::get('idPerfil')=='3' or Session::get('idPerfil')=='18') )
                    @if($despachado==0)
                        <button class="btn btn-sm btn-danger" onclick="abrirCajaSuspender();">Suspender</button>
                    @endif
                @endif
            @elseif ($accion=='6')
                <button class="btn btn-sm btn-primary" style="width:100px" onclick="aprobarPedidoCliente();">Aprobar</button>
                @if($despachado==0 && $pedido[0]->idEstadoPedido==0)
                    <button class="btn btn-sm btn-danger" onclick="abrirCajaSuspender();">Suspender</button>
                @endif    
                @if($despachado==0 && $pedido[0]->idEstadoPedido==-1 && ((Session::get('idPerfil')=='2' or Session::get('idPerfil')=='4' or Session::get('idPerfil')=='3' or Session::get('idPerfil')=='18' or Session::get('idPerfil')=='14')))
                    <button class="btn btn-sm btn-danger" onclick="pasarHistorico();">Pasar a Histórico</button>
                @endif                                 
            @endif
            @if ($pedido[0]->idEstadoPedido==7 and (Session::get('idPerfil')=='2' or Session::get('idPerfil')=='3' or Session::get('idPerfil')=='18') )
            <button class="btn btn-sm btn-danger" onclick="abrirCajaSuspender();">Suspender</button>
            @endif
            <!-- Mostrar boton "Pasar a Historico" sólo si NO es pedido en historico -->
        
            @if($plantilla!='plantilla2') 
                @if ($accion=='3' or $accion=='1')          
                    @if ( ( $pedido[0]->cerrada==0 and $pedido[0]->idEstadoPedido <= '3') and 
                        (Session::get('idPerfil')=='5' or Session::get('idPerfil')=='6' or Session::get('idPerfil')=='7'))
                        @if( $pedido[0]->idEstadoPedido <= '0' or ( $despachado>0 and $sinDespachar>0) )
                            <button class="btn btn-sm btn-danger" onclick="pasarHistorico();">Pasar a Histórico</button>
                        @endif              
                    @endif
                @endif
            @endif
            
            @if($plantilla=='plantilla')
                @if ($accion == 1)
                    <a href="{{ asset('/') }}listarPedidos/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                @else
                    @if ($accionNota == 2)
                        @if (Session::get('idPerfil') == '11') <!--MATIAS-->
                            <a href="{{ asset('/') }}listarPedidos/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                        @else
                            @if (Session::get('idPerfil') == '4' || Session::get('idPerfil') == '2' || Session::get('idPerfil') == '3' || Session::get('idPerfil') == '12' || Session::get('idPerfil') == '18' || Session::get('idPerfil') == '19')<!--MATIAS-->
                                <a href="{{ asset('/') }}listarPedidos/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                            @else
                                <a href="{{ asset('/') }}programacion/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                            @endif
                        @endif
                    @elseif ($accionNota == 5)
                        <a href="{{ asset('/') }}guiasEnProceso/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                    @elseif ($accionNota == 6)
                        <a onclick="history.go(-1);" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                    @elseif ($accionNota == 7) <!-- MATIAS -->
                        <a href="{{ asset('/') }}listaIngresosClienteporAprobar/" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                    @else
                        <a  onclick="history.go(-1);" class="btn btn-sm btn-warning" style="width:80px">Atrás</a><!--MATIAS-->                    
                    @endif
                @endif
            @endif            

            <!--
            @if($plantilla=='plantilla')
                <a href="{{ URL::previous() }}" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
            @endif
            -->
        </div>
        
        @if (Session::get('grupoUsuario')!='CL')
        <div class="panel panel-default" id="contenedor3">
            <div class="panel-heading">
                <div class="panel-tab clearfix">
                    <ul class="tab-bar">
                        <li class="active"><a href="#tabLogAcciones" data-toggle="tab"><b>Registro de acciones sobre este Pedido</b></a></li>
                        <li><a href="#tabNotas" data-toggle="tab"><b>Notas</b>
                            @if (count($notas)>0)  
                                &nbsp&nbsp<span class="label label-danger" id="contNotas">{{ count($notas) }}</span>
                            @endif    
                        </a></li>                        
                    </ul>
                </div>
            </div>
            <div class="panel-body">
                <div class="tab-content clearfix">
                    <div class="tab-pane active" id="tabLogAcciones" style="padding-top: 5px">
                        <table id="tablaLog" class="table table-hover table-condensed table-responsive" style="width: 850px;">
                            <thead>
                                <th style="width:200px">Fecha</th>
                                <th style="width:250px">Usuario</th>
                                <th style="width:350px">Acción</th>
                                <th style="width:350px">Motivo</th>
                            </thead>
                            <tbody>
                                @foreach($log as $item)
                                <?php
                                    $fechahora=explode(" " ,$item->fechaHora);
                                    $fechaseparar = explode("-",$fechahora[0]);
                                    $fecha = ($fechaseparar[2]."/".$fechaseparar[1]."/".$fechaseparar[0]);
                                ?>

                                <tr>
                                    <td style="width:200px"> {{$fecha." ". $fechahora[1]}} </td>
                                    <td style="width:250px"> {{ $item->nombreUsuario }} </td>
                                    <td style="width:350px"> {{ $item->accion }} </td>
                                    <td style="width:350px;word-break: break-all;"> {{ $item->motivo }} </td>
                                </tr>
                                @endforeach  
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane" id="tabNotas" style="padding-top: 5px">
                        <div style="padding: 10px">
                            <div class="col-md-1">
                                Nota
                            </div>
                            <div class="col-md-6">
                                <input id="txtNota" class="form-control input-sm" maxlength="255">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-success btn-sm" onclick="agregarNota();">Agregar</button>
                            </div>                                                         
                        </div>
                        <div style="padding-left: 20px;padding-top: 40px;">
                            <table id="tablaNotas" class="table table-hover table-condensed table-responsive" style="width: 900px">
                                <thead>
                                    <th style="width:150px">Fecha</th>
                                    <th style="width:150px">Usuario</th>
                                    <th style="width:600px">Nota</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                @foreach($notas as $item)
                                <tr>
                                    <td style="width:200px"> {{  date('d/m/Y', strtotime($item->fechaHora)) }} </td>
                                    <td style="width:200px" data-idUsuario="{{ $item->idUsuario }}"> {{ $item->nombreUsuario }} </td>
                                    <td style="width:100px"> {{ $item->nota }} </td>
                                    <td>
                                        @if( Session::get('idUsuario')==$item->idUsuario )
                                        <button class="btn btn-warning btn-sm" onclick="eliminarNota({{ $item->idNota }}, this.parentNode.parentNode.rowIndex)">Eliminar</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach                                      
                                </tbody>
                            </table>
                        </div>
                    </div>                
                </div>
            </div>                 
        </div>
        @endif


    </div>
</div>

<div id="mdSuspender" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="height: 45px">
                <h5><b>Suspender pedido</b></h5>
            </div>
            <div id="bodyGuia" class="modal-body">
                Indique el motivo (máx.200 caract.)
                <div class="row">
                    <div class="col-md-12">
                        <input class="form-control input-sm" id="obsSuspension" maxlength="200">
                    </div> 

                </div>
            </div>
            <div style="padding-top: 20px; padding-bottom: 20px; padding-right: 20px; text-align: right;">
               <button type="button" class="btn btn-success btn-sm" onclick="SuspenderPedido({{ $pedido[0]->idPedido }})" style="width: 80px">Suspender</button>                
               <button id="btnCerrarCajaSuspender" type="button" class="btn btn-danger btn-sm" onclick="cerrarCajaSuspender()" style="width: 80px">Cancelar</button>
            </div>

        </div>
    </div>
</div>
@include('guiaDespacho');


@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>  

    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  

    <script src="{{ asset('/') }}js/app/funciones.js?{{$parametros[0]->version}}"></script>
    <script src="{{ asset('/') }}js/app/verpedido.js"></script>
    <script src="{{ asset('/') }}js/app/guiaDespacho.js?{{$parametros[0]->version}}"></script>
    <!-- Datatable -->
    <script src="{{ asset('/') }}js/jquery.dataTables.min.js"></script>
    <script>

        function pasarHistorico(){
            swal(
                {
                    title: 'Este pedido va a pasar al listado histórico. ¿Desea continuar?' ,
                    text: '',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'SI',
                    cancelButtonText: 'NO',
                    closeOnConfirm: true,
                    closeOnCancel: true
                },
                function(isConfirm)
                {
                    if(isConfirm){
                        $.ajax({
                            url: urlApp + "cerrarPedido",
                            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                            type:'POST',
                            dataType: 'json',
                            data: { 
                                    idPedido: document.getElementById('idPedido').value,
                                    motivo: ""
                                  },
                            success:function(data){
                                if(document.getElementById('idPerfilSession').dataset.grupo=='P'){
                                    swal(
                                        {
                                            title: 'el pedido ha pasado a Historico!!' ,
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
                                                location.href=urlApp+"programacion";                                                                    
                                            }
                                        }
                                    );
                                    
                                }else{
                                    swal(
                                        {
                                            title: 'el pedido ha pasado a Historico!!' ,
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
                                                location.href=urlApp+"listarPedidos";
                                            }
                                        }
                                    );
                                }
                            },
                            error: function(jqXHR, text, error){
                                alert('Error!,No se pudo completar la operación');
                            }
                        });                 
                    }
                }
            );            
        }

        $('#datosGuia').on('submit', function(e) {
          // evito que propague el submit
          e.preventDefault();
          // agrego la data del form a formData
          document.getElementById('btnSubirGuia').disabled=true;

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
                        document.getElementById('btnSubirGuia').disabled=false;
                        return;                         
                    }
                }
            );
            document.getElementById('btnSubirGuia').disabled=false;
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
                        document.getElementById('btnSubirGuia').disabled=false;
                        return;                         
                    }
                }
            );
            document.getElementById('btnSubirGuia').disabled=false;
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
            var table = $('#tablaDetalle').DataTable();
            var cadena = "";
            var numGuia=document.getElementById('folioDTE').dataset.numeroguia;
            var filas=table.rows().count();

            for (var i = 0; i < filas; i++){
                cadena=table.cell(i,1).data();
                table.cell(i,1).data( cadena.replace(numeroGuiaOrigen, nuevoNumeroGuia) );
            }

          var existe=true;  
          var urlApiSoft;
          urlApiSoft="http://webservice.quimicalatinoamericana.cl:8082/qrysoftland/api/datosdte";
          $.ajax({
                async: false,
                url: urlApiSoft,
                type: 'POST',
                dataType: 'json',
                data: { 
                    tipoDocumento: 52,
                    folio: $("#nuevoFolioDTE").val().trim()
                      },
                headers: { 
                    'X-CSRF-TOKEN' : 'WiyfqvBuHrUnzT6zCvidq9lMVIQSB220Wtsx8EK5'
                },
              success:function(data){
                if(!data[0]){

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
                                document.getElementById('btnSubirGuia').disabled=false;
                                return;                         
                            }
                        }
                    );
                    document.getElementById('btnSubirGuia').disabled=false;
                    return;
                }
                document.getElementById('folioDTE').dataset.numeroguia=$("#nuevoFolioDTE").val();
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

                $("#folioDTE").val( $("#nuevoFolioDTE").val() );
                document.getElementById('btnEmitirGuia').style.display='none';
                document.getElementById('btnBajar').style.display='inline';
                cerrarModalSubirGuiaPdf();
              },
              error: function(jqXHR, text, error){
                  alert('Error!, No se pudo Añadir los datos');
              }
          });
        });

        $(document).ready(function() {
            var tablaDetalle="#tablaDetalle";
            // Datepicker      
            $('.date').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true,
                startDate: '+0d'
            }) 
            cargarListas();
        });

        function abrirCajaSuspender(){
            $("#mdSuspender").modal('show');
            $("#obsSuspension").val('');
            $("#obsSuspension").focus();
        }

        function cerrarCajaSuspender(){
            $("#obsSuspension").val('');
            $("#mdSuspender").modal('hide');
        }

        function SuspenderPedido(idPedido){
            if($("#obsSuspension").val().trim()=='' ){
                swal(
                    {
                        title: 'Es obligatorio ingresar el motivo!!' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonText: '',
                        cancelButtonText: '',
                    });
            }else{
                $.ajax({
                    url: urlApp + "suspenderPedido",
                    headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                    type: 'POST',
                    dataType: 'json',
                    data: { 
                            idPedido: idPedido,
                            motivo: $("#obsSuspension").val().trim()
                          },
                    success:function(dato){
                        swal(
                        {
                            title: 'el pedido ha sido suspendido!!' ,
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
                                location.href=dato.url;
                                                     
                            }
                        }
                    );
                    }
                })                
            }
        }

        function bajarOCpedido(){
            $.ajax({
                url: urlApp + "existeArchivo",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        carpeta: 'ocpedido',
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
                            location.href= urlApp + "bajarOCpedido/"+  document.getElementById('txtOrdenCompra').dataset.ocarchivo +"/";
                       };
                }
            })            
        }


    </script>
       
@endsection