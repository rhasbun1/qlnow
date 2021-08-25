@extends('plantilla')      

@section('contenedorprincipal')

<script>
    function aprobarPedido(idPedido, row){
        var tabla=$("#tablaDetalle").DataTable();
        var fila=tabla.row( row ).index();

        $.ajax({
            url: urlApp + "aprobarPedidoCliente",
            headers: { 'X-CSRF-TOKEN' : $("#_token").val() },
            type: 'POST',
            dataType: 'json',
            data: { idPedido: idPedido
                  },
            success:function(dato){
                for (var i = 0; i < tabla.rows().count(); i++){
                    if(tabla.cell(i,0).node().dataset.pedido==idPedido){
                        tabla.cell(i,0).node().getElementsByTagName('button')[0].style.visibility = 'hidden';        
                    }
                }
                tabla.draw();
                window.location.reload(false);
            }
        })
    }
</script>

<div style="padding: 5px">
    <div class="panel panel-default table-responsive">
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-6">
                    <b>PEDIDOS INGRESADOS POR CLIENTE EN ESPERA DE PRE-APROBACIÓN</b>
                </div>   
        </div>
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
                </div>
            </div> 

            <table id="tablaDetalle" class="table table-hover table-condensed table-responsive">
                <thead>
                    <th>Pedido Nº</th>
                    <th style="width: 30px"></th>
                    <th>Fecha Creación</th>
                    <th>Cliente</th>
                    <th>Obra/Planta</th>
                    @if( Session::get('idPerfil')=='11' )
                        <th><b>Total c/IVA</b></th>
                    @else
                        <th style="display: none"><b>Total c/IVA</b></th>
                    @endif    
                    <th>Fecha Entrega</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                </thead>
                <tbody>
                    @foreach($pedidos as $item)

                    <?php 
                         $fecha_hora = explode(" ",$item->fechahora_creacion);
                         $fecha = $fecha_hora[0];
                    ?>
                        <tr>
                            <td style="width: 80px" data-pedido='{{ $item->idPedido }}'>
                                <a href="{{ asset('/') }}verpedido/{{ $item->idPedido }}/6-7/" class="btn btn-xs btn-success">{{ $item->idPedido }}</a> <!-- MATIAS -->
                                @if( Session::get('grupoUsuario')=='C')
                                    <button class="btn btn-sm btn-primary" title="Aprobar pedido" onclick="aprobarPedido({{ $item->idPedido }}, this.parentNode.parentNode)"><span class="glyphicon glyphicon-ok"></span></button>
                                @endif                                          
                            </td>
                            <td style="width: 30px">
                                @if ($item->tipoTransporte==2)
                                    <span class="badge badge-danger">M</span>
                                @endif
                            </td>                         
                            <td>{{ $fecha }}</td>
                            <td>{{ $item->emp_nombre }}</td>
                            <td>{{ $item->Obra }}</td>
                            @if( Session::get('idPerfil')=='11' )
                                <td align="right"><b>$ {{ number_format( $item->totalNeto + $item->montoIva, 0, ',', '.' ) }}</b></td>
                            @else
                                <td align="right" style="display: none"><b>$ {{ number_format( $item->totalNeto + $item->montoIva, 0, ',', '.' ) }}</b></td>
                            @endif    
                            <td>{{ $item->fechaEntrega }}</td>
                            <td>{{ $item->prod_nombre }}</td>
                            <td>{{ number_format( $item->cantidad, 0, ',', '.' ) }}</td>    
                            <td>{{  $item->u_abre}}</td>                            
                        </tr>
                    @endforeach
                </tbody>            
            </table>      
        </div>
    </div>
    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
        <a href="{{ asset('/') }}listarPedidos" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
    </div>    
</div>

@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>

    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  

    <script src="{{ asset('/') }}js/app/funciones.js"></script>

    <script>

        $(document).ready(function() {
            // Setup - add a text input to each footer cell
            $('#tablaDetalle thead tr').clone(true).appendTo( '#tablaDetalle thead' );
            $('#tablaDetalle thead tr:eq(1) th').each( function (i) {
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


            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {

                    var min = fechaIngles( $('#min').val().trim() );
                    var max = fechaIngles( $('#max').val().trim() );

                    var startDate=fechaIngles(data[6].substr(0,10));
                    if (min == '' && max == '') { return true; }
                    if (min == '' && startDate <= max) { return true;}
                    if(max == '' && startDate >= min) {return true;}
                    if (startDate <= max && startDate >= min) { return true; }
                    return false;
                }
            );



            // DataTable
            var table=$('#tablaDetalle').DataTable({
                autoWidth: false,
                orderCellsTop: true,
                fixedHeader: true,                
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Pedidos en Proceso',
                        text: '<i class="fa fa-file-excel-o"></i>',
                        titleAttr: 'Excel',                        
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4,5,6,7 ]
                        }
                    }
                   
                ],                
                "order": [[ 0, "desc" ]],
                columnDefs: [
                    { width: 75, targets: 0 },
                    { width: 100, targets: 1 },
                    { width: 200, targets: 2 },
                    { width: 100, targets: 4 },
                    { width: 100, targets: 5 }
                ],                
                language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                initComplete: function () {
                    this.api().columns(6).every( function () {
                        var column = this;

                        var select = $('<select class="form-control input-sm" style="width:100px"><option value=""></option></select>')
                            .appendTo( $( '#tablaDetalle thead tr:eq(1) th:eq(6)' ).empty() )
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


            //$("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
            //$("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });            

        } );

    </script>
    
@endsection
