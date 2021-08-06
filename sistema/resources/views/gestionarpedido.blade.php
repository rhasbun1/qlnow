@extends('plantilla')      

@section('contenedorprincipal')

<div style="padding: 5px">
    <div class="panel panel-default table-responsive">
        <div class="panel-heading">
            <b>Nuevo Pedido</b>
        </div>
        <div> 
            <div style="padding: 10px" class="panel panel-default panel-stat2" > 
            <input  id="idempresa" value="{{Session::get('empresaUsuario')}}" hidden>
            <input type="hidden" id="idCliente1" value="{{Session::get('idPerfil')}}">

                <input type="hidden" id="idCliente" data-idperfil="{{Session::get('idPerfil')}}">
                <input class="form-control input-sm"   min="0" maxlength="9" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)" type="hidden" id="cmgttn" value="{{ $param[0]->carga_max_granel_tte_normal }}">
                <input class="form-control input-sm"  min="0" maxlength="9" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)" type="hidden" id="cmgttm2" value="{{ $param[0]->carga_max_granel_tte_mixto_2 }}">
                <input class="form-control input-sm"   min="0" maxlength="9" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength)" type="hidden" id="cmgttm1" value="{{ $param[0]->carga_max_granel_tte_mixto_1 }}">

                <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                <div class="row">
                    <div class="col-sm-1 col-lg-1">
                        Nota de Venta
                    </div>
                    <div class="col-sm-1 col-lg-1">
                        <input type="hidden" id="txtNumeroNotaVenta" value="{{ $NotadeVenta[0]->idNotaVenta }}">
                        <button class="btn btn-success btn-sm" style="width: 100%" onclick="verNotaVenta();">{{ $NotadeVenta[0]->idNotaVenta }}</button>
                    </div>
                 
                    <div class="col-sm-1 col-lg-1">
                        Cliente
                    </div>
                    <div class="col-sm-3 col-lg-3">
                        <input class="form-control input-sm" id="txtNombreCliente" readonly  value="{{ $NotadeVenta[0]->emp_nombre}}">
                    </div>
                    <div class="col-sm-1 col-lg-1">
                        Fecha
                    </div>
                    <div class="col-sm-2 col-md-2 col-lg-2">
                        <input class="form-control input-sm" id="txtFechaCotizacion" readonly value="{{ $NotadeVenta[0]->fechahora_creacion}}">
                    </div>
                    <div class="col-sm-1 col-lg-1">
                        Cotización
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-2">
                        <input class="form-control input-sm" id="txtAno" readonly value="{{ $NotadeVenta[0]->cot_numero }} / {{ $NotadeVenta[0]->cot_ano}}">
                    </div>
                </div>
                <div class="row" style="padding-top: 10px">
                    <div class="col-sm-1 col-lg-1">
                        Obra
                    </div>
                    <div class="col-sm-3 col-lg-3">
                        <input class="form-control input-sm" id="txtUsuarioValida" readonly value="{{ $NotadeVenta[0]->Obra}}">
                    </div>                 
                    <div class="col-sm-1 col-lg-1">
                        Ejecutivo QL
                    </div>
                    <div class="col-sm-3 col-lg-3">
                        <input class="form-control input-sm" id="txtUsuarioCrea" readonly value="{{ $NotadeVenta[0]->usuario_encargado }}">
                    </div>
                    <div class="col-sm-1 col-lg-1">
                        Aprueba
                    </div>
                    <div class="col-sm-3 col-lg-3">
                        <input class="form-control input-sm" id="txtUsuarioValida" readonly value="{{ $NotadeVenta[0]->usuario_validacion}}">
                    </div> 
                </div>
                <div class="row" style="padding-top: 20px; padding-bottom: 20px">
                    <div class="col-lg-1 col-sm-2 col-md-1">
                        O.Compra
                    </div>

                    <div class="col-lg-3 col-sm-4 col-md-3">
                        <div class="input-group">                           
                            <input id="txtOrdenCompra" class="form-control input-sm" value="{{ $NotadeVenta[0]->ordenCompraCliente }}" data-ocarchivo="{{ $NotadeVenta[0]->nombreArchivoOC }}" maxlength="15" >
                            <span class="input-group-addon glyphicon glyphicon-cloud-download" title="Bajar Orden de Compra" onclick="bajarOC();">

                            </span>
                            <span id="btnCargarArchivo" class="input-group-addon glyphicon glyphicon-cloud-upload" onclick="subirArchivoOc();" title="Subir Orden de Compra" style="background-color: #FFFFFF"></span>
                        </div> 
                        <div id="divNombreArchivo" style="display: none;"></div>                           
                    </div>                     
                    <div class="col-md-1">
                        Formato 
                    </div>
                     <div class="col-md-2">
                        <select id="tipoCarga" class="form-control input-sm" onchange="selTipoCarga();">
                            <option selected value="1">Granel</option>
                            <option value="2">Otros</option>
                        </select>
                    </div> 
                 
                    <div id="opciones" style="display: none">
                        <div class="col-md-1">
                            Tipo&nbsp;Transporte
                        </div>
                        <div class="col-md-2">
                            <select id="tipoTransporte" class="form-control input-sm" onchange="selTipoTransporte();">
                                <option selected value="1">Normal</option>
                                <option value="2">Mixto</option>
                            </select>
                        </div>                                    
                    </div>
                   
                </div>                   
            </div>
            <div id="notificaciones" style="padding-right: 5px; padding-left: 5px">
            </div>
            <div style="padding-top: 5px; display: none" id="divPedidoProductosporUnidad">
                <table id="tablaDetallePedidoNormal" class="table table-condensed table-hover table-responsive">
                    <thead>
                        <th style="display: none">Código</th>
                        <th width="15%">Producto</th>
                        <th width="5%">Diseño</th>
                        <th style="width:10%;text-align: right;">Precio<br>Reajustado($ **)</th>
                        <th style="width:5%;text-align: right;">Cantidad</th>
                        <th width="5%">Unidad</th>
                        <th width="5%">Saldo</th>
                        <th width="5%">Cantidad<br>Solicitada</th>
                        <th width="15%">Planta de Origen</th>
                        <th width="20%">Forma de Entrega</th>
                    </thead>
                    <tbody>
                        @foreach($NotadeVentaDetalle as $item)
                            @if ($item->u_nombre !='tonelada')
                                <tr>
                                    <td style="display: none">{{ $item->prod_codigo }}</td>
                                    <td>{{ $item->prod_nombre }}</td>
                                    <td>{{ $item->formula }}</td>
                                    @if($item->cp_tipo_reajuste=='Con reajuste')
                                        <td align="right">{{ number_format( $item->precioActual, 0, ',', '.' ) }}</td>
                                    @else
                                        <td align="right">{{ number_format( $item->precio, 0, ',', '.' ) }}</td>
                                    @endif    

                                    <td align="right">{{ number_format($item->cantidad, 0, ',', '.') }}</td>
                                    <td>{{ $item->u_nombre }}</td>
                                    <td align="right">{{number_format($item->saldo, 0, ',', '.') }}</td>
                                    <td aling="right"><input class="form-control input-sm" onblur="verificarCantidad(this);" onkeypress="return isIntegerKey(event)" maxlength="6" ></td>
                                    <td>
                                            
                                                <select  id="selectPlanta" class="selectPlanta{{ $item->prod_codigo }} form-control input-sm">
                                                
                                                </select>                                    
                                    </td>
                                    <td>
                                            @if($item->idFormaEntrega==2)
                                                <select id="pruebacarga" class="form-control input-sm">
                                                    @foreach($FormasdeEntrega as $formaEntrega)
                                                        @if( $item->idFormaEntrega==$formaEntrega->idFormaEntrega )
                                                            <option  value="{{ $formaEntrega->idFormaEntrega }}" selected>{{ $formaEntrega->nombre }}</option>
                                                        @endif
                                                    @endforeach 
                                                </select>                                            
                                            @else
                                                <select class="form-control input-sm">
                                                    @foreach($FormasdeEntrega as $formaEntrega)
                                                        @if( $item->idFormaEntrega==$formaEntrega->idFormaEntrega )
                                                            <option value="{{ $formaEntrega->idFormaEntrega }}" selected>{{ $formaEntrega->nombre }}</option>
                                                        @else
                                                            <option value="{{ $formaEntrega->idFormaEntrega }}">{{ $formaEntrega->nombre }}</option>
                                                        @endif
                                                    @endforeach 
                                                </select>
                                            @endif
                                                                     
                                    </td>
                                </tr>
                                <script  type="text/javascript"> 
                                        setTimeout(() => {
                                            val = plantaspedidos("{{ $item->prod_nombre }}","{{$item->u_nombre}}","{{ $item->prod_codigo }}");

                                        }, 5000);
                                    </script>
                            @endif
                        @endforeach            
                    </tbody>
                </table>
            </div> 

            <div style="padding-top: 5px; display: none" id="divPedidoProductosaGranel">
                <div class="row" style="padding-left: 10px">
                    <div class="col-md-1">
                        Producto
                    </div>
                    <div class="col-md-3">
                        <select id="listaProductos" class="form-control input-sm">
                            <option value="">Seleccione un producto...</option>
                            @foreach($NotadeVentaDetalle as $item)
                                @if ($item->u_nombre =='tonelada')
                                    <option value="{{ $item->prod_codigo }}"> {{ $item->prod_nombre }} </option>
                                @endif
                            @endforeach
                        </select> 
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success btn-sm" onclick="agregarProducto();">Agregar</button>
                    </div>
                    <div class="col-md-1">
                    </div>
                    <div id="notaMaxProducto" class="col-md-5">
                        <b><u>Consideraciones para pedidos a granel</u></b>
                    </div>
                </div>
                <div style="padding-top: 5px; padding-left: 10px; padding-right: 10px; padding-bottom: 5px">
                    <table id="tablaDetallePedidoGranel" class="table table-hover table-condensed  table-responsive" style="width: 100%">
                        <thead>
                            <th style="display: none">Código</th>
                            <th style="width:150px">Producto</th>
                            <th style="width:80">Diseño</th>
                            <th style="width:10%;text-align: right;">Precio<br>Reajustado($ **)</th>
                            <th style="width:5%;text-align: right;">Cantidad</th>
                            <th style="width:80px">Unidad</th>
                            <th style="width:40px;text-align: right;">Saldo</th>
                            <th style="width:40px;text-align: right;">Cantidad<br>Solicitada</th>
                            <th style="width:80px">Planta de Origen</th>
                            <th style="width:80px">Forma de Entrega</th>                            
                            <th style="width:80px"></th>
                        </thead>
                        <tbody>
                            @foreach($NotadeVentaDetalle as $item)
                                @if ($item->u_nombre =='tonelada')
                                
                                    <tr>
                                        <td style="display: none">{{ $item->prod_codigo }}</td>
                                        <td style="width:150px" >{{ $item->prod_nombre }}</td>
                                        <td style="width:80px">{{ $item->formula }}</td>
                                        @if($item->cp_tipo_reajuste=='Con reajuste')
                                            <td align="right" style="width:80px">{{ number_format( $item->precioActual, 0, ',', '.' ) }}</td>
                                        @else
                                            <td align="right" style="width:80px">{{ number_format( $item->precio, 0, ',', '.' ) }}</td>
                                        @endif    

                                        <td align="right" style="width:40px">{{ number_format( $item->cantidad, 0, ',', '.') }}</td>
                                        <td style="width:80px">{{ $item->u_nombre }}</td>
                                        <td align="right" style="width:40px"><b>{{ number_format($item->saldo, 0, ',', '.') }}</b></td>
                                        <td aling="right" style="width:40px">
                                            <input class="form-control input-sm" onblur="verificarCantidad(this);" maxlength="6" onkeypress="return isIntegerKey(event)" >
                                        </td>
                                        <td style="width:80px">
                                         
                                            <select disabled  id="selectPlanta"  class="selectPlanta{{ $item->prod_codigo }} form-control input-sm" > 

                                    
                                                
                                            </select>    
                                                                           
                                        </td>
                                        <td style="width:80px">
                                            @if($item->idFormaEntrega==2)
                                                <select id="pruebacarga" class="form-control input-sm">
                                                    @foreach($FormasdeEntrega as $formaEntrega)
                                                        @if( $item->idFormaEntrega==$formaEntrega->idFormaEntrega )
                                                            <option value="{{ $formaEntrega->idFormaEntrega }}" selected>{{ $formaEntrega->nombre }}</option>
                                                        @endif
                                                    @endforeach 
                                                </select>                                            
                                            @else
                                                <select id="pruebacarga" class="form-control input-sm">
                                                    @foreach($FormasdeEntrega as $formaEntrega)
                                                        @if( $item->idFormaEntrega==$formaEntrega->idFormaEntrega )
                                                            <option value="{{ $formaEntrega->idFormaEntrega }}" selected>{{ $formaEntrega->nombre }}</option>
                                                        @else
                                                            <option value="{{ $formaEntrega->idFormaEntrega }}">{{ $formaEntrega->nombre }}</option>
                                                        @endif
                                                    @endforeach 
                                                </select>
                                            @endif
                                        </td>                                        
                                        <td style="width:80px">
                                            <button class="btn btn-warning btn-sm" onclick="ocultarFila(this.parentNode.parentNode.rowIndex);">
                                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                            </button>
                                        </td>
                                       
                                    </tr>
                                    <script  type="text/javascript"> 
                                        setTimeout(() => {
                                            val = plantaspedidos("{{ $item->prod_nombre }}","{{$item->u_nombre}}","{{ $item->prod_codigo }}","{{ $item->idPlanta }}");

                                        }, 5000);
                                        // setTimeout(() => {
                                        //     $("#selectPlanta option[value='{{ $item->idPlanta }}']").attr("selected", true);
                                        //     alert("{{ $item->idPlanta }}");
                                        // }, 6000);
                                    </script>
                                    
                                @endif
                            @endforeach            
                        </tbody>
                    </table>
                </div>
            </div> 

            <div id="divPiePedido" style="display: none" class="panel panel-default panel-stat2">
                <div style="padding: 10px">
                    <div class="row">
                        <div class="col-sm-2 col-md-2">
                            Contacto
                            <input id="txtNombreContacto" class="form-control input-sm" value="{{ $NotadeVenta[0]->nombreContacto}}">
                        </div> 
                        <div class="col-sm-2 col-md-2">
                            Correo
                            <input id="txtCorreoContacto" class="form-control input-sm" value="{{ $NotadeVenta[0]->correoContacto}}">
                        </div>
                        <div class="col-sm-2 col-md-2">
                            Teléfono/Móvil
                            <input id="txtTelefono" class="form-control input-sm" value="{{ $NotadeVenta[0]->telefonoContacto}}">
                        </div>
                        <div class="col-sm-2 col-md-2">
                            Fecha de Entrega (*)
                            <div class="input-group date" id="divFechaEntrega">
                                <input type="text" class="form-control input-sm" id="txtFechaEntrega">
                                <div class="input-group-addon">
                                    <span class="glyphicon glyphicon-th"></span>
                                </div>
                            </div>                        
                        </div>
                        <div class="col-sm-2 col-md-2 col-lg-1">
                            Horario
                            <select id="horario" class="form-control input-sm">
                                <option>AM</option>
                                <option>PM</option>
                            </select> 
                        </div>            
                     
                    </div>
                    <div class="row" style="padding-top: 10px">
                        <div class="col-sm-4 col-md-8">
                            Observaciones (máx.100 carac.)
                            <input id="txtObservaciones" class="form-control input-sm" maxlength="100">
                        </div>
                        <div class="col-sm-4 col-md-3" style="padding-top: 20px">
                            @foreach($NotadeVenta as $item)
                                @if($item->condiciondePago == "contado" || $item->condiciondePago == "Contado")
                                    <label class="label-checkbox"><input disabled checked type="checkbox" id="noExcederCantidad"><span class="custom-checkbox"></span>No exceder la cantidad solicitada</label>
                                @else
                                    <label class="label-checkbox"><input type="checkbox" id="noExcederCantidad"><span class="custom-checkbox"></span>No exceder la cantidad solicitada</label>
                                @endif
                            @endforeach                 
                        </div>                     
                    </div>
                    <div class="row">
                        <div class="col-sm-4 col-md-3" style="padding-top: 20px"> 
                            <h5><b> (*) Dato Obligatorio</b></h5>
                            <h5><b> (**) Precio indicado es el reajustado a la fecha de creación del pedido. Pueden existir diferencias con el precio final de facturación si el despacho se realiza en un mes distinto.</b></h5>
                        </div>                          
                    </div>                    

                </div>
                <div id="divFleteFalso" style="display: none">
                    <div class="row" style="padding: 15px">
                        <div class="col-sm-3 col-md-2">
                            Valor Flete Falso
                        </div>
                        <div class="col-sm-2 col-md-2">
                            <input id="valorFleteFalso" class="form-control input-sm" readonly style="text-align: right;">
                        </div>
                        <div class="col-sm-1 col-md-1">
                            Cantidad
                        </div>
                        <div class="col-sm-1 col-md-1">
                            <input id="cantidadFleteFalso" class="form-control input-sm" readonly style="text-align: right;">
                        </div>
                        <div class="col-sm-1 col-md-1">
                            Total
                        </div>
                        <div class="col-sm-1 col-md-1">
                            <input id="totalFleteFalso" class="form-control input-sm" readonly style="text-align: right;">
                        </div>                     
                    </div>                   
                </div>
                <div style="padding:18px">
                @if( Session::get('idPerfil') == 14 || Session::get('idPerfil')== 15)
                    <button id="btnCrearPedido" class="btn btn-success btn-sm" onclick="crearPedido('Q');">Crear Pedido</button>
                @else
                   <button id="btnCrearPedido" class="btn btn-success btn-sm" onclick="crearPedido('QL');">Crear Pedido</button>

                @endif
                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
                </div>
            </div>       
        </div>
    </div>
</div>

<div id="mdNotaVenta" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5><b>Detalle Nota de Venta</b></h5>
            </div>
            <div id="bodyNotaVenta" class="modal-body">
                <table id="tablaDetalleNotaVenta" class="table table-hover table-condensed table-responsive">
                    <thead>
                        <th style="width:100px">Producto</th>
                        <th style="width:50px">Cantidad</th>
                        <th style="width:50px">Unidad</th>
                        <th style="text-align: right;width:80px">Precio Base ($)</th>
                        <th style="width:150px">Glosa de Reajuste</th>
                        <th style="width:50px">Valor Pitch</th>
                        <th style="width:50px">% Pitch</th>
                        <th style="width:50px">Valor IPC</th>
                        <th style="width:50px">% IPC</th>                                           
                    </thead>
                    <tbody>
                    @foreach($NotadeVentaDetalle as $item)
                        <tr>
                            <td style="width:100px">{{ $item->prod_nombre }}</td>
                            <td style="text-align: right;width:50px">{{ $item->cantidad }}</td>
                            <td style="width:50px">{{ $item->u_nombre }}</td>
                            <td style="text-align: right;width:80px">{{ number_format( $item->precio, 0, ',', '.' ) }}</td>
                            <td style="width:150px">{{ $item->cp_glosa_reajuste }}</td>
                            <td style="width:50px">{{ $item->cp_valor_pitch }}</td>
                            <td style="width:50px">{{ $item->cp_pitch }}</td>
                            <td style="width:50px">{{ $item->cp_valor_ipc }}</td>
                            <td style="width:50px">{{ $item->cp_ipc }}</td>                                
                        </tr>
                    @endforeach                              
                    </tbody>
                </table>
            </div> 

            <div class="col-md-offset-11" style="padding-top: 20px; padding-bottom: 20px">
                <button class="btn btn-warning btn-sm" onclick="cerrarDetalleNotaVenta();">Cerrar</button>
            </div>
        </div>
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
                        <br>
                        <b>Nota: Este archivo se subirá cuando presione el boton "Crear Pedido".</b>
                    </div>
                    <div style="padding-top: 20px; padding-bottom: 20px; text-align: right;">
                       <button type="button" class="btn btn-danger data-dismiss=modal btn-sm" onclick="cerrarModalSubirArchivo()" style="width: 80px">Cerrar</button>
                    </div>                   
                </form>     
            </div>
        </div>
    </div>
</div>

<div id="modNotasGranel" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="filaTabla" name="filaTabla">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h5><b>Consideraciones para pedidos a granel</b></h5>              
            </div>
            <div id="bodyNotasGranel" class="modal-body">
                <div style="padding: 20px">
                  <b>{!! nl2br(e($parametros[0]->consideracionesPedidosGranel)) !!}</b>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>  
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>
    
    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  

    <script src="{{ asset('/') }}js/app/funciones.js?{{$parametros[0]->version}}"></script>
    
    <script src="{{ asset('/') }}js/app/gestionarpedido.js?{{$parametros[0]->version}}"></script>
    <!-- Datatable -->
    <script src="{{ asset('/') }}js/jquery.dataTables.min.js"></script>
    <script>
        function subirArchivoOc(){
            $("#modSubirArchivo").modal('show'); 
        }

        function cerrarModalSubirArchivo(){
            if($("#upload-demo").val()!=''){
                document.getElementById('btnCargarArchivo').style.backgroundColor ="#f89406";
            }else{
                document.getElementById('btnCargarArchivo').style.backgroundColor ="#FFFFFF";
            }
            document.getElementById('divNombreArchivo').innerHTML= $("#upload-demo").val();
            document.getElementById('divNombreArchivo').style.display='block';      
            $("#modSubirArchivo").modal('hide'); 
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
                            location.href= urlApp + "bajarOCnventa/"+  document.getElementById('txtOrdenCompra').dataset.ocarchivo +"/";
                       };
                }
            })            
        }
        function plantaspedidos(nombrePlanta,unidad,codigo,idplanta){
        
        $.ajax({
            async:false, 
            url: urlApp + 'plantaspedidos',
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: {
                nomPlanta:nombrePlanta,
                unidad:unidad
            },
            success:function(dato){
                console.log("salida de datos",dato);
                $(dato).each(function(i, v){ // indice, valor

                    $(".selectPlanta"+codigo).append('<option value="' + v.idPlanta + '">' + v.nombre + '</option>');
                })

            }
        }); 

        

    }
    

    </script>
@endsection