@extends('plantilla')      

@section('contenedorprincipal')

<div style="padding: 5px">
    <div class="panel panel-default table-responsive">
        <div class="panel-heading">
            <b>Notas de Venta Vigentes</b>
        </div>

        <div class="padding-md clearfix"> 
            <div style="padding-bottom: 15px">
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-2">
                    <input style="display: none" disabled value="{{Session::get('idPerfil')}}" type="text" class="form-control input-sm" id="idPerfil">

                        Filtro&nbsppor&nbspFecha&nbspCreación
                    </div>
                    <div class="col-md-2">
                        <div class="input-group date" id="divFechaMin" data-date-end-date="-3d">
                            <input type="text" class="form-control input-sm" id="min" >
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group date" id="divFechaMax" data-date-end-date="0d">
                            <input type="text" class="form-control input-sm" id="max" >
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>                    
                </div>
            </div>
            <table id="tablaDetalle" class="table table-hover table-condensed table-responsive" style="width:100%">
                <thead>
                    <th style="width:100px;">Nota Venta</th>
                    <th>Fecha Creación</th>
                    <th>Cliente</th>
                    <th>Obra/Planta</th>
                    <th>Ejecutivo QL</th>
                    <th>Estado</th>
                    @if( Session::get('grupoUsuario')=='C' and (Session::get("idPerfil")!=11 and Session::get("idPerfil")!=19 )  )

                        <th></th>
                    @else
                        <th style="display: none;"></th>
                    @endif
                </thead>
                <tbody>
                    @foreach($listaNotasdeVenta as $item)
                        <tr>
                            <!--MATIAS-->
                            <td><a href="vernotaventa/{{ $item->idNotaVenta }}-0/1/" class="btn btn-xs btn-info" title="Ver Nota Venta">{{ $item->idNotaVenta }}</a></td>
                            <td>{{ $item->fechahora_creacion }}</td>
                            <td>{{ $item->emp_nombre }}</td>
                            <td>{{ $item->Obra }}</td>
                            <td>{{ $item->nombreUsuarioEncargado }}</td>
                            @if($item->aprobada==1)
                                <td>Aprobado</td>
                            @else
                                <td>Pendiente de Aprobación</td>
                            @endif
                            @if($item->aprobada==1 and Session::get('grupoUsuario')=='C' and (Session::get("idPerfil")!=11 and Session::get("idPerfil")!=19 )  )

                                <td>
                                        <a href="gestionarpedido/{{ $item->idNotaVenta }}/" class="btn btn-xs btn-success">Crear Pedido</a>
                                </td>
                                @else
                                    <td style="display: none;"></td>
                                @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>      
        </div>
    </div>
    <div style="padding-top:18px; padding-bottom: 20px;padding-left: 20px">
        <a href="{{ asset('/') }}dashboard" class="btn btn-sm btn-warning" style="width:80px">Atrás</a>
    </div>
</div>


@endsection

@section('javascript')
    <!-- Datepicker -->
    <script src="{{ asset('/') }}js/bootstrap-datepicker.min.js"></script> 
    <script src="{{ asset('/') }}locales/bootstrap-datepicker.es.min.js"></script>
    <script src="js/syncfusion/ej.web.all.min.js"> </script>
    <!-- Timepicker -->
    <script src="{{ asset('/') }}js/bootstrap-timepicker.min.js"></script>  

    <script src="{{ asset('/') }}js/app/funciones.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>

    <script src="//cdn.datatables.net/plug-ins/1.10.12/sorting/datetime-moment.js"></script>


    <script>

        $(document).ready(function() {
            $.fn.dataTable.moment('DD/MM/YYYY HH:mm:ss');
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
                    var min = $('#min').val().trim();
                    var max = $('#max').val().trim();

                    if(min!=''){min=fechaAtexto(min);}
                    if(max!=''){max=fechaAtexto(max);}

                    var startDate=fechaAtexto(data[1]);

                    if (min == '' && max == '') { return true; }
                    if (min == '' && startDate <= max) { return true;}
                    if(max == '' && startDate >= min) {return true;}
                    if (startDate <= max && startDate >= min) { return true; }
                    return false;
                }
            );            

            var uPerfil='{{ Session::get("idPerfil") }}';
            var grupo='{{ Session::get("grupoUsuario") }}';


            if(uPerfil==11 || grupo!='C'){
                    var table=$('#tablaDetalle').DataTable({
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
                            title: 'Notas de Venta Vigentes',
                            text: '<i class="fa fa-file-excel-o"></i>',
                            titleAttr: 'Excel',                        
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4,5 ]
                            }
                        }
                    ],                  
                    "order": [[ 0, "desc" ]],
                    language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                    initComplete: function () {
                        this.api().columns(4).every( function () {
                            var column = this;

                            var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                                .appendTo( $( '#tablaDetalle thead tr:eq(1) th:eq(4)' ).empty() )
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
                                .appendTo( $( '#tablaDetalle thead tr:eq(1) th:eq(5)' ).empty() )
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
                    var table=$('#tablaDetalle').DataTable({
                    orderCellsTop: true,
                    fixedHeader: true,  
                    "lengthMenu": [[6, 12, 20, -1], ["6", "12", "20", "Todos"]],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',   
                        {
                            text: 'Nueva Nota',
                            className: 'orange',
                            attr:  {
                                        id: 'btnNuevaNota'
                                    },
                            action: function ( e, dt, node, config ) {
                                if( uPerfil==11 || grupo!='C' ){
                                    swal(
                                        {
                                            title: 'Este perfil no tiene acceso a Crear Notas' ,
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
                                    location.href="{{ asset('/') }}nuevanotaventa";
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
                            title: 'Notas de Venta Vigentes',
                            text: '<i class="fa fa-file-excel-o"></i>',
                            titleAttr: 'Excel',                        
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4,5 ]
                            }
                        }
                    ],                  
                    "order": [[ 0, "desc" ]],
                    language:{url: "{{ asset('/') }}locales/datatables_ES.json"},
                    initComplete: function () {
                        this.api().columns(4).every( function () {
                            var column = this;

                            var select = $('<select class="form-control input-sm"><option value=""></option></select>')
                                .appendTo( $( '#tablaDetalle thead tr:eq(1) th:eq(4)' ).empty() )
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
                                .appendTo( $( '#tablaDetalle thead tr:eq(1) th:eq(5)' ).empty() )
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
            // DataTable
            

            $('.date').datepicker({
                todayHighlight: true,
                format: "dd/mm/yyyy",
                weekStart: 1,
                language: "es",
                autoclose: true
            }) 


    //        $("#min").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });
    //        $("#max").datepicker({ onSelect: function () { table.draw(); }, changeMonth: true, changeYear: true });

            // Event listener to the two range filtering inputs to redraw on input
            $('#min, #max').change(function () {
                table.draw();
            });                

        } );

    </script>
    
@endsection