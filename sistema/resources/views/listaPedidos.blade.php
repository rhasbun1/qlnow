@extends('plantilla')      

@section('contenedorprincipal')


<div style="padding: 5px">
    <div class="panel panel-default">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" id="idPerfil" value="{{ Session::get('idPerfil') }}">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-3">
                    <b>Pedidos en Proceso</b>
                </div>
                <div class="col-md-9" style="text-align: right;">
                    @if ($cantidadIngresoCliente>0 and (Session::get('idPerfil')=='2' or Session::get('idPerfil')=='3' or Session::get('idPerfil')=='12' or Session::get('idPerfil')=='4' or Session::get('idPerfil')=='18'))
                         <a href="{{ asset('/') }}listaIngresosClienteporAprobar" class="btn btn-danger btn-sm">EXISTEN {{ $cantidadIngresoCliente }} PEDIDOS INGRESADOS POR CLIENTE EN ESPERA DE PRE-APROBACIÓN</a>
                    @endif
                </div>
            </div>       
        </div>
        <!--<div class="panel-body" id="panelBody" style="display: block">-->
        <div id="contenidoPrincipal" style="display: block">    
            <div class="padding-md clearfix">
                <div style="padding-bottom: 15px">  
                    <div class="row">
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-2">
                            Filtrar por Fecha de Entrega
                        </div>
                        <div class="col-md-2">
                            <div class="input-group date" id="divFechaMin">
                                <input type="text" class="form-control input-sm" id="min">
                                <div id="test" class="input-group-addon">
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
                    </div>
                </div> 

                @if( Session::get('idPerfil')=='11' ) <!-- Ejecutivo de Crédito -->
                    <div style="width: 100%">
                        <div id="Grid"></div>
                        <table id="tablaDetalle" class="table table-hover table-condensed" style="width: 100%">
                            <thead>
                                <th style="width: 80px">Pedido Nº</th>
                                <th style="width: 100px">Fecha Creación</th>
                                <th style="width: 250px">Cliente</th>
                                <th style="width: 250px">Obra/Planta</th>
                                <th style="width: 100px"><b>Total c/IVA</b></th>
                                <th style="width: 100px">Fecha Entrega</th>
                                <th style="width: 70px">Estado</th>
                                <th style="width: 70px">Estado2</th>
                            </thead>
                            <tbody>
                                @foreach($pedidos as $item)
                                    <tr>
                                        <td style="width: 80px" data-pedido='{{ $item->idPedido }}'>
                                            <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/1-2/" class="btn btn-xs btn-success">{{ $item->idPedido }}</a>
                                            @if( Session::get('grupoUsuario')=='C' and $item->idEstadoPedido==1 )
                                                <button class="btn btn-sm btn-primary" title="Aprobar pedido" onclick="aprobarPedido({{ $item->idPedido }}, this)"><span class="glyphicon glyphicon-ok"></span></button>
                                            @endif                                          
                                        </td>
                                        <td style="width: 100px">{{ $item->fechahora_creacion }}</td>
                                        <td style="width: 250px">{{ $item->nombreCliente }}</td>
                                        <td style="width: 250px">{{ $item->nombreObra }}</td>
                                        <td style="width: 100px; text-align: right;"><b>$ {{number_format( $item->totalNeto + $item->montoIva, 0, ',', ',' )  }}</b></td>
                                        <td style="width: 100px">{{ date('d/m/Y', strtotime($item->fechaEntrega)) }} {{ $item->horarioEntrega}}</td>
                                        <td style="width: 70px">{{ $item->estado }}</td>
                                        <td style="width: 70px">{{ $item->estadoAtrasado }}</td>
                                    </tr>
                                @endforeach
                            </tbody>            
                        </table>
                    <div>   
                @else

                    <table id="tablaDetalle" class="table table-hover" style="width:100%">
                        <thead>
                            <th style="width:20px;text-align: center;">Pedido</th>
                            <th style="width: 60px;text-align: left;"></th>
                            <th style="width: 50px;text-align: center;">Estado</th>                            
                            <th style="width: 120px">Cliente</th>
                            <th style="width: 120px">Obra/Planta</th>
                            <th style="width: 70px">Producto</th>
                            <th style="width: 30px;text-align: right;">Cant.</th>
                            <th style="width: 30px;text-align: center">Unidad</th>
                            <th style="width: 100px">Fecha Entrega</th>
                            <th style="width: 70px">Forma Entrega</th>
                            <th style="width: 70px">Planta Origen</th>
                            <th style="width: 100px">Fecha Carga<br>Programada</th>
                            <th style="width: 150px">Transporte</th>
                            <th style="width: 50px;text-align: right;">Fecha Creación</th>
                            <th style="width: 80px">Nº Aux.</th>
                            <th style="width: 70px">Estado2</th>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $item)
                                @if ( $item->idEstadoPedido=='0' )
                                    <tr style="background-color: #A93226; color: #FDFEFE">
                                @else
                                    @if ( $item->modificado>0)
                                        <tr style="background-color: yellow">
                                    @else
                                        <tr>
                                    @endif
                                @endif    
                                    <td data-pedido='{{ $item->idPedido }}' style="width:20px;">
                                        <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/1-2/" class="btn btn-xs btn-success" title="Ver Pedido">{{ $item->idPedido }}</a>
                                        @if( Session::get('grupoUsuario')=='C' and (Session::get('idPerfil')=='2' or Session::get('idPerfil')=='11') and $item->idEstadoPedido==1 )
                                            <button class="btn btn-xs btn-primary" title="Aprobar Pedido" onclick="aprobarPedido({{ $item->idPedido }}, this)"><span class="glyphicon glyphicon-ok"></span></button>
                                        @endif                                        
                                    </td>
                                    <td style="width: 60px;text-align: left;">
                                    <?php
                                        $fechacarga =  explode( " ",$item->fechaCarga_dma);
                                    ?>
                                        @if ($item->modificado>0)
                                            <span class="badge badge-primary">{{$item->modificado}}</span>
                                        @endif                                        
                                        @if ($item->tipoTransporte==2)
                                            <span class="badge badge-danger">M</span>
                                        @endif
                                        @if ( $item->formula!='' )
                                            <span><img src="{{ asset('/') }}img/iconos/matraz.png" border="0" title="{{ $item->formula }}" width="15px" height="15pxs"></span>
                                        @endif                                                                       
                                        @if ( $item->cantidadReal>0 )
                                            <span><img src="{{ asset('/') }}img/iconos/cargacompleta.png" border="0"></span>
                                        @endif
                                        @if ( $item->horaCarga!='' )
                                            <span><img src="{{ asset('/') }}img/iconos/time.png" border="0" title="{{$fechacarga[0] }} {{$item->horaCarga}}"></span>
                                        @endif
                                        @if ( $item->empresaTransporte!='' )
                                            <span><img src="{{ asset('/') }}img/iconos/user.png" border="0" title="{{$item->empresaTransporte}} / {{$item->apellidoConductor}}"></span>
                                        @endif                                    
                                        @if ( $item->numeroGuia>0 )
                                            <span name="{{$item->numeroGuia}}" onclick='abrirGuia({{ $item->tipoGuia }}, {{ $item->numeroGuia }}, this.parentNode.parentNode);' style="cursor:pointer; cursor: hand"><img src="{{ asset('/') }}img/iconos/guiaDespacho2.png" border="0"></span>
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
                                    <td style="width: 50px">{{ $item->estadoPedido }}</td>
                                   
                                    <td style="width: 120px">{{ $item->nombreCliente }}</td>
                                    <td style="width: 120px">{{ $item->nombreObra }}</td>
                                    <td style="width: 70px">
                                        {{ $item->prod_nombre }}                                   
                                    </td>
                                    <td style="width: 30px;text-align: right;">{{number_format( $item->cantidad, 0, ',', ',' ) }}</td>
                                    <td style="width: 30px;text-align: center">{{ $item->u_abre }}</td>
                                    <td style="width: 100px">{{ $item->fechaEntrega }} {{ $item->horarioEntrega }}</td>
                                    <td style="width: 70px">{{ $item->formaEntrega }}</td>
                                    <td style="width: 70px">{{ $item->nombrePlanta }}</td>
                                    <td style="width: 100px">{{ $item->fechaCarga }} {{ $item->horaCarga }} </td>
                                    <td style="width: 150px">{{ $item->apellidoConductor }} / {{ $item->empresaTransporte }}</td>
                                    <td style="width: 50px;text-align: right;">{{ date('d/m/Y', strtotime($item->fecha)) }} {{ $item->hora}}</td>
                                    <td style="width: 80px;text-align: center;">{{ $item->numeroAuxiliar }}</td>
                                    <td style="width: 70px">{{ $item->estadoAtrasado }}</td>
                                </tr>
                            @endforeach
                        </tbody>            
                    </table>            
 
               @endif 
            </div>
        </div>
    </div>
    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
    </div>    
</div>

@include('guiaDespacho')

@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>

    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  

    <script src="{{ asset('/') }}js/app/funciones.js?{{$parametros[0]->version}}"></script>
    <script src="{{ asset('/') }}js/app/guiaDespacho.js?{{$parametros[0]->version}}"></script>

    <script>

        function aprobarPedido(idPedido, btn){
            $.ajax({
                url: urlApp + "aprobarPedido",
                headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
                type: 'POST',
                dataType: 'json',
                data: { idPedido: idPedido
                      },
                success:function(dato){
                    console.log(dato.identificador);
                    if(dato.identificador == 0){
                        swal(
                                {
                                    title: '¡¡El pedido fue modificado anteriormente porfavor actualizar pagina!!' ,
                                    text: 'por favor refresque la pagina',
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
                                        location.reload(true); 
                                        return;                         
                                    }
                                }
                            );
                    }else{
                     btn.style.visibility = 'hidden';
                     location.reload(true);     
                    }
                   
                }
            })
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
                if(tabla.rows[i].cells[4].getElementsByTagName('input')[0].value.trim().replace(".", "")>parseInt(tabla.rows[i].cells[3].innerHTML.trim().replace(".", ""))){
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
                }
            }


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
                                return;                         
                            }
                        }
                    );
                    return;
                }
                document.getElementById('folioDTE').dataset.numeroguia=$("#nuevoFolioDTE").val();
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

        $(document).ready(function(){
            var tablaDetalle="#tablaDetalle";
            // Setup - add a text input to each footer cell
            $('#tablaDetalle thead tr').clone(true).appendTo( '#tablaDetalle thead' );
            $('#tablaDetalle thead tr:eq(1) th').each( function (i) {
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
                }else if(title.trim()!='' && title.trim()=='Producto' ){
                    $(this).html( '<select id="selProducto" class="form-control input-sm"></select>' );                                     
                }else if(title.trim()!='' ){
                    $(this).html( '<input type="text" class="form-control input-sm" placeholder="" />' );
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
            


            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = fechaIngles( $('#min').val().trim() );
                    var max = fechaIngles( $('#max').val().trim() );

                    if (document.getElementById('tablaDetalle').rows[0].cells.length==8 ){
                        var startDate=fechaIngles(data[5].substr(0,10));
                    }else{
                        var startDate=fechaIngles(data[8].substr(0,10));
                    }   

                    if (min == '' && max == '') { return true; }
                    if (min == '' && startDate <= max) { return true;}
                    if(max == '' && startDate >= min) {return true;}
                    if (startDate <= max && startDate >= min) { return true; }
                    return false;
                }
            );

           
           

            // DataTable
            var uPerfil='{{ Session::get("idPerfil") }}';
            if(uPerfil == 11){
                    var table=$('#tablaDetalle').DataTable({
                    orderCellsTop: true,
                    fixedHeader: true,
                    "scrollX": true,
                    lengthMenu: [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],                
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: 'Nuevo Pedido',
                            className: 'orange',
                            attr:  {
                                        id: 'btnNuevoPedido'
                                    },
                            action: function ( e, dt, node, config ) {
                                if( uPerfil==11 || uPerfil==19 ){
                                    swal(
                                        {
                                            title: 'Este perfil no tiene acceso a Crear Pedidos' ,
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
                                }else{
                                    location.href="{{ asset('/') }}listarNotasdeVenta";
                                }
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
                            title: 'Pedidos en Proceso',
                            text: '<i class="fa fa-file-excel-o"></i>',
                            titleAttr: 'Excel',                           
                            exportOptions: {
                                columns: [ 0, 2, 3, 4, 5, 6 ]
                            }
                        },
                        {
                            text: 'Próximos pedidos Granel',
                            action: function ( e, dt, node, config ) {
                                window.open(urlApp + 'verResumenGranel', "QL Now")                    
                            }
                        },
                        
                        {
                            text: 'Pedidos despachados (Granel)',
                            action: function ( e, dt, node, config ) {
                                window.open(urlApp + 'verPedidosDespachados', "QL Now")                    
                            }
                        }                                        
                    ],                
                    "order": [[ 0, "desc" ]],             
                    language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                    preDrawCallback: function( settings ) {
                        document.getElementById('contenidoPrincipal').style.display="block";
                    },                
                    initComplete: function () {
                        if( $("#idPerfil").val() == '11' ){
                            this.api().columns(2).every( function () {
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
                            this.api().columns(3).every( function () {
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
                        
                            this.api().columns(6).every( function () {
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
                        }else{

                            this.api().columns(2).every( function () {
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

                            this.api().columns(3).every( function () {
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

                            this.api().columns(4).every( function () {
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
                            this.api().columns(5).every( function () {
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

                            this.api().columns(7).every( function () {
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
                            this.api().columns(9).every( function () {
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

                            this.api().columns(10).every( function () {
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
                                    

                    }

                });
            }else{

                    var table=$('#tablaDetalle').DataTable({
                        orderCellsTop: true,
                        fixedHeader: true,
                        "scrollX": true,
                        lengthMenu: [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],                
                        dom: 'Bfrtip',
                        buttons: [
                            {
                                text: 'Nuevo Pedido',
                                className: 'orange',
                                attr:  {
                                            id: 'btnNuevoPedido'
                                        },
                                action: function ( e, dt, node, config ) {
                                    if( uPerfil==11 || uPerfil==19 ){
                                        swal(
                                            {
                                                title: 'Este perfil no tiene acceso a Crear Pedidos' ,
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
                                    }else{
                                        location.href="{{ asset('/') }}listarNotasdeVenta";
                                    }
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
                                title: 'Pedidos en Proceso',
                                text: '<i class="fa fa-file-excel-o"></i>',
                                titleAttr: 'Excel',                           
                                exportOptions: {
                                    columns: [ 0, 2, 3, 4, 5, 6,7,8,9,10,11,12,13 ]
                                }
                            },
                           
                            {
                                text: 'Próximos pedidos Granel',
                                action: function ( e, dt, node, config ) {
                                    window.open(urlApp + 'verResumenGranel', "QL Now")                    
                                }
                            },
                            
                            {
                                text: 'Pedidos despachados (Granel)',
                                action: function ( e, dt, node, config ) {
                                    window.open(urlApp + 'verPedidosDespachados', "QL Now")                    
                                }
                            }                                        
                        ],                
                        "order": [[ 0, "desc" ]],             
                        language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                        preDrawCallback: function( settings ) {
                            document.getElementById('contenidoPrincipal').style.display="block";
                        },                
                        initComplete: function () {
                            if( $("#idPerfil").val() == '11' ){
                                this.api().columns(2).every( function () {
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
                                this.api().columns(3).every( function () {
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
                            
                                this.api().columns(6).every( function () {
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
                            }else{

                                this.api().columns(2).every( function () {
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

                                this.api().columns(3).every( function () {
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

                                this.api().columns(4).every( function () {
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
                                this.api().columns(5).every( function () {
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

                                this.api().columns(7).every( function () {
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
                                this.api().columns(9).every( function () {
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

                                this.api().columns(10).every( function () {
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
                                        

                        }

                    });
                }
                 
            $('#max').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true,
                
            })

            $('#divFechaMax').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true,
                
            })
            
            $('#min').datepicker({
                format: 'dd/mm/yyyy',
                endDate: '+0d',
                autoclose: true,
                language: 'es'
                
            });
            $('#divFechaMin').datepicker({
                format: 'dd/mm/yyyy',
                endDate: '+0d',
                autoclose: true,
                language: 'es'
                
            });

  //          $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
  //          $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            }); 

            table.draw();
        });

    </script>
    
@endsection
