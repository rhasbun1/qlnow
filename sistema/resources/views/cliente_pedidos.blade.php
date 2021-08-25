@extends('plantilla')      

@section('contenedorprincipal')

<div style="padding: 5px">

    <div class="panel panel-default" id="contenedor3">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <div class="panel-heading">
            <div class="panel-tab clearfix">
                <input type="hidden" id="idCliente" data-idperfil="{{Session::get('idPerfil')}}">

                <ul class="tab-bar">
                                <input type="hidden" id="idCliente" data-idperfil="{{Session::get('idPerfil')}}">

                    <li class="active"><a href="#tabAprobados" data-toggle="tab"><b>Pedidos en Proceso</b></a></li> 
                    <li><a href="#tabPendientes" data-toggle="tab"><b>Pedidos Pendientes de Preaprobación</b></a></li>

                </ul>
            </div>
        </div> 
        <div class="panel-body">
            <div class="tab-content clearfix">
                <div class="tab-pane active" id="tabAprobados" style="padding-top: 5px">
                    <table id="tablaAprobados" class="pedidos table table-condensed" style="width:100%">
                        <thead>
                            <th style="width:20px;text-align: center;">Pedido</th>
                            <th style="width: 60px;text-align: left;"></th>
                            <th style="width:80px">Estado</th>
                            <th>Fecha Creación</th>
                            <th>Cliente</th>
                            <th>Obra/Planta</th>
                            <th>Producto</th>
                            <th style="text-align: right">Cantidad</th>
                            <th>Unidad</th>
                            <th>Planta Origen</th>
                            <th>Forma Entrega</th>
                            <th>Fecha Entrega</th>
                            <th>Transporte</th>
                            <th>Fecha Carga</th>
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
                                    <td style="width:150px">
                                        <a href="{{ asset('/') }}clienteVerPedido/{{ $item->idPedido }}/7/" class="btn btn-xs btn-success">{{ $item->idPedido }}</a>                                   
                                    </td>                                        
                                    <td>
                                        @if ( $item->cantidadReal>0 )
                                            <span><img src="{{ asset('/') }}img/iconos/cargacompleta.png" border="0"></span>
                                        @endif
                                        @if ( $item->numeroGuia>0 )
                                        <span onclick='abrirGuia(1, {{ $item->numeroGuia }}, this.parentNode.parentNode);' style="cursor:pointer; cursor: hand"><img src="{{ asset('/') }}img/iconos/guiaDespacho2.png" border="0"></span>                               
                                        @endif    
                                        @if ( $item->certificado==1 )  
                                            <span><img src="{{ asset('/') }}img/iconos/certificado.png" border="0"></span>
                                        @endif
            
                                        @if ($item->modificado>0)
                                        <span class="badge badge-primary">{{$item->modificado}}</span>
                                         @endif     
                                        @if ($item->tipoTransporte==2)
                                            <span class="badge badge-danger" title="Pedido Mixto">M</span>
                                        @endif    
                                    </td>
                                    <td style="width:80px">{{ $item->estadoPedido }}</td>
                                    <td>{{ $item->fechahora_creacion }}</td>
                                    <td>{{ $item->nombreCliente }}</td>
                                    <td>{{ $item->nombreObra }}</td>
                                    <td>{{ $item->prod_nombre }}</td>
                                    <td style="text-align: right">{{ $item->cantidad }}</td>
                                    <td>{{ $item->u_abre }}</td>
                                    <td>{{ $item->nombrePlanta }}</td>
                                    <td>{{ $item->formaEntrega }}</td>
                                    <td>{{ $item->fechaEntrega }} {{ $item->horarioEntrega }}</td>
                                    <td>{{ $item->apellidoConductor }} / {{ $item->empresaTransporte }}</td>
                                    <td>{{ $item->fechaCarga }} {{ $item->horaCarga }} </td>
                                </tr>
                            @endforeach
                        </tbody>            
                    </table>
                </div>
                <div class="tab-pane" id="tabPendientes" style="padding-top: 5px">
                        <table id="tablaPendientes" class="pedidos table table-hover table-condensed">
                            <thead>
                                <th >Pedido</th> 
                                <th >Fecha Creación</th>            
                                <th >Estado</th>
                                <th >Cliente</th>
                                <th>Obra/Planta</th>
                                <th>Fecha de Entrega</th>
                                <th>Total ($)</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                            </thead>
                            <tbody>
                            @foreach($listaPedidoSinAprobarClientes as $item)
                                <tr>
                                
                                    <td style="width:150px">
                                        <a href="{{ asset('/') }}clienteVerPedido/{{ $item->idPedido }}/7/" class="btn btn-xs btn-success">{{ $item->idPedido }}</a>                                   
                                    </td>  
                                    <td style="width: 120px">{{ $item->fechahora_creacion }}</td>
                                    <td style="width: 120px">{{ $item->estado }}</td>
                                    <td style="width: 120px">{{ $item->emp_nombre }}</td>
                                    <td style="width: 120px">{{ $item->Obra }}</td>
                                    <td style="width: 120px">{{ $item->fechaEntrega }}</td>
                                    <td style="width: 120px">{{ number_format($item->total, 0, ",", ".") }}</td>
                                    <td style="width: 120px">{{ $item->cantidad }}</td>
                                    <td style="width: 120px">{{ $item->u_abre }}</td>
                                    
                                </tr>

                                @endforeach    
                            </tbody>              
                        </table>      
            </div>
        </div>
        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>

    </div>
   
</div>


@include('guiaDespacho')

@endsection

@section('javascript')
    <!-- Datepicker -->

    <script src="{{ asset('/') }}js/app/funciones.js?{{$parametros[0]->version}}"></script>
    <script src="{{ asset('/') }}js/app/guiaDespacho.js?{{$parametros[0]->version}}"></script>
    <!-- Datatable -->
    <script src="{{ asset('/') }}js/jquery.dataTables.min.js"></script>


    <script src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>

    <script>
        
        $(document).ready(function() {
            // Setup - add a text input to each footer cell

            // DataTable

            // Setup - add a text input to each footer cell
            $('#tablaAprobados thead tr').clone(true).appendTo( '#tablaAprobados thead' );
            $('#tablaAprobados thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();

                if(title.trim()!='' && title.trim()!='Estado' ){
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

            if($("#idCliente").val()==14){
                var table=$('#tablaAprobados').DataTable({
                 orderCellsTop: true,
                 fixedHeader: true,         
                "lengthMenu": [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: 'Nuevo Pedido',
                        className: 'orange',
                        attr:  {
                                    id: 'btnNuevoPedido'
                                },
                        action: function ( e, dt, node, config ) {
                                location.href="{{ asset('/') }}clienteNotasdeVenta";
                            }
                    },
                   
                    'pageLength',
                    {
                        extend: 'excelHtml5',
                        title: 'Notas de Venta Vigentes',
                        exportOptions: {
                            columns: [ 0, 2, 3, 4,5,6,7,8,9,10,11,12,13 ]
                        }
                    }
                ],                       
                "order": [[ 0, "desc" ]],                        
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                initComplete: function () {
                    this.api().columns(2).every( function () {
                        var column = this;

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(1)' ).empty() )
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

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(4)' ).empty() )
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
                    this.api().columns(8).every( function () {
                        var column = this;

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(7)' ).empty() )
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

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(8)' ).empty() )
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


            }else{
                var table=$('#tablaAprobados').DataTable({
                 orderCellsTop: true,
                 fixedHeader: true,         
                "lengthMenu": [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],
                dom: 'Bfrtip',
                buttons: [
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
                        title: 'Pedidos Ingresados Aprobados',
                        exportOptions: {
                            columns: [ 0, 2, 3, 4,5,6,7,8,9,10,11,12,13 ]
                        }
                    }
                ],                       
                "order": [[ 0, "desc" ]],                        
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                initComplete: function () {
                    this.api().columns(2).every( function () {
                        var column = this;

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(2)' ).empty() )
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

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(5)' ).empty() )
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

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(9)' ).empty() )
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
                    this.api().columns(8).every( function () {
                        var column = this;

                        var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                            .appendTo( $( '#tablaAprobados thead tr:eq(1) th:eq(8)' ).empty() )
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

            }
            



          

        } );


    </script>

@endsection