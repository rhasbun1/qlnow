@extends('plantilla')      

@section('contenedorprincipal')


<div style="padding: 5px">
    <div class="panel panel-default table-responsive">
     <!--     <div class="panel-heading">
            <div class="row">
              <div class="col-sm-3 col-md-3 col-lg-2">
                    <h5><b>Pedido Nº {{ $pedido[0]->idPedido }}</b></h5>
                </div>
            </div>             
        </div>-->
        <div class="padding-md clearfix">
        	<div>
                <input type="hidden" id="tipoTransporte" value="{{$pedido[0]->tipoTransporte}}">
                <input type="hidden" id="tipoCarga" value="{{$pedido[0]->tipoCarga}}">                
                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" id="idPedido" name="_token" value="{{ $pedido[0]->idPedido }}">
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
                        Creación
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-4">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->fechahora_creacion }}">
                    </div> 
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        N.Venta
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-3">
                        @if ( Session::get('grupoUsuario')!='CL' or Session::get('idPerfil')=='6' ) 
                            <a class="btn btn-success btn-sm" style="width: 100%" href="{{ asset('/') }}vernotaventa/{{ $pedido[0]->idNotaVenta }}-{{ $pedido[0]->idPedido }}/2/">{{ $pedido[0]->idNotaVenta }}</a><!--MATIAS-->                            
                        @else
                            <input class="form-control input-sm" value="{{ $pedido[0]->idNotaVenta }}" readonly style="text-align: center;">
                        @endif    
                    </div>                          			
        		</div>
        		<div class="row" style="padding-top: 5px">
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        Obra
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-5">
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
        			<div class="col-lg-1 col-md-1 col-sm-1">
        				Estado
        			</div>
         			<div class="col-lg-2 col-md-2 col-sm-3">
        				<input id="idEstadoPedido" class="form-control input-sm" readonly value="{{ $pedido[0]->estado }}" data-idestadopedido="{{$pedido[0]->idEstadoPedido}}">
        			</div>
                    <div class="col-lg-1 col-md-2 col-sm-2">
                        Observaciones
                    </div>
                    <div class="col-lg-8 col-md-7 col-sm-6">
                        <input class="form-control input-sm" readonly value="{{ $pedido[0]->observaciones }}">
                    </div>                          			     			
        		</div>
                                  		  		
        	</div>
            <div style="text-align: right;">
                    @if ($pedido[0]->noExcederCantidadSolicitada==1)
                            <h4><span class="label label-danger">NO EXCEDER LA CANTIDAD SOLICITADA</span></h4>
                    @endif
            </div>
        </div>

        <div style="padding-top: 0px; padding-left: 20px; padding-bottom: 5px; padding-right: 10px">
            <table id="tablaDetalle" class="table table-hover table-condensed" style="width: 100%">
                <thead>
                    <th style="display: none">Código</th>
                    <th style="width:100px">Producto</th>
                    <th style="width:30px; text-align: right;">Cantidad<br>Solicitada</th>
                    <th style="width:40px">Unidad</th>
                    <th style="width:100px">Planta de Origen</th>
                    <th style="width:70px">Entrega</th>
                    <th style="width:70px">Transporte</th>
                    <th style="width:70px">Camion</th>
                    <th style="width:70px">Rampla</th>
                    <th style="width:70px">Conductor</th>
                    <th style="width:70px">Fecha prog. Carga</th>
                    <th style="width:70px">Hora prog. Carga</th>
                    <th style="width:70px">Select./<br>Guía</th>
                    <th style="display: none;"></th>
                </thead>
            
                <tbody>
                    <?php 
                        $productosSinGuia = 0;
                        $ln = 1;
                        $despachado=0;
                        $sinDespachar=0;
                    ?>
                    @foreach($listaDetallePedido as $item)
                   

                    <?php
                        if($item->salida==1){$despachado++;}
                        if($item->salida==0){$sinDespachar++;}
                    ?>                    
                    <tr>
                        <td style="display: none">
                            {{ $item->prod_codigo }}
                        </td>
                        <td style="width:100px" data-guia="{{ $item->numeroGuia }}" data-foliodte="{{ $item->folioDTE }}" dato-existelistaprecios="{{ $item->existeEnListaPrecios }}">
                            {{ $item->prod_nombre }}
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
                            @if ( $item->certificado==1 )  
                                <a target="_blank" href="{{ asset('/') }}bajarCertificado/{{ $item->certificado }}">
                                    <img src="{{ asset('/') }}img/iconos/certificado.png" border="0">
                                </a>                                
                            @endif
                             @if( $item->numeroGuia>0 )
                                <span onclick='abrirGuia(1, {{ $item->numeroGuia }}, this.parentNode.parentNode);' style="cursor:pointer; cursor: hand">
                                    <img src="{{ asset('/') }}img/iconos/guiaDespacho2.png" border="0">
                                </span>                                
                            @endif 
                            @if ( $item->salida==1 )
                                <span><img src="{{ asset('/') }}img/iconos/enTransporte.png" border="0" onclick="verUbicacionGmaps('{{ $item->patente }}');" style="cursor:pointer; cursor: hand"></span>                                      
                            @endif                              
                        </td>
                        <td style="width:30px; text-align: right;"> {{ $item->cantidad }} </td>
                        <td style="width:40px"> {{ $item->u_nombre }} </td>
                       
                        <td style="width:100px" disabled data-idplanta="{{ $item->idPlanta }}">
                            @if( Session::get('idPerfil')=='8' )
                                {{ $item->nombrePlanta }}
                            @else
                            @if( Session::get('idPerfil')=='8' || Session::get('idPerfil')=='7' )
                            <select  id="idPlanta" class="form-control input-sm">  
                            @else
                            <select disabled id="idPlanta" class="form-control input-sm">  
                            @endif
                                @foreach($plantas as $planta)
                                    @if( $item->nombrePlanta==$planta->nombre )
                                        <option value="{{ $planta->idPlanta }}" selected>{{ $planta->nombre }}</option>
                                    @else
                                        <option value="{{ $planta->idPlanta }}">{{ $planta->nombre }}</option>
                                    @endif    
                                @endforeach
                            </select>
                            @endif
                        </td>

                        <td style="width:70px"> {{ $item->nombreFormaEntrega }} </td>
                        @if ( $item->numeroGuia==0 and $pedido[0]->bloqueado==0)
                            <?php $productosSinGuia+=1; ?>
                            <td style="width:70px">
                                @if ( $item->nombreFormaEntrega !='Retira' )
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <select id="idEmpresaTransporte" class="form-control input-sm" onchange="cargarListas(this.value, this.parentNode.parentNode.rowIndex);" @if (Session::get('idPerfil')=='8') disabled @endif >
                                          <option value="0"></option>  
                                          @foreach($emptransporte as $empresa)
                                            @if( $empresa->habilitada==1 )
                                                @if( $empresa->idEmpresaTransporte == $item->idEmpresaTransporte) then
                                                    <option value="{{ $empresa->idEmpresaTransporte }}" selected>{{ $empresa->nombre }}</option>
                                                @else
                                                    <option value="{{ $empresa->idEmpresaTransporte }}">{{ $empresa->nombre }}</option>
                                                @endif
                                            @endif 
                                          @endforeach   
                                        </select>
                                    @endif
                                @else
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input class="form-control input-sm" maxlength="100" value="{{ $item->nombreEmpresaTransporte }}">
                                    @endif
                                @endif                              
                            </td>
                            <td style="width:70px">
                                @if ( $item->nombreFormaEntrega !='Retira' )
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <select id="idCamion" class="form-control input-sm" @if ( Session::get('idPerfil')=='8') disabled @endif >
                                            <option value="{{ $item->idCamion }}" selected>{{ $item->patente }}</option>
                                        </select>
                                    @endif
                                @else
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input class="form-control input-sm" maxlength="20" value="{{ $item->patente }}">       
                                    @endif
                                @endif                                 
                            </td>
                            <td style="width:70px">
                                @if ( $item->nombreFormaEntrega !='Retira' )
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <select id="idRampla" class="form-control input-sm" @if (Session::get('idPerfil')=='8') disabled @endif >
                                            <option value="0" selected>-</option>
                                            @foreach($ramplas as $rampla)
                                                @if( $rampla->numero == $item->numeroRampla) then
                                                    <option value="{{ $rampla->numero }}" selected>{{ $rampla->numero }}</option>
                                                @else
                                                    <option value="{{ $rampla->numero }}">{{ $rampla->numero }}</option>
                                                @endif
                                            @endforeach                                             
                                        </select>
                                    @endif
                                @else
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input class="form-control input-sm" maxlength="3" value="{{ $item->numeroRampla }}" readonly="true">
                                    @endif 
                                @endif                                   
                            </td>

                            <td style="width:70px">
                                @if ( $item->nombreFormaEntrega !='Retira' )
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <select id="idConductor" class="form-control input-sm" @if (Session::get('idPerfil')=='8') disabled @endif>
                                            <option value="{{ $item->idConductor }}" selected>{{ $item->nombreConductor }}</option>
                                        </select>
                                    @endif
                                @else
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input class="form-control input-sm" maxlength="100"  value="{{ $item->nombreConductor }}">
                                    @endif
                                @endif                               
                            </td>

                            @if( Session::get('idPerfil')=='5' or Session::get('idPerfil')=='6' or Session::get('idPerfil')=='7' )

                                <td style="width:70px">
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input type="text" class="form-control input-sm date" id="fechaEntrega" value="{{ $item->fechaCarga }}">
                                    @endif    
                                </td>
                                <td style="width:70px">
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input id="horaEntrega" type="text" class="form-control input-sm bootstrap-timepicker" value="{{ $item->horaCarga }}">
                                    @endif
                                </td> 
                            @elseif ( Session::get('idPerfil')=='8' or Session::get('idPerfil')=='10'  )
                                <td style="width:70px">
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input type="text" class="form-control input-sm" id="fechaEntrega" value="{{ $item->fechaCarga }}" readonly>
                                    @endif
                                </td>
                                <td style="width:70px">
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input id="horaEntrega" type="text" class="form-control input-sm" value="{{ $item->horaCarga }}" readonly>
                                    @endif
                                </td>                            
                            @else
                                <td style="display: none;">
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input type="text" class="form-control input-sm date" id="fechaEntrega" value="{{ $item->fechaCarga }}">
                                    @endif
                                </td>
                                <td style="display: none;">
                                    @if(($pedido[0]->tipoTransporte==2 and $ln==1) or $pedido[0]->tipoTransporte==1)
                                        <input id="horaEntrega" type="text" class="form-control input-sm bootstrap-timepicker" value="{{ $item->horaCarga }}">
                                    @endif
                                </td>                        
                            @endif    

                            <td>
                                @if( $item->existeEnListaPrecios )
                                    @if( (Session::get('idPerfil')=='5' or Session::get('idPerfil')=='6' or Session::get('idPerfil')=='7') )
                                        <label class="label-checkbox"  style="display: inline;"><input type="checkbox"><span class="custom-checkbox"></span></label>
                                    @else
                                        <label class="label-checkbox" style="display: none;"><input type="checkbox"><span class="custom-checkbox"></span></label>    
                                    @endif
                                @else
                                    <p style="color:#FF0000";><b>No existe en lista de precios</b></p>
                                @endif
                            </td>

                        @else    

                            <td style="width:70px">{{ $item->nombreEmpresaTransporte }}</td>
                            <td style="width:70px">{{ $item->patente }}</td>
                            <td style="width:70px">{{ $item->numeroRampla }}</td>
                            <td style="width:70px">{{ $item->nombreConductor }}</td>
                            @if( Session::get('idPerfil')=='5' or Session::get('idPerfil')=='6'  )
                                <td style="width:70px">{{ $item->fechaCarga }}</td>
                                <td style="width:70px">{{ $item->horaCarga }}</td>
                            @elseif ( Session::get('idPerfil')=='7' or Session::get('idPerfil')=='8' or Session::get('idPerfil')=='10'  )
                                <td style="width:70px">{{ $item->fechaCarga }}</td>
                                <td style="width:70px">{{ $item->horaCarga }}</td>                           
                            @else
                                <td style="display: none;">{{ $item->fechaCarga }}</td>
                                <td style="display: none;">{{ $item->horaCarga }}</td>                     
                            @endif    
                            <td></td>
                        @endif    
                        <td style="display: none;">{{$item->tipoTransporte}}</td>

                    </tr>
                    <?php $ln+=1; ?>
                    @endforeach
                </tbody>
            </table>
        </div> 

        <div style="padding-top:10px; padding-bottom: 20px;padding-left: 20px">
            @if( Session::get('idPerfil')!='8' and $productosSinGuia>0 )
            <button id="btnGuardarProgramacion" class="btn btn-sm btn-primary" style="width:80px" onclick="guardarDatosProgramacion( {{ $pedido[0]->idPedido }} , 1);">Guardar</button>
            @endif
            @if( (Session::get('idPerfil')=='5' or Session::get('idPerfil')=='6' or Session::get('idPerfil')=='7') and $productosSinGuia>0 )
            <button id="btnAsignarGuia" class="btn btn-sm btn-success" onclick="asignarFolio();">Asignar Guía a elementos seleccionados</button>
            @endif
            @if( ($pedido[0]->tipoCarga==1 or $pedido[0]->tipoCarga==2 ) and $pedido[0]->idFormaEntrega==2 and (Session::get('idPerfil')=='5' or Session::get('idPerfil')=='7') and $sinDespachar>0 )
                <button id="btnHistorico" class="btn btn-sm btn-danger" onclick="pasarHistorico();">Pasar a Histórico</button>
            @else
                @if ( ( ( intval($pedido[0]->idEstadoPedido) >= 2 and intval($pedido[0]->idEstadoPedido <=5) ) or intval($pedido[0]->idEstadoPedido==0) ) and
                    (Session::get('idPerfil')=='5' or Session::get('idPerfil')=='6' or Session::get('idPerfil')=='7' ) )

                    @if($despachado>0 and $sinDespachar>0)
                        <button class="btn btn-sm btn-danger" onclick="pasarHistorico();">Pasar a Histórico</button>
                    @endif

                @endif
            @endif
            @if( $pedido[0]->idEstadoPedido==7 and (Session::get('idPerfil')=='5' or Session::get('idPerfil')=='6' or Session::get('idPerfil')=='7'))
                <button id="btnHistorico" class="btn btn-sm btn-danger" onclick="pasarHistorico();">Cerrar Pedido</button>
            @endif 

            <a href="{{ asset('/') }}programacion" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>         
        </div> 


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
                        <table id="tablaLog" class="table table-hover table-condensed table-responsive" style="width: 850px">
                            <thead>
                                <th style="width:170px">Fecha</th>
                                <th style="width:200px">Usuario</th>
                                <th style="width:250px">Acción</th>
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
                                    <td style="width:200px"> {{ $item->nombreUsuario }} </td>
                                    <td style="width:250px"> {{ $item->accion }} </td>
                                    <td style="width:350px"> {{ $item->motivo }} </td>
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
                                    <td style="width:200px"> {{$item->fechaHora  }} </td>
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

    </div>
</div>

<div id="mdDespachoParcial" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header" style="height: 45px">
                <h5><b>Pasar pedido a histórico</b></h5>
            </div>
            <div id="bodyGuia" class="modal-body">
                Indique el motivo (máx.200 caract.)
                <div class="row">
                    <div class="col-md-12">
                        <input class="form-control input-sm" id="obsDespachoParcial" maxlength="200">
                    </div> 

                </div>
            </div>
            <div style="padding-top: 20px; padding-bottom: 20px; padding-right: 20px; text-align: right;">
               <button type="button" class="btn btn-success btn-sm" onclick="cerrarPedido()" style="width: 80px">Aceptar</button>                
               <button id="btnCerrarCajaSuspender" type="button" class="btn btn-danger btn-sm" onclick="cerrarDespachoParcial()" style="width: 80px">Cancelar</button>
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
    <script src="{{ asset('/') }}js/app/programacionPedido.js?{{$parametros[0]->version}}"></script>
    <script src="{{ asset('/') }}js/app/guiaDespacho.js?{{$parametros[0]->version}}"></script>
    
    <script>

        function pasarHistorico(){
            document.getElementById('obsDespachoParcial').value='';
            $("#mdDespachoParcial").modal('show');

        }


        function cerrarPedido(){

            if(document.getElementById('obsDespachoParcial').value.trim()==''){
                swal(
                    {
                        title: '¡Debe ingresar el motivo!' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'OK',
                        cancelButtonText: '',
                        closeOnConfirm: true,
                        closeOnCancel: false
                    })
                return;              
            }
            
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
                                    motivo: document.getElementById('obsDespachoParcial').value
                                  },
                            success:function(data){
                                if(document.getElementById('idPerfilSession').dataset.grupo=='P'){
                                    location.href=urlApp+"programacion";
                                }else{
                                    location.href=urlApp+"listarPedidos";
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

        function despachoParcialPedido(idPedido){

        }

        function cerrarDespachoParcial(){
            $("#mdDespachoParcial").modal('hide');
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

            // a continuación se envía el formulario con el nuevo número de guía y el archivo pdf correspondiente a la guía

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

                if(data.folioExiste=='1'){
                    swal(
                        {
                            title: '¡El Número de guía ingresado ya existe!' ,
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
                }else{

                    // Se recorre el DataTable para modificar la funcion abrirGuia con el nuevo número ingresado por el usuario

                    var numeroGuiaOrigen="abrirGuia(1, " + document.getElementById('folioDTE').dataset.numeroguia + ", this.parentNode.parentNode)";
                    var nuevoNumeroGuia ="abrirGuia(1, " + $('#nuevoFolioDTE').val() + ", this.parentNode.parentNode)";
                    var table = $('#tablaDetalle').DataTable();
                    var cadena = "";
                    var filas=table.rows().count();
                    for (var i = 0; i < filas; i++){
                        cadena=table.cell(i,1).data();
                        table.cell(i,1).data( cadena.replace(numeroGuiaOrigen, nuevoNumeroGuia) );
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

                    if(data.despachoCompleto==1){
                        if(document.getElementById('btnHistorico')){
                            document.getElementById('btnHistorico').style.display='none';
                        }
                        
                        if(document.getElementById('btnAsignarGuia')){
                           document.getElementById('btnAsignarGuia').style.display='none'; 
                        }
                        
                    }  
                }




              },
              error: function(jqXHR, text, error){
                  alert('Error!, No se pudo Añadir los datos');
              }
          });
        });

        $(document).ready(function() {
            var tablaDetalle="#tablaDetalle";

            $('.date').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true,
                startDate: '+0d'
            })

            $('.bootstrap-timepicker').timepicker({
                showMeridian: false,
                defaultTime: false
            });

            var tabla=document.getElementById('tablaDetalle');        

            $.ajax({
                async: true,
                url: urlApp + "listaCamiones",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: {},
                success:function(dato){
                    for(var x=0;x<dato.length;x++){
                        var camion = new Array(dato[x].idEmpresaTransporte, dato[x].idCamion, dato[x].patente);
                        arrCamiones.push(camion);
                    }

                    for (var i = 1; i < tabla.rows.length; i++){
                        if(tabla.rows[i].cells[1].dataset.guia=='0'){

                            if(tabla.rows[i].cells[5].innerHTML.trim()!='Retira'  && !tabla.rows[i].cells[11].getElementsByTagName('button')[0] ){
                                if(idEmpresa=tabla.rows[i].cells[6].getElementsByTagName('select')[0]){
                                    idEmpresa=tabla.rows[i].cells[6].getElementsByTagName('select')[0].value;
                                    selCamion=tabla.rows[i].cells[7].getElementsByTagName('select')[0];
                                    idCamion=0;
                                    if(selCamion.selectedIndex>=0){
                                        idCamion=selCamion.value;
                                    }                        
                                    selCamion.length=0; 
                                    var opt = document.createElement('option');
                                    opt.value = "0";
                                    opt.innerHTML = "";
                                    selCamion.appendChild(opt);

                                    for(var x=0;x<arrCamiones.length;x++){
                                        if(arrCamiones[x][0]==idEmpresa){
                                            var opt = document.createElement('option');
                                            opt.value = arrCamiones[x][1];
                                            opt.innerHTML = arrCamiones[x][2];
                                            if(idCamion==arrCamiones[x][1]){
                                                opt.selected=true;
                                            }else{
                                                opt.selected=false;
                                            }                                
                                            selCamion.appendChild(opt);
                                        }
                                    }                                     
                                }
               
                            }
                        }    
                    }

                }
            })

            $.ajax({
                async: true,
                url: urlApp + "listaConductores",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: {},
                success:function(dato){
                    for(var x=0;x<dato.length;x++){
                        var conductor = new Array(dato[x].idEmpresaTransporte, dato[x].idConductor, dato[x].nombre, dato[x].apellidoPaterno, dato[x].apellidoMaterno);
                        arrConductores.push(conductor);
                    }

                    for (var i = 1; i < tabla.rows.length; i++){
                        if(tabla.rows[i].cells[1].dataset.guia=='0'){
                            
                            if(tabla.rows[i].cells[5].innerHTML.trim()!='Retira' && !tabla.rows[i].cells[11].getElementsByTagName('button')[0] ){
                                if(tabla.rows[i].cells[6].getElementsByTagName('select')[0]){
                                    idEmpresa=tabla.rows[i].cells[6].getElementsByTagName('select')[0].value;
                                    selConductor=tabla.rows[i].cells[9].getElementsByTagName('select')[0];
                                    idConductor=0;
                                    if(selConductor.selectedIndex>=0){
                                        idConductor=selConductor.value;
                                    }
                                    selConductor.length=0; 
                                    var opt = document.createElement('option');
                                    opt.value = "0";
                                    opt.innerHTML = "";
                                    selConductor.appendChild(opt);

                                    for(var x=0;x<arrConductores.length;x++){
                                        if(arrConductores[x][0]==idEmpresa ){
                                            var opt = document.createElement('option');
                                            opt.value = arrConductores[x][1];
                                            opt.innerHTML = arrConductores[x][2]+' '+arrConductores[x][3]+' ' +arrConductores[x][4];
                                            if(idConductor==arrConductores[x][1]){
                                                opt.selected=true;
                                            }else{
                                                opt.selected=false;
                                            }
                                            selConductor.appendChild(opt);
                                        }
                                    }                                    
                                }

                            }               
                        }
                    }                    
                }
            })



            var numFilas=0;
            var numGuias=0;
            for (var i = 1; i < tabla.rows.length; i++){
                if(tipoTransporte.value==2){
                    if(tabla.rows[i].cells[12].getElementsByTagName('input')[0]){
                       tabla.rows[i].cells[12].getElementsByTagName('input')[0].checked=true;
                    }
                }
                numFilas++;

                if(tabla.rows[i].cells[1].dataset.foliodte>0){
                    numGuias++;
                }
            } 

            if(numFilas==numGuias){
                document.getElementById("btnHistorico").style.display="none";
            }

            if(tipoTransporte.value==2){
                if( document.getElementById('btnAsignarGuia') ){
                    btnAsignarGuia.innerHTML="Asignar Guía";
                    ocultarColumna(12);
                }
            }

        });

        //la función recibe como parámetros el numero de la columna a ocultar
        function ocultarColumna(num)
        {
          //aquí utilizamos el id de la tabla, en este caso es 'tabla'
          fila=document.getElementById('tablaDetalle').getElementsByTagName('tr');

         //mostramos u ocultamos la cabecera de la columna
         if (fila[0].getElementsByTagName('th')[num].style.display=='none')
            {
            fila[0].getElementsByTagName('th')[num].style.display=''
            }
          else
            {
            fila[0].getElementsByTagName('th')[num].style.display='none'
            }
           //mostramos u ocultamos las celdas de la columna seleccionada
          for(i=1;i<fila.length;i++)
            {
                if (fila[i].getElementsByTagName('td')[num].style.display=='none')
                    {
                        fila[i].getElementsByTagName('td')[num].style.display=''; 
                     }     
                else
                    {
                     fila[i].getElementsByTagName('td')[num].style.display='none'
                    }      
            }        
           
        }


    </script>
       
@endsection