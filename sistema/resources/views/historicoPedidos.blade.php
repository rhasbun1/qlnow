@extends('plantilla')      

@section('contenedorprincipal')


<div style="padding: 5px">
    <div class="panel panel-default">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <div class="panel-heading">
            <b>Pedidos Despachados</b>
        </div>
        <div class="panel-body" id="panelBody" style="display: none;">
            <div> 
                <div class="row">
                    <div class="col-md-4">
                        @if( Session::get('idPerfil')=='14' or Session::get('idPerfil')=='15')
                            <div class="row" style="padding-top: 5px;display: none">
                        @else
                            <div class="row" style="padding-top: 5px">
                        @endif                             
                            <div class="col-md-4">
                                Cliente
                            </div>
                            <div class="col-md-8">
                                @if ( Session::get('empresaUsuario')!='0' )
                                    <select id="idCliente" class="form-control input-sm" readonly>
                                        <option value="{{ $clientes[0]->emp_codigo }}">{{ $clientes[0]->emp_nombre }}</option>
                                @else
                                    <select id="idCliente" class="form-control input-sm">
                                        <option value="0">Todos los clientes</option>
                                        @foreach($clientes as $item)
                                            <option value="{{ $item->emp_codigo }}">{{ $item->emp_nombre }}</option>
                                        @endforeach
                                 @endif    
                                </select>
                            </div>
                        </div>
                        @if( Session::get('idPerfil')=='14' or Session::get('idPerfil')=='15')
                            <div class="row" style="padding-top: 5px;display: none">
                        @else
                            <div class="row" style="padding-top: 5px">
                        @endif 
                            <div class="col-md-4">
                                Planta QLSA
                            </div>
                            <div class="col-md-8">
                                <select id="idPlanta" class="form-control input-sm">
                                    @if ( Session::get('idPlanta')=='0' )
                                        <option value="0">Todas las plantas</option>
                                        @foreach($plantas as $item)
                                            <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    @else    
                                        @foreach($plantas as $item)
                                            @if ( Session::get('idPlanta')==$item->idPlanta)
                                                <option value="{{ $item->idPlanta }}">{{ $item->nombre }}</option>
                                            @endif    
                                        @endforeach
                                    @endif                                            
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-4" style="padding-top: 5px">
                                Filtro por Fecha de Salida (<b>NO</b> se incluyen <b>pedidos suspendidos</b>)
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaMin" data-date-end-date="0d">
                                    <input type="text" class="form-control input-sm" id="min" >
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaMax">
                                    <input type="text" class="form-control input-sm" id="max">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(1);">Buscar</button>
                            </div>                                
                        </div>
                        <div class="row" style="padding-top: 5px">  
                            <div class="col-md-4" style="padding-top: 5px">
                                Filtro por Fecha Creación (<b>SI</b> se incluyen <b>pedidos suspendidos</b>)
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaCreacionMin" data-date-end-date="0d">
                                    <input type="text" class="form-control input-sm" id="fechaCreacionMin">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group date" id="divFechaCreacionMax" data-date-end-date="0d">
                                    <input type="text" class="form-control input-sm" id="fechaCreacionMax">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(4);">Buscar</button>
                            </div>                                
                        </div>                                                       
                        <div class="row" style="padding-top: 5px">                               
                            <div class="col-md-4">
                                Rango por Nº de Pedido
                            </div>
                            <div class="col-md-3">
                                <input id="txtPedidoDesde" class="form-control input-sm" id="min" maxlength="9" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-3">
                                <input id="txtPedidoHasta" class="form-control input-sm" id="max" maxlength="9" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(2);">Buscar</button>
                            </div>                                   
                        </div>
                        <div class="row" style="padding-top: 5px">
                            <div class="col-md-4">
                                Rango por Nº Guía Despacho
                            </div>
                            <div class="col-md-3">
                                <input id="txtGuiaDesde" class="form-control input-sm" id="min" maxlength="10" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-3">
                                <input id="txtGuiaHasta" class="form-control input-sm" id="max" maxlength="10" onkeypress="return isIntegerKey(event)">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-success btn-sm" onclick="obtenerHistorico(3);">Buscar</button>
                            </div>                                   
                        </div>
                    </div>
                </div>                                                                             
            </div>
            <hr style="color: #0056b2;" />
        </div>
        <div id="divTabla" style="width:100%;">
                <table id="tablaDetalle" class="display">
                    <thead>
                        <th style="width: 60px">Pedido</th>
                        <th style="width: 120px"></th>
                        <th style="width: 120px;">Cliente</th>
                        <th style="width: 120px">Obra/Planta</th>
                        <th style="width: 80px">Producto</th>
                        <th style="width: 40px;text-align: right;">Cantidad<br>Real</th>
                        <th style="width: 60px">Unidad</th>
                        <th style="width: 80px">Fecha Entrega<br>Solicitada</th>
                        <th style="width: 80px">Fecha Salida/Hora Salida</th>
                        <th style="width: 80px">Forma de<br>Entrega</th>
                        <th style="width: 80px">Planta de Origen</th>
                        <th style="width: 80px">Estado</th>
                        <th style="width: 80px;">Plazo</th>
                        <th style="width: 60px; text-align: right;">Nº Nota<br>de Venta</th>
                        <th style="width: 60px; text-align: right;">Nº de guía</th>
                        <th style="width: 80px; text-align: right;">Nº Aux.</th>
                        <th style="width: 80px; text-align: right;">Mod</th>
                    </thead>
                    <tbody>
                    </tbody>            
                </table>      

        </div>
    </div>
    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
    </div>    
</div>

<div id="mdProcesando" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body" style="text-align: center">
          <img src="{{ asset('/') }}img/procesando.gif" alt="User Avatar">
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
@include('guiaDespacho');

@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>

    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  

    <script src="{{ asset('/') }}js/app/funciones.js?{{$parametros[0]->version}}"></script>
    <script src="{{ asset('/') }}js/app/guiaDespacho.js?{{$parametros[0]->version}}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.0/js/dataTables.fixedColumns.min.js"></script>  
    
    <script>

        $('#datosGuia').on('submit', function(e) {
          // evito que propague el submit
          e.preventDefault();
          // agrego la data del form a formData

            document.getElementById('btnSubirGuia').disabled=true;

            if( $("#nuevoFolioDTE").val().trim()=='' ){
                swal(
                    {
                        title: 'Debe ingresar el numero Folio DTE!!' ,
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

            if(document.getElementById('upload-demo').value.trim()==''){
                swal(
                    {
                        title: 'Debe seleccionar un archivo!!' ,
                        text: '',
                        type: 'warning',
                        showCancelButton: false,
                        closeOnConfirm: true,
                        confirmButtonText: 'Cerrar',
                        cancelButtonText: '',
                    });
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
                if( table.cell(i,13).data()==numGuia ){
                    cadena=table.cell(i,1).data();
                    table.cell(i,1).data( cadena.replace(numeroGuiaOrigen, nuevoNumeroGuia) );
                    table.cell(i,13).data( $('#nuevoFolioDTE').val() );
                }
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

            // a continuación se envia el formulario con el nuevo número de guía y el archivo pdf correspondiente a la guía

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
                $("#folioDTE").val( $("#nuevoFolioDTE").val() );
            //    $("#numGuia").val( $("#nuevoFolioDTE").val() );
                document.getElementById('btnEmitirGuia').style.display='none';
                document.getElementById('btnBajar').style.display='inline';
                cerrarModalSubirGuiaPdf();                 
              },
              error: function(jqXHR, text, error){
                  alert('Error!, No se pudo Añadir los datos');
              }
          });
        });

        const formatter = new Intl.NumberFormat('es-CL', {
        minimumFractionDigits: 2
        })

        function obtenerHistorico(opcion){


            $("#mdProcesando").modal('show');    

            var tabla=$("#tablaDetalle").DataTable();

            tabla.rows().remove();

            if(opcion==4){
                var fechaSalidaDesde=$("#fechaCreacionMin").val().trim();
                var fechaSalidaHasta=$("#fechaCreacionMax").val().trim();  
            }else{
                var fechaSalidaDesde=$("#min").val().trim();
                var fechaSalidaHasta=$("#max").val().trim();                
            }


            if(fechaSalidaDesde==''){
                fechaSalidaDesde='01/01/1900';
            }
            
            if(fechaSalidaHasta==''){   
                var hoy = new Date();
                var dd = hoy.getDate();
                var mm = hoy.getMonth()+1;
                var yyyy = hoy.getFullYear();

                if (dd < 10) {dd = '0' + dd; }
                if (mm < 10) {mm = '0' + mm; }
            
                fechaSalidaHasta=dd + '/' + mm + '/' + yyyy;
            }

            var pedDesde="0";
            if( $("#txtPedidoDesde").val()!=''){
                pedDesde=$("#txtPedidoDesde").val();
            }

            var pedHasta="0";
            if( $("#txtPedidoHasta").val()!=''){
                pedHasta=$("#txtPedidoHasta").val();
            }

            var guiaDesde="0";
            if( $("#txtGuiaDesde").val()!=''){
                guiaDesde=$("#txtGuiaDesde").val();
            }

            var guiaHasta="0";
            if( $("#txtGuiaHasta").val()!=''){
                guiaHasta=$("#txtGuiaHasta").val();
            }
           
            $.ajax({
                url: urlApp +'obtenerHistoricoPedidos',
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { 
                        salidaDesde: fechaSalidaDesde,
                        salidaHasta: fechaSalidaHasta,
                        emp_codigo: $("#idCliente").val(),
                        idPlanta: $("#idPlanta").val(),
                        pedidoDesde: pedDesde,
                        pedidoHasta: pedHasta,
                        guiaDesde: guiaDesde,
                        guiaHasta: guiaHasta,
                        opcion: opcion 
                      },
                success:function(dato){
                    for(var x=0;x<dato.length;x++){
                        cadena='<tr style="background-color: #A93226; color: #FDFEFE">';
                        if(dato[x].modificado>0){
                            cadena+='<span class="badge badge-primary">' + dato[x].modificado + '</span>';
                            modificado="si";
                        }else{
                            modificado="no";
                        }    
                        if(dato[x].tipoTransporte==2){
                            cadena+='<span class="badge badge-danger">M</span>';
                        }
                        if( dato[x].formula!='' ){
                            cadena+='<span><img src="' + urlApp +'img/iconos/matraz.png" border="0" title="' + dato[x].formula +'" width="15px" height="15pxs"></span>';
                        }
                        if( dato[x].numeroGuia>0 ){
                            cadena+='<span onclick="abrirGuia(1, ' + dato[x].numeroGuia + ', this.parentNode.parentNode);" style="cursor:pointer; cursor: hand"><img src="'+ urlApp + 'img/iconos/guiaDespacho2.png" border="0"></span>';
                        }
                        if( dato[x].certificado!='' && dato[x].certificado!='S/C' ){  
                            cadena+='<a target="_blank" href="'+ urlApp + 'bajarCertificado/'+ dato[x].certificado +'">';
                            cadena+='<img src="'+ urlApp + 'img/iconos/certificado.png" border="0"></a>';
                        }

                        if( dato[x].salida==1 ){
                            if (dato[x].gps==1){
                                patenteSinGuion="'"+ dato[x].Patente.replace('-','')+"'";
                                cadena+='<span><img src="' + urlApp + 'img/iconos/enTransporteGps.png" border="0" onclick="verUbicacionGmaps(' + patenteSinGuion + ');" style="cursor:pointer; cursor: hand"></span>'; 

                            }else{
                                cadena+='<span><img src="' + urlApp + 'img/iconos/enTransporte.png" border="0" style="cursor:pointer; cursor: hand"></span>'; 
                            }
                        }

                        celdaPedido='<a target="_blank" href="'+urlApp+'verpedidoNuevaVentana/'+ dato[x].idPedido + '/1/" class="btn btn-xs btn-success">' + dato[x].idPedido +'</a>';


                        celdaNumAux="";
                        if(document.getElementById('idPerfilSession').value>=5 && document.getElementById('idPerfilSession').value<=7 ){
                            celdaNumAux='<td style="width: 80px; text-align: center;">' + dato[x].numeroAuxiliar + '</td>';                                  
                        }else{
                           celdaNumAux='<td style="width: 80px; text-align: center;">' + dato[x].numeroAuxiliar + '</td>';
                        }                        
                        num = dato[x].cantidadReal;
                        var fila=tabla.row.add( [
                                celdaPedido,
                                "<div style='white-space:normal;width:100%'>" + cadena + "</div>",
                                dato[x].emp_nombre,
                                dato[x].nombreObra,
                                dato[x].prod_nombre,
                                dato[x].cantidadReal,
                                dato[x].unidad,
                                dato[x].fechaEntrega,
                                dato[x].fechaHoraSalida,
                                dato[x].formaEntrega,
                                dato[x].nombrePlanta,
                                dato[x].estadoPedido,
                                dato[x].estadoAtrasado,
                                dato[x].idNotaVenta,
                                dato[x].numeroGuia,
                                celdaNumAux,
                                modificado
                            ] ).index();
                        tabla.cell(fila,0).node().width="60px";
                        tabla.cell(fila,1).node().width="120px";
                        tabla.cell(fila,2).node().width="120px";
                        tabla.cell(fila,3).node().width="120px";
                        tabla.cell(fila,4).node().width="80px";
                        tabla.cell(fila,5).node().width="40px";tabla.cell(fila,5).node().style.textAlign="right";
                        tabla.cell(fila,6).node().width="60px";
                        tabla.cell(fila,7).node().width="80px";
                        tabla.cell(fila,8).node().width='80px';
                        tabla.cell(fila,9).node().width='80px';
                        tabla.cell(fila,10).node().width='80px';
                        tabla.cell(fila,11).node().width='80px';
                        tabla.cell(fila,12).node().width='80px';
                        tabla.cell(fila,13).node().width='60px';tabla.cell(fila,13).node().style.textAlign="right";
                        tabla.cell(fila,14).node().width='60px';tabla.cell(fila,14).node().style.textAlign="right";
                        tabla.cell(fila,15).node().width='80px';
                        

                    }                 
                    tabla.draw();
                    actualizarFiltros(tabla);
                    $("#mdProcesando").modal('hide');
                },
                error: function(jqXHR, text, error){
                    $("#mdProcesando").modal('hide');
                    alert('Ha ocurrido un error!, vuelva a presionar el botón Buscar Selección');
                }                
            })
        }

        function ingresarNumeroAuxiliar(row){
            var tabla=$("#tablaDetalle").DataTable();
            var fila=tabla.row(row).index();
            var celda=tabla.cell(fila,14).node();
            $("#numeroAuxiliar").val(celda.getElementsByTagName('a')[0].innerHTML);            
            document.getElementById("numeroAuxiliar").dataset.fila=tabla.row( row ).index();;
            $("#modNumeroAuxiliar").modal('show');
        } 

        function cerrarModalNumeroAuxliar(){
            $("#modNumeroAuxiliar").modal('hide');
        }
        
        function guardarNumeroAuxiliar(){
            var fila=document.getElementById('numeroAuxiliar').dataset.fila;
            var tabla=$("#tablaDetalle").DataTable();
            var celda=tabla.row(document.getElementById("numeroAuxiliar").dataset.fila,0).node();
            var pedido=celda.getElementsByTagName('a')[0].innerHTML;

            for(var x=0;x<tabla.rows().count();x++){
                celda=tabla.cell(x,0).node();
                if(celda.getElementsByTagName('a')[0].innerHTML==pedido){
                    celda=tabla.cell(x,14).node();
                    celda.getElementsByTagName('a')[0].innerHTML=$("#numeroAuxiliar").val();
                    tabla.cell(x,14).draw();
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

        $(document).ready(function() {
            var hoy = new Date();
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1;
            var yyyy = hoy.getFullYear();

            if (dd < 10) {dd = '0' + dd; }
            if (mm < 10) {mm = '0' + mm; }
            $("#max").val(dd + '/' + mm + '/' + yyyy);


            hoy.setDate(hoy.getDate() - 30);
            var dd = hoy.getDate();
            var mm = hoy.getMonth()+1;
            var yyyy = hoy.getFullYear();
            if (dd < 10) {dd = '0' + dd; }
            if (mm < 10) {mm = '0' + mm; }
            $("#min").val(dd + '/' + mm + '/' + yyyy);


            var tablaDetalle="#tablaDetalle";
            
            // Setup - add a text input to each footer cell
            $('#tablaDetalle thead tr').clone(true).appendTo( '#tablaDetalle thead' );
            $('#tablaDetalle thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                if(title.trim()!='' && title.trim()=='Estado Pedido' ){
                    $(this).html( '<select id="selEstado" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Planta de Origen' ){
                    $(this).html( '<select id="selPlanta" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Producto' ){
                    $(this).html( '<select id="selProducto" class="form-control input-sm"></select>' );                    
                }else if(title.trim()!='' && title.trim()=='Forma deEntrega' ){
                    $(this).html( '<select id="selFormaEntrega" class="form-control input-sm"></select>' );
                }else if(title.trim()!='' && title.trim()=='Estado' ){
                    $(this).html( '<select id="selEstado" class="form-control input-sm"></select>' );                                                 
                }else if(title.trim()!='' ){
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

            var tituloArchivo='Pedidos Despachados';

            // DataTable
            var table=$('#tablaDetalle').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                lengthMenu: [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],
                scrollX: true,
                scrollCollapse: true,
                dom: 'Bfrtip',              
                columnDefs:[
                                {
                                    render: function (data, type, full, meta) {
                                        return "<div style='white-space:normal;width:100%'>" + data + "</div>";
                                    },                                     
                                    targets:[1]
                                },

                                {
                                 "targets": [ 16 ],
                                    "visible": false
                                }
                            ],

                buttons: [
                    'pageLength', 
                    {
                        text: 'Actualizar',
                        action: function ( e, dt, node, config ) {
                            this.disable();    
                            location.reload(true);                        
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: tituloArchivo,
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14,15 ]
                        }
                    }                       
                   
                ],                        
                "order": [[ 0, "desc" ]],                       
                language:{ url: "{{ asset('/') }}locales/datatables_ES.json",
                           "decimal": ","},
                preDrawCallback: function( settings ) {
                    document.getElementById('panelBody').style.display="block";
                  },
                initComplete: function () {
                    actualizarFiltros(this.api());
                },
                "createdRow": function( row, data, dataIndex){
                if( data[11]=="Suspendido"){
                    console.log(data);
                    $(row).addClass('red');
                }
                if( data[11]!="Suspendido" && data[16]=="si"){
                    $(row).addClass('yellow');
                }
            }
                      
            });



            $('.date').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true
        
            });

            $('#wrapper').toggleClass('sidebar-hide');
            $('.main-menu').find('.openable').removeClass('open');
            $('.main-menu').find('.submenu').removeAttr('style'); 

            obtenerHistorico(1);

        } );

        function actualizarFiltros(tabla){
            tabla.columns(10).every( function () {
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

            tabla.columns(4).every( function () {
                var column = this;
                var select = $("#selProducto" ).empty().append( '<option value=""></option>' )
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
            tabla.columns(6).every( function () {
                var column = this;
                var select = $("#selUnidad" ).empty().append( '<option value=""></option>' )
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
            tabla.columns(9).every( function () {
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
            tabla.columns(11).every( function () {
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

        }   

    </script>
    <style>
        .red {
            background-color: #A93226 !important;
            color: #FDFEFE !important;
            }

        .yellow {
            background-color: yellow !important;
            }
    </style>
@endsection
